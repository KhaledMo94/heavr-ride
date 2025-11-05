<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp

    <h1 class="mb-3 text-gray-800 h3">{{ __('Branch') }} {{ $branch->name }} {{ __('Details') }}</h1>

    <div class="row">
        <div class="col-md-12">
            <div class="mb-4 shadow card">
                <div class="py-3 card-header">
                    <div class="float-right d-inline">
                        <a href="{{ route('admins.branches.index') }}" class="btn btn-primary btn-sm">
                            {{ __('Back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">

                            <tr>
                                <td>{{ __('Branch Name') }}</td>
                                <td>
                                    {{ $branch->getTranslation('name', app()->getLocale()) }} 
                                    <br>
                                    {{ $branch->getTranslation('name', $rev_locale) }}
                                </td>
                            </tr>

                            <tr>
                                <td>{{ __('Image') }}</td>
                                <td>
                                    @if ($branch->image)
                                        <img src="{{ asset('storage/' . $branch->image) }}" class="w_100">
                                    @else
                                        <p>{{ __('No Image') }}</p>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td>{{ __('Status') }}</td>
                                <td>{{ $branch->status == 'active' ? __('Active') : __('Inactive') }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('Provider') }}</td>
                                <td>
                                    {{ $branch->serviceProvider->getTranslation('name', app()->getLocale()) }}
                                    <br>
                                    {{ $branch->serviceProvider->getTranslation('name', $rev_locale) }}
                                </td>
                            </tr>

                            <tr>
                                <td>{{ __('Address') }}</td>
                                <td>{{ $branch->address ?? __('N/A') }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('Location') }}</td>
                                <td>
                                    <a href="https://www.google.com/maps?q={{ $branch->latitude }},{{ $branch->longitude }}"
                                        target="_blank">
                                        {{ __('View on Map') }}
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td>{{ __('Cashiers Count') }}</td>
                                <td>
                                   {{ $branch->users_count }}
                                </td>
                            </tr>

                            <tr>
                                <td>{{ __('Failed Order This Month') }}</td>
                                <td>
                                    {{ $branch->failed_orders_this_month_count }}
                                </td>
                            </tr>

                            <tr>
                                <td>{{ __('Failed Order') }}</td>
                                <td>{{ $branch->failed_orders_count }}</td>
                            </tr>


                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard.main-layout>
