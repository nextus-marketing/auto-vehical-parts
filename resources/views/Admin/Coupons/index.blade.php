@extends('layouts.admin')
@section('title') Coupon Codes Management | Auto Vehicle Parts @endsection
@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card w-100 position-relative overflow-hidden">
                <div class="card-header px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title fw-semibold mb-0">Promo &amp; Coupon Codes ($ USD)</h5>
                        <small class="text-muted">Create discount codes for customer checkout in US Dollars or percentages.</small>
                    </div>
                    <div>
                        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> Create Coupon
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive rounded-2 mb-4">
                        <table class="table border table-bordered table-sm text-nowrap mb-0 align-middle" id="datatable">
                            <thead class="table-light fs-3">
                                <tr>
                                    <th width="4%">#</th>
                                    <th>Coupon Code &amp; Title</th>
                                    <th>Discount</th>
                                    <th>Min Order ($)</th>
                                    <th>Status</th>
                                    <th>Expires At</th>
                                    <th width="10%">Action</th>
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
    $(function() {
        var dataTable = $('#datatable').DataTable({
            dom: "Bfrtip",
            buttons: ["copy", "csv", "excel", "pdf", "print"],
            processing: true,
            serverSide: true,
            ajax: {
                url: '{!! route('admin.coupons.data') !!}',
                type: 'POST',
                data: function(d) {
                    d._token = $('meta[name=csrf-token]').attr('content');
                }
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'code', name: 'coupons.code'},
                {data: 'discount_value', name: 'coupons.discount_value'},
                {data: 'min_order_amount', name: 'coupons.min_order_amount'},
                {data: 'status', orderable: false, searchable: false},
                {data: 'expires_at', name: 'coupons.expires_at'},
                {data: 'action', orderable: false, searchable: false},
            ],
            order: [[0, 'desc']],
            columnDefs: [
                { targets: [0, 4, 6], className: "text-center"},
            ],
        });

        $(document).on('click', '.btn-delete-coupon', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            if (!confirm('Are you sure you want to permanently delete coupon "' + name + '"? This cannot be undone.')) return;
            var btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
            $.ajax({
                type: 'DELETE',
                url: '/admin/coupons/' + id,
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    toastr.success(response.message || 'Coupon deleted successfully.');
                    dataTable.ajax.reload(null, false);
                },
                error: function() {
                    toastr.error('Failed to delete coupon.');
                    btn.prop('disabled', false).html('<i class="ti ti-trash"></i> Delete');
                }
            });
        });

        $(document).on('change', '.coupons-status-switch', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';

            $.ajax({
                type: 'POST',
                url: "{{ route('admin.coupons.change.status') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    status: status
                },
                success: function(response) {
                    toastr.success(response.message);
                },
                error: function() {
                    toastr.error('Failed to update status');
                }
            });
        });
    });
</script>
@endsection
