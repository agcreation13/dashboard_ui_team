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
                    $isStandaloneActive = collect($standaloneRoutes)->contains(fn($route) => Request::is($route));
                    $isOnlineSoftwareActive = collect($onlineSoftware)->contains(fn($route) => Request::is($route));
                    $isMasterEntry = collect($isMasterEntry)->contains(fn($route) => Request::is($route));
                    $isReportEntry = collect($isReportEntry)->contains(fn($route) => Request::is($route));
                    $isWorkSheetAppEntry = collect($isWorkSheetAppEntry)->contains(fn($route) => Request::is($route));
                    $userRole = Auth::user()->role;
                @endphp
                @if ($userRole == 'admin' || $userRole == 'superadmin' || $userRole == 'supervisor')
                    {{-- Standalone App --}}
                    <li class="dropdown {{ $isStandaloneActive ? 'show' : '' }}">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon dw dw-property"></span>
                            <span class="mtext">Standalone App</span>
                        </a>
                        <ul class="submenu {{ $isStandaloneActive ? 'show' : '' }}"
                            style="{{ $isStandaloneActive ? 'display:block;' : '' }}">
                            <li><a href="{{ url('/dashboard/') }}">Dashboard</a></li>
                            <li><a href="{{ url('/dashboard/site-details') }}">Site Details</a></li>
                            @if ($userRole == 'admin' || $userRole == 'superadmin')
                            <li><a href="{{ url('/dashboard/daily-sheet') }}">Daily Entry Sheet</a></li>
                 
                                <li><a href="{{ url('/dashboard/site-bill') }}">Site Bill</a></li>
                                <li><a href="{{ url('/dashboard/payment-receipt') }}">Payment Receipt</a></li>
                                     @if ($userRole == 'superadmin')
                                <li><a href="{{ route('completedSites.Index') }}">Completed Site</a></li>
                            @endif
                            @endif
                        </ul>
                    </li>
                @endif
                @if ($userRole == 'representative' || $userRole == 'admin' || $userRole == 'superadmin')
                    {{-- Online Software --}}
                    <li class="dropdown {{ $isOnlineSoftwareActive ? 'show' : '' }}">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon dw dw-internet-1"></span>
                            <span class="mtext">Online Software</span>
                        </a>
                        <ul class="submenu {{ $isOnlineSoftwareActive ? 'show' : '' }}"
                            style="{{ $isOnlineSoftwareActive ? 'display:block;' : '' }}">

                            <li><a href="{{ url('/dashboard-leads') }}">Dashboard</a></li>
                            <li><a href="{{ route('leadsSheet.Index') }}">Leads List</a></li>
                        </ul>
                    </li>
                @endif
                @if ($userRole == 'superadmin')
                    {{-- Online Software --}}
                    <li class="dropdown {{ $isMasterEntry ? 'show' : '' }}">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon dw dw-settings"></span>
                            <span class="mtext">Master Entry</span>
                        </a>
                        <ul class="submenu {{ $isMasterEntry ? 'show' : '' }}"
                            style="{{ $isMasterEntry ? 'display:block;' : '' }}">
                            @if (env('MENU_SHOW') == 'Yes')
                                <li><a href="{{ route('UersRole.Index') }}">User Role</a></li>
                            @endif
                            <li><a href="{{ route('labourRoles.Index') }}">Labour Role</a></li>
                            <li><a href="{{ route('labour.Index')  }}">Labour List</a></li>
                            <li><a href="{{ route('material.Index') }}">Material List</a></li>
                            <li><a href="{{ route('Uers.Index') }}">User List</a></li>
                        </ul>
                    </li>
                    @if ($userRole == 'supervisor' || $userRole == 'admin' || $userRole == 'superadmin')
                    <li class="dropdown {{ $isWorkSheetAppEntry ? 'show' : '' }}">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon dw dw-list"></span>
                            <span class="mtext">Work Sheet App</span>
                        </a>
                        <ul class="submenu {{ $isWorkSheetAppEntry ? 'show' : '' }}"
                            style="{{ $isWorkSheetAppEntry ? 'display:block;' : '' }}">
                            <li><a href="{{ route('worksheet') }}">Dashboard</a></li>
                            <li><a href="{{ route('workSheet.Index') }}">Rome List</a></li>
                            <li><a href="{{ route('workKittyAssignment.Index') }}">Work Assignment</a></li>
                            <li><a href="{{ route('workProductList.Index') }}">Work Product List</a></li>
                        </ul>
                    </li>
                    @endif
                    <li class="dropdown {{ $isReportEntry ? 'show' : '' }}">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon dw dw-analytics-3"></span>
                            <span class="mtext">View Report</span>
                        </a>
                        <ul class="submenu {{ $isReportEntry ? 'show' : '' }}"
                            style="{{ $isReportEntry ? 'display:block;' : '' }}">
                            <li><a href="{{ route('leadsReport') }}">Lead Report</a></li>
                            <li><a href="{{ route('siteReport') }}">Site Report</a></li>
                        </ul>
                    </li>
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