@extends('root.layouts.main')

@section('sidebar')
    @component('root.components.sidebar')
    @endcomponent
@endsection

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--hide"><i class="la la-gear"></i></span>

                    <h3 class="m-portlet__head-text">Edit category</h3>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('root.categories.update', $category) }}" id="form-category-update" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed m-form--state">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}

            <div class="m-portlet__body">
                <!-- Name -->
                <div class="form-group m-form__group row {{ $errors->has('name') ? 'has-danger' : '' }}">
                    <label for="name" class="col-lg-2 col-form-label">
                        Name <span class="m--font-danger">*</span>
                    </label>

                    <div class="col-lg-6">
                        <input type="text" name="name" id="name" class="form-control m-input {{ $errors->has('name') ?
                            'form-control-danger' :'' }}" placeholder="Please enter a name" value="{{ $category->name }}">

                        <div id="name-error" class="form-control-feedback">
                            <span class="m--font-danger">{{ $errors->first('name') }}</span>
                        </div>

                        <span class="m-form__help">The name of this category.</span>
                    </div>
                </div>
                <!--/. Name -->

                <!-- Description -->
                <div class="form-group m-form__group row {{ $errors->has('description') ? 'has-danger' : '' }}">
                    <label for="description" class="col-lg-2 col-form-label">
                        Description
                    </label>

                    <div class="col-lg-6">
                        <textarea name="description" id="description" class="summernote"
                            {{ $errors->has('description') ? 'form-control-danger' :'' }}>{{ $category->description }}
                        </textarea>

                        <div id="description-error" class="form-control-feedback">
                            <span class="m--font-danger">{{ $errors->first('description') }}</span>
                        </div>
                    </div>
                </div>
                <!--/. Description -->

                <!-- Bottom -->
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions--solid">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-6">
                                <button type="submit" id="submit" class="btn btn-brand">Update</button>
                                <a href="{{ route('root.categories.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/. Bottom -->
            </div>

        </form>
    </div>
@endsection

@section('scripts')
    <script>
        var category = function () {
            var formValidationInit = function () {
                $("form[id=form-category-update]").validate({
                    rules: {
                        name: {
                            required: true,
                            maxlength: 255
                        },

                        description: {
                            maxlength: 510
                        },
                    },

                    invalidHandler: function(event, validator) {
                        var form = $('form[id=form-category-update]');

                        mApp.scrollTo(form, -200);
                    },
                });
            }

            // summernote
            var summernoteInit = function () {
                $('.summernote').summernote({
                    height: 150
                });
            }
            //. summernote

            return {
                init: function() {
                    formValidationInit();
                    summernoteInit();
                }
            };
        }();

        $(document).ready(function() {
            category.init();
        });
    </script>
@endsection