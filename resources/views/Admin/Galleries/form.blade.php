@extends('layouts.admin')
@section('title') Galleries @endsection
@section('content')

<form method="POST"
        action="{{ Route::is('admin.galleries.create') ? route('admin.galleries.store') : route('admin.galleries.update', ['gallery' => $gallery->id]) }}"
        enctype="multipart/form-data" autocomplete="off" id="galleries-form">
        @csrf
        {{ Route::is('admin.galleries.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.galleries.create') ? 'Create' : 'Edit' }} galleries  </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label for="basicInput">Upload Images </label>
                                    <input type="file" name="images[]" class="form-control-file"
                                        id="gallery-images" placeholder="Please Select Images" multiple />
                                    <div id="images-error" style="color:red"></div>
                                    <input type="hidden" id="delete_images_source" name="delete_images_source"
                                        value="">
                                </fieldset>
                                @if (isset($gallery) && isset($gallery->images))
                                    @if ($gallery->images)
                                        <div class="row"> <!-- Start of the row -->
                                            @php $count = 1; @endphp
                                            @foreach ($gallery->images as $other_image)
                                                <div class="col-md-6"> <!-- Each image takes up half the row -->
                                                    <div>
                                                        <img src="{{ asset(Storage::url($other_image)) }}" border="10"
                                                            width="100" height="100"
                                                            class="img-rounded img-thumbnail btn-delete"
                                                            id="images{{ $count }}">
                                                        <span class="remove-image" style="display:inline;"
                                                            id="otherimageSpan{{ $count }}"
                                                            data-source="{{ $other_image }}"
                                                            onclick="deleteOtherImage({{ $count }})">&#215;</span>
                                                    </div>
                                                </div>
                                                @if ($count % 2 == 0)
                                                    </div><div class="row"> <!-- End and start a new row every 2 images -->
                                                @endif
                                                @php $count++; @endphp
                                            @endforeach
                                        </div> <!-- End of the last row -->
                                    @endif
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="control-label col-form-label">Youtube Links</label>
                                <button type="button" id="add-link-btn" class="btn btn-primary btn-sm"><i class="ti ti-plus"></i></button>
                                <div id="link-inputs-container">
                                    @if(isset($gallery) && is_array($gallery->links))
                                        @foreach($gallery->links as $link)
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Enter Link" name="links[]" value="{{ $link }}"/>
                                                <div class="input-group-append">
                                                    <button class="btn btn-danger remove-link-btn" type="button">-</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Enter Link" name="links[]" value=""/>
                                            <div class="input-group-append">
                                                <button class="btn btn-danger remove-link-btn" type="button">-</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div id="links-error" style="color:red"></div>
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
                        <a href="{{ route('admin.galleries.index') }}" type="button" class="btn btn-secondary">
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
        $('#galleries-form').submit(function(e) {
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
                            window.location.href = "{!! route('admin.galleries.index') !!}";
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
            $('#images' + count).hide();
            var image = $('#otherimageSpan' + count).data('source');
            otherImage.push(image);
            $("input[name=delete_images_source]").val(otherImage.join(","));
        }

        document.getElementById('add-link-btn').addEventListener('click', function() {
            var container = document.getElementById('link-inputs-container');
            var inputGroup = document.createElement('div');
            inputGroup.innerHTML = '<div class="input-group mb-3"><input type="text" class="form-control" placeholder="Enter Link" name="links[]" value=""/><div class="input-group-append"><button class="btn btn-danger remove-link-btn" type="button">-</button></div></div>';
            container.appendChild(inputGroup);
        });

        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-link-btn')) {
                e.target.closest('.input-group').remove();
            }
        });
    </script>

@endsection