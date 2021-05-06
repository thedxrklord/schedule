@extends('layouts.app')

@section('content')
    <div class="container">
        {{ $university->full_name }}<br>
        <form method="post">
            <input type="email" name="userEmail" placeholder="Enter user email"><br>
            <input type="submit" value="Give access"><br>
        </form>
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @foreach($university->sharedUsers() as $user)
            {{ $user->name }}<br>
            {{ $user->email }}<br>
            <a href="{{ route('shared.remove', [$university->id, $user->email]) }}">Удалить</a>
        @endforeach
    </div>
@endsection
