<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'ar' ? 'en' : 'ar';
    @endphp
    <h1 class="mb-3 text-gray-800 h3">{{ __('Cashiers') }}</h1>
    <livewire:cashiers-index :rev_locale="$rev_locale" :cities="$cities" :serviceProviders="$service_providers" />

</x-dashboard.main-layout>
