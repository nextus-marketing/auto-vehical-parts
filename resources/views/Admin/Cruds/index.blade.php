@extends('layouts.admin')
@section('title') Cruds @endsection
@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card w-100 position-relative overflow-hidden">
                <div class="card-header px-4 py-3 border-bottom">
                    <div class="row">
                        <div class="col-6 d-flex justify-content-start">
                            <h5 class="card-title fw-semibold mb-0 lh-sm">Crud</h5>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <a href="{{ route('admin.cruds.create') }}" class="btn btn-info">
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
                                <th width="3%">
                                    <h6 class="fs-3 fw-semibold mb-0">Actions</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Model</h6>
                                </th>
                                <th width="10%">
                                    <h6 class="fs-3 fw-semibold mb-0">Table</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Controller</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Route</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Resource Folder</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Fields</h6>
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
                url: '{!! route('admin.cruds.data') !!}',
                type: 'POST',
                data: function(d) {
                    d._token = $('meta[name=csrf-token]').attr('content');
                }
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'action',name: 'cruds.id',searchable: false},
                {data: 'model_name',name: 'cruds.model_name'},
				{data: 'table_name',name: 'cruds.table_name'},
				{data: 'controller_name',name: 'cruds.controller_name'},
				{data: 'route_name',name: 'cruds.route_name'},
				{data: 'view_folder_name',name: 'cruds.view_folder_name'},
				{data: 'fields',name: 'cruds.fields'},
            ],
            order: [],
            columnDefs: [
                { targets: [0,1], className: "text-center"},
            ],
        });
        $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel").addClass("btn btn-primary mr-1");
    });

    $(document).on('click', '.crud-create', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                if (response.status) {
                    toastr.success(response.message, '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error('There is some error!!', '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                }
            }
        });
    });

    $(document).on('click', '.crud-undo', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                if (response.status) {
                    toastr.success(response.message, '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 1000);                
                } else {
                    toastr.error('There is some error!!', '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                }
            }
        });
    });
</script>
@endsection
