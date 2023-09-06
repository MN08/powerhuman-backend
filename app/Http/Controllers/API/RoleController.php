<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);
        $withResponsibilities = $request->input('with_responsibilities', false);

        $roleQuery = Role::query();

        if ($id) {
            $role = $roleQuery->with('responsibilities')->find($id);
            if ($role) {
                return ResponseFormatter::success($role, 'Role Founded');
            }
            return ResponseFormatter::error('Role Not Found', 404);
        }
        $roles = $roleQuery->where('company_id', $request->company_id);
        if ($name) {
            $roles->where('name', 'like', '%' . $name . '%');
        }
        if ($withResponsibilities) {
            $roles->with('responsibilities');
        }
        return ResponseFormatter::success(
            $roles->paginate($limit),
            'Role Found',
        );
    }

    public function create(CreateRoleRequest $request)
    {
        try {
            $role = Role::create([
                'name' => $request->name,
                'company_id' => $request->company_id,
            ]);

            if (!$role) {
                throw new Exception('Role Not Created');
            }


            return ResponseFormatter::success($role, 'Role Created Successfully');
        } catch (Exception $err) {
            return ResponseFormatter::error($err->getMessage(), 500);
        }
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        try {
            $role = Role::find($id);
            if (!$role) {
                throw new Exception('Role Not Found');
            }

            $role->update([
                'name' => $request->name,
                'company_id' => $request->company_id,
            ]);

            return ResponseFormatter::success($role, 'Update Role Success');
        } catch (Exception $err) {
            return ResponseFormatter::error($err->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $role = Role::find($id);
            if (!$role) {
                throw new Exception('Role Not Found');
            }
            $role->delete();
            return ResponseFormatter::success($role, 'Delete Role Success');
        } catch (Exception $err) {
            return ResponseFormatter::error($err->getMessage());
        }
    }
}
