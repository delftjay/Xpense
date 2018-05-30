@extends('layouts.app')

@push('head')
	<link href="/static/css/login.css" rel="stylesheet" type="text/css" />
@endpush

@section('title', __('Register'))

@section('content')

<!--.home start-->
<div class="container login">
	<div class="body">			
		<div class="form reg">
			<div class="row">
				<div class="form-input">
					<div class="content-left">
						<div class="login-form">
							<h2>{{ __('Register') }}</h2>
							@if (count($errors) > 0)
							    <div class="alert alert-danger">
							        <ul>
							            @foreach ($errors->all() as $k => $v)
							                <li>{{ $v }}</li>
							            @endforeach
							        </ul>
							    </div>
							@endif
							<form id="register-form"  method="POST" action="{{ url('register') }}">
								{{ csrf_field() }}
								<div class="input-row input-name">
									<input class="input-text" type="text" placeholder="{{ __('Username') }}" name="name" value="">
									<i class="input-status"></i>
								</div>
								<div class="input-row input-password">
									<input class="input-text" type="password" placeholder="{{ __('Password') }}" name="password" value="">
									<i class="input-status"></i>
								</div>
								<div class="input-row input-confirm_password">
									<input class="input-text" type="password" placeholder="{{ __('Confirm Password') }}" name="confirm_password" value="">
									<i class="input-status"></i>									
								</div>
								<div class="input-row input-mobile">
									<input class="input-text" type="text" placeholder="{{ __('Mobile Phone') }}" name="mobile" value="">
									<i class="input-status"></i>
								</div>
								<div class="input-row input-verify_code">
									<input class="input-text verifily" type="text" placeholder="{{ __('Enter verifiable code') }}" name="verify_code" value="">
									<button type="button" class="input-text verifily-btn">{{ __('Send verifiable code') }}</button>
									<i class="input-status"></i>
								</div>
								<div class="input-row">
									<input class="input-text" type="text" placeholder="{{ __('Enter invitation code') }}" name="invite_code" value="">
									<i class="input-status"></i>
								</div>
								<div class="input-row">
									<button id="submit-button" class="input-button input-block" type="submit">{{ __('Register') }}</button>
								</div>
							</form>
						</div>
					</div>
					<div class="content-right">
						<div class="login-txt" style="display: none;">
							<h3>文案正文</h3>
							<h4>If you don’t already have an account click the button below to create your account.</h4>								
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

@push('script')
	<script type="text/javascript" src="/static/js/ajax.setup.js"></script>
	<script type="text/javascript">
		$(function () {			
			var $form = $('#register_form'), sentSms = false, posting = false	

			var getRegisterData = function() {
				return {
					'name': $('input[name=name]').val(),
					'password': $('input[name=password]').val(),
					'confirm_password': $('input[name=confirm_password]').val(),
					'mobile': $('input[name=mobile]').val(),
					'verify_code': $('input[name=verify_code]').val(),
					'invitation_code': $('input[name=invitation_code]').val(),
				}
			}

			var formcheckByVal = function(val, pattern) {
	            switch (pattern) {
	                case "mobile":
	                    pattern = /^1[34578]\d{9}$/
	                    break

	                case "email":
	                    pattern = /^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$/
	                    break

	                default:
	                    break
	            }

	            return !!val.match(pattern)
	        }

	        var err, succ

			err = function(input_name) {
				var $el = $('.' + input_name)
				$el.addClass('err')
				$el.find('.input-status').addClass('fail')
			}

			succ = function(input_name) {
				var $el = $('.' + input_name)
				$el.removeClass('err')
				$el.find('.input-status').removeClass('fail succ').addClass('succ')
			}

			function CoolDown($btn, end_time){
				var text = $btn.text()

				$btn.attr('disabled', true)
				$btn.addClass('disabled');

				function leftTimeTimer($btn, end_time) {
					var now = Math.ceil(new Date().getTime() / 1000)
					var left_time = end_time - now
					if(left_time > 0) {
						$btn.text(left_time + 's')
						setTimeout(function() {
							leftTimeTimer($btn, end_time)
						}, 1000)
					}else {
						$btn.removeAttr('disabled')
						$btn.text(text)
					}
				}

				leftTimeTimer($btn, Math.ceil(new Date().getTime() / 1000) +  end_time)
			}

	        $('.verifily-btn').on('click', function(e) {
	        	e.stopPropagation()
				e.preventDefault()

				var data = getRegisterData(), $btn = $(this)

				$btn.attr('disabled', true)

				$('.input-mobile').addClass('checked')
				if (!data.mobile || !formcheckByVal(data.mobile, 'mobile')) {
					err('input-mobile')
					return false
				} else {
					$('.input-mobile').removeClass('checked')
				}

				$.ajax({
					type: 'POST',
					url: '/api/verify/mobile',
					data: {						
						mobile: data.mobile
					},
					success: function(result) {
						sentSms = true
						new CoolDown($btn, parseInt(result.data))
					},
					complete: function(xhr, ts){
						if(ts != 'success'){
							$btn.removeAttr('disabled')
						}
					}
				})
	        })

			$('#register-form').submit(function () {				
				var data = getRegisterData(), $btn = $('#submit-button')

				if (posting == true) {
					$btn.attr('disabled', true)
					return false
				}

				$('.input-name').addClass('checked')			
				if (!data.name || data.name.length < 4) {				
					err('input-name')
					return false
				} else {
					succ('input-name')
				}

				$('.input-password').addClass('checked')			
				if (!data.password || data.password.length < 6) {
					err('input-password')
					return false
				} else {
					succ('input-password')
				}

				$('.input-confirm_password').addClass('checked')			
				if (data.confirm_password != data.password) {
					err('input-confirm_password')
					return false
				} else {
					succ('input-confirm_password')
				}

				$('.input-mobile').addClass('checked')
				if (!data.mobile || !formcheckByVal(data.mobile, 'mobile')) {
					err('input-mobile')
					return false
				} else {
					succ('input-mobile')
				}

				$('.input-verify_code').addClass('checked')
				if (!data.verify_code || data.verify_code.length != 4 || !sentSms) {
					err('input-verify_code')
					return false
				} else {
					succ('input-verify_code')
				}

				return true
			})
		})
	</script>

@endpush

@endsection