@extends('layouts.admin')
@section('title') Product @endsection
@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card w-100 position-relative overflow-hidden">
                <div class="card-header px-4 py-3 border-bottom">
                    <div class="row">
                        <div class="col-6 d-flex justify-content-start">
                            <h5 class="card-title fw-semibold mb-0 lh-sm">Products</h5>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <a href="{{ route('admin.products.create') }}" class="btn btn-info">
                                Create
                                &nbsp;
                                <i class="ti ti-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive rounded-2 mb-4">
                    <table class="table border table-bordered table-sm text-nowrap mb-0 align-middle" id="datatable">
                        <thead class="text-dark fs-3">
                            <tr>
                                <th width="3%">
                                    <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                </th>
                                <th width="5%">
                                    <h6 class="fs-3 fw-semibold mb-0">Actions</h6>
                                </th>
                                <th width="5%">
                                    <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                </th>
                                <th width="5%" >
                                    <h6 class="fs-3 fw-semibold mb-0">Featured</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Index</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Category Name</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Name</h6>
                                </th>
                            </tr>
                        </thead>
                    </table>
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
            scrollCollapse: true,
            scrollX:false,
            ajax: {
                url: '{!! route('admin.products.data') !!}',
                type: 'POST',
                data: function(d) {
                    d._token = $('meta[name=csrf-token]').attr('content');
                }
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'action',name: 'products.id',searchable: false},
                {data: 'status',name: 'products.id',searchable: false},
				{data: 'home_featured',name: 'products.home_featured'},
				{data: 'index',name: 'products.index'},
				{data: 'category_id',name: 'products.category_id'},
				{data: 'name',name: 'products.name'},
            ],
            order: [],
            columnDefs: [
                { targets: [0,2], className: "text-center"},
            ],
        });
        $(
            ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
        ).addClass("btn btn-primary mr-1");


        $(document).on('change', '.products-status-switch, .products-home_featured-switch', function(e) {
                e.preventDefault();
                var routeKey = $(this).data('routekey');
                var column = $(this).data('column') ||
                'status'; // Default to 'status' if 'column' is not defined
                var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';

                // alert('column')
                var url = (column === 'home_featured') ?
                    "{{ route('admin.products.change.home_featured.status') }}" :
                    "{{ route('admin.products.change.status') }}";

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        route_key: routeKey,
                        status: status,
                        column: column
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            toastr.success(data.message, '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                            if ($.fn.DataTable.isDataTable("#datatable")) {
                                $('#datatable').DataTable().draw();
                            }
                        } else {
                            toastr.error(data.message, '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        }
                    },
                    error: function(data) {
                        toastr.error('Something went wrong!');
                    }
                });
            });
    });
</script>
@endsection
