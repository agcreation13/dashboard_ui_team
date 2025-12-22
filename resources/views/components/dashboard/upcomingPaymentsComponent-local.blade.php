<!-- Upcoming Payments Component -->
<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-black h4">Upcoming Payments</h4>
    </div>
    <div class="pb-20">
        <div class="table-responsive px-3 text-capitalize">
            <div id="upcoming-payments-grid" class="table table-responsive table-striped"></div>
        </div>
    </div>
</div>

@php
    $paginationLimit = env('TABLE_PAGINATION_LIMIT', 5);
    $csrf = csrf_token();

    // Convert to collection if not already
    $upcomingPaymentsCollection = is_array($upcomingPayments) ? collect($upcomingPayments) : $upcomingPayments;

    // Sort by project_cost_ration sortByDesc
    $upcomingPaymentsCollection = $upcomingPaymentsCollection->sortByDesc('project_cost_ration')->values();
    // Sort by project_cost_ration ascending
    // $upcomingPaymentsCollection = $upcomingPaymentsCollection->sortBy('project_cost_ration')->values();

    $today = \Carbon\Carbon::now()->format('Y-m-d');

    $gridData = $upcomingPaymentsCollection->map(function ($payment, $index) use ($today) {
        $viewUrl = route('siteDetails.Show', $payment['site_id']);
        $siteBillUrl = route('paymentReceipt.CreateById', $payment['site_id']);

        $viewButton = '<a href="' . $viewUrl . '" data-toggle="tooltip" class="btn btn-outline-info btn-sm"><i class="dw dw-eye"></i></a>';
        $status = $payment['status'] ?? '';
        $followUpButton = '';
        $nextPaymentDate = isset($payment['next_payment_date']) ? \Carbon\Carbon::parse($payment['next_payment_date'])->format('Y-m-d') : null;

        if ($nextPaymentDate) {
            if ($nextPaymentDate === $today) {
                $followUpButton = '<a href="' . $siteBillUrl . '" data-toggle="tooltip" class="btn btn-outline-success btn-sm"><i class="dw dw-notification-1"></i></a>';
            } elseif ($nextPaymentDate < $today) {
                $followUpButton = '<a href="' . $siteBillUrl . '" data-toggle="tooltip" class="btn btn-outline-danger btn-sm"><i class="dw dw-notification-11"></i></a>';
            } else {
                $followUpButton = '<a href="' . $siteBillUrl . '" data-toggle="tooltip" class="btn btn-outline-secondary btn-sm"><i class="dw dw-file-33"></i></a>';
            }
        } else {
            $followUpButton = '<a href="' . $siteBillUrl . '" data-toggle="tooltip" class="btn btn-outline-secondary btn-sm"><i class="dw dw-file-33"></i></a>';
        }

        $actions = <<<HTML
            <div class="btn-group">
                {$viewButton}
                {$followUpButton}
            </div>
        HTML;

        return [
            e($index + 1),
            e($payment['site_name'] ?? 'N/A'),
            '₹' . number_format($payment['bill_value'] ?? 0, 2),
           e($payment['project_cost_ration'] ?? 'N/A') . ' % <br><small class="text-danger">' . $status . '</small>',
            '₹' . number_format($payment['payment_received'] ?? 0, 2),
            e($payment['next_payment_date'] ?? 'N/A'),
            e($payment['project_cost'] ?? 'N/A'),
          
            $actions,
        ];
    });
@endphp

@push('js')
<script src="{{ asset('assets/table/js/tablenew.js') }}"></script>
<script>
    new gridjs.Grid({
        columns: [
            { name: "#", sort: false },
            { name: "Site Name", sort: false },
            { name: "Bill Value (₹)", sort: false },
            {
                name: "Project Cost Ratio",
                sort: true, // still allow user to sort on client-side if needed
                formatter: cell => gridjs.html(cell) 
            },
            { name: "Payment Received (₹)", sort: false },
            { name: "Next Payment Date", sort: true },
            { name: "Project cost", sort: true },
      
            {
                name: "Action",
                sort: false,
                formatter: cell => gridjs.html(cell)
            }
        ],
        data: {!! json_encode($gridData) !!}.length 
            ? {!! json_encode($gridData) !!} 
            : [[1, 'No underpaid sites found.', '', '', '', '', '']],
        search: true,
        pagination: {
            enabled: true,
            limit: {{ $paginationLimit }}
        },
        resizable: true
    }).render(document.getElementById("upcoming-payments-grid"));
</script>
@endpush
