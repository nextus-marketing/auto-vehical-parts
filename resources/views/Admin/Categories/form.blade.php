@extends('layouts.admin')
@section('title')
    {{ isset($category) ? 'Edit Category' : 'Create Category' }} | Auto Vehicle Parts
@endsection
@section('content')
    <form method="POST"
        action="{{ isset($category) ? route('admin.categories.update', ['category' => $category->id]) : route('admin.categories.store') }}"
        autocomplete="off" id="categories-form">
        @csrf
        @if(isset($category))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="card w-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="ti ti-category me-1"></i> {{ isset($category) ? 'Edit Auto Part Category' : 'Create New Auto Part Category' }}
                        </h5>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="ti ti-arrow-left me-1"></i> Back to Categories
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cat_name" placeholder="e.g. Used Engines & Powertrain, Transmissions"
                                    name="name" value="{{ isset($category) ? $category->name : '' }}" required />
                                <div id="name-error" class="text-danger small mt-1"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">URL Slug <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cat_slug" placeholder="e.g. used-engines, transmissions" 
                                    name="slug" value="{{ isset($category) ? $category->slug : '' }}" required />
                                <small class="text-muted">Used for shop filtering (e.g. /shop?category=used-engines)</small>
                                <div id="slug-error" class="text-danger small mt-1"></div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Display Sort Order</label>
                                <input type="number" class="form-control" placeholder="1" name="index"
                                    value="{{ isset($category) ? $category->index : '1' }}" />
                                <div id="index-error" class="text-danger small mt-1"></div>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Status</label>
                                <select class="form-select" name="status">
                                    <option value="ACTIVE" {{ (!isset($category) || $category->status == 'ACTIVE') ? 'selected' : '' }}>Active (Visible Everywhere)</option>
                                    <option value="INACTIVE" {{ (isset($category) && $category->status == 'INACTIVE') ? 'selected' : '' }}>Inactive (Hidden)</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="3"
                                    placeholder="Brief description of this auto part category...">{{ isset($category) ? $category->description : '' }}</textarea>
                                <div id="description-error" class="text-danger small mt-1"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4" id="btn-submit">
                            <i class="ti ti-device-floppy me-1"></i> Save Category
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            $('#cat_name').on('input', function() {
                var slug = $(this).val()
                    .toLowerCase()
                    .replace(/[^\w ]+/g, '')
                    .replace(/ +/g, '-');
                @if(!isset($category))
                    $('#cat_slug').val(slug);
                @endif
            });

            $('#categories-form').submit(function(e) {
                e.preventDefault();
                $('div[id$="-error"]').empty();
                var submitBtn = $('#btn-submit');
                submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');

                var form = $(this);
                var url = form.attr('action');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function(data) {
                        toastr.success(data.message || 'Category saved successfully!');
                        setTimeout(function() {
                            window.location.href = "{!! route('admin.categories.index') !!}";
                        }, 1000);
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Save Category');
                        toastr.error('Please fix errors in the form.');
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                $('#' + key + '-error').html(value[0]);
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
