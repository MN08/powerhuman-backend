<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use Exception;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $email = $request->input('email');
        $gender = $request->input('gender');
        $age = $request->input('age');
        $team_id = $request->input('team_id');
        $role_id = $request->input('role_id');
        $limit = $request->input('limit', 10);

        $withTeam = $request->input('with_teams', false);
        $withRole = $request->input('with_roles ', false);

        $employeeQuery = Employee::query();

        if ($id) {
            $employee = $employeeQuery->with(['team', 'role'])->find($id);
            if ($employee) {
                return ResponseFormatter::success($employee, 'Employee Founded');
            }
            return ResponseFormatter::error('Employee Not Found', 404);
        }

        $employees = $employeeQuery;
        if ($name) {
            $employees->where('name', 'like', '%' . $name . '%');
        }
        if ($email) {
            $employees->where('email', 'like', '%' . $email . '%');
        }
        if ($age) {
            $employees->where('age', $age);
        }
        if ($gender) {
            $employees->where('age', $gender);
        }
        if ($team_id) {
            $employees->where('team_id', $team_id);
        }
        if ($role_id) {
            $employees->where('role_id', $role_id);
        }
        return ResponseFormatter::success(
            $employees->paginate($limit),
            'Employee Found',
        );
    }

    public function create(CreateEmployeeRequest $request)
    {
        try {
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('public/photo');
            }
            $employee = Employee::create([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'age' => $request->age,
                'phone' => $request->phone,
                'photo' => $path,
                'team_id' => $request->team_id,
                'role_id' => $request->role_id,
            ]);

            if (!$employee) {
                throw new Exception('Employee Not Created');
            }


            return ResponseFormatter::success($employee, 'Employee Created Successfully');
        } catch (Exception $err) {
            return ResponseFormatter::error($err->getMessage(), 500);
        }
    }

    public function update(UpdateEmployeeRequest $request, $id)
    {
        try {
            $employee = Employee::find($id);
            if (!$employee) {
                throw new Exception('Employee Not Found');
            }

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('public/photo');
            }
            $employee = Employee::create([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'age' => $request->age,
                'phone' => $request->phone,
                'photo' => $path,
                'team_id' => $request->team_id,
                'role_id' => $request->role_id,
            ]);

            return ResponseFormatter::success($employee, 'Update Employee Success');
        } catch (Exception $err) {
            return ResponseFormatter::error($err->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $employee = Employee::find($id);
            if (!$employee) {
                throw new Exception('Employee Not Found');
            }
            $employee->delete();
            return ResponseFormatter::success($employee, 'Delete Employee Success');
        } catch (Exception $err) {
            return ResponseFormatter::error($err->getMessage());
        }
    }
}
