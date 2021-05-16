@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                @foreach($universities as $university)
                    <div class="card">
                        <img src="{{ $university->image }}" class="card-img-top" alt="img">
                        <div class="card-body">
                            <h5 class="card-title">{{ $university->short_name }}</h5>
                            <p class="card-text">{{ $university->full_name }}</p>
                        </div>
                        <div class="list-group list-group-flush">
                            @if(!$university->belongsToCurrentUser() && $university->currentUserHasAccess())
                                <div class="list-group-item">Shared access</div>
                            @endif
                            @if($university->belongsToCurrentUser())
                                <a href="{{ route('shared', $university->id) }}"
                                   class="list-group-item list-group-item-action">User access</a>
                                <a href="{{ route('university.edit', $university->id) }}"
                                   class="list-group-item list-group-item-action">Edit</a>
                                <a href="#" class="list-group-item list-group-item-action">Delete</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
