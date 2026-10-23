@extends('layouts.admin')
@section('title')
    Product Management
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.products.create') ? route('admin.products.store') : route('admin.products.update', ['product' => $product->id]) }}"
        enctype="multipart/form-data" autocomplete="off" id="products-form">
        @csrf
        {{ Route::is('admin.products.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.products.create') ? 'Create' : 'Edit' }} Products  </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Category <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control" name="category_id">
                                    <option value="">Select Product Category</option>
                                    @foreach ($categories as $category)
                                        @if (isset($product) && $product->category_id == $category->id)
                                            <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                        @else
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div id="category_id-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Index <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <input type="text" class="form-control" placeholder="Write Index here..." name="index"
                                    value="{{ isset($product) ? $product->index : '' }}" />
                                <div id="index-error" style="color:red"></div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Name <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <input type="text" class="form-control" placeholder="Write Product Name here..."
                                    name="name" value="{{ isset($product) ? $product->name : '' }}" />
                                <div id="name-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Slug <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <input type="text" class="form-control" placeholder="Write Slug here..." name="slug"
                                    value="{{ isset($product) ? $product->slug : '' }}" />
                                <div id="slug-error" style="color:red"></div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="basicInput" class="control-label col-form-label">Description
                                        <sup class="tcul-star-restrict">*</sup></label>
                                    <textarea type="text" name="description" class="form-control" rows="3" id="service-full_description"
                                        placeholder="Enter product Description ...">{{ isset($product) ? $product->description : '' }}</textarea>
                                    <div id="description-error" style="color:red"></div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <label for="basicInput" class="control-label col-form-label">Upload Photos</label>
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label for="basicInput">Main Photo <sup class="tcul-star-restrict">*</sup></label>
                                    <input type="file" name="photo" class="form-control-file" id="product-photo"
                                        placeholder="Please Select Main Photo"></input>
                                    <div id="photo-error" style="color:red"></div>
                                </fieldset>
                                @if (isset($product))
                                    <img src="{{ asset(Storage::url($product->photo)) }}" border="10" width="100"
                                        height="100" class="img-rounded img-thumbnail">
                                @endif
                            </div>
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label for="basicInput">Multiple Images </label>
                                    <input type="file" name="other_photo[]" class="form-control-file"
                                        id="product-other_photo" placeholder="Please Select Images" multiple />
                                    <div id="other_photo-error" style="color:red"></div>
                                    <input type="hidden" id="delete_images_source" name="delete_images_source"
                                        value="">
                                </fieldset>
                                @if (isset($product) && isset($product->other_photo))
                                    @if (isset($product->other_photo))
                                        @php $count = 1; @endphp
                                        @foreach ($product->other_photo as $other_image)
                                            <div>
                                                <img src="{{ asset(Storage::url($other_image)) }}" border="10"
                                                    width="100" height="100"
                                                    class="img-rounded img-thumbnail btn-delete"
                                                    id="other_photo{{ $count }}">
                                                <span class="remove-image" style="display:inline;"
                                                    id="otherimageSpan{{ $count }}"
                                                    data-source="{{ $other_image }}"
                                                    onclick="deleteOtherImage({{ $count }})">&#215;</span>
                                            </div>
                                            @php $count++; @endphp
                                        @endforeach
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            Save
                            &nbsp;
                            <i class="ti ti-device-floppy"></i>
                        </button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="{{ route('admin.products.index') }}" type="button" class="btn btn-secondary">
                            Cancel
                            &nbsp;
                            <i class="ti ti-arrow-back-up-double"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>
        $('#products-form').submit(function(e) {
            e.preventDefault();
            $('div[id$="-error"]').empty();
            var form = $(this);
            var url = form.attr('action');
            $.ajax({
                type: "POST",
                url: url,
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.status == 'success') {
                        toastr.success(data.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                        setTimeout(function() {
                            window.location.href = "{!! route('admin.products.index') !!}";
                        }, 100);
                    } else {
                        toastr.error('There is some error!!', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    toastr.error('There are some errors in Form. Please check your inputs', '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        $('#' + key + '-error').html(value);
                    });
                    $('html, body').animate({
                        scrollTop: $('#' + Object.keys(xhr.responseJSON.errors)[0] + '-error')
                            .offset().top - 200
                    }, 500);
                }
            });
        });


        var otherImage = [];

        function deleteOtherImage(count) {
            $('#otherimageSpan' + count).removeAttr("style");
            $('#other_photo' + count).hide();
            var image = $('#otherimageSpan' + count).data('source');
            otherImage.push(image);
            $("input[name=delete_images_source]").val(otherImage.join(","));
        }
    </script>
@endsection
