@extends('layouts.admin')
@section('title') Galleries @endsection
@section('content')

@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card w-100 position-relative overflow-hidden">
                <div class="card-header px-4 py-3 border-bottom">
                    <div class="row">
                        <div class="col-6 d-flex justify-content-start">
                            <h5 class="card-title fw-semibold mb-0 lh-sm">Gallery</h5>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <a href="{{ route('admin.galleries.create') }}" class="btn btn-info">
                                Create
                                &nbsp;
                                <i class="ti ti-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive rounded-2 mb-4">
                    <table class="table border table-bordered table-sm mb-0 align-middle" id="datatable">
                        <thead class="text-dark fs-3">
                            <tr>
                                <th width="3%">
                                    <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                </th>
                                <th width="5%">
                                    <h6 class="fs-3 fw-semibold mb-0">Actions</h6>
                                </th>
                                <th width="10%">
                                    <h6 class="fs-3 fw-semibold mb-0">Date-Time</h6>
                                </th>
                                <th width="41%">
                                    <h6 class="fs-3 fw-semibold mb-0">Images</h6>
                                </th>
                                <th width="41%">
                                    <h6 class="fs-3 fw-semibold mb-0">Links</h6>
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
                url: '{!! route('admin.galleries.data') !!}',
                type: 'POST',
                data: function(d) {
                    d._token = $('meta[name=csrf-token]').attr('content');
                }
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'action',name: 'galleries.id',searchable: false},
				{data: 'datetime',name: 'galleries.created_at'},
                {data: 'images', name: 'galleries.images',
                    render: function(data, type, full, meta) {
                        if (data && Array.isArray(data) && data.length > 0) {
                            return data.map(function(filename) {
                                var imageUrl = '{{ asset(Storage::url(":filename")) }}'.replace(':filename', filename);
                                return '<img src="' + imageUrl + '" alt="Image" class="img-fluid" style="max-width: 80px; max-height: 80px;">';
                            }).join(' '); // Join images with a space or any other separator as needed
                        } else {
                            return 'No Images'; // Placeholder text or image in case there are no images
                        }
                    }
                },
                {data: 'links', name: 'galleries.links',
                    render: function(data, type, full, meta) {
                        if (data && Array.isArray(data) && data.length > 0) {
                            return data.map(function(link, index) {
                                // Increment index by 1 for user-friendly numbering (Link 1, Link 2, ...)
                                var linkNumber = index + 1;
                                // Use "Link N" as the text of the hyperlink, where N is the link number
                                return '<a href="' + link + '" target="_blank">Link ' + linkNumber + '</a>';
                            }).join('<br>'); // Join links with a line break or any other separator as needed
                        } else {
                            return 'No Links'; // Placeholder text or link in case there are no links
                        }
                    }
                },
            ],
            order: [],
            columnDefs: [
                { targets: [0,1], className: "text-center"},
            ],
        }); 
        $(
            ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
        ).addClass("btn btn-primary mr-1");
    });
</script>
@endsection