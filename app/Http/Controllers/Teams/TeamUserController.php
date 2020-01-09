<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Team;
use App\User;
use App\Teams\Roles;
use Illuminate\Http\Request;
use App\Http\Requests\TeamUserStoreRequest;

class TeamUserController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('in_team:' . $request->team);

        $this->middleware(['permission:add users,' . $request->team])
             ->only(['store']);

        $this->middleware(['permission:delete users,' . $request->team])
             ->only(['delete', 'destroy']);
    }

    public function index(Team $team)
    {
        return view('teams.users.index', compact('team'));
    }

    public function store(TeamUserStoreRequest $request, Team $team)
    {
        $validated = $request->validated();

        if ($team->hasReachedMemberLimit()){
            return back();
        }

        $team->users()->syncWithoutDetaching(
            $user = User::where('email', $request->email)->first()
        );

        $user->attachRole(Roles::$roleWhenJoiningTeam, $team->id);

        return back();
    }

    public function delete(Team $team, User $user)
    {   
        if (!$team->users->contains($user)) {
            return back();
        }


        if ($user->isOnlyAdminInTeam($team)) {
            return back();
        }

        if ($team->users->count() === 1) {
            return back();
        }

        return view('teams.users.delete', compact('team', 'user'));
    }

    public function destroy(Team $team, User $user)
    {
        if (!$team->users->contains($user)) {
            return back();
        }

        if ($user->isOnlyAdminInTeam($team)) {
            return back();
        }

        if ($team->users->count() === 1) {
            return back();
        }

        $team->users()->detach($user);

        $user->detachRoles([], $team->id);

        return redirect()->route('teams.users.index', $team);
    }
}
