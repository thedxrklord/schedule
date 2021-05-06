@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @foreach($universities as $university)
                <div class="card" style="width: 23rem; margin: 5px;">
                    <img src="{{ $university->image }}" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">{{ $university->short_name }} {{ (!$university->belongsToCurrentUser() && $university->currentUserHasAccess()) ? '(Общий доступ)' : '' }}</h5>
                        <p class="card-text">{{ $university->description }}</p>
                        @if($university->belongsToCurrentUser())
                            <a href="{{ route('shared', $university->id) }}" class="btn btn-outline-dark">Общий доступ</a>
                            <a href="#" class="btn btn-outline-info">Редактировать</a>
                            <a href="#" class="btn btn-outline-danger">X</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
