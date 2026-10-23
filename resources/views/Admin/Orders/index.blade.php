@extends('layouts.admin')
@section('title') Orders @endsection
@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card w-100 position-relative overflow-hidden">
                <div class="card-header px-4 py-3 border-bottom">
                    <div class="row">
                        <div class="col-6 d-flex justify-content-start">
                            <h5 class="card-title fw-semibold mb-0 lh-sm">Orders</h5>
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
                                    <h6 class="fs-3 fw-semibold mb-0">Customer id</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Number</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Service id</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Type</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Customer Addresses Id</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Final Amount</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Description</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Vendor id</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Razorpay order id</h6>
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
                url: '{!! route('admin.orders.data') !!}',
                type: 'POST',
                data: function(d) {
                    d._token = $('meta[name=csrf-token]').attr('content');
                }
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false},
				{data: 'customer_id',name: 'enquiries.customer_id'},
                {data: 'number',name: 'enquiries.number'},
                {data: 'service_id',name: 'enquiries.service_id'},
                {data: 'type',name: 'enquiries.type'},
                {data: 'customer_addresses_id',name: 'enquiries.customer_addresses_id'},
                {data: 'final_amount',name: 'enquiries.final_amount'},
                {data: 'description',name: 'enquiries.description'},
                {data: 'vendor_id',name: 'enquiries.vendor_id'},
                {data: 'razorpay_order_id',name: 'enquiries.razorpay_order_id'},
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