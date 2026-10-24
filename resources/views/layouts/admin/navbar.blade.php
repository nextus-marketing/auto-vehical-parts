<nav class="sidebar-nav scroll-sidebar" data-simplebar>
    <ul id="sidebarnav">
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Home</span>
        </li>

        @can('dashboard-view')
        <li class="sidebar-item">
            <a class="sidebar-link @if(Route::is('admin.dashboard.*')) active @endif" href="{{ route('admin.dashboard.index') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-aperture"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
            </a>
        </li>
        @endcan

        <!-- @can('role-view')
        <li class="sidebar-item">
            <a class="sidebar-link @if(Route::is('admin.roles.*')) active @endif" href="{{ route('admin.roles.index') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-user-check"></i>
                </span>
                <span class="hide-menu">Roles</span>
            </a>
        </li>
        @endcan -->

        <!-- @can('employee-view')
        <li class="sidebar-item">
            <a class="sidebar-link @if(Route::is('admin.employees.*')) active @endif" href="{{ route('admin.employees.index') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-user-circle"></i>
                </span>
                <span class="hide-menu">Employees</span>
            </a>
        </li>
        @endcan -->

        <!-- @can('user-view')
        <li class="sidebar-item">
            <a class="sidebar-link @if(Route::is('admin.users.*')) active @endif" href="{{ route('admin.users.index') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu">Customers</span>
            </a>
        </li>
        @endcan -->

      <!-- @can('slider-view')
        <li class="sidebar-item">
            <a class="sidebar-link @if(Route::is('admin.sliders.*')) active @endif" href="{{ route('admin.sliders.index') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu">Sliders</span>
            </a>
        </li>
        @endcan  -->


        @can('category-view')
        <li class="sidebar-item">
            <a class="sidebar-link @if(Route::is('admin.categories.*')) active @endif" href="{{ route('admin.categories.index') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu">Categories</span>
            </a>
        </li>
        @endcan


        @can('product-view')
        <li class="sidebar-item">
            <a class="sidebar-link @if(Route::is('admin.products.*')) active @endif" href="{{ route('admin.products.index') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu">Products</span>
            </a>
        </li>
        @endcan


        <!-- @can('service-view')
        <li class="sidebar-item">
            <a class="sidebar-link @if(Route::is('admin.services.*')) active @endif" href="{{ route('admin.services.index') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu">Services</span>
            </a>
        </li>
        @endcan -->

        <!-- @can('enquiry-view')
        <li class="sidebar-item">
            <a class="sidebar-link @if(Route::is('admin.enquiries.*')) active @endif" href="{{ route('admin.enquiries.index') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu">Enquiries</span>
            </a>
        </li>
        @endcan -->

        <!-- @can('setting-view')
        <li class="sidebar-item">
            <a class="sidebar-link @if(Route::is('admin.settings.*')) active @endif" href="{{ route('admin.settings.index') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu">Settings</span>
            </a>
        </li>
        @endcan  -->

        @can('coupon-view')
        <li class="sidebar-item">
            <a class="sidebar-link @if(Route::is('admin.coupons.*')) active @endif" href="{{ route('admin.coupons.index') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-ticket"></i>
                </span>
                <span class="hide-menu">Coupons ($ USD)</span>
            </a>
        </li>
        @endcan

        @can('order-view')
        <li class="sidebar-item">
            <a class="sidebar-link @if(Route::is('admin.orders.*') && !request()->has('type')) active @endif" href="{{ route('admin.orders.index') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-shopping-cart"></i>
                </span>
                <span class="hide-menu">All Orders</span>
            </a>
        </li>
        <li class="sidebar-item ms-3">
            <a class="sidebar-link text-success @if(request()->query('type') == 'success') active @endif" href="{{ route('admin.orders.index', ['type' => 'success']) }}" aria-expanded="false">
                <span>
                    <i class="ti ti-circle-check"></i>
                </span>
                <span class="hide-menu">Success Orders</span>
            </a>
        </li>
        <li class="sidebar-item ms-3">
            <a class="sidebar-link text-danger @if(request()->query('type') == 'failed') active @endif" href="{{ route('admin.orders.index', ['type' => 'failed']) }}" aria-expanded="false">
                <span>
                    <i class="ti ti-alert-triangle"></i>
                </span>
                <span class="hide-menu">Failed Orders</span>
            </a>
        </li>
        @endcan 
        
        <!-- End of File -->
    </ul>
</nav>
