<x-dashboard.main-layout>

    <div class="card-body">
        <form class="my-3" action="{{ route('admins.cities.update', $city->id) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">{{ __('Name In Arabic') }}</label>
                <input type="text" name="name_ar" class="form-control" id="name" placeholder="{{ __('Name') }}"
                    required value="{{ old('name_ar', $city->getTranslation('name', 'ar')) }}">
            </div>

            <div class="form-group">
                <label for="name">{{ __('Name In English') }}</label>
                <input type="text" name="name_en" class="form-control" id="name"
                    placeholder="{{ __('Name') }}" required
                    value="{{ old('name_en', $city->getTranslation('name', 'en')) }}">
            </div>

            <div class="form-group">
                <label for="description_ar">{{ __('Description In Arabic') }}</label>
                <textarea name="description_ar" class="form-control" id="description_ar"
                    placeholder="{{ __('Description in Arabic') }}">
                    {{ old('description_ar', $city->getTranslation('description', 'ar')) }}</textarea>
            </div>

            <div class="form-group">
                <label for="description_en">{{ __('Description In English') }}</label>
                <textarea name="description_en" class="form-control" id="description_en"
                    placeholder="{{ __('Description in English') }}">{{ old('description_en', $city->getTranslation('description', 'en')) }}</textarea>
            </div>

            <div class="form-group">
                <label for="">{{ __('Existing Photo') }}</label>
                <div>
                    <img src="{{ asset('storage/' . $city->image) }}" class="w_200" alt="">
                </div>
            </div>
            
            <div class="form-group">
                <label for="image" class="for">{{ __('Image') }}</label>
                <input type="file" name="image" class="form-control" id="image"
                    placeholder="{{ __('Image') }}">
            </div>

            <button type="submit" class="btn btn-success btn-block mb_40">{{ __('Update') }}</button>
        </form>

    </div>

</x-dashboard.main-layout>
