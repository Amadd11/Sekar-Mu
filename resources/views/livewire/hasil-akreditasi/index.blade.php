<div class="space-y-6 max-w-7xl mx-auto pb-12">
    <!-- Top Hero Banner & Metrik KPI -->
    @include('livewire.hasil-akreditasi.partials.banner')

    <!-- 2-Columns Detailed Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Main Content (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            @include('livewire.hasil-akreditasi.partials.content')
        </div>

        <!-- Right: Sidebar Meta & Details (1/3 width) -->
        <div class="space-y-6">
            @include('livewire.hasil-akreditasi.partials.sidebar')
        </div>
    </div>

    <!-- Modals Form Edit -->
    @include('livewire.hasil-akreditasi.partials.modals')
</div>
