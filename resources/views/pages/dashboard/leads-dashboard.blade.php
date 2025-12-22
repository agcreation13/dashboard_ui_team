{{-- dashboard-blade.php --}}
@extends('layouts.app')
@push('styles')
   @endpush
@section('main_content')
      <div class="page-header">
				<div class="row">
					<div class="col-md-6 col-sm-12">
						<div class="title">
							<h4>Leads-Dashboard</h4>
						</div>
						<nav aria-label="breadcrumb" role="navigation">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{url('/dashboard-leads')}}">Leads-Dashboard</a></li>
								<li class="breadcrumb-item active" aria-current="page">Leads-Dashboard</li>
							</ol>
						</nav>
					</div>
					
				</div>
			</div>
       @include('components.leads-dashboard.dashboardComponent',[$leadSheetList])
       {{-- @include('components.leads-dashboard.upcomingPaymentsComponent',[$upcomingPayments]) --}}
@endsection

