@extends('layouts.admin')
@section('title') Category Management | Auto Vehicle Parts @endsection
@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card w-100 position-relative overflow-hidden">
                <div class="card-header px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title fw-semibold mb-0">Auto Parts Categories</h5>
                        <small class="text-muted">Categories created here automatically display in the shop navigation dropdown.</small>
                    </div>
                    <div>
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> Add Category
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive rounded-2 mb-4">
                        <table class="table border table-bordered table-sm text-nowrap mb-0 align-middle" id="datatable">
                            <thead class="table-light fs-3">
                                <tr>
                                    <th width="3%">#</th>
                                    <th>Category Details</th>
                                    <th>URL Slug</th>
                                    <th width="5%">Sort</th>
                                    <th width="5%">Status</th>
                                    <th width="10%">Actions</th>
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
                url: '{!! route('admin.categories.data') !!}',
                type: 'POST',
                data: function(d) {
                    d._token = $('meta[name=csrf-token]').attr('content');
                }
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'categories.name'},
                {data: 'slug', name: 'categories.slug'},
                {data: 'index', name: 'categories.index'},
                {data: 'status', orderable: false, searchable: false},
                {data: 'action', orderable: false, searchable: false},
            ],
            order: [[3, 'asc']],
            columnDefs: [
                { targets: [0, 3, 4, 5], className: "text-center"},
            ],
        });

        $(document).on('change', '.categories-status-switch', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';

            $.ajax({
                type: 'POST',
                url: "{{ route('admin.categories.change.status') }}",
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

        $(document).on('click', '.btn-delete-category', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            if (!confirm('Are you sure you want to permanently delete category "' + name + '" and all its associated products? This cannot be undone.')) return;
            var btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
            $.ajax({
                type: 'DELETE',
                url: '/admin/categories/' + id,
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    toastr.success(response.message || 'Category deleted successfully.');
                    dataTable.ajax.reload(null, false);
                },
                error: function() {
                    toastr.error('Failed to delete category.');
                    btn.prop('disabled', false).html('<i class="ti ti-trash"></i>');
                }
            });
        });
    });
</script>
@endsection
