@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Login</div>
                    <div class="panel-body">
                        <a href="{!! route('loginSocial', ['social' => 'facebook']) !!}" class="btn btn-primary btn-lg btn-block">Facebook Login</a>
                        <a href="{!! route('loginSocial', ['social' => 'google']) !!}" class="btn btn-primary btn-lg btn-block">Google Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
