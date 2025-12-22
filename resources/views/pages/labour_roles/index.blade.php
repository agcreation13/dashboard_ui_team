@extends('layouts.app')

@section('main_content')
<div class="page-header">
        <div class="row pb-2">
            <div class="col-md-6 col-left">
                <div class="title">
                    <h4>Labour Role Details</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/master-entry/labour-roles') }}" class="text-primary ">Labour Role List</a></li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 text-right col-right">
                <a href="{{ route('labourRoles.Create') }}" class="add_button text-primary btn-sm border border-primary p-2 h4">+
                    New</span></a>
                <a href="{{ url('/dashboard') }}" class="back_button text-danger border btn-sm border-danger p-2 h4">
                    <i class="dw dw-return1"></i> <span class="back_title">Back</span>
                </a>
            </div>
        </div>
    <hr class="pb-2">

    {{-- Success message --}}
    @if (session('success'))
        <div class="alert alert-{{ session('bg-color') }} alert-dismissible fade show">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div id="site-grid" class="text-capitalize"></div>
</div>

@php
    $paginationLimit = env('TABLE_PAGINATION_LIMIT', 5); // Default fallback
    $csrf = csrf_token();

    // <a href="{$deleteUrl}" class="btn btn-danger btn-sm"><i class="dw dw-delete-3"></i></a>
    $gridData = $labourRoles->map(function ($labourRole, $index) use ($csrf) {
        $editUrl = route('labourRoles.Edit', $labourRole->id);
        $deleteUrl = route('labourRoles.Delete', $labourRole->id);
        $StatusUpdate = route('labourRoles.StatusUpdate', $labourRole->id);
         $statusButton = $labourRole->status === 'active'
        ? "<a href='{$StatusUpdate}' data-toggle='tooltip' data-placement='top' title='Activate' class='btn btn-outline-success btn-sm'>
                <i class='fa fa-toggle-off'></i>
           </a>"
        : "<a href='{$StatusUpdate}' data-toggle='tooltip' data-placement='top' title='Deactivate' class='btn btn-outline-danger btn-sm'>
                <i class='fa fa-toggle-on'></i>
           </a>";
        $actions = <<<HTML
            <div class="btn-group">
                <a href="{$editUrl}" data-toggle="tooltip" data-placement="top" data-original-title="Edit" class="btn btn-outline-primary btn-sm"><i class="dw dw-edit-1"></i></a>
             {$statusButton}
            </div>
        HTML;

        return [
            e($index + 1),
            e($labourRole->name),
            e($labourRole->status),
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
            { name: "Status", sort: true },
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
