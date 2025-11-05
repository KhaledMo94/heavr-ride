<x-dashboard.main-layout>

    <div class="row">
        <div class="mb-2 col-xl-12 col-md-12">
            <h1 class="mb-3 text-gray-800 h3">{{ __('Dashboard') }}</h1>
        </div>
    </div>

    <!-- Box Start -->
    <div class="row dashboard-page" data-aos="fade-up">
        @hasrole('admin|super-admin')
            @haspermission('users')
                <div class="mb-4 col-xl-3 col-md-6">
                    <div class="py-2 shadow card border-left-success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="mr-2 col">
                                    <div class="mb-1 h4 font-weight-bold text-success">{{ __('Users') }}</div>
                                    <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $users_count }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="text-gray-300 fas fa-user-friends fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xl-3 col-md-6">
                    <div class="py-2 shadow card border-left-success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="mr-2 col">
                                    <div class="mb-1 h4 font-weight-bold text-success">{{ __('Male Users') }}</div>
                                    <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $male_count }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="text-gray-300 fas fa-user-friends fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xl-3 col-md-6">
                    <div class="py-2 shadow card border-left-success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="mr-2 col">
                                    <div class="mb-1 h4 font-weight-bold text-success">{{ __('Female Users') }}</div>
                                    <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $female_count }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="text-gray-300 fas fa-user-friends fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xl-3 col-md-6">
                    <div class="py-2 shadow card border-left-success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="mr-2 col">
                                    <div class="mb-1 h4 font-weight-bold text-success">{{ __('Banned Users') }}</div>
                                    <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $banned_count }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="text-gray-300 fas fa-user-friends fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endhaspermission
            @haspermission('categories')
                <div class="mb-4 col-xl-3 col-md-6">
                    <div class="py-2 shadow card border-left-success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="mr-2 col">
                                    <div class="mb-1 h4 font-weight-bold text-success">{{ __('Main Categories') }}</div>
                                    <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $main_categories_count }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="text-gray-300 fas fa-tags fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xl-3 col-md-6">
                    <div class="py-2 shadow card border-left-success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="mr-2 col">
                                    <div class="mb-1 h4 font-weight-bold text-success">{{ __('Sub Categories') }}</div>
                                    <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $sub_categories_count }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="text-gray-300 fas fa-tags fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xl-3 col-md-6">
                    <div class="py-2 shadow card border-left-success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="mr-2 col">
                                    <div class="mb-1 h4 font-weight-bold text-success">{{ __('Pending Categories') }}</div>
                                    <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $pending_categories }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="text-gray-300 fas fa-tags fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endhaspermission
            @haspermission('providers')
                <div class="mb-4 col-xl-3 col-md-6">
                    <div class="py-2 shadow card border-left-success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="mr-2 col">
                                    <div class="mb-1 h4 font-weight-bold text-success">{{ __('Service Providers') }}</div>
                                    <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $providers_count }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="text-gray-300 fas fa-user-tie fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xl-3 col-md-6">
                    <div class="py-2 shadow card border-left-success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="mr-2 col">
                                    <div class="mb-1 h4 font-weight-bold text-success">{{ __('Pending Service Providers') }}
                                    </div>
                                    <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $pending_providers_count }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="text-gray-300 fas fa-user-tie fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endhaspermission

            @haspermission('orders')
                <div class="mb-4 col-xl-3 col-md-6">
                    <div class="py-2 shadow card border-left-success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="mr-2 col">
                                    <div class="mb-1 h4 font-weight-bold text-success">{{ __('Orders Today') }}</div>
                                    <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $orders_today }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="text-gray-300 fas fa-receipt fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xl-3 col-md-6">
                    <div class="py-2 shadow card border-left-success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="mr-2 col">
                                    <div class="mb-1 h4 font-weight-bold text-success">{{ __('Orders this Month') }}</div>
                                    <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $orders_this_month }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="text-gray-300 fas fa-receipt fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xl-3 col-md-6">
                    <div class="py-2 shadow card border-left-success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="mr-2 col">
                                    <div class="mb-1 h4 font-weight-bold text-success">{{ __('Orders This Year') }}</div>
                                    <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $orders_this_year }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="text-gray-300 fas fa-receipt fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @role('super-admin')
                    <div class="mb-4 col-xl-3 col-md-6">
                        <div class="py-2 shadow card border-left-success h-100">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="mr-2 col">
                                        <div class="mb-1 h4 font-weight-bold text-success">{{ __('All Sales') }}</div>
                                        <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $orders_total_sales }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="text-gray-300 fas fa-chart-line fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 col-xl-3 col-md-6">
                        <div class="py-2 shadow card border-left-success h-100">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="mr-2 col">
                                        <div class="mb-1 h4 font-weight-bold text-success">{{ __('Today All Sales') }}</div>
                                        <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $orders_total_sales }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="text-gray-300 fas fa-chart-line fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 col-xl-3 col-md-6">
                        <div class="py-2 shadow card border-left-success h-100">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="mr-2 col">
                                        <div class="mb-1 h4 font-weight-bold text-success">{{ __('This Month Sales') }}</div>
                                        <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $orders_total_sales_this_month }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="text-gray-300 fas fa-chart-line fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 col-xl-3 col-md-6">
                        <div class="py-2 shadow card border-left-success h-100">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="mr-2 col">
                                        <div class="mb-1 h4 font-weight-bold text-success">{{ __('Profit Today') }}</div>
                                        <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $today_profit }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="text-gray-300 fas fa-coins fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 col-xl-3 col-md-6">
                        <div class="py-2 shadow card border-left-success h-100">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="mr-2 col">
                                        <div class="mb-1 h4 font-weight-bold text-success">{{ __('This Month Profit') }}</div>
                                        <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $this_month_profit }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="text-gray-300 fas fa-coins fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 col-xl-3 col-md-6">
                        <div class="py-2 shadow card border-left-success h-100">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="mr-2 col">
                                        <div class="mb-1 h4 font-weight-bold text-success">{{ __('Overall Profit') }}</div>
                                        <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $overall_profit }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="text-gray-300 fas fa-coins fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endrole

                <h4 class="w-100 d-flex justify-content-center align-items-center text-primary">{{ __('Orders Overview') }}
                </h4>
                <div style="position:relative; height:40vh; width:80%"
                    class="my-3 mb-4 d-flex justify-content-center align-items-center col-xl-12 col-md-12">
                    <canvas id="profitLineChart"></canvas>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const ctx = document.getElementById('profitLineChart').getContext('2d');

                    // Gradient for Profit
                    const profitGradient = ctx.createLinearGradient(0, 0, 0, 400);
                    profitGradient.addColorStop(0, 'rgba(54, 162, 235, 0.4)');
                    profitGradient.addColorStop(1, 'rgba(54, 162, 235, 0.05)');

                    // Gradient for Orders
                    const ordersGradient = ctx.createLinearGradient(0, 0, 0, 400);
                    ordersGradient.addColorStop(0, 'rgba(255, 99, 132, 0.4)');
                    ordersGradient.addColorStop(1, 'rgba(255, 99, 132, 0.05)');

                    const profitChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: [
                                "{{ __('January') }}", "{{ __('February') }}", "{{ __('March') }}",
                                "{{ __('April') }}", "{{ __('May') }}", "{{ __('June') }}",
                                "{{ __('July') }}", "{{ __('August') }}", "{{ __('September') }}",
                                "{{ __('October') }}", "{{ __('November') }}", "{{ __('December') }}"
                            ],
                            datasets: [{
                                    label: "{{ __('Successful Orders') }}",
                                    data: @json($successOrderCount),
                                    fill: true,
                                    backgroundColor: profitGradient,
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 2,
                                    tension: 0.4,
                                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                                    pointRadius: 3
                                },
                                {
                                    label: "{{ __('Unsuccessful Orders') }}",
                                    data: @json($othersOrdersCount),
                                    fill: true,
                                    backgroundColor: ordersGradient,
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 2,
                                    tension: 0.4,
                                    pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                                    pointRadius: 3
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: {
                                duration: 2000,
                                easing: 'easeOutQuart'
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    callbacks: {
                                        label: function(context) {
                                            const value = context.formattedValue.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                            return context.dataset.label + ': ' + value;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: "{{ __('Month') }}"
                                    }
                                },
                                y: {
                                    beginAtZero: true,

                                    title: {
                                        display: true,
                                        text: ''
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            return value.toLocaleString() + ' ';
                                        },
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                </script>
            @endhaspermission
        @endhasrole
    </div>
</x-dashboard.main-layout>
