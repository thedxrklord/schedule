@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">{{ $university->full_name }}</div>
                    <div class="card-body">
                        <form method="post">
                            @csrf
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">
                                    User email
                                </label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control"
                                           name="userEmail" required>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Add
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Access</div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach($university->sharedUsers() as $user)
                                <li class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <div class="row">
                                                <div class="col">{{ $user->name }}</div>
                                                <div class="col">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="{{ route('shared.remove', [$university->id, $user->email]) }}"
                                               class="btn btn-danger btn-sm">Delete</a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
