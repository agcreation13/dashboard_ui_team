{{-- sideBarComponent.blade.php --}}

{{-- Left Sidebar --}}
<div class="left-side-bar">
    {{-- Brand Logo --}}
    <div class="brand-logo">
        <a href="{{ url('/') }}">
            {{-- Use asset() helper for logo images --}}
            <img src="{{ asset('assets/theme/src/images/logo/main-logo.png') }}" alt="" class="dark-logo">
            <img src="{{ asset('assets/theme/src/images/logo/main-logo.png') }}" alt="" class="light-logo">
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>

    {{-- Menu Block --}}
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">

                {{-- Dashboard --}}
                {{-- <li>
                    <a href="{{ url('/dashboard') }}" class="dropdown-toggle no-arrow">
                        <span class="micon dw dw-house-1"></span>
                        <span class="mtext">Dashboard</span>
                    </a>
                </li> --}}

                @php
                    // Check active routes for Standalone App menu
                    $standaloneRoutes = ['dashboard/*'];
                    $onlineSoftware = ['dashboard-leads*'];
                    $isMasterEntry = ['master-entry*'];
                    $isReportEntry = ['report*'];
                    $isWorkSheetAppEntry = ['worksheet*'];
                    $isInventory = ['inventory*','dashboard*'];
                    $isStandaloneActive = collect($standaloneRoutes)->contains(fn($route) => Request::is($route));
                    $isOnlineSoftwareActive = collect($onlineSoftware)->contains(fn($route) => Request::is($route));
                    $isMasterEntry = collect($isMasterEntry)->contains(fn($route) => Request::is($route));
                    $isReportEntry = collect($isReportEntry)->contains(fn($route) => Request::is($route));
                    $isWorkSheetAppEntry = collect($isWorkSheetAppEntry)->contains(fn($route) => Request::is($route));
                    $isInventoryActive = collect($isInventory)->contains(fn($route) => Request::is($route));
                    $userRole = Auth::user()->role;
                @endphp
         
            
                {{-- Dashboard --}}
                    <!-- <li>
                        <a href="{{ url('/dashboard') }}" class="dropdown-toggle no-arrow">
                            <span class="micon dw dw-house-1"></span>
                            <span class="mtext">Dashboard</span>
                        </a>
                    </li> -->

                {{-- Inventory Management --}}
                <li class="dropdown {{ $isInventoryActive ? 'show' : '' }}">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon dw dw-house-1"></span>
                        <span class="mtext">Inventory</span>
                    </a>
                    <ul class="submenu {{ $isInventoryActive ? 'show' : '' }}"
                        style="{{ $isInventoryActive ? 'display:block;' : '' }}">
                        <li><a href="{{ route('inventory.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('categories.index') }}">Categories</a></li>
                        <li><a href="{{ route('products.index') }}">Products</a></li>
                        <li><a href="{{ route('invoices.index') }}">Invoices</a></li>
                        <li><a href="{{ route('customers.index') }}">Customers</a></li>
                        <!-- <li><a href="{{ route('reports.index') }}">Reports</a></li> -->
                    </ul>
                </li>

                @if ($userRole == 'superadmin')
                    {{-- Master Entry --}}
                    <!-- <li class="dropdown {{ $isMasterEntry ? 'show' : '' }}">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon dw dw-settings"></span>
                            <span class="mtext">Master Entry</span>
                        </a>
                        <ul class="submenu {{ $isMasterEntry ? 'show' : '' }}"
                            style="{{ $isMasterEntry ? 'display:block;' : '' }}">
                            @if (env('MENU_SHOW') == 'Yes')
                                <li><a href="{{ route('UersRole.Index') }}">User Role</a></li>
                            @endif
                            <li><a href="{{ route('Uers.Index') }}">User List</a></li>
                        </ul>
                    </li> -->
                @endif
                {{-- user-list --}}
            </ul>
        </div>
    </div>
</div>


<style>
    @media only screen and (max-width: 768px) {
    .submenu[style*="display:block"] {
        display: none !important; /* Overrides inline display:block on mobile */
    }
}
</style>