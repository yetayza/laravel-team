@extends('layouts.team')

@section('teamcontent')
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('teams.partials._nav')
        </div>
        <div class="col-md-9">

            @if(!$team->hasSubscription())
                <div class="card mb-4">
                    <div class="card-header">Subscriptions</div>

                    <div class="card-body">
                        <form action="{{ route('teams.subscriptions.store', $team) }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label>Chose a plan</label>

                                @foreach($plans as $index=>$plan)
                                    <div class="form-check">
                                        <input 
                                                type="radio" 
                                                name="plan" 
                                                id="plan_{{ $plan->id }}" 
                                                class="form-check-input"
                                                value="{{ $plan->id }}"
                                                {{ $index === 0 ? 'checked' : ''  }}
                                                >
                                        <label class="form-check-label" for="plan_{{ $plan->id }}">
                                            {{ $plan->name }} ({{ $plan->teams_limit }} Users)
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group">
                                <label>Payment details</label>

                                <stripe/>
                            </div>

                            <button type="submit" class="btn btn-primary">Process Payment</button>
                        </form>
                    </div>
                </div>
            @else

            <div class="card mb-4">
                    <div class="card-header">Team Subscriptions</div>

                    <div class="card-body">
                        You're on the {{ $team->plan->name }} plan. ({{ $team->plan->teams_limit }} users)
                    </div>
                </div>

            <div class="card mb-4">
                <div class="card-header">Swap Subscriptions</div>

                    <div class="card-body">
                        @include('teams.subscriptions.partials._usage')

                            <form action="{{ route('teams.subscriptions.swap.store', $team) }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label>Chose a plan</label>

                                    @foreach($plans as $index=>$plan)
                                        <div class="form-check">
                                            <input 
                                                    type="radio" 
                                                    name="plan" 
                                                    id="plan_{{ $plan->id }}" 
                                                    class="form-check-input"
                                                    value="{{ $plan->id }}"
                                                    {{ !$team->canDowngrade($plan) ? 'disabled' : '' }}
                                                    {{ $team->isOnPlan($plan->provider_id) ? 'checked' : ''  }}
                                                    >
                                            <label class="form-check-label" for="plan_{{ $plan->id }}">
                                                {{ $plan->name }} ({{ $plan->teams_limit }} Users)
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="submit" class="btn btn-primary">Swap the plan</button>
                            </form>
                    </div>
            </div>
            @endif
        </div>
    </div>
@endsection