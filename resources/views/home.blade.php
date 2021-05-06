@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        <a href="{{ route('university.create') }}">Create university</a>
                        @if(sizeof(auth()->user()->universities()) != 0)
                                <a href="{{ route('university.list') }}">My universities</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
