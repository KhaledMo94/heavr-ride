<x-dashboard.main-layout>

    <div class="card-body">
        <form class="my-3" action="{{ route('admins.services.update', $service->id) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name_ar">{{ __('Name In Arabic') }}</label>
                <input type="text" name="name_ar" class="form-control" id="name_ar"
                    placeholder="{{ __('Name In Arabic') }}" required
                    value="{{ old('name_ar') ?? $service->getTranslation('name', 'ar') }}">
            </div>

            <div class="form-group">
                <label for="name_en">{{ __('Name In English') }}</label>
                <input type="text" name="name_en" class="form-control" id="name_en"
                    placeholder="{{ __('Name In English') }}" required
                    value="{{ old('name_en') ?? $service->getTranslation('name', 'en') }}">
            </div>
            <div class="form-group">
                <label for="description_ar">{{ __('Description In Arabic') }}</label>
                <textarea name="description_ar" class="form-control" id="description_ar" rows="3"
                    placeholder="{{ __('Description In Arabic') }}" required>{{ old('description_ar') ?? $service->getTranslation('description', 'ar') }}</textarea>
            </div>
            <div class="form-group">
                <label for="description_en">{{ __('Description In English') }}</label>
                <textarea name="description_en" class="form-control" id="description_en" rows="3"
                    placeholder="{{ __('Description In English') }}" required>{{ old('description_en') ?? $service->getTranslation('description', 'en') }}</textarea>
            </div>
            
            <div class="form-group form-check form-switch">
                <input class="form-check-input" type="checkbox" id="show" name="show" value="1" {{ old('show') || $service->show  ? 'checked' : '' }}>
                <label class="form-check-label" for="show">{{ __('Show this service on Mobile Application') }}</label>
            </div>

            <div class="form-group">
                <label for="discount_label">{{ __('Discount Label') }}</label>
                <input type="number" step="0.01" name="discount_label" class="form-control" id="discount_label"
                    placeholder="{{ __('Discount Label') }}"
                    value="{{ old('discount_label') ?? $service->discount_label }}">
            </div>
            
            <div class="form-group">
                <label for="">{{ __('Class') }}</label>
                <select name="class" class="form-control">
                    @foreach (range('A', 'J') as $class)
                        <option value="{{ strtolower($class) }}" @selected(old('class',$service->class) == strtolower($class))>{{ __('Class '). $class }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="">{{ __('Existing Image') }}</label>
                <div>
                    @if ($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" class="w_200" alt="">
                    @else
                        <p>{{ __('No Image') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="image" class="for">{{ __('Image') }}</label>
                <input type="file" name="image" class="form-control" id="image"
                    placeholder="{{ __('Image') }}">
            </div>

            <div class="form-group">
                <label for="">{{ __('Existing Banner Image') }}</label>
                <div>
                    @if ($service->banner_image)
                        <img src="{{ asset('storage/' . $service->banner_image) }}" class="w_200" alt="">
                    @else
                        <p>{{ __('No Banner Image') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="banner_image" class="for">{{ __('Banner Image') }}</label>
                <input type="file" name="banner_image" class="form-control" id="banner_image"
                    placeholder="{{ __('Banner Image') }}">
            </div>

            <button type="submit" class="btn btn-success btn-block mb_40">{{ __('Update') }}</button>
        </form>

    </div>
    
        <style>
        .form-check-input {
        width: 2.5em;
        height: 1.3em;
        background-color: #e9ecef;
        border-radius: 1em;
        position: relative;
        appearance: none;
        outline: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    
    .form-check-input:checked {
        background-color: #198754;
    }
    
    .form-check-input:checked::before {
        transform: translateX(1.2em);
    }
    
    .form-check-input::before {
        content: "";
        position: absolute;
        top: 0.15em;
        left: 0.2em;
        width: 0.9em;
        height: 0.9em;
        background-color: white;
        border-radius: 50%;
        transition: transform 0.3s;
    }
    
    .form-switch{
        display: flex;
        align-items: center;
        gap: 14px;
    }
        
    </style>

</x-dashboard.main-layout>
