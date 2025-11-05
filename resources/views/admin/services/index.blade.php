<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp
    <h1 class="mb-3 text-gray-800 h3">{{ __('Services') }}</h1>
    <livewire:services-index :rev_locale="$rev_locale" />

</x-dashboard.main-layout>
