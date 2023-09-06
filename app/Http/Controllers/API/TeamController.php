<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Models\Team;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        $teamQuery = Team::query();

        if ($id) {
            $team = $teamQuery->find($id);
            if ($team) {
                return ResponseFormatter::success($team, 'Team Founded');
            }
            return ResponseFormatter::error('Team Not Found', 404);
        }
        $teams = $teamQuery->where('company_id', $request->company_id);
        if ($name) {
            $teams->where('name', 'like', '%' . $name . '%');
        }
        return ResponseFormatter::success(
            $teams->paginate($limit),
            'Team Found',
        );
    }

    public function create(CreateTeamRequest $request)
    {
        try {
            if ($request->hasFile('icon')) {
                $path = $request->file('icon')->store('public/icons');
            }
            $team = Team::create([
                'name' => $request->name,
                'icon' => $path,
                'company_id' => $request->company_id,
            ]);

            if (!$team) {
                throw new Exception('Team Not Created');
            }


            return ResponseFormatter::success($team, 'Team Created Successfully');
        } catch (Exception $err) {
            return ResponseFormatter::error($err->getMessage(), 500);
        }
    }

    public function update(UpdateTeamRequest $request, $id)
    {
        try {
            $team = Team::find($id);
            if (!$team) {
                throw new Exception('Team Not Found');
            }

            if ($request->hasFile('icon')) {
                $path = $request->file('icon')->store('public/icons');
            }
            $team->update([
                'name' => $request->name,
                'icon' => $path,
                'company_id' => $request->company_id,
            ]);

            return ResponseFormatter::success($team, 'Update Team Success');
        } catch (Exception $err) {
            return ResponseFormatter::error($err->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $team = Team::find($id);
            if (!$team) {
                throw new Exception('Team Not Found');
            }
            $team->delete();
            return ResponseFormatter::success($team, 'Delete Team Success');
        } catch (Exception $err) {
            return ResponseFormatter::error($err->getMessage());
        }
    }
}
