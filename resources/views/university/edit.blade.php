@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit university</div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label for="short-name" class="col-md-4 col-form-label text-md-right">
                                    Short name
                                </label>
                                <div class="col-md-6">
                                    <input id="short-name" type="text" class="form-control"
                                           name="university_short_name" required value="{{ $university->short_name }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="full-name" class="col-md-4 col-form-label text-md-right">
                                    Full name
                                </label>
                                <div class="col-md-6">
                                    <input id="full-name" type="text" class="form-control"
                                           name="university_full_name" required value="{{ $university->full_name }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-md-right">
                                    Description
                                </label>
                                <div class="col-md-6">
                                    <textarea id="description" class="form-control"
                                              name="university_description">{{ $university->description }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right"></label>
                                <div class="col-md-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="public"
                                               name="university_public" {{ $university->public === 1 ? 'checked' : '' }} >
                                        <label class="custom-control-label" for="public">
                                            Public ?
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Edit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
