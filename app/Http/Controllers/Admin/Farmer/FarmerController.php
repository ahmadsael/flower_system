<?php

namespace App\Http\Controllers\Admin\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farmer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class FarmerController extends Controller
{
    public function index(Request $request)
    {
        $query = Farmer::query();


        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                    ->orWhere('phone', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by gender
        if ($request->has('gender') && $request->gender != '') {
            $query->where('gender', $request->gender);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date') && $request->from_date != '') {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date != '') {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Set number of items per page from request or default to 10
        $perPage = $request->get('per_page', 10);

        $farmers = $query->paginate($perPage)->appends($request->all());


        return view('admin.Farmer.index', compact('farmers'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:farmers',
                'password' => 'required|min:8|max:20|string|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/',
                'phone' => [
                    'required',
                    'unique:farmers',
                    'regex:/^(?:\+971|971|0)?5[0-9]{8}$|^(?:\+963|963|0)?9[0-9]{8}$/'
                ],
                'birthday' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) {
                        if (\Carbon\Carbon::parse($value)->diffInYears(now()) < 18) {
                            $fail('The ' . $attribute . ' indicates the farmer is not at least 18 years old.');
                        }
                    }
                ],
                'gender' => 'required|in:male,female',
                'status' => 'required|in:active,inactive',
                'img' => 'nullable|image|max:10240',
            ]);

            $password = Hash::make($validatedData['password']);
            $image = $request->file('img')->getClientOriginalName();
            $path = $request->file('img')->storeAs('FarmerImage', $image, 'public');
            
            Farmer::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => $password,
                'phone' => $validatedData['phone'],
                'birthday' => $validatedData['birthday'],
                'gender' => $validatedData['gender'],
                'status' => $validatedData['status'],
                'img' => $path,
                'created_by' => Auth::guard('admin')->user()->id,
                'updated_by' => Auth::guard('admin')->user()->id,
            ]);
            return redirect()->route('admin.farmer.index')->with('success_message_create', 'Farmer created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error_message_create', 'Failed to create farmer: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $farmer = Farmer::findOrFail($id);
            
            return view('Admin.Farmer.edit', compact('farmer'));
        } catch (\Exception $e) {
            return redirect()->route('admin.farmer.index')->with('error_message', 'Farmer not found: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $farmer = Farmer::findOrFail($id);
            
            $validatedData = $request->validate([
                'name' => 'required',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('farmers')->ignore($id)
                ],
                'phone' => [
                    'required',
                    Rule::unique('farmers')->ignore($id),
                    'regex:/^(?:\+971|971|0)?5[0-9]{8}$|^(?:\+963|963|0)?9[0-9]{8}$/'
                ],
                'birthday' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) {
                        if (\Carbon\Carbon::parse($value)->diffInYears(now()) < 18) {
                            $fail('The ' . $attribute . ' indicates the admin is not at least 18 years old.');
                        }
                    }
                ],
                'gender' => 'required|in:male,female',
                'status' => 'required|in:active,inactive',
                'img' => 'nullable|image|max:10240',
            ]);
            
            $updateData = [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'birthday' => $validatedData['birthday'],
                'gender' => $validatedData['gender'],
                'status' => $validatedData['status'],
                'updated_by' => Auth::guard('admin')->user()->id,
            ];
            
            // Handle image upload if a new image is provided
            if ($request->hasFile('img')) {
                // Delete old image if exists
                if ($farmer->img && Storage::disk('public')->exists($farmer->img)) {
                    Storage::disk('public')->delete($farmer->img);
                }
                
                $image = $request->file('img')->getClientOriginalName();
                $path = $request->file('img')->storeAs('FarmerImage', $image, 'public');
                $updateData['img'] = $path;
            }
            
            $farmer->update($updateData);
            
            return redirect()->route('admin.farmer.index')->with('success_message', 'Farmer updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error_message', 'Failed to update farmer: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request, $id)
    {
        try {
            $farmer = Farmer::findOrFail($id);
            
            $validatedData = $request->validate([
                'password' => 'required|min:8|max:20|string|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/',
                'password_confirmation' => 'required|same:password',
            ]);
            
            $farmer->update([
                'password' => Hash::make($validatedData['password']),
                'updated_by' => Auth::guard('admin')->user()->id,
            ]);
            
            return redirect()->route('admin.farmer.index')->with('success_message', 'Password updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error_message', 'Failed to update password: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
           try {
               $farmer = Farmer::findOrFail($id);
               
               // Delete the image if exists
               if ($farmer->img && Storage::disk('public')->exists($farmer->img)) {
                   Storage::disk('public')->delete($farmer->img);
               }
               
               $farmer->delete();
               
               return redirect()->route('admin.farmer.index')->with('success_message', 'Farmer deleted successfully');
           } catch (\Exception $e) {
               return redirect()->route('admin.farmer.index')->with('error_message', 'Failed to delete farmer: ' . $e->getMessage());
           }
    }
   
    public function export(Request $request)
    {
           try {
                 $query = Farmer::query();
   
               // Apply the same filters as in index
               if ($request->has('search') && $request->search != '') {
                   $searchTerm = $request->search;
                   $query->where(function ($q) use ($searchTerm) {
                       $q->where('name', 'like', '%' . $searchTerm . '%')
                           ->orWhere('email', 'like', '%' . $searchTerm . '%')
                           ->orWhere('phone', 'like', '%' . $searchTerm . '%');
                   });
               }
   
               if ($request->has('gender') && $request->gender != '') {
                   $query->where('gender', $request->gender);
               }
   
               if ($request->has('status') && $request->status != '') {
                   $query->where('status', $request->status);
               }

           
   
               if ($request->has('from_date') && $request->from_date != '') {
                   $query->whereDate('created_at', '>=', $request->from_date);
               }
   
               if ($request->has('to_date') && $request->to_date != '') {
                   $query->whereDate('created_at', '<=', $request->to_date);
               }
   
               $farmers = $query->get();
   
               $filename = 'farmers_export_' . date('Y-m-d_H-i-s') . '.csv';
               $headers = [
                   'Content-Type' => 'text/csv',
                   'Content-Disposition' => 'attachment; filename="' . $filename . '"',
               ];
   
               $callback = function () use ($farmers) {
                   $handle = fopen('php://output', 'w');
                   fputcsv($handle, [
                       'ID', 'Name', 'Email', 'Phone', 'Gender', 'Birthday', 'Age', 
                       'Status' , 'Created By', 'Created At', 'Updated At'
                   ]);
   
                   foreach ($farmers as $farmer) {
                       // Calculate age
                       $age = $farmer->birthday ? \Carbon\Carbon::parse($farmer->birthday)->age : 'N/A';
                       
                       // Get creator name
                       $createdBy = $farmer->createdBy ? $farmer->createdBy->name : 'N/A';
   
                       fputcsv($handle, [
                           $farmer->id,
                           $farmer->name,
                           $farmer->email,
                           $farmer->phone,
                           ucfirst($farmer->gender),
                           $farmer->birthday ?: 'N/A',
                           $age,
                           ucfirst($farmer->status),
                           $createdBy,
                           $farmer->created_at->format('Y-m-d H:i:s'),
                           $farmer->updated_at->format('Y-m-d H:i:s'),
                       ]);
                   }
   
                   fclose($handle);
               };
   
               return response()->stream($callback, 200, $headers);
           } catch (\Exception $e) {
               return redirect()->back()->with('error_message', 'Failed to export farmers: ' . $e->getMessage());
           }
    }

}
