<x-dashboard.main-layout>

    <div class="card-body">
        <form class="my-3" action="{{ route('admins.admins.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">{{ __('Name') }}</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="{{ __('Name') }}"
                    required value="{{ old('name') }}">
            </div>

            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input type="email" name="email" class="form-control" id="email"
                    placeholder="{{ __('Email') }}" required value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label for="password">{{ __('Password') }}</label>
                <input type="password" name="password" class="form-control" id="password"
                    placeholder="{{ __('Password') }}" required value="{{ old('password') }}">
            </div>

            <div class="form-group">
                <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                <input type="password" name="password_confirmation" class="form-control"
                    id="password_confirmation" placeholder="{{ __('Confirm Password') }}" required
                    value="{{ old('password_confirmation') }}">
            </div>

            <div class="form-group">
                <label for="role">{{ __('Country Code') }}</label>
                <input type="text" name="country_code" class="form-control" id="country_code"
                    placeholder="{{ __('Country Code') }}" required value="{{ old('country_code') }}">
            </div>

            <div class="form-group">
                <label for="role">{{ __('Phone') }}</label>
                <input type="text" name="phone_number" class="form-control" id="phone"
                    placeholder="{{ __('Phone') }}" required value="{{ old('phone_number') }}">
            </div>

            <div class="form-group">
                <label for="status">{{ __('Status') }}</label>
                <select name="status" class="form-control" id="status" required>
                    <option value="active" @selected(old('status') == 'active')>{{ __('Active') }}</option>
                    <option value="inactive" @selected(old('status') == 'inactive')>{{ __('Banned') }}</option>
                </select>
            </div>
            @php
                $permissions_tr = [];
                foreach ($permissions as $value) {
                    $permissions_tr[] = [
                        'id' => $value->id,
                        'name' => __(ucfirst($value->name)),
                    ];
                }
            @endphp

            <div class="form-group">
                <label for="permission">{{ __('Permissions') }}</label>
                <select name="permissions[]" class="form-control select2" id="permissions" required multiple >
                    @foreach ($permissions_tr as $permission)
                        <option dir="rtl" value="{{ $permission['id'] }}" @selected(in_array($permission['id'], old('permissions', [])))>
                            {{ $permission['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="image" class="for">{{ __('Image') }}</label>
                <input type="file" name="image" class="form-control" id="image"
                    placeholder="{{ __('Image') }}">
            </div>

            <button type="submit" class="btn btn-success btn-block mb_40">{{ __('Create') }}</button>
        </form>

    </div>

    @if (app()->getLocale() == 'ar')
        <style>
            .select2-results__option--selectable{
                display: flex
            }
        </style>
    @endif
</x-dashboard.main-layout>
