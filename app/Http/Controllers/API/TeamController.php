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

        if ($id) {
            $team = Team::whereHas('users', function ($query) {
                $query->where('user_id', Auth::id());
            })->with(['users'])->find($id);
            if ($team) {
                return ResponseFormatter::success($team, 'Team Founded');
            }
            return ResponseFormatter::error('Company Not Found', 404);
        }
        $teams = Team::with(['users'])->whereHas('users', function ($query) {
            $query->where('user_id', Auth::id());
        });
        if ($name) {
            $teams->where('name', 'like', '%' . $name . '%');
        }
        return ResponseFormatter::success(
            $teams->paginate($limit),
            'Company Found',
        );
    }

    public function create(CreateTeamRequest $request)
    {
        try {
            if ($request->hasFile('icon')) {
                $path = $request->file('icon')->store('public/icon');
            }
            $team = Team::create([
                'name' => $request->name,
                'icon' => $path,
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
                $path = $request->file('icon')->store('public/icon');
            }
            $team->update([
                'name' => $request->name,
                'icon' => $path,
            ]);

            return ResponseFormatter::success($team, 'Update Team Success');
        } catch (Exception $err) {
            return ResponseFormatter::error($err->getMessage(), 500);
        }
    }

    public function delete()
    {
    }
}
