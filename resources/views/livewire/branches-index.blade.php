<div class="mb-4 shadow card">
    <div class="py-3 card-header">
        <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
        <div class="float-right d-inline">
            <a href="{{ route('admins.branches.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i>{{ __('Add New') }}
            </a>
        </div>
    </div>

    <div class="px-3 py-2 border-bottom bg-light">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <input wire:model.live.debounce.500="search" type="text"
                class="form-control form-control-sm w-auto flex-grow-1"
                placeholder="{{ __('Search by name or description...') }}">

            <select wire:model.live="selectedProvider" class="form-control form-control-sm w-auto">
                <option value="">{{ __('All Service Providers') }}</option>
                @foreach ($serviceProviders as $provider)
                    <option value="{{ $provider->id }}">
                        {{ $provider->getTranslation('name', app()->getLocale()) }}
                    </option>
                @endforeach
            </select>
            <select wire:model.live="selectedCity" class="form-control form-control-sm w-auto">
                <option value="">{{ __('All Cities') }}</option>
                @foreach ($cities as $city)
                    <option value="{{ $city->id }}">
                        {{ $city->getTranslation('name', app()->getLocale()) }}
                    </option>
                @endforeach
            </select>

        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable-ar" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>{{ __('Serial') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Location') }}</th>
                        <th>{{ __('Service Provider') }}</th>
                        <th>{{ __('City Name') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Cashiers Count') }}</th>
                        <th>{{ __('Failed Order This Month') }}</th>
                        <th>{{ __('Failed Order') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i=0; @endphp
                    @foreach ($branches as $branch)
                        <tr data-id="{{ $branch->id }}">
                            <td>{{ ++$i }}</td>
                            <td>{{ $branch->getTranslation('name', app()->getLocale()) }}
                                <br>
                                {{ $branch->getTranslation('name', $rev_locale) }}
                            </td>
                            <td>
                                <a href="https://www.google.com/maps?q={{ $branch->latitude }},{{ $branch->longitude }}"
                                    target="_blank">
                                    {{ __('View on Map') }}
                                </a>
                            </td>
                            <td>{{ $branch->serviceProvider->getTranslation('name', app()->getLocale()) }}
                                <br>
                                {{ $branch->serviceProvider->getTranslation('name', $rev_locale) }}
                            </td>
                            <td>{{ $branch->city->getTranslation('name', app()->getLocale()) }}
                                <br>
                                {{ $branch->city->getTranslation('name', $rev_locale) }}
                            </td>
                            <td>{{ $branch->phone }}</td>
                            <td>{{ $branch->users_count }}</td>
                            <td>{{ $branch->failed_orders_this_month_count }}</td>
                            <td>{{ $branch->failed_orders_count }}</td>
                            <td>
                                <div class="d-flex justify-content-center h-100">
                                    <a href="{{ route('admins.branches.show', $branch->id) }}"
                                        class="mx-1 btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admins.branches.edit', $branch->id) }}"
                                        class="mx-1 btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form id="delete-form-{{ $branch->id }}"
                                        action="{{ route('admins.branches.destroy', $branch->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="mx-1 btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $branch->id }}); event.preventDefault(); ">
                                            <i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="py-2 d-flex justify-content-center">
        {{ $branches->links() }}
    </div>
</div>
