@if ($team->hasSubscription())
    <p>You used {{ $team->users->count() }} out of {{ optional($team->plan)->teams_limit ?? '0' }} avalible users slots.</p>
@endif