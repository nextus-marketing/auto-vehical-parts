@extends('layouts.admin')
@section('title')
    {{ isset($product) ? 'Edit Part' : 'Add New Part' }} | Auto Vehicle Parts
@endsection
@section('content')
<!-- Summernote Lite Rich Text Editor CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<form method="POST"
    action="{{ isset($product) ? route('admin.products.update', ['product' => $product->id]) : route('admin.products.store') }}"
    enctype="multipart/form-data" autocomplete="off" id="products-form">
    @csrf
    @if(isset($product))
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card w-100">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="ti ti-tool me-1"></i> {{ isset($product) ? 'Edit Auto Part Details' : 'Create New Auto Part' }}
                    </h5>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-arrow-left me-1"></i> Back to Products
                    </a>
                </div>

                <div class="card-body">
                    <!-- General Details -->
                    <h6 class="fw-bold text-primary mb-3"><i class="ti ti-info-circle me-1"></i> Basic Identification & Category</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <select class="form-select" name="category_id" required>
                                <option value="">-- Select Auto Part Category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ (isset($product) && $product->category_id == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="category_id-error" class="text-danger small mt-1"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="product_name" name="name" 
                                placeholder="e.g. Tested OEM Complete V6 / V8 Engine Assembly"
                                value="{{ isset($product) ? $product->name : '' }}" required />
                            <div id="name-error" class="text-danger small mt-1"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">URL Slug <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="product_slug" name="slug" 
                                placeholder="e.g. tested-oem-engine-assembly"
                                value="{{ isset($product) ? $product->slug : '' }}" required />
                            <small class="text-muted">Unique part web address (e.g. /shop/your-part-slug)</small>
                            <div id="slug-error" class="text-danger small mt-1"></div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">OEM / Part Number</label>
                            <input type="text" class="form-control" name="part_number" 
                                placeholder="e.g. ENG-OEM-54200-V6"
                                value="{{ isset($product) ? $product->part_number : '' }}" />
                            <div id="part_number-error" class="text-danger small mt-1"></div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Sort Order / Index</label>
                            <input type="number" class="form-control" name="index" 
                                placeholder="e.g. 1"
                                value="{{ isset($product) ? $product->index : '1' }}" />
                            <div id="index-error" class="text-danger small mt-1"></div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Pricing & Stock (USD) -->
                    <h6 class="fw-bold text-success mb-3"><i class="ti ti-currency-dollar me-1"></i> USA Pricing & Inventory ($ USD)</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Sale Price ($ USD) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" name="price" 
                                    placeholder="1150.00"
                                    value="{{ isset($product) ? $product->price : '' }}" required />
                            </div>
                            <small class="text-muted">Current selling price charged to customer in USD</small>
                            <div id="price-error" class="text-danger small mt-1"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Compare / Regular Price ($ USD)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" name="compare_price" 
                                    placeholder="1650.00"
                                    value="{{ isset($product) ? $product->compare_price : '' }}" />
                            </div>
                            <small class="text-muted">Original / MSRP price crossed out on shop page</small>
                            <div id="compare_price-error" class="text-danger small mt-1"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Stock Status</label>
                            <select class="form-select" name="stock_status">
                                <option value="IN_STOCK" {{ (isset($product) && $product->stock_status == 'IN_STOCK') ? 'selected' : '' }}>In Stock & Ready to Freight</option>
                                <option value="LOW_STOCK" {{ (isset($product) && $product->stock_status == 'LOW_STOCK') ? 'selected' : '' }}>Low Stock (1-2 Left)</option>
                                <option value="OUT_OF_STOCK" {{ (isset($product) && $product->stock_status == 'OUT_OF_STOCK') ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Auto Vehicle Specifications -->
                    <h6 class="fw-bold text-secondary mb-3"><i class="ti ti-car me-1"></i> Auto Part Verification & Warranty Specs</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Mileage / Donor Condition</label>
                            <input type="text" class="form-control" name="mileage" 
                                placeholder="e.g. 54,200 Original Miles"
                                value="{{ isset($product) ? $product->mileage : 'Verified Low Miles' }}" />
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Warranty Terms</label>
                            <input type="text" class="form-control" name="warranty" 
                                placeholder="e.g. 1-Year Comprehensive Replacement Guarantee"
                                value="{{ isset($product) ? $product->warranty : '1-Year Comprehensive Replacement Guarantee' }}" />
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">VIN Fitment Notes</label>
                            <input type="text" class="form-control" name="fitment" 
                                placeholder="e.g. 100% Guaranteed Exact Fit by VIN Verification"
                                value="{{ isset($product) ? $product->fitment : '100% Guaranteed Exact Fit by VIN Verification' }}" />
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Descriptions -->
                    <h6 class="fw-bold text-dark mb-3"><i class="ti ti-file-text me-1"></i> Part Descriptions (Rich Text Box Format)</h6>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Short Summary / Overview</label>
                            <textarea name="short_description" class="form-control" rows="2" 
                                placeholder="Brief 1-2 sentence overview shown in product card and summary...">{{ isset($product) ? $product->short_description : '' }}</textarea>
                            <div id="short_description-error" class="text-danger small mt-1"></div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Full Technical Description & Quality Testing Report <span class="text-danger">*</span></label>
                            <textarea id="product_description" name="description" class="form-control" rows="8" required>{{ isset($product) ? $product->description : '' }}</textarea>
                            <div id="description-error" class="text-danger small mt-1"></div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Images -->
                    <h6 class="fw-bold text-dark mb-3"><i class="ti ti-photo me-1"></i> Product Photos</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Primary Feature Image</label>
                            <input type="file" name="photo" class="form-control" accept="image/*" />
                            <small class="text-muted">Recommended: Square or 800x800 clear image</small>
                            <div id="photo-error" class="text-danger small mt-1"></div>

                            @if (isset($product) && $product->photo)
                                <div class="mt-2">
                                    <img src="{{ $product->photo_url }}" width="120" height="120" class="rounded border shadow-sm" style="object-fit:cover;">
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Additional Gallery Images</label>
                            <input type="file" name="other_photo[]" class="form-control" accept="image/*" multiple />
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple angle shots</small>
                            <input type="hidden" id="delete_images_source" name="delete_images_source" value="">

                            @if (isset($product) && is_array($product->other_photo))
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach ($product->other_photo as $idx => $img)
                                        <div class="position-relative" id="gallery_wrap_{{ $idx }}">
                                            <img src="{{ Str::startsWith($img, '/') ? $img : '/storage/' . $img }}" 
                                                width="80" height="80" class="rounded border shadow-sm" style="object-fit:cover;">
                                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 p-0 px-1 rounded-circle" 
                                                onclick="removeGalleryImg('{{ $img }}', 'gallery_wrap_{{ $idx }}')">&times;</button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4" id="btn-submit">
                        <i class="ti ti-device-floppy me-1"></i> Save Product
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Summernote Lite JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Summernote Rich Text Editor
        $('#product_description').summernote({
            placeholder: 'Write comprehensive technical specifications, compression test results, parts included, and vehicle fitment details here...',
            tabsize: 2,
            height: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        // Auto Slug Generation from Name
        $('#product_name').on('input', function() {
            var slug = $(this).val()
                .toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
            @if(!isset($product))
                $('#product_slug').val(slug);
            @endif
        });

        // Handle AJAX Form Submission
        $('#products-form').submit(function(e) {
            e.preventDefault();
            $('div[id$="-error"]').empty();
            
            // Sync Summernote content to textarea
            $('#product_description').val($('#product_description').summernote('code'));

            var submitBtn = $('#btn-submit');
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');

            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                type: "POST",
                url: url,
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    toastr.success(response.message || 'Product saved successfully!');
                    setTimeout(function() {
                        window.location.href = "{!! route('admin.products.index') !!}";
                    }, 1000);
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Save Product');
                    toastr.error('Please fix the errors in the form.');

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '-error').html(value[0]);
                        });
                        var firstError = Object.keys(xhr.responseJSON.errors)[0];
                        if ($('#' + firstError + '-error').length) {
                            $('html, body').animate({
                                scrollTop: $('#' + firstError + '-error').offset().top - 150
                            }, 400);
                        }
                    }
                }
            });
        });
    });

    var deletedImages = [];
    function removeGalleryImg(imgPath, elemId) {
        deletedImages.push(imgPath);
        $('#delete_images_source').val(deletedImages.join(','));
        $('#' + elemId).remove();
    }
</script>
@endsection
