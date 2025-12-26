@extends('layouts.app')

@section('main_content')
<div class="page-header">
    <div class="row pb-2">
        <div class="col-md-6 col-left">
            <div class="title">
                <h4>Categories</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}" class="text-primary">Categories</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right col-right">
            <a href="{{ route('categories.create') }}" class="btn btn-sm border border-primary text-primary p-2 mr-2" style="font-size: 14px; font-weight: 500; min-width: 80px;">+ New</a>
            <a href="{{ url('/dashboard') }}" class="btn btn-sm border border-danger text-danger p-2" style="font-size: 14px; font-weight: 500; min-width: 80px;">
                <i class="dw dw-return1"></i> Back
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
    $paginationLimit = env('TABLE_PAGINATION_LIMIT', 5);
    $csrf = csrf_token();

    $gridData = $categories->map(function ($category, $index) use ($csrf) {
        $editUrl = route('categories.edit', $category->id);
        $deleteUrl = route('categories.destroy', $category->id);

        $actions = <<<HTML
            <div class="btn-group">
                <a href="{$editUrl}" data-toggle="tooltip" data-placement="top" data-original-title="Edit" class="btn btn-outline-primary btn-sm"><i class="dw dw-edit-1"></i></a>
                <form action="{$deleteUrl}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                    <input type="hidden" name="_token" value="{$csrf}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" data-toggle="tooltip" data-placement="top" data-original-title="Delete" class="btn btn-outline-danger btn-sm"><i class="dw dw-delete-3"></i></button>
                </form>
            </div>
        HTML;

        return [
            e($index + 1),
            e($category->name),
            e($category->status),
            e($category->created_at->format('Y-m-d')),
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
            { name: "Category Name", sort: true },
            { name: "Status", sort: true },
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

