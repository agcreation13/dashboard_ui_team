@extends('layouts.app')

@section('main_content')
<div class="page-header">
    <div class="row pb-2">
        <div class="col-md-6 col-left">
            <div class="title">
                <h4>Customers</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}" class="text-primary">Customers</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right col-right">
            <a href="{{ route('customers.create') }}" class="btn btn-sm border border-primary text-primary p-2 mr-2" style="font-size: 14px; font-weight: 500; min-width: 80px;">+ New</a>
            <a href="{{ url('/dashboard') }}" class="btn btn-sm border border-danger text-danger p-2" style="font-size: 14px; font-weight: 500; min-width: 80px;">
                <i class="dw dw-return1"></i> Back
            </a>
        </div>
    </div>
    <hr class="pb-2">

    @if (session('success'))
        <div class="alert alert-{{ session('bg-color') }} alert-dismissible fade show">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div id="site-grid"></div>
</div>

@php
    $paginationLimit = env('TABLE_PAGINATION_LIMIT', 5);
    $csrf = csrf_token();

    $gridData = $customers->map(function ($customer, $index) use ($csrf) {
        $editUrl = route('customers.edit', $customer->id);
        $showUrl = route('customers.show', $customer->id);
        $deleteUrl = route('customers.destroy', $customer->id);

        $actions = <<<HTML
            <div class="btn-group">
                <a href="{$showUrl}" data-toggle="tooltip" data-placement="top" data-original-title="View" class="btn btn-outline-info btn-sm"><i class="dw dw-eye"></i></a>
                <a href="{$editUrl}" data-toggle="tooltip" data-placement="top" data-original-title="Edit" class="btn btn-outline-primary btn-sm"><i class="dw dw-edit-1"></i></a>
                <form action="{$deleteUrl}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                    <input type="hidden" name="_token" value="{$csrf}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" data-toggle="tooltip" data-placement="top" data-original-title="Delete" class="btn btn-outline-danger btn-sm"><i class="dw dw-delete-3"></i></button>
                </form>
            </div>
        HTML;

        return [
            e($index + 1),
            e($customer->name),
            e($customer->phone ?? 'N/A'),
            e($customer->address ?? 'N/A'),
            e($customer->invoices_count ?? 0),
            e($customer->created_at->format('Y-m-d')),
            $actions,
        ];
    });
@endphp
@endsection

@push('js')
<script src="{{ asset('assets/table/js/tablenew.js') }}"></script>
<script>
    new gridjs.Grid({
        columns: [
            { name: "ID", sort: false },
            { name: "Name", sort: true },
            { name: "Phone", sort: true },
            { name: "Address", sort: true },
            { name: "Invoices", sort: true },
            { name: "Created Date", sort: true },
            {
                name: "Actions",
                sort: false,
                formatter: cell => gridjs.html(cell)
            }
        ],
        data: {!! json_encode($gridData) !!},
        search: true,
        pagination: {
            enabled: true,
            limit: {{ $paginationLimit }}
        },
        resizable: true
    }).render(document.getElementById("site-grid"));
</script>
@endpush

