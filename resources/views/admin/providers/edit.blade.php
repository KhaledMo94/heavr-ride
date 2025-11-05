<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp
    <div class="card-body">
        <form class="my-3" action="{{ route('admins.providers.update', $provider->id) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4 shadow card t-left">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">
                                <a class="nav-link active" id="p1_tab" data-toggle="pill" href="#p1"
                                    role="tab" aria-controls="p1" aria-selected="true">{{ __('Main Section') }}
                                </a>
                                <a class="nav-link" id="p2_tab" data-toggle="pill" href="#p2" role="tab"
                                    aria-controls="p2" aria-selected="false">{{ __('Calculations') }}
                                </a>
                                <a class="nav-link" id="p3_tab" data-toggle="pill" href="#p3" role="tab"
                                    aria-controls="p3" aria-selected="false">{{ __('Moderator') }}
                                </a>
                                <a class="nav-link" id="p4_tab" data-toggle="pill" href="#p4" role="tab"
                                    aria-controls="p4" aria-selected="false">{{ __('Privacy Policy') }}
                                </a>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="tab-content" id="v-pills-tabContent">
                                <!-- Tab 1 -->
                                <div class="tab-pane fade show active" id="p1" role="tabpanel"
                                    aria-labelledby="p1_tab">
                                    <h4 class="heading-in-tab">{{ __('Main Section') }}</h4>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('Name In Arabic') }}</label>
                                                <input type="text" name="name_ar" class="form-control"
                                                    value="{{ old('name_ar') ?? $provider->getTranslation('name', 'ar') }}"
                                                    autofocus>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('Name In English') }}</label>
                                                <input type="text" name="name_en" class="form-control"
                                                    value="{{ old('name_en') ?? $provider->getTranslation('name', 'en') }}"
                                                    autofocus>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="time_from">{{ __('Time From') }}</label>
                                                <input type="time" name="time_from" class="form-control"
                                                    value="{{ old('time_from') ?? optional($provider->options)['open_at'] }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="time_to">{{ __('Time To') }}</label>
                                                <input type="time" name="time_to" class="form-control"
                                                    value="{{ old('time_to') ?? optional($provider->options)['close_at'] }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ __('Description In Arabic') }}</label>
                                        <textarea name="description_ar" class="form-control editor" cols="30" rows="10">
                                            {{ old('description_ar') ?? $provider->getTranslation('description', 'ar') }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ __('Description In English') }}</label>
                                        <textarea name="description_en" class="form-control editor" cols="30" rows="10">
                                            {{ old('description_en') ?? $provider->getTranslation('description', 'en') }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ __('Category') }}</label>
                                        <select name="category_id" class="form-control">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" @selected($category->id == $provider->category_id)>
                                                    {{ $category->name }} -
                                                    {{ $category->getTranslation('name', $rev_locale) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="">{{ __('Class') }}</label>
                                        <select name="class" class="form-control">
                                            @foreach (range('A', 'J') as $class)
                                                <option value="{{ strtolower($class) }}" @selected(old('class',$provider->class) == strtolower($class))>{{ __('Class '). $class }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="services">{{ __('Services') }}</label>
                                        <select name="services[]" class="w-100 select2" id="services" multiple>
                                            @foreach ($services as $service)
                                                <option value="{{ $service['id'] }}" @selected(in_array($service['id'], old('services', $provider->services->pluck('id')->toArray())))>
                                                    {{ $service['name'] }} -
                                                    {{ $service->getTranslation('name', $rev_locale) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ __('Existing Image') }}</label>
                                        <div>
                                            @if ($provider->image)
                                                <img src="{{ asset('storage/' . $provider->image) }}" class="w_200"
                                                    alt="">
                                            @else
                                                <p>{{ __('No Image') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ __('Featured Photo') }}</label>
                                        <div>
                                            <input type="file" name="image">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ __('Existing Banner Image') }}</label>
                                        <div>
                                            @if ($provider->banner_image)
                                                <img src="{{ asset('storage/' . $provider->banner_image) }}"
                                                    class="w_200" alt="">
                                            @else
                                                <p>{{ __('No Image') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ __('Banner Photo') }}</label>
                                        <div>
                                            <input type="file" name="banner_image">
                                        </div>
                                    </div>
                                </div>
                                <!-- // Tab 1 -->

                                <!-- Tab 2 -->
                                <div class="tab-pane fade" id="p2" role="tabpanel" aria-labelledby="p2_tab">
                                    <h4 class="heading-in-tab">{{ __('Calculations') }}</h4>
                                    <div class="row">
                                        <div id="service-discounts" class="w-100">
                                        </div>
                                    </div>
                                </div>
                                <!-- // Tab 2 -->
                                {{-- Tab 3  --}}
                                <div class="tab-pane fade" id="p3" role="tabpanel" aria-labelledby="p3_tab">
                                    <h4 class="heading-in-tab">{{ __('Moderator') }}</h4>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="name">{{ __('Moderator Name') }}</label>
                                            <input type="text" name="moderator_name" class="form-control"
                                                id="name" placeholder="{{ __('Name') }}" required
                                                value="{{ old('moderator_name') ?? optional($provider->serviceProviderModerator)->name }}">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="email">{{ __('Moderator Email') }}</label>
                                            <input type="email" name="moderator_email" class="form-control"
                                                id="email" placeholder="{{ __('Email') }}"
                                                value="{{ old('moderator_email') ?? optional($provider->serviceProviderModerator)->email }}">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="status">{{ __('Status') }}</label>
                                            <select name="moderator_status" class="form-control" id="status">
                                                <option value="active" @selected(old('moderator_status') == 'active' || optional($provider->serviceProviderModerator)->status == 'active')>
                                                    {{ __('Active') }}</option>
                                                <option value="inactive" @selected(old('moderator_status') == 'inactive' || optional($provider->serviceProviderModerator)->status == 'inactive')>
                                                    {{ __('Banned') }}</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="gender">{{ __('Gender') }}</label>
                                            <select name="moderator_gender" class="form-control" id="gender"
                                                required>
                                                <option value="m" @selected(old('moderator_status') == 'm' || optional($provider->serviceProviderModerator)->status == 'm')>
                                                    {{ __('Male') }}</option>
                                                <option value="f" @selected(old('moderator_status') == 'f' || optional($provider->serviceProviderModerator)->status == 'f')>
                                                    {{ __('Female') }}</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="password">{{ __('Password') }}</label>
                                            <input type="password" name="moderator_password" class="form-control"
                                                id="password" placeholder="{{ __('Password') }}">
                                            <small
                                                class="form-text text-danger">{{ __('Leave blank if you don\'t want to change it') }}</small>

                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="re_password">{{ __('Retype Password') }}</label>
                                            <input type="password" name="moderator_re_password" class="form-control"
                                                id="re_password" placeholder="{{ __('Retype Password') }}">
                                            <small
                                                class="form-text text-danger">{{ __('Leave blank if you don\'t want to change it') }}</small>

                                        </div>

                                        <div class="form-group">
                                            <label for="">{{ __('Existing Image') }}</label>
                                            <div>
                                                @if (optional($provider->serviceProviderModerator)->image)
                                                    <img src="{{ asset('storage/' . optional($provider->serviceProviderModerator)->image) }}"
                                                        width="100" alt="">
                                                @else
                                                    <p>{{ __('No Image') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="image" class="for">{{ __('Image') }}</label>
                                            <input type="file" name="moderator_image" class="form-control"
                                                id="image" placeholder="{{ __('Image') }}">
                                        </div>
                                    </div>
                                </div>
                                {{-- //tab 3  --}}
                                
                                 <div class="tab-pane fade" id="p4" role="tabpanel" aria-labelledby="p4_tab">
                                    <h4 class="heading-in-tab">{{ __('Privacy Policy') }}</h4>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="privacy_ar">{{ __('Privacy Content (Arabic)') }}</label>
                                            <textarea name="privacy_ar" class="form-control editor"
                                                id="privacy_ar" placeholder="{{ __('Privacy Content (Arabic)') }}" >
                                                {!! old('privacy_ar',$provider->getTranslation('privacy_policy','ar')) !!}
                                            </textarea>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="name">{{ __('Privacy Content (English)') }}</label>
                                            <textarea type="text" name="privacy_en" class="form-control editor"
                                                id="name" placeholder="{{  __('Privacy Content (English)') }}" >
                                                {!! old('privacy_en',$provider->getTranslation('privacy_policy','en')) !!}
                                            </textarea>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success btn-block mb_40">{{ __('Update') }}</button>
        </form>
        <script>
    let oldServiceDiscounts = @json(
        old('service_discounts', $serviceDiscounts)
    );

            $(document).ready(function() {
                let discountsContainer = $('#service-discounts');

                function renderBlocks(selectedServices) {
                    discountsContainer.empty();

                    selectedServices.forEach(serviceId => {
                        let serviceName = $("#services option[value='" + serviceId + "']").text();

                        // Use optional chaining (?.) safely, defaulting to empty string
                        let providerVal = oldServiceDiscounts?.[serviceId]?.provider_discount ?? '';
                        let normalVal = oldServiceDiscounts?.[serviceId]?.normal_discount ?? '';

                        let block = `
                    <div class="card mb-3 w-100 shadow-sm d-inline-block" style="background: #f5f5f5;">
                        <h5 class="m-2 text-center">${serviceName}</h5>
                        <div class="card-body row">
                            <div class="col-md-6">
                                <label>Provider Discount</label>
                                <input type="number" step="0.01"
                                    name="service_discounts[${serviceId}][provider_discount]"
                                    class="form-control"
                                    value="${providerVal}">
                            </div>
                            <div class="col-md-6">
                                <label>Normal Discount</label>
                                <input type="number" step="0.01"
                                    name="service_discounts[${serviceId}][normal_discount]"
                                    class="form-control"
                                    value="${normalVal}">
                            </div>
                        </div>
                    </div>
                `;

                        discountsContainer.append(block);
                    });
                }


                $('#services').on('change', function() {
                    renderBlocks($(this).val() || []);
                });

                // trigger initial load (for edit mode)
                $('#services').trigger('change');
            });
        </script>


    </div>
</x-dashboard.main-layout>
