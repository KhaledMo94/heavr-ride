<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}"
        style="margin-top: 30px;margin-bottom:20px">
        <div class="mx-3 sidebar-brand-text ttn d-flex align-items-center justify-content-center">
            <div class="center d-flex align-items-center justify-content-center">
                <img width="60%" class="py-2 my-2" src="{{ asset(config('app.logo')) }}" alt="">
            </div>
            <!-- <div class="right">
               {{ env('APP_NAME') }}
            </div> -->
        </div>
    </a>

    <!-- Divider -->
    <hr class="my-0 sidebar-divider">

    <!-- Dashboard -->
    <li class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-home"></i>
            <span> {{ __('Dashboard') }} </span>
        </a>
    </li>

    @hasrole('admin|super-admin')
        @can('categories')
            <li class="nav-item {{ Route::is('admins.categories.*') || Route::is('admins.main-categories.*') ? 'active' : '' }}"
                style="{{ Route::is('admins.categories.*') || Route::is('admins.main-categories.*') ? 'background-color: darkslategrey;' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsecategories"
                    aria-expanded="true" aria-controls="collapsecategories">
                    <i class="far fa-caret-square-right"></i>
                    <span>{{ __('Categories Section') }}</span>
                </a>
                <div id="collapsecategories"
                    class="collapse {{ Route::is('admins.categories.*') || Route::is('admins.main-categories.*') ? 'show' : '' }}"
                    aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="py-2 bg-white rounded collapse-inner">
                        <a class="collapse-item"
                            href="{{ route('admins.main-categories.index') }}">{{ __('Main Categories') }}</a>
                        <a class="collapse-item" href="{{ route('admins.categories.index') }}">{{ __('Sub Categories') }}</a>
                        <a class="collapse-item"
                            href="{{ route('admins.main-categories.create') }}">{{ __('Create Main Category') }}</a>
                        <a class="collapse-item"
                            href="{{ route('admins.categories.create') }}">{{ __('Create Sub Category') }}</a>
                    </div>
                </div>
            </li>
        @endcan

        @can('providers')
            <li class="nav-item {{ Route::is('admins.providers.*') ? 'active' : '' }}"
                style="{{ Route::is('admins.providers.*') ? 'background-color: darkslategrey;' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseproviders"
                    aria-expanded="true" aria-controls="collapseproviders">
                    <i class="far fa-caret-square-right"></i>
                    <span>{{ __('Providers Section') }}</span>
                </a>
                <div id="collapseproviders" class="collapse {{ Route::is('admins.providers.*') ? 'show' : '' }}"
                    aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="py-2 bg-white rounded collapse-inner">
                        <a class="collapse-item"
                            href="{{ route('admins.providers.index') }}">{{ __('Service providers') }}</a>
                        <a class="collapse-item"
                            href="{{ route('admins.providers.create') }}">{{ __('Create Service Provider') }}</a>
                    </div>
                </div>
            </li>

            <li class="nav-item {{ Route::is('admins.branches.*') ? 'active' : '' }}"
                style="{{ Route::is('admins.branches.*') ? 'background-color: darkslategrey;' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsebranches"
                    aria-expanded="true" aria-controls="collapsebranches">
                    <i class="far fa-caret-square-right"></i>
                    <span>{{ __('Branches Section') }}</span>
                </a>
                <div id="collapsebranches" class="collapse {{ Route::is('admins.branches.*') ? 'show' : '' }}"
                    aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="py-2 bg-white rounded collapse-inner">
                        <a class="collapse-item" href="{{ route('admins.branches.index') }}">{{ __('Provider branches') }}</a>
                        <a class="collapse-item"
                            href="{{ route('admins.branches.create') }}">{{ __('Create Provider Branch') }}</a>
                    </div>
                </div>
            </li>
        @endcan

        @can('users')
            <li class="nav-item {{ Route::is('admins.users.*') ? 'active' : '' }}"
                style="{{ Route::is('admins.users.*') ? 'background-color: darkslategrey;' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseusers"
                    aria-expanded="true" aria-controls="collapseusers">
                    <i class="far fa-caret-square-right"></i>
                    <span>{{ __('Users Section') }}</span>
                </a>
                <div id="collapseusers" class="collapse {{ Route::is('admins.users.*') ? 'show' : '' }}"
                    aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="py-2 bg-white rounded collapse-inner">
                        <a class="collapse-item" href="{{ route('admins.users.index') }}">{{ __('All Users') }}</a>
                    </div>
                </div>
            </li>
        @endcan

        @can('cities')
            <li class="nav-item {{ Route::is('admins.cities.*') ? 'active' : '' }}"
                style="{{ Route::is('admins.cities.*') ? 'background-color: darkslategrey;' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsecities"
                    aria-expanded="true" aria-controls="collapsecities">
                    <i class="far fa-caret-square-right"></i>
                    <span>{{ __('Cities Section') }}</span>
                </a>
                <div id="collapsecities" class="collapse {{ Route::is('admins.cities.*') ? 'show' : '' }}"
                    aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="py-2 bg-white rounded collapse-inner">
                        <a class="collapse-item" href="{{ route('admins.cities.index') }}">{{ __('All Cities') }}</a>
                        <a class="collapse-item" href="{{ route('admins.cities.create') }}">{{ __('Create New City') }}</a>
                    </div>
                </div>
            </li>
        @endcan

        @can('banners')
            <li class="nav-item {{ Route::is('admins.banners.*') ? 'active' : '' }}"
                style="{{ Route::is('admins.banners.*') ? 'background-color: darkslategrey;' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsebanners"
                    aria-expanded="true" aria-controls="collapsebanners">
                    <i class="far fa-caret-square-right"></i>
                    <span>{{ __('Banners Section') }}</span>
                </a>
                <div id="collapsebanners" class="collapse {{ Route::is('admins.banners.*') ? 'show' : '' }}"
                    aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="py-2 bg-white rounded collapse-inner">
                        <a class="collapse-item" href="{{ route('admins.banners.index') }}">{{ __('All Banners') }}</a>
                        <a class="collapse-item"
                            href="{{ route('admins.banners.create') }}">{{ __('Create New Banner') }}</a>
                    </div>
                </div>
            </li>
        @endcan

        @can('cashiers')
            <li class="nav-item {{ Route::is('admins.cashier.*') ? 'active' : '' }}"
                style="{{ Route::is('admins.cashier.*') ? 'background-color: darkslategrey;' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsecashier"
                    aria-expanded="true" aria-controls="collapsecashier">
                    <i class="far fa-caret-square-right"></i>
                    <span>{{ __('Cashier Section') }}</span>
                </a>
                <div id="collapsecashier" class="collapse {{ Route::is('admins.cashier.*') ? 'show' : '' }}"
                    aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="py-2 bg-white rounded collapse-inner">
                        <a class="collapse-item" href="{{ route('admins.cashiers.index') }}">{{ __('All Cashiers') }}</a>
                        <a class="collapse-item"
                            href="{{ route('admins.cashiers.create') }}">{{ __('Create New Cashier') }}</a>
                    </div>
                </div>
            </li>
        @endcan

        @can('cards')
            <li class="nav-item {{ Route::is('admins.cards.*') ? 'active' : '' }}"
                style="{{ Route::is('admins.cards.*') ? 'background-color: darkslategrey;' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsecards"
                    aria-expanded="true" aria-controls="collapsecards">
                    <i class="far fa-caret-square-right"></i>
                    <span>{{ __('Cards Section') }}</span>
                </a>
                <div id="collapsecards" class="collapse {{ Route::is('admins.cards.*') ? 'show' : '' }}"
                    aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="py-2 bg-white rounded collapse-inner">
                        <a class="collapse-item" href="{{ route('admins.cards.index') }}">{{ __('All Cards') }}</a>
                        <a class="collapse-item" href="{{ route('admins.cards.edit') }}">{{ __('Edit Cards') }}</a>
                    </div>
                </div>
            </li>
        @endcan

        @can('orders')
            <li class="nav-item {{ Route::is('admins.orders.*') ? 'active' : '' }}"
                style="{{ Route::is('admins.orders.*') ? 'background-color: darkslategrey;' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOrders"
                    aria-expanded="true" aria-controls="collapseOrders">
                    <i class="far fa-caret-square-right"></i>
                    <span>{{ __('Orders Section') }}</span>
                </a>
                <div id="collapseOrders" class="collapse {{ Route::is('admins.orders.*') ? 'show' : '' }}"
                    aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="py-2 bg-white rounded collapse-inner">
                        <a class="collapse-item" href="{{ route('admins.orders.index') }}">{{ __('All Orders') }}</a>
                        <a class="collapse-item" href="{{ route('admins.orders.export') }}">{{ __('Export Orders') }}</a>
                    </div>
                </div>
            </li>
        @endcan

        @can('services')
            <li class="li nav-item {{ Route::is('admins.services.*') ? 'active' : '' }} "
                style="{{ Route::is('admins.services.*') ? 'background-color: darkslategrey;' : '' }}">
                <a class="nav-link" href="{{ route('admins.services.index') }}">
                    <i class="far fa-caret-square-right"></i>
                    <span> {{ __('Services') }} </span>
                </a>
            </li>
        @endcan

        @can('notifications')
            <li class="nav-item {{ Route::is('admins.notifications.*') ? 'active' : '' }}"
                style="{{ Route::is('admins.notifications.*') ? 'background-color: darkslategrey;' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseNotifications"
                    aria-expanded="true" aria-controls="collapseNotifications">
                    <i class="far fa-caret-square-right"></i>
                    <span>{{ __('Notifications Section') }}</span>
                </a>
                <div id="collapseNotifications" class="collapse {{ Route::is('admins.notifications.*') ? 'show' : '' }}"
                    aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="py-2 bg-white rounded collapse-inner">
                        <a class="collapse-item"
                            href="{{ route('admins.notifications.user') }}">{{ __('Users Notifications') }}</a>
                        <a class="collapse-item"
                            href="{{ route('admins.notifications.cashier') }}">{{ __('Providers Notifications') }}</a>
                    </div>
                </div>
            </li>
        @endcan

        @role('super-admin')
            <li class="nav-item {{ Route::is('admins.admins.*') ? 'active' : '' }}"
                style="{{ Route::is('admins.admins.*') ? 'background-color: darkslategrey;' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseadmins"
                    aria-expanded="true" aria-controls="collapseadmins">
                    <i class="far fa-caret-square-right"></i>
                    <span>{{ __('Admins Section') }}</span>
                </a>
                <div id="collapseadmins" class="collapse {{ Route::is('admins.admins.*') ? 'show' : '' }}"
                    aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="py-2 bg-white rounded collapse-inner">
                        <a class="collapse-item" href="{{ route('admins.admins.index') }}">{{ __('All Admins') }}</a>
                        <a class="collapse-item" href="{{ route('admins.admins.create') }}">{{ __('Create New Admin') }}</a>
                    </div>
                </div>
            </li>
        @endrole
    @endhasrole

    @hasrole('provider-moderator')
        <li class="li nav-item {{ Route::is('moderators.provider.details') ? 'active' : '' }} "
            style="{{ Route::is('moderators.provider.details') ? 'background-color: darkslategrey;' : '' }}">
            <a class="nav-link" href="{{ route('moderators.provider.details') }}">
                <i class="far fa-caret-square-right"></i>
                <span> {{ __('Company Profile') }} </span>
            </a>
        </li>
        <li class="li nav-item {{ Route::is('moderators.orders.index') ? 'active' : '' }} "
            style="{{ Route::is('moderators.orders.index') ? 'background-color: darkslategrey;' : '' }}">
            <a class="nav-link" href="{{ route('moderators.orders.index') }}">
                <i class="far fa-caret-square-right"></i>
                <span> {{ __('Latest Orders') }} </span>
            </a>
        </li>
        <li class="li nav-item {{ Route::is('moderators.orders.export') ? 'active' : '' }} "
            style="{{ Route::is('moderators.orders.export') ? 'background-color: darkslategrey;' : '' }}">
            <a class="nav-link" href="{{ route('moderators.orders.export') }}">
                <i class="far fa-caret-square-right"></i>
                <span> {{ __('Export Orders') }} </span>
            </a>
        </li>
        <li class="li nav-item {{ Route::is('moderators.provider.cashiers') ? 'active' : '' }} "
            style="{{ Route::is('moderators.provider.cashiers') ? 'background-color: darkslategrey;' : '' }}">
            <a class="nav-link" href="{{ route('moderators.provider.cashiers') }}">
                <i class="far fa-caret-square-right"></i>
                <span> {{ __('Cashiers') }} </span>
            </a>
        </li>
        <li class="li nav-item {{ Route::is('moderators.provider.branches') ? 'active' : '' }} "
            style="{{ Route::is('moderators.provider.branches') ? 'background-color: darkslategrey;' : '' }}">
            <a class="nav-link" href="{{ route('moderators.provider.branches') }}">
                <i class="far fa-caret-square-right"></i>
                <span> {{ __('Branches') }} </span>
            </a>
        </li>
        
    @endhasrole
    
    @hasrole('cashier')
        <li class="li nav-item {{ Route::is('cashiers.orders.index') ? 'active' : '' }} "
            style="{{ Route::is('cashiers.orders.index') ? 'background-color: darkslategrey;' : '' }}">
            <a class="nav-link" href="{{ route('cashiers.orders.index') }}">
                <i class="far fa-caret-square-right"></i>
                <span> {{ __('Latest Orders') }} </span>
            </a>
        </li>
        <li class="li nav-item {{ Route::is('cashiers.orders.create') ? 'active' : '' }} "
            style="{{ Route::is('cashiers.orders.create') ? 'background-color: darkslategrey;' : '' }}">
            <a class="nav-link" href="{{ route('cashiers.orders.create') }}">
                <i class="far fa-caret-square-right"></i>
                <span> {{ __('Create Order') }} </span>
            </a>
        </li>
        {{-- <li class="li nav-item {{ Route::is('moderators.orders.export') ? 'active' : '' }} "
            style="{{ Route::is('moderators.orders.export') ? 'background-color: darkslategrey;' : '' }}">
            <a class="nav-link" href="{{ route('moderators.orders.export') }}">
                <i class="far fa-caret-square-right"></i>
                <span> {{ __('Export Orders') }} </span>
            </a>
        </li>
        <li class="li nav-item {{ Route::is('moderators.provider.cashiers') ? 'active' : '' }} "
            style="{{ Route::is('moderators.provider.cashiers') ? 'background-color: darkslategrey;' : '' }}">
            <a class="nav-link" href="{{ route('moderators.provider.cashiers') }}">
                <i class="far fa-caret-square-right"></i>
                <span> {{ __('Cashiers') }} </span>
            </a>
        </li>
        <li class="li nav-item {{ Route::is('moderators.provider.branches') ? 'active' : '' }} "
            style="{{ Route::is('moderators.provider.branches') ? 'background-color: darkslategrey;' : '' }}">
            <a class="nav-link" href="{{ route('moderators.provider.branches') }}">
                <i class="far fa-caret-square-right"></i>
                <span> {{ __('Branches') }} </span>
            </a>
        </li> --}}
        
    @endhasrole

    @hasrole('branch_moderator')
        {{-- <li class="li nav-item {{ Route::is('branch-moderators.provider.details') ? 'active' : '' }} "
            style="{{ Route::is('branch-moderators.provider.details') ? 'background-color: darkslategrey;' : '' }}">
            <a class="nav-link" href="{{ route('branch-moderators.provider.details') }}">
                <i class="far fa-caret-square-right"></i>
                <span> {{ __('Branch Profile') }} </span>
            </a>
        </li> --}}
        <li class="li nav-item {{ Route::is('branch-moderators.orders.index') ? 'active' : '' }} "
            style="{{ Route::is('branch-moderators.orders.index') ? 'background-color: darkslategrey;' : '' }}">
            <a class="nav-link" href="{{ route('branch-moderators.orders.index') }}">
                <i class="far fa-caret-square-right"></i>
                <span> {{ __('Latest Orders') }} </span>
            </a>
        </li>
        <li class="li nav-item {{ Route::is('branch-moderators.orders.export') ? 'active' : '' }} "
            style="{{ Route::is('branch-moderators.orders.export') ? 'background-color: darkslategrey;' : '' }}">
            <a class="nav-link" href="{{ route('branch-moderators.orders.export') }}">
                <i class="far fa-caret-square-right"></i>
                <span> {{ __('Export Orders') }} </span>
            </a>
        </li>
        <li class="li nav-item {{ Route::is('branch-moderators.provider.cashiers') ? 'active' : '' }} "
            style="{{ Route::is('branch-moderators.provider.cashiers') ? 'background-color: darkslategrey;' : '' }}">
            <a class="nav-link" href="{{ route('branch-moderators.provider.cashiers') }}">
                <i class="far fa-caret-square-right"></i>
                <span> {{ __('Cashiers') }} </span>
            </a>
        </li>
    @endhasrole

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="border-0 rounded-circle" id="sidebarToggle"></button>
    </div>
</ul>
