<?php

namespace App\Http\Controllers\Admin\Admin\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class RoleController extends Controller
{

    public function index(Request $request)
    {
        $query = Role::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $status = ($request->status == 1) ? 1 : 0;
            $query->where('is_active', $status);
        }

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
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

        $roles = $query->paginate($perPage)->appends($request->all());
        
        // Get all permissions grouped by type
        $adminPermissions = Permission::where('status', 'active')
            ->where('type', 'admin')
            ->get();
            
        $employeePermissions = Permission::where('status', 'active')
            ->where('type', 'employee')
            ->get();
            
        $allPermissions = Permission::where('status', 'active')->get();

        return view('admin.Admin.role.index', compact('roles', 'adminPermissions', 'employeePermissions', 'allPermissions'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|unique:roles,name',
                'description' => 'nullable|string',
                'status' => 'required|in:active,inactive',
                'permissions' => 'required|array',
                'permissions.*' => 'exists:permissions,id',
                'type' => 'required|string|in:admin,employee',
            ]);

            // Create role
            $role = Role::create([
                'name' => $validatedData['name'],
                'slug' => Str::slug($validatedData['name']),
                'description' => $validatedData['description'] ?? null,
                'status' => $validatedData['status'],
                'type' => $validatedData['type'],
            ]);

            // Attach permissions
            foreach ($validatedData['permissions'] as $permissionId) {
                RolePermission::create([
                    'role_id' => $role->id,
                    'permission_id' => $permissionId,
                    'status' => 'active'
                ]);
            }

            return redirect()->route('admin.admin.role.index')->with('success_message_create', 'Role created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error_message_create', 'Failed to create role: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $role = Role::findOrFail($id);
            
            // Get all permissions grouped by type
            $adminPermissions = Permission::where('status', 'active')
                ->where('type', 'admin')
                ->get();
                
            $employeePermissions = Permission::where('status', 'active')
                ->where('type', 'employee')
                ->get();
                
            $allPermissions = Permission::where('status', 'active')->get();
            
            // Get assigned permissions
            $assignedPermissions = RolePermission::where('role_id', $id)
                ->where('status', 'active')
                ->pluck('permission_id')
                ->toArray();
            
            return view('admin.admin.role.edit', compact('role', 'adminPermissions', 'employeePermissions', 'allPermissions', 'assignedPermissions'));
        } catch (\Exception $e) {
            return redirect()->route('admin.admin.role.index')->with('error_message', 'Role not found: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $role = Role::findOrFail($id);
            
            $validatedData = $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('roles')->ignore($id)
                ],
                'description' => 'nullable|string',
                'status' => 'required|in:active,inactive',
                'permissions' => 'required|array',
                'permissions.*' => 'exists:permissions,id',
                'type' => 'required|string|in:admin,employee',
            ]);
            
            // Update role
            $role->update([
                'name' => $validatedData['name'],
                'slug' => Str::slug($validatedData['name']),
                'description' => $validatedData['description'] ?? null,
                'status' => $validatedData['status'],
                'type' => $validatedData['type'],
            ]);
            
            // Update permissions
            // First deactivate all existing permissions
            RolePermission::where('role_id', $id)->update(['status' => 'inactive']);
            
            // Then activate or create the selected ones
            foreach ($validatedData['permissions'] as $permissionId) {
                $rolePermission = RolePermission::where('role_id', $id)
                    ->where('permission_id', $permissionId)
                    ->first();
                
                if ($rolePermission) {
                    $rolePermission->update(['status' => 'active']);
                } else {
                    RolePermission::create([
                        'role_id' => $id,
                        'permission_id' => $permissionId,
                        'status' => 'active'
                    ]);
                }
            }
            
            return redirect()->route('admin.admin.role.index')->with('success_message', 'Role updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error_message', 'Failed to update role: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $role = Role::findOrFail($id);
            
            // Check if the role is being used by any admins
            $adminCount = Admin::where('role_id', $id)->count();
            if ($adminCount > 0) {
                return redirect()->route('admin.admin.role.index')
                    ->with('error_message', 'This role cannot be deleted because it is assigned to ' . $adminCount . ' admin(s).');
            }
            
            // Deactivate all related role permissions
            RolePermission::where('role_id', $id)->update(['status' => 'inactive']);
            
            // Delete the role
            $role->delete();
            
            return redirect()->route('admin.admin.role.index')->with('success_message', 'Role deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.admin.role.index')->with('error_message', 'Failed to delete role: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $query = Role::query();

            // Apply the same filters as in index
            if ($request->has('search') && $request->search != '') {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%');
                });
            }

            if ($request->has('status') && $request->status != '') {
                $status = ($request->status === 'active') ? 'active' : 'disactive';
                $query->where('status', $status);
            }

            if ($request->has('type') && $request->type != '') {
                $query->where('type', $request->type);
            }

            if ($request->has('from_date') && $request->from_date != '') {
                $query->whereDate('created_at', '>=', $request->from_date);
            }

            if ($request->has('to_date') && $request->to_date != '') {
                $query->whereDate('created_at', '<=', $request->to_date);
            }

            $roles = $query->get();

            $filename = 'roles_export_' . date('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function () use ($roles) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['ID', 'Name', 'Slug', 'Description', 'Type', 'Status', 'Permissions Count', 'Created At', 'Updated At']);

                foreach ($roles as $role) {
                    // Get active permissions count for this role
                    $permissionsCount = RolePermission::where('role_id', $role->id)
                        ->where('status', 'active')
                        ->count();

                    fputcsv($handle, [
                        $role->id,
                        $role->name,
                        $role->slug,
                        $role->description ?? 'N/A',
                        ucfirst($role->type),
                        $role->status,
                        $permissionsCount,
                        $role->created_at->format('Y-m-d H:i:s'),
                        $role->updated_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return redirect()->back()->with('error_message', 'Failed to export roles: ' . $e->getMessage());
        }
    }
}
