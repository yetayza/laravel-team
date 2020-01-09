@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">Teams</div>
                    <div class=card-body>
                        @if($teams->count())
                            <ul class="list-group">
                                @foreach($teams as $team)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{ route('teams.show', $team) }}">
                                        {{ $team->name }}
                                        </a>

                                    @if($team->ownedByCurrentUser())
                                        <span class="badge badge-primary badge-pill">Admin</span>
                                    @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="mb-0">You're not part of any teams!!!</p>
                        @endif
                    </div>                
                </div>
            <div class="card">
                <div class="card-header">Create A Team</div>
                    <div class=card-body>
                        <form action="{{ route('teams.store') }}" method="POST">
                        @csrf

                            <div class="form-group">
                                <label for="name">Team Name</label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary">Create Team</button>
                        </form>
                    </div>                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
