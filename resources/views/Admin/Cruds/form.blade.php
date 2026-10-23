@extends('layouts.admin')
@section('title')
Crud
@endsection
<style>
    .tcul-grey-text {
        color: grey;
    }

</style>
@section('content')
<form method="POST"
    action="{{ Route::is('admin.cruds.create') ? route('admin.cruds.store') : route('admin.cruds.update', ['crud' => $crud->route_key]) }}"
    method="POST" enctype="multipart/form-data" autocomplete="off" id="crud-form">
    @csrf
    {{ Route::is('admin.cruds.create') ? '' : method_field('PUT') }}
    <div class="row">
        <div class="row">
            <div class="col-md-12">
                <h5>&nbsp;&nbsp;{{ Route::is('admin.cruds.create') ? 'Create' : 'Edit' }} Crud</h5>
            </div>
        </div>
        <br/><br/>
        <div class="row">
            <div class="col-md-6">
                <div class="accordion" id="accordionOne">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Basics
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#accordionOne">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 mt-2">
                                        <label class="control-label col-form-label">Model Name &nbsp;&nbsp;&nbsp;<small
                                                class="tcul-grey-text">["Student"]</small></label>
                                        <input type="text" class="form-control" placeholder="Model Name" name="model_name"
                                            value="{{ isset($crud) ? $crud->model_name : '' }}" />
                                        <div id="model_name-error" style="color:red"></div>
                                    </div>
                                    <div class="col-sm-12 col-md-12">
                                        <label class="control-label col-form-label">Table Name <sup
                                                class="tcul-star-restrict">*</sup>&nbsp;&nbsp;&nbsp;<small
                                                class="tcul-grey-text">["students"]</small></label>
                                        <input type="text" class="form-control" placeholder="Table Name" name="table_name"
                                            value="{{ isset($crud) ? $crud->table_name : '' }}" />
                                        <div id="table_name-error" style="color:red"></div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 mt-2">
                                        <label class="control-label col-form-label">Single Entity Variable Name &nbsp;&nbsp;&nbsp;<small
                                                class="tcul-grey-text">["student"]</small></label>
                                        <input type="text" class="form-control" placeholder="Singular Name" name="singular_name"
                                            value="{{ isset($crud) ? $crud->singular_name : '' }}" />
                                        <div id="singular_name-error" style="color:red"></div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 mt-2">
                                        <label class="control-label col-form-label">Controller Name &nbsp;&nbsp;&nbsp;<small
                                                class="tcul-grey-text">["StudentController"]</small></label>
                                        <input type="text" class="form-control" placeholder="Controller Name" name="controller_name"
                                            value="{{ isset($crud) ? $crud->controller_name : '' }}" />
                                        <div id="controller_name-error" style="color:red"></div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 mt-2">
                                        <label class="control-label col-form-label">Route Name &nbsp;&nbsp;&nbsp;<small
                                                class="tcul-grey-text">["students"]</small></label>
                                        <input type="text" class="form-control" placeholder="Route Name" name="route_name"
                                            value="{{ isset($crud) ? $crud->route_name : '' }}" />
                                        <div id="route_name-error" style="color:red"></div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 mt-2">
                                        <label class="control-label col-form-label">View Folder Name &nbsp;&nbsp;&nbsp;<small
                                                class="tcul-grey-text">["Students"]</small></label>
                                        <input type="text" class="form-control" placeholder="View Folder Name"
                                            name="view_folder_name" value="{{ isset($crud) ? $crud->view_folder_name : '' }}" />
                                        <div id="view_folder_name-error" style="color:red"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="accordion" id="accordionTwo">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                Fields
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionTwo">
                            <div class="accordion-body">
                                @for($i=1; $i <= 30; $i++) 
                                <div class="row">
                                    <div class="col-md-1 mt-2">
                                        {{ $i }})
                                    </div>
                                    <div class="col-md-5">
                                        @if(isset($crud) && isset($crud->fields) && isset($crud->fields[$i]))
                                            <input class="form-control field-name" data-id="{{ $i }}" type="text" placeholder="DB Column Name"
                                            name="fields[{{ $i }}][field]" value="{{ $crud->fields[$i]['field'] }}" />
                                        @else
                                            <input class="form-control field-name" data-id="{{ $i }}" type="text" placeholder="DB Column Name"
                                            name="fields[{{ $i }}][field]" />
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" name="fields[{{ $i }}][fieldType]">
                                            <option value="">Select Field Type</option>
                                            <option value="INPUT" {{ isset($crud) && isset($crud->fields) && isset($crud->fields[$i]) && $crud->fields[$i]['fieldType'] == 'INPUT' ? 'selected' : '' }}>Input Field</option>
                                            <option value="TEXTAREA" {{ isset($crud) && isset($crud->fields) && isset($crud->fields[$i]) && $crud->fields[$i]['fieldType'] == 'TEXTAREA' ? 'selected' : '' }}>Textarea</option>
                                            <option value="SELECT" {{ isset($crud) && isset($crud->fields) && isset($crud->fields[$i]) && $crud->fields[$i]['fieldType'] == 'SELECT' ? 'selected' : '' }}>Dropdown</option>
                                        </select>
                                    </div>
                                </div>
                                <br/>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="accordion" id="accordionThree">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                Validation Rules
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingThree"
                            data-bs-parent="#accordionThree">
                            <div class="accordion-body">
                                @for($i=1; $i <= 30; $i++) 
                                <div class="row">
                                    <div class="col-md-1 mt-2">
                                        {{ $i }})
                                    </div>
                                    <div class="col-md-3">
                                        @if(isset($crud) && isset($crud->validations) && isset($crud->validations[$i]))
                                            <input class="form-control rule-field" id="rule-field-{{ $i }}" data-id="{{ $i }}" type="text" placeholder="Field"
                                            name="validations[{{ $i }}][validationField]" value="{{ $crud->validations[$i]['validationField'] }}" />
                                        @else
                                            <input class="form-control rule-field" id="rule-field-{{ $i }}" data-id="{{ $i }}" type="text" placeholder="Field"
                                            name="validations[{{ $i }}][validationField]" />
                                        @endif  
                                    </div>
                                    <div class="col-md-8">
                                        @if(isset($crud) && isset($crud->validations) && isset($crud->validations[$i]))
                                            <input class="form-control rule" id="rule-{{ $i }}" data-id="{{ $i }}" type="text" placeholder="Rule"
                                            name="validations[{{ $i }}][validationRule]" value="{{ $crud->validations[$i]['validationRule'] }}" />
                                        @else
                                            <input class="form-control rule" id="rule-{{ $i }}" data-id="{{ $i }}" type="text" placeholder="Rule"
                                            name="validations[{{ $i }}][validationRule]" />
                                        @endif
                                    </div>
                                </div>
                                <br/>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="accordion" id="accordionFour">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                Validation Messages
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingFour"
                            data-bs-parent="#accordionFour">
                            <div class="accordion-body">
                                @for($i=1; $i <= 30; $i++) 
                                <div class="row">
                                    <div class="col-md-1 mt-2">
                                        {{ $i }})
                                    </div>
                                    <div class="col-md-3">
                                        @if(isset($crud) && isset($crud->messages) && isset($crud->messages[$i]))
                                            <input class="form-control" id="message-field-{{ $i }}" type="text" placeholder="Field"
                                            name="messages[{{ $i }}][validationField]" value="{{ $crud->messages[$i]['validationField'] }}" />
                                        @else
                                            <input class="form-control" id="message-field-{{ $i }}" type="text" placeholder="Field"
                                            name="messages[{{ $i }}][validationField]" />
                                        @endif
                                    </div>
                                    <div class="col-md-8">
                                        @if(isset($crud) && isset($crud->messages) && isset($crud->messages[$i]))
                                            <input class="form-control" type="text" placeholder="Custom Message"
                                            name="messages[{{ $i }}][validationMessage]" value="{{ $crud->messages[$i]['validationMessage'] }}" />
                                        @else
                                            <input class="form-control" type="text" placeholder="Custom Message"
                                            name="messages[{{ $i }}][validationMessage]" />
                                        @endif
                                    </div>
                                </div>
                                <br/>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br/>
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    Save
                    &nbsp;
                    <i class="ti ti-device-floppy"></i>
                </button>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <a href="{{ route('admin.cruds.index') }}" type="button" class="btn btn-secondary">
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
    $('#crud-form').submit(function (e) {
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
            success: function (data) {
                if (data.status == 'success') {
                    toastr.success(data.message, '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                    setTimeout(function () {
                        window.location.href = "{!! route('admin.cruds.index') !!}";
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
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error('There are some errors in Form. Please check your inputs', '', {
                    showMethod: "slideDown",
                    hideMethod: "slideUp",
                    timeOut: 1500,
                    closeButton: true,
                });
                $.each(xhr.responseJSON.errors, function (key, value) {
                    $('#' + key + '-error').html(value);
                });
                $('html, body').animate({
                    scrollTop: $('#' + Object.keys(xhr.responseJSON.errors)[0] + '-error')
                        .offset().top - 200
                }, 500);
            }
        });
    });

    $('input[name="model_name"]').keyup(function () {
        $('input[name="singular_name"]').val($(this).val().toLowerCase());
        $('input[name="table_name"]').val($(this).val().toLowerCase() + 's');
        $('input[name="controller_name"]').val($(this).val() + 'Controller');
        $('input[name="route_name"]').val($(this).val().toLowerCase() + 's');
        $('input[name="view_folder_name"]').val($(this).val() + 's');
    });

    $('.field-name').keyup(function () {
        var id = $(this).data('id');
        $("#rule-field-"+id).val($(this).val());
    });

    $('.rule').keyup(function () {
        var rules = $(this).val();
        setValidationMessages();
    });
    
    function setValidationMessages(){
        var i = 1;
        $('.rule').each(function(){
            if($(this).val() != ''){
                var id = $(this).data('id');
                var ruleArr = $(this).val().split('|');
                var field = $("#rule-field-"+id).val();
                ruleArr.forEach(function(rule){
                    $("#message-field-"+i).val(field+'.'+rule);
                    i++;
                });
            }
        });
    }

</script>
@endsection
