@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">Links</div>
                    <div class="card-body">
                        <div class="list-group">
                            <a class="list-group-item list-group-item-action"
                               href="{{ route('university.create') }}">Create university</a>
                            @if(sizeof(auth()->user()->universities()) != 0)
                                <a class="list-group-item list-group-item-action"
                                   href="{{ route('university.list') }}">My universities</a>
                            @endif
                        </div>
                    </div>
                </div>
                @if(auth()->user()->isAdmin())
                    <div class="card">
                        <div class="card-header">Settings</div>
                        <div class="card-body">
                            <div class="list-group">
                                <div class="list-group-item">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="register-switch"
                                               {{ Setting::registerOpened() ? 'checked' : '' }} onchange="{{ Setting::registerOpened() ? 'registerClose();' : 'registerOpen();' }}">
                                        <label class="custom-control-label" for="register-switch"
                                               id="register-switch-label">
                                            {{ Setting::registerOpened() ? 'Register on' : 'Register off' }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <script>
        function registerOpen() {
            $.ajax({
                url: "{{ route('register.open') }}",
                type: "GET",
                success: function (response) {
                    $('#register-switch-label').text('Register on');
                    $('#register-switch').attr('onchange', 'registerClose();')
                }
            });
        }

        function registerClose() {
            $.ajax({
                url: "{{ route('register.close') }}",
                type: "GET",
                success: function (response) {
                    $('#register-switch-label').text('Register off');
                    $('#register-switch').attr('onchange', 'registerOpen();')
                }
            });
        }
    </script>
@endsection
