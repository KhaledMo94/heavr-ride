<div class="mb-4 shadow card">
    <div class="py-3 card-header">
        <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
        <div class="float-right d-inline">
            <a href="{{ route('admins.cashiers.create') }}" class="btn btn-primary btn-sm"><i
                    class="fa fa-plus"></i>{{ __('Add New') }}
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
            <!-- ⚙️ Status Filter -->
            <select wire:model.live="selectedStatus" class="form-control form-control-sm w-auto">
                <option value="">{{ __('All Statuses') }}</option>
                <option value="active">{{ __('Active') }}</option>
                <option value="inactive">{{ __('Inactive') }}</option>
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
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Image') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Service Provider') }}</th>
                        <th>{{ __('Service Provider Image') }}</th>
                        <th>{{ __('Branch Address') }}</th>
                        <th>{{ __('Failed Orders This Month Count') }}</th>
                        <th>{{ __('Overall Failed Orders Count') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i=0; @endphp
                    @foreach ($cashiers as $cashier)
                        <tr data-id="{{ $cashier->id }}">
                            <td>{{ ++$i }}</td>
                            <td>{{ $cashier->name ?? '' }}</td>
                            <td>{{ $cashier->email ?? __('No Email') }}</td>
                            <td>
                                @if ($cashier->image)
                                    <img src="{{ asset('storage/' . $cashier->image) }}" alt="{{ $cashier->name }}"
                                        width="50px">
                                @endif
                            </td>
                            <td style="direction: ltr">{{ $cashier->phone_number ? $cashier->phone : __('No Number') }}
                            </td>

                            <td>
                                {{ $cashier->serviceProviderBranch->serviceProvider->getTranslation('name', app()->getLocale()) }}
                                <br>
                                {{ $cashier->serviceProviderBranch->serviceProvider->getTranslation('name', $rev_locale) }}
                            </td>

                            <td>
                                @if ($cashier->serviceProviderBranch->serviceProvider->image)
                                    <img src="{{ asset('storage/' . $cashier->serviceProviderBranch->serviceProvider->image) }}"
                                        alt="" width="100px">
                                @endif
                            </td>
                            <td>{{ $cashier->serviceProviderBranch->getTranslation('address', app()->getLocale()) }}
                                <br>
                                {{ $cashier->serviceProviderBranch->getTranslation('address', $rev_locale) }}
                            </td>
                            <td>{{ $cashier->failed_orders_this_month_count }}</td>
                            <td>{{ $cashier->failed_orders_count }}</td>
                            <td>
                                <input type="checkbox" @if ($cashier->status == 'active') checked @endif
                                    data-toggle="toggle" data-on="{{ __('Active') }}" data-off="{{ __('Banned') }}"
                                    data-onstyle="success" data-id = "{{ $cashier->id }}" data-offstyle="danger">
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admins.cashiers.edit', $cashier->id) }}"
                                        class="mx-1 btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admins.cashiers.destroy', $cashier->id) }}"
                                        id="delete-form-{{ $cashier->id }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="mx-1 btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $cashier->id }}); event.preventDefault();">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
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
        {{ $cashiers->links() }}
    </div>
</div>
