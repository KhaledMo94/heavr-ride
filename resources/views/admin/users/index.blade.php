<x-dashboard.main-layout>
    <h1 class="mb-3 text-gray-800 h3">{{ __('Users') }}</h1>


    <livewire:users-index :cities="$cities" />

</x-dashboard.main-layout>
