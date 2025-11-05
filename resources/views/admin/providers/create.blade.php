<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp
    <div class="card-body">
        <form class="my-3" action="{{ route('admins.providers.store') }}" method="post" enctype="multipart/form-data">
            @csrf
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
                                    aria-controls="p3" aria-selected="false">{{ __('Calculations') }}
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
                                                    value="{{ old('name_ar') }}" autofocus>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{ __('Name In English') }}</label>
                                                <input type="text" name="name_en" class="form-control"
                                                    value="{{ old('name_en') }}" autofocus>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="services">{{ __('Services') }}</label>
                                                <select name="services[]" class="w-100 select2" id="services" multiple>
                                                    @foreach ($services as $service)
                                                        <option value="{{ $service['id'] }}"
                                                            @selected(in_array($service['id'], old('services', [])))>
                                                            {{ $service['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="time_from">{{ __('Time From') }}</label>
                                                <input type="time" name="time_from" class="form-control"
                                                    value="{{ old('time_from') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="time_to">{{ __('Time To') }}</label>
                                                <input type="time" name="time_to" class="form-control"
                                                    value="{{ old('time_to') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ __('Description In Arabic') }}</label>
                                        <textarea name="description_ar" class="form-control" cols="30" rows="10">{{ old('description_ar') }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ __('Description In English') }}</label>
                                        <textarea name="description_en" class="form-control" cols="30" rows="10">{{ old('description_en') }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ __('Category') }}</label>
                                        <select name="category_id" class="form-control">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }} -
                                                    {{ $category->getTranslation('name', $rev_locale) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="">{{ __('Class') }}</label>
                                        <select name="class" class="form-control">
                                            @foreach (range('A', 'J') as $class)
                                                <option value="{{ strtolower($class) }}" @selected(old('class') == strtolower($class))>{{ __('Class '). $class }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ __('Featured Photo') }}</label>
                                        <div>
                                            <input type="file" name="image">
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
                                        <div id="service-discounts" class="w-100"></div>
                                        <!--<div class="col-md-4">-->
                                        <!--    <div class="form-group">-->
                                        <!--        <label for="">{{ __('Silver Percentage') }}</label>-->
                                        <!--        <input type="number" step="0.01" name="silver_percentage"-->
                                        <!--            class="form-control" value="{{ old('silver_percentage') }}">-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                        <!--<div class="col-md-4">-->
                                        <!--    <div class="form-group">-->
                                        <!--        <label for="">{{ __('Golden Percentage') }}</label>-->
                                        <!--        <input type="number" step="0.01" name="golden_percentage"-->
                                        <!--            class="form-control" value="{{ old('golden_percentage') }}">-->
                                        <!--    </div>-->
                                        <!--</div>-->

                                    </div>
                                </div>
                                <!--// Tab 2 -->
                                <!-- Tab 3 -->
                                <div class="tab-pane fade" id="p3" role="tabpanel" aria-labelledby="p3_tab">
                                    <h4 class="heading-in-tab">{{ __('Moderator') }}</h4>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="name">{{ __('Moderator Name') }}</label>
                                            <input type="text" name="moderator_name" class="form-control"
                                                id="name" placeholder="{{ __('Name') }}" required
                                                value="{{ old('moderator_name') }}">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="email">{{ __('Moderator Email') }}</label>
                                            <input type="email" name="moderator_email" class="form-control"
                                                id="email" placeholder="{{ __('Email') }}"
                                                value="{{ old('moderator_email') }}">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="status">{{ __('Status') }}</label>
                                            <select name="moderator_status" class="form-control" id="status">
                                                <option value="active" @selected(old('moderator_status') == 'active')>
                                                    {{ __('Active') }}</option>
                                                <option value="inactive" @selected(old('moderator_status') == 'inactive')>
                                                    {{ __('Banned') }}</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="gender">{{ __('Gender') }}</label>
                                            <select name="moderator_gender" class="form-control" id="gender"
                                                required>
                                                <option value="m" @selected(old('moderator_gender') == 'm')>
                                                    {{ __('Male') }}</option>
                                                <option value="f" @selected(old('moderator_gender') == 'f')>
                                                    {{ __('Female') }}</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="password">{{ __('Password') }}</label>
                                            <input type="password" name="moderator_password" class="form-control"
                                                id="password" placeholder="{{ __('Password') }}" required>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="re_password">{{ __('Retype Password') }}</label>
                                            <input type="password" name="moderator_re_password" class="form-control"
                                                id="re_password" placeholder="{{ __('Retype Password') }}" required>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="image" class="for">{{ __('Image') }}</label>
                                            <input type="file" name="moderator_image" class="form-control"
                                                id="image" placeholder="{{ __('Image') }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="tab-pane fade" id="p4" role="tabpanel" aria-labelledby="p4_tab">
                                    <h4 class="heading-in-tab">{{ __('Privacy Policy') }}</h4>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="privacy_ar">{{ __('Privacy Content (Arabic)') }}</label>
                                            <textarea name="privacy_ar" class="form-control editor"
                                                id="privacy_ar" placeholder="{{ __('Privacy Content (Arabic)') }}" >
                                                {!! old('privacy_ar') !!}
                                            </textarea>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="name">{{ __('Privacy Content (English)') }}</label>
                                            <textarea type="text" name="privacy_en" class="form-control editor"
                                                id="name" placeholder="{{  __('Privacy Content (English)') }}" >
                                                {!! old('privacy_en') !!}
                                            </textarea>
                                        </div>

                                    </div>
                                </div>
                                <!-- // Tab 3 -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success btn-block mb_40">{{ __('Create') }}</button>
        </form>

    </div>
    <script>
        $(document).ready(function() {
            $('#services').on('change', function() {
                let selectedServices = $(this).val();
                let discountsContainer = $('#service-discounts');

                discountsContainer.empty(); // clear old inputs

                if (selectedServices && selectedServices.length > 0) {
                    selectedServices.forEach(serviceId => {
                        let serviceName = $("#services option[value='" + serviceId + "']").text();

                        // Create a card for each service
                        let block = `
                    <div class="card mb-3 w-100 shadow-sm d-inline-block" style="background: #f5f5f5;">
                        <h5 class="m-2 text-center">${serviceName}</h5>
                        <div class="card-body row">
                                <div class="col-md-6">
                                    <label>{{ __('Provider Discount') }}</label>
                                    <input type="number" step="0.01" 
                                           name="service_discounts[${serviceId}][provider_discount]" 
                                           class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label>{{ __('Normal Discount') }}</label>
                                    <input type="number" step="0.01" 
                                           name="service_discounts[${serviceId}][normal_discount]" 
                                           class="form-control">
                                </div>
                        </div>
                    </div>
                `;

                        discountsContainer.append(block);
                    });
                }
            });

            // Trigger change event if old values exist (edit mode)
            $('#services').trigger('change');
        });
    </script>
</x-dashboard.main-layout>
