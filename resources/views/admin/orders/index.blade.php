<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'ar' ? 'en' : 'ar';
    @endphp
    <h1 class="mb-3 text-gray-800 h3">{{ __('Orders') }}</h1>
    <livewire:orders-index :rev_locale="$rev_locale" :serviceProviders="$serviceProviders"
        :services="$services" />

</x-dashboard.main-layout>
