@extends('layouts.app')

@push('head')
	<link href="/static/css/login.css" rel="stylesheet" type="text/css" />
@endpush

@section('title', __('Login'))

@section('content')

<!--.home start-->
<div class="container login">
	<div class="body">
		<div class="ico" style="display: none;"></div>
		<div class="txt" style="display: none;">
			<p>
				<strong>文案正文Various Business</strong><br>
				parties are also a part of
				Xpense. They act as both
				value creators and
				beneﬁciaries. All the
				consumption records are
				open to the business in an
				anonymous way in the chain.
				While the consumption
			</p>
		</div>
		<div class="form">
			<div class="row">				
				<div class="form-input">
					<div class="content-left">
						<div class="login-form">
							<h2>{{ __('Login') }}</h2>
							@if (count($errors) > 0)
							    <div class="alert alert-danger">
							        <ul>
							            @foreach ($errors->all() as $k => $v)
							                <li>{{ $v }}</li>
							            @endforeach
							        </ul>
							    </div>
							@endif
							<form  method="POST" action="{{ url('login') }}">
								{{ csrf_field() }}
								<div class="input-row">
									<input class="input-text" type="text" placeholder="{{ __('Username') }}" name="name" value="">
								</div>
								<div class="input-row">
									<input class="input-text" type="password" placeholder="{{ __('Password') }}" name="password" value="">
								</div>
								<div class="input-row small">
									<label><input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}/>{{ __('Keep me signed in') }} </label>
								</div>
								<div class="input-row">
									<button class="input-button input-block" type="submit">{{ __('Login') }}</button>
								</div>
							</form>
						</div>
					</div>
					<div class="content-right">
						<div class="login-txt">
							<h3>&nbsp;</h3>
							<h4>{{ __('If you don’t already have an account click the button below to create your account.') }}</h4>
							<a class="btn-link" href="{{ url('/register') }}" role="button">{{ __('CREATE ACCOUNT') }}</a>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div class="row">
				<div class="form-foot">
					<div class="forgot">
						<p>
							<!-- 不能登录你的账号？是否<a href="#">忘记密码</a>了？<br> -->
							<!-- So you can’t get in to your account? Did you <a href="#">forget your password</a>? -->
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<!--.home end-->

@endsection