@extends('layouts.admin')
@section('title')
    Category Management
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.categories.create') ? route('admin.categories.store') : route('admin.categories.update', ['category' => $category->id]) }}"
        enctype="multipart/form-data" autocomplete="off" id="categories-form">
        @csrf
        {{ Route::is('admin.categories.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.categories.create') ? 'Create' : 'Edit' }} Category </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Index <sup class="tcul-star-restrict">*</sup></label>
                                <input type="text" class="form-control" placeholder="Write Index here..." name="index"
                                    value="{{ isset($category) ? $category->index : '' }}" />
                                <div id="index-error" style="color:red"></div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Name <sup class="tcul-star-restrict">*</sup></label>
                                <input type="text" class="form-control" placeholder="Write Category Name here..."
                                    name="name" value="{{ isset($category) ? $category->name : '' }}" />
                                <div id="name-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Slug <sup class="tcul-star-restrict">*</sup></label>
                                <input type="text" class="form-control" placeholder="Write Slug here..." name="slug"
                                    value="{{ isset($category) ? $category->slug : '' }}" />
                                <div id="slug-error" style="color:red"></div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    <label for="basicInput" class="control-label col-form-label">Description
                                        </label>
                                    <textarea type="text" name="description" class="form-control" rows="3" id="service-full_description"
                                        placeholder="Enter Description ...">{{ isset($category) ? $category->description : '' }}</textarea>
                                    <div id="description-error" style="color:red"></div>
                                </fieldset>
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
                        <a href="{{ route('admin.categories.index') }}" type="button" class="btn btn-secondary">
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
        $('#categories-form').submit(function(e) {
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
                            window.location.href = "{!! route('admin.categories.index') !!}";
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
