@extends('layouts.login')
@section('content')
  {{-- Form::open( array('route' => array('main.provider.update-sppassword',$serviceProvider->id),'method'=>'PUT', 'class'=>'form-signin', 'accept-charset'=>'utf-8')) --}}
<form class="form-signin" method="POST" action="{{ URL::to('update-sppassword'.'/'.$serviceProvider->id) }}">
    {{ csrf_field() }}
        <h2 class="form-signin-heading">{{ trans('main.changepassword')}}</h2>
            <div class="login-wrap">
                <div class="user-login-info">
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <input type="password" class="form-control" placeholder="Password" name="password" required autofocus>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Re-enter" required autofocus>
                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <button class="btn btn-lg btn-login btn-block" type="submit">{{ trans('main.save')}}</button>
            </div>
{{-- Form::close() --}}
@endsection
