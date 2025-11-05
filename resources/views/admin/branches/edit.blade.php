<x-dashboard.main-layout>

    @php
        $locale = app()->getLocale();
    @endphp
    <div class="card-body" data-aos="fade-up">
        <form class="my-3" action="{{ route('admins.branches.update', $branch->id) }}" method="post"
            enctype="application/x-www-form-urlencoded">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">{{ __('Name In Arabic') }}</label>
                        <input type="text" name="name_ar" class="form-control"
                            value="{{ old('name_ar') ?? $branch->getTranslation('name', 'ar') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">{{ __('Name In English') }}</label>
                        <input type="text" name="name_en" class="form-control"
                            value="{{ old('name_en') ?? $branch->getTranslation('name', 'en') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">{{ __('Address In Arabic') }}</label>
                        <input type="text" name="address_ar" class="form-control"
                            value="{{ old('address_ar') ?? $branch->getTranslation('address', 'ar') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">{{ __('Address In English') }}</label>
                        <input type="text" name="address_en" class="form-control"
                            value="{{ old('address_en') ?? $branch->getTranslation('address', 'en') }}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">{{ __('City') }}</label>
                        <select name="city_id" class="form-control select2 city-select" required>
                            <option value="{{ $branch->city_id }}" selected>
                                {{ $branch->city->getTranslation('name', $locale) }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">{{ __('Service Provider') }}</label>
                        <select name="service_provider_id" class="form-control select2 service-provider-select"
                            required>
                            <option value="{{ $branch->service_provider_id }}" selected>
                                {{ $branch->serviceProvider->getTranslation('name', $locale) }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">{{ __('Phone') }}</label>
                        <input type="text" name="phone" class="form-control"
                            value="{{ old('phone') ?? $branch->phone }}" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">{{ __('Alternate Phone') }}</label>
                        <input type="text" name="phone_alt" class="form-control"
                            value="{{ old('phone_alt') ?? $branch->phone_alt }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">{{ __('Latitude') }}</label>
                        <input type="text" name="latitude" class="form-control"
                            value="{{ old('latitude') ?? $branch->latitude }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">{{ __('Longitude') }}</label>
                        <input type="text" name="longitude" class="form-control"
                            value="{{ old('longitude') ?? $branch->longitude }}">
                    </div>
                </div>
            </div>


            <div class="mb-4 shadow-sm card">
                <div class="text-center text-white card-header bg-primary fw-bold">
                    {{ __('Branch Moderator Section') }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">{{ __('Moderator Name') }}</label>
                                <input type="text" name="moderator_name" class="form-control"
                                    value="{{ old('moderator_name') ?? $branch->moderator?->name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Moderator Email') }}</label>
                                <input type="email" name="moderator_email" class="form-control"
                                    value="{{ old('moderator_email') ?? $branch->moderator?->email }}" required>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Moderator Country Code') }}</label>
                                <input type="text" name="moderator_country_code" class="form-control"
                                    value="{{ old('moderator_country_code') ?? $branch->moderator?->country_code }}" required>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Moderator Phone') }}</label>
                                <input type="text" name="moderator_phone_number" class="form-control"
                                    value="{{ old('moderator_phone_number') ?? $branch->moderator?->phone_number }}" required>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Moderator Password') }}</label>
                                <input type="password" name="moderator_password" class="form-control"
                                    value="{{ old('moderator_password') }}" >
                                    <small class="text-danger">* {{ __('If you want to change the password, please enter a new password.') }}</small>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Moderator Password Confirmation') }}</label>
                                <input type="password" name="moderator_password_confirmation" class="form-control"
                                    value="{{ old('moderator_password_confirmation') }}" >
                                    <small class="text-danger">* {{ __('Please confirm the new password.') }}</small>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Type') }}</label>
                                <select name="moderator_type" class="form-control" required>
                                    <option value="m" @if (old('moderator_type') == 'm' || $branch->moderator?->type == 'm') selected @endif>
                                        {{ __('Male') }}</option>
                                    <option value="f" @if (old('moderator_type') == 'f' || $branch->moderator?->type == 'f') selected @endif>
                                        {{ __('Female') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Status') }}</label>
                                <select name="moderator_status" class="form-control" required>
                                    <option value="active" @if (old('moderator_status') == 'active' || $branch->moderator?->status == 'active') selected @endif>
                                        {{ __('Active') }}</option>
                                    <option value="inactive" @if (old('moderator_status') == 'inactive' || $branch->moderator?->status == 'inactive') selected @endif>
                                        {{ __('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('.service-provider-select').select2({
                placeholder: "{{ __('Search for a service provider') }}",
                ajax: {
                    url: "{{ route('admins.providers.search') }}",
                    dataType: 'json',
                    delay: 500,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(provider => ({
                                id: provider.id,
                                text: provider.name
                            }))
                        };
                    },
                    cache: true
                }
            });
            $('.city-select').select2({
                placeholder: "{{ __('Search for a city') }}",
                ajax: {
                    url: "{{ route('admins.cities.search') }}",
                    dataType: 'json',
                    delay: 500,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(city => ({
                                id: city.id,
                                text: city.name
                            }))
                        };
                    },
                    cache: true
                }
            });
        });
    </script>

</x-dashboard.main-layout>
