@extends('layouts.app')

@section('main_content')
<div class="page-header">
    <div class="row pb-2">
        <div class="col-md-6 col-left">
            <div class="title">
                <h4>Reports</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}" class="text-primary">Reports</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right col-right">
            <a href="{{ url('/dashboard') }}" class="back_button text-danger border btn-sm border-danger p-2 h4">
                <i class="dw dw-return1"></i> <span class="back_title">Back</span>
            </a>
        </div>
    </div>
    <hr class="pb-2">

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Stock Report</h5>
                    <p>View product stock report</p>
                    <a href="{{ route('reports.stock') }}" class="btn btn-primary btn-sm" style="font-size: 14px; font-weight: 500; min-width: 120px; padding: 8px 20px;">View Report</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Category Report</h5>
                    <p>Category-wise product report</p>
                    <a href="{{ route('reports.category') }}" class="btn btn-primary">View Report</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Invoice Report</h5>
                    <p>Date-wise invoice report</p>
                    <a href="{{ route('reports.invoice') }}" class="btn btn-primary">View Report</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Sales Summary</h5>
                    <p>Sales summary report</p>
                    <a href="{{ route('reports.sales') }}" class="btn btn-primary">View Report</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

