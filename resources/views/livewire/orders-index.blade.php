<div class="mb-4 shadow card">
    <div class="px-3 py-2 border-bottom bg-light">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <input wire:model.live.debounce.500="search" type="text"
                class="form-control form-control-sm w-auto flex-grow-1"
                placeholder="{{ __('Search by name or description...') }}">

            <select wire:model.live="selectedServiceProvider" class="form-control form-control-sm w-auto">
                <option value="">{{ __('All Service Providers') }}</option>
                @foreach ($serviceProviders as $provider)
                    <option value="{{ $provider->id }}">
                        {{ $provider->getTranslation('name', app()->getLocale()) }}
                    </option>
                @endforeach
            </select>
            <select wire:model.live="selectedService" class="form-control form-control-sm w-auto">
                <option value="">{{ __('All Services') }}</option>
                @foreach ($services as $service)
                    <option value="{{ $service->id }}">
                        {{ $service->getTranslation('name', app()->getLocale()) }}
                    </option>
                @endforeach
            </select>
            <select wire:model.live="selectedStatus" class="form-control form-control-sm w-auto">
                <option value="">{{ __('All Statuses') }}</option>
                <option value="success">{{ __('Success') }}</option>
                <option value="failed">{{ __('Failed') }}</option>
            </select>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable-ar" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>{{ __('Serial') }}</th>
                        <th>{{ __('Client Name') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Service Provider') }}</th>
                        <th>{{ __('Service') }}</th>
                        <th>{{ __('Sum') }}</th>
                        <th>{{ __('Paid Amount') }}</th>
                        <th>{{ __('Applicable Discounts %') }}</th>
                        <th>{{ __('Our Percent %') }}</th>
                        <th>{{ __('Our Profit') }}</th>
                        <th>{{ __('Cashier Name') }}</th>
                        <th>{{ __('Branch Address') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Created At') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i=0; @endphp
                    @foreach ($orders as $order)
                        <tr data-id="{{ $order->id }}">
                            <td>{{ ++$i }}</td>
                            <td>{{ $order->user->name ?? __('User Deleted') }}</td>
                            <td>{{ $order->user->phone ?? __('User Deleted') }}</td>
                            @if ($order->serviceProvider)
                                <td>{{ $order->serviceProvider->getTranslation('name', app()->getLocale()) }} <br>
                                    {{ $order->serviceProvider->getTranslation('name', $rev_locale) }}</td>
                            @else
                                <td>{{ __('Provider Deleted') }}</td>
                            @endif
                            <td>{{ $order->service?->getTranslation('name', app()->getLocale()) ??
                                $order->getTranslation('service_name', app()->getLocale()) }}
                            </td>
                            <td style="direction: ltr">{{ $order->sum }}</td>
                            <td style="direction: ltr">{{ $order->sum - $order->applicable_discount }}</td>
                            <td style="direction: ltr">{{ $order->applicable_discount_percentage }} %</td>
                            <td style="direction: ltr">{{ $order->profit_percentage }} %</td>
                            <td style="direction: ltr">{{ $order->profit }} </td>
                            <td>{{ __($order->cashier?->name ?? $order->cashier_name) }}</td>

                            <td>{{ __($order->serviceProviderBranch?->getTranslation('address', app()->getLocale())) ?? __($order->getTranslation('service_provider_branch_address', app()->getLocale())) }}
                            </td>
                            <td>
                                @if ($order->status == 'success')
                                    <span class="badge badge-success">{{ __('Success') }}</span>
                                @else
                                    <span
                                        class="badge badge-danger">{{ $order->status == 'pending' ? __('Pending') : __('Failed') }}</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
                <!-- Pagination -->
        <div class="py-3 d-flex justify-content-between">
            {{ $orders->links() }}
        </div>
    </div>
</div>
