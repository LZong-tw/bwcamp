@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }} <br>
                    @if(isset(auth()->user()->getPermission()))
                        <a href="{{ route("backendIndex") }}" class="btn btn-success">後台首頁</a>
                        <a href="" class="btn btn-primary">報到系統</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
