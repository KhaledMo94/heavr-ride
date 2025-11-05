<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'ar' ? 'en' : 'ar';
    @endphp
    <h1 class="mb-3 text-gray-800 h3">{{ __('Orders') }}</h1>
    <div class="mb-4 shadow card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable-ar" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Serial') }}</th>
                            <th>{{ __('Order Ref.') }}</th>
                            <th>{{ __('Service Type') }}</th>
                            <th>{{ __('Branch Address') }}</th>
                            <th>{{ __('Sum') }}</th>
                            <th>{{ __('Paid Amount') }}</th>
                            <th>{{ __('Quick Discount Percentage') }}</th>
                            <th>{{ __('Quick Discount Share Profit') }}</th>
                            <th>{{ __('Cashier Name') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Created At') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=0; @endphp
                        @foreach ($orders as $order)
                            <tr data-id="{{ $order->id }}">
                                <td>{{ ++$i }}</td>
                                <td>{{ $order->uuid }}</td>
                                <td>{{ __($order->service?->getTranslation('name',app()->getLocale())) ?? __($order->getTranslation('service_name',app()->getLocale())) }}</td>
                                <td>{{ __($order->serviceProviderBranch->getTranslation('address',app()->getLocale())) ?? __($order->getTranslation('service_provider_branch_address',app()->getLocale())) }}</td>
                                <td style="direction: ltr">{{ $order->sum }}</td>
                                <td style="direction: ltr">{{ $order->sum - $order->applicable_discount }}</td>
                                <td style="direction: ltr">{{ $order->profit_percentage }} %</td>
                                <td style="direction: ltr">{{ $order->profit }} </td>
                                <td>{{ __($order->cashier->name) ?? __($order->cashier_name) }}</td>
                                <td>
                                    @if ($order->status == 'success')
                                        <span class="badge badge-success">{{ __('Success') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ $order->status == 'pending' ? __('Pending') : __('Failed') }}</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-dashboard.main-layout>
