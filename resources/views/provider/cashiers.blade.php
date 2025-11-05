<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'ar' ? 'en' : 'ar';
    @endphp
    <h1 class="mb-3 text-gray-800 h3">{{ __('Cashiers') }}</h1>
    <div class="mb-4 shadow card">

        <div class="py-3 card-header">
            <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
            <div class="float-right d-inline">
                <a href="{{ route('admins.cashiers.create') }}" class="btn btn-primary btn-sm"><i
                        class="fa fa-plus"></i>{{ __('Add New') }}</a>
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
                            <th>{{ __('Branch Address') }}</th>
                            <th>{{ __('Status') }}</th>
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
                                        <img src="{{ asset('storage/' . $cashier->image) }}"
                                            alt="{{ $cashier->name }}" width="50px">
                                    @endif
                                </td>
                                <td style="direction: ltr">{{ $cashier->phone_number ? $cashier->phone : __('No Number') }}</td>
                                <td>{{ $cashier->serviceProviderBranch->getTranslation('address', app()->getLocale()) }}
                                    <br>
                                    {{ $cashier->serviceProviderBranch->getTranslation('address', $rev_locale) }}
                                </td>
                                <td>
                                    {{ $cashier->status == 'active' ? __('Active') : __('Inactive') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="py-2 d-flex justify-content-center">
        </div>
    </div>

</x-dashboard.main-layout>
