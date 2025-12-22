{{-- dashboard-blade.php --}}
@extends('layouts.app')
@push('styles')
   @endpush
@section('main_content')
      <div class="page-header">
				<div class="row">
					<div class="col-md-6 col-sm-12">
						<div class="title">
							<h4>Dashboard</h4>
						</div>
						<nav aria-label="breadcrumb" role="navigation">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
								<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
							</ol>
						</nav>
					</div>
					<div class="col-md-6 col-sm-12 text-right">
					
					</div>
				</div>
			</div>
       @include('components.dashboard.dashboardComponent',[$SiteDetails])
	   @if(Auth::user()->role =='admin' || Auth::user()->role =='superadmin')
	       @if (env('MENU_SHOW') == 'Yes')
		   @include('components.dashboard.upcomingPaymentsComponent-local',[$upcomingPayments])
		   @else
		   @include('components.dashboard.upcomingPaymentsComponent',[$upcomingPayments])
		   @endif

	   @endif
@endsection

