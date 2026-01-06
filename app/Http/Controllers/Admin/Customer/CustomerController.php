<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();


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

        $customers = $query->paginate($perPage)->appends($request->all());


        return view('admin.Customer.index', compact('customers'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:customers',
                'password' => 'required|min:8|max:20|string|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/',
                'phone' => [
                    'required',
                    'unique:customers',
                    'regex:/^(?:\+971|971|0)?5[0-9]{8}$|^(?:\+963|963|0)?9[0-9]{8}$/'
                ],
                'birthday' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) {
                        if (\Carbon\Carbon::parse($value)->diffInYears(now()) < 18) {
                            $fail('The ' . $attribute . ' indicates the customer is not at least 18 years old.');
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
            
            Customer::create([
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
            return redirect()->route('admin.customer.index')->with('success_message_create', 'Customer created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error_message_create', 'Failed to create Customer: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            
            return view('Admin.Customer.edit', compact('customer'));
        } catch (\Exception $e) {
            return redirect()->route('admin.customer.index')->with('error_message', 'Customer not found: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);
            
            $validatedData = $request->validate([
                'name' => 'required',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('customers')->ignore($id)
                ],
                'phone' => [
                    'required',
                    Rule::unique('customers')->ignore($id),
                    'regex:/^(?:\+971|971|0)?5[0-9]{8}$|^(?:\+963|963|0)?9[0-9]{8}$/'
                ],
                'birthday' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) {
                        if (\Carbon\Carbon::parse($value)->diffInYears(now()) < 18) {
                            $fail('The ' . $attribute . ' indicates the customer is not at least 18 years old.');
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
                if ($cuatomer->img && Storage::disk('public')->exists($customer->img)) {
                    Storage::disk('public')->delete($customer->img);
                }
                
                $image = $request->file('img')->getClientOriginalName();
                $path = $request->file('img')->storeAs('FarmerImage', $image, 'public');
                $updateData['img'] = $path;
            }
            
            $customer->update($updateData);
            
            return redirect()->route('admin.customer.index')->with('success_message', 'Customer updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error_message', 'Failed to update customer: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);
            
            $validatedData = $request->validate([
                'password' => 'required|min:8|max:20|string|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/',
                'password_confirmation' => 'required|same:password',
            ]);
            
            $customer->update([
                'password' => Hash::make($validatedData['password']),
                'updated_by' => Auth::guard('admin')->user()->id,
            ]);
            
            return redirect()->route('admin.customer.index')->with('success_message', 'Password updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error_message', 'Failed to update password: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
           try {
               $customer = Customer::findOrFail($id);
               
               // Delete the image if exists
               if ($customer->img && Storage::disk('public')->exists($customer->img)) {
                   Storage::disk('public')->delete($customer->img);
               }
               
               $customer->delete();
               
               return redirect()->route('admin.customer.index')->with('success_message', 'Customer deleted successfully');
           } catch (\Exception $e) {
               return redirect()->route('admin.customer.index')->with('error_message', 'Failed to delete customer: ' . $e->getMessage());
           }
    }
   
    public function export(Request $request)
    {
           try {
                 $query = Customer::query();
   
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
   
               $customers = $query->get();
   
               $filename = 'customers_export_' . date('Y-m-d_H-i-s') . '.csv';
               $headers = [
                   'Content-Type' => 'text/csv',
                   'Content-Disposition' => 'attachment; filename="' . $filename . '"',
               ];
   
               $callback = function () use ($customers) {
                   $handle = fopen('php://output', 'w');
                   fputcsv($handle, [
                       'ID', 'Name', 'Email', 'Phone', 'Gender', 'Birthday', 'Age', 
                       'Status' , 'Created By', 'Created At', 'Updated At'
                   ]);
   
                   foreach ($customers as $customer) {
                       // Calculate age
                       $age = $customer->birthday ? \Carbon\Carbon::parse($customer->birthday)->age : 'N/A';
                       
                       // Get creator name
                       $createdBy = $customer->createdBy ? $customer->createdBy->name : 'N/A';
   
                       fputcsv($handle, [
                           $customer->id,
                           $customer->name,
                           $customer->email,
                           $customer->phone,
                           ucfirst($customer->gender),
                           $customer->birthday ?: 'N/A',
                           $age,
                           ucfirst($customer->status),
                           $createdBy,
                           $customer->created_at->format('Y-m-d H:i:s'),
                           $customer->updated_at->format('Y-m-d H:i:s'),
                       ]);
                   }
   
                   fclose($handle);
               };
   
               return response()->stream($callback, 200, $headers);
           } catch (\Exception $e) {
               return redirect()->back()->with('error_message', 'Failed to export customers: ' . $e->getMessage());
           }
    }
}
