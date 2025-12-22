@extends('layouts.app')

@section('main_content')
    <div class="page-header">
          <div class="row pb-2">
            <div class="col-md-6 col-left">
                <div class="title">
                    <h4>Labour Details</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/master-entry/labour') }}" class="text-primary ">Labour List</a></li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 text-right col-right">
                <a href="{{ route('labour.Create') }}" class="add_button text-primary btn-sm border border-primary p-2 h4">+
                    New</span></a>
                <a href="{{ url('/dashboard') }}" class="back_button text-danger border btn-sm border-danger p-2 h4">
                    <i class="dw dw-return1"></i> <span class="back_title">Back</span>
                </a>
            </div>
        </div>

        <hr class="pb-2">

        {{-- Flash Success Message --}}
        @if (session('success'))
            <div class="alert alert-{{ session('bg-color') }} alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Data Table Mount Point --}}
        <div id="site-grid" class="text-capitalize"></div>
    </div>

    @php
       $paginationLimit = env('TABLE_PAGINATION_LIMIT', 5); // fallback to 10 if not set
        $csrf = csrf_token();

        // Prepare data for Grid.js
        // <a href="{$deleteUrl}" class="btn btn-danger btn-sm"><i class="dw dw-delete-3"></i></a>
        $gridData = $labourDetails->values()->map(function ($site, $index) use ($csrf) {
            $viewUrl = route('labour.Show', $site->id);
            $editUrl = route('labour.Edit', $site->id);
            $deleteUrl = route('labour.Delete', $site->id);

            $actions = <<<HTML
                <div class="btn-group">
                    <a href="{$editUrl}" data-toggle="tooltip" data-placement="top" data-original-title="Edit" class="btn btn-outline-primary btn-sm"><i class="dw dw-edit-1"></i></a>
                </div>
            HTML;

            return [
                e($site->labour_id),                // Serial number
                e($site->name),               // Labour name
                e($site->phoneno),            // Phone number
                e($site->labourRole->name),   // Labour type/role
                e($site->dailywage),          // Daily wage
                e($site->status),          // Daily wage
                $actions                      // Action buttons (Edit/Delete)
            ];
        });
    @endphp
@endsection

@push('js')
    {{-- Grid.js Table Initialization --}}
    <script src="{{ asset('assets/table/js/tablenew.js') }}"></script>
    <script>
        new gridjs.Grid({
            columns: [
                { name: "#", sort: false },
                { name: "Name", sort: true },
                { name: "Phone No", sort: false },
                { name: "Type", sort: true },
                { name: "Daily Wage (₹)", sort: false },
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
