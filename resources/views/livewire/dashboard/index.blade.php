<div class="space-y-6 max-w-7xl mx-auto pb-12">
    @if ($user->isAsessor() && ! $user->isAdmin())
    @include('livewire.dashboard.partials.asesor')

    @elseif ($user->isAdmin())
    @include('livewire.dashboard.partials.admin')

    @else
    @include('livewire.dashboard.partials.kepk')
    @endif
</div>