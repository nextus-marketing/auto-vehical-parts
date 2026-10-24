@extends('layouts.admin')
@section('title') Customer Orders Management | Auto Vehicle Parts @endsection
@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card w-100 position-relative overflow-hidden">
                <div class="card-header px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title fw-semibold mb-0">Customer Auto Parts Orders</h5>
                        <small class="text-muted">Track successful payments, failed transactions, and order fulfillment in USD ($).</small>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Status Filter Tabs -->
                    <ul class="nav nav-pills mb-4" id="orderTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link {{ $type == 'all' ? 'active' : '' }}" onclick="filterOrders('')">
                                <i class="ti ti-list me-1"></i> All Orders 
                                <span class="badge bg-secondary ms-1">{{ $allCount }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link {{ $type == 'success' ? 'active' : '' }} text-success" onclick="filterOrders('SUCCESS')">
                                <i class="ti ti-check-circle me-1"></i> Successful Orders 
                                <span class="badge bg-success ms-1">{{ $successCount }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link {{ $type == 'failed' ? 'active' : '' }} text-danger" onclick="filterOrders('FAILED')">
                                <i class="ti ti-alert-triangle me-1"></i> Failed Orders 
                                <span class="badge bg-danger ms-1">{{ $failedCount }}</span>
                            </button>
                        </li>
                    </ul>

                    <div class="table-responsive rounded-2 mb-4">
                        <table class="table border table-bordered table-sm text-nowrap mb-0 align-middle" id="datatable">
                            <thead class="table-light fs-3">
                                <tr>
                                    <th width="3%">#</th>
                                    <th>Order # &amp; Date</th>
                                    <th>Customer Info</th>
                                    <th>Vehicle &amp; VIN</th>
                                    <th>Total ($ USD)</th>
                                    <th>Payment</th>
                                    <th>Order Status</th>
                                    <th width="8%">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    var currentPaymentStatus = '{{ $type == "success" ? "SUCCESS" : ($type == "failed" ? "FAILED" : "") }}';
    var dataTable;

    function filterOrders(status) {
        currentPaymentStatus = status;
        $('#orderTabs .nav-link').removeClass('active');
        if (event && event.currentTarget) {
            $(event.currentTarget).addClass('active');
        }
        dataTable.ajax.reload();
    }

    $(function() {
        dataTable = $('#datatable').DataTable({
            dom: "Bfrtip",
            buttons: ["copy", "csv", "excel", "pdf", "print"],
            processing: true,
            serverSide: true,
            ajax: {
                url: '{!! route('admin.orders.data') !!}',
                type: 'POST',
                data: function(d) {
                    d._token = $('meta[name=csrf-token]').attr('content');
                    d.payment_status = currentPaymentStatus;
                }
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'order_number', name: 'orders.order_number'},
                {data: 'customer_name', name: 'orders.customer_name'},
                {data: 'vehicle_details', name: 'orders.vehicle_details'},
                {data: 'total_amount', name: 'orders.total_amount'},
                {data: 'payment_status', name: 'orders.payment_status'},
                {data: 'order_status', name: 'orders.order_status'},
                {data: 'action', orderable: false, searchable: false},
            ],
            order: [[0, 'desc']],
            columnDefs: [
                { targets: [0, 4, 5, 6, 7], className: "text-center"},
            ],
        });

        $(document).on('click', '.btn-delete-order', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            if (!confirm('Are you sure you want to permanently delete order "' + name + '" and all its items? This cannot be undone.')) return;
            var btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
            $.ajax({
                type: 'DELETE',
                url: '/admin/orders/' + id,
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    toastr.success(response.message || 'Order deleted successfully.');
                    dataTable.ajax.reload(null, false);
                },
                error: function() {
                    toastr.error('Failed to delete order.');
                    btn.prop('disabled', false).html('<i class="ti ti-trash"></i>');
                }
            });
        });
    });
</script>
@endsection