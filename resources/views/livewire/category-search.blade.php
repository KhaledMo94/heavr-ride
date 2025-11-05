<div class="form-group">
    <input type="text" wire:model.debounce.300ms="query" placeholder="{{ __('Search categories...') }}" class="w-full p-2 border rounded">
    @php
        $rev_locale = app()->getLocale() == 'ar' ? 'en' : 'ar';
    @endphp

    @if (!empty($categories))
        <ul class="mt-2 bg-white border rounded">
            @foreach ($categories as $category)
                <li class="p-2 hover:bg-gray-100">{{ $category->getTranslation('name' , app()->getLocale()) }} - {{ $category->getTranslation('name',$rev_locale) }}</li>
            @endforeach
        </ul>
    @endif
</div>