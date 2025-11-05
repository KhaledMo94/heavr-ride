<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp
    <h1 class="mb-3 text-gray-800 h3">{{ __('Branches') }}</h1>
    <div class="mb-4 shadow card">
        <div class="py-3 card-header">
            <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable-ar" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Serial') }}</th>
                            <th>{{ __('Address') }}</th>
                            <th>{{ __('Location') }}</th>
                            <th>{{ __('City Name') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Alternative Phone') }}</th>
                            <th>{{ __('Cashiers Count') }}</th>
                            <!--<th>{{ __('Failed Order This Month') }}</th>-->
                            <!--<th>{{ __('Failed Order') }}</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=0; @endphp
                        @foreach ($branches as $branch)
                            <tr data-id="{{ $branch->id }}">
                                <td>{{ ++$i }}</td>
                                <td>{{ $branch->getTranslation('address', app()->getLocale()) }} <br>
                                    {{ $branch->getTranslation('address', $rev_locale) }}</td>
                                <td>
                                    <a href="https://maps.google.com/?latitude={{ $branch->latitude }}&longitude={{ $branch->longitude }}"
                                        target="_blank">{{ __('View on Map') }}
                                    </a>
                                </td>

                                <td>{{ $branch->city->getTranslation('name', app()->getLocale()) }}
                                    <br>
                                    {{ $branch->city->getTranslation('name', $rev_locale) }}
                                </td>

                                <td>{{ $branch->phone }}</td>
                                <td>{{ $branch->phone_alt }}</td>
                                <td>{{ $branch->users_count }}</td>
                                <!--<td>{{ $branch->failed_orders_this_month_count }}</td>-->
                                <!--<td>{{ $branch->failed_orders_count }}</td>-->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-dashboard.main-layout>
