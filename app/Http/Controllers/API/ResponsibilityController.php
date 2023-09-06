<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateResponsibilityRequest;
use App\Http\Requests\UpdateResponsibilityRequest;
use App\Models\Responsibility;
use Exception;
use Illuminate\Http\Request;

class ResponsibilityController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        $responsibilityQuery = Responsibility::query();

        if ($id) {
            $responsibility = $responsibilityQuery->find($id);
            if ($responsibility) {
                return ResponseFormatter::success($responsibility, 'Responsibility Founded');
            }
            return ResponseFormatter::error('Responsibility Not Found', 404);
        }
        $responsibilities = $responsibilityQuery->where('role_id', $request->role_id);
        if ($name) {
            $responsibilities->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success(
            $responsibilities->paginate($limit),
            'Responsibility Found',
        );
    }

    public function create(CreateResponsibilityRequest $request)
    {
        try {
            $responsibility = Responsibility::create([
                'name' => $request->name,
                'role_id' => $request->role_id,
            ]);

            if (!$responsibility) {
                throw new Exception('Responsibility Not Created');
            }


            return ResponseFormatter::success($responsibility, 'Responsibility Created Successfully');
        } catch (Exception $err) {
            return ResponseFormatter::error($err->getMessage(), 500);
        }
    }

    public function update(UpdateResponsibilityRequest $request, $id)
    {
        try {
            $responsibility = Responsibility::find($id);
            if (!$responsibility) {
                throw new Exception('Responsibility Not Found');
            }

            $responsibility->update([
                'name' => $request->name,
                'role_id' => $request->role_id,
            ]);

            return ResponseFormatter::success($responsibility, 'Update Responsibility Success');
        } catch (Exception $err) {
            return ResponseFormatter::error($err->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $responsibility = Responsibility::find($id);
            if (!$responsibility) {
                throw new Exception('Responsibility Not Found');
            }
            $responsibility->delete();
            return ResponseFormatter::success($responsibility, 'Delete Responsibility Success');
        } catch (Exception $err) {
            return ResponseFormatter::error($err->getMessage());
        }
    }
}
