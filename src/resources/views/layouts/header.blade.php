<!--.head start-->
<div class="head">
	<div class="logo">
		<h1>
			<a href="#home">xpense</a>
		</h1>		</div>
	<div class="nav">
		<div class="nav-toggle">
			<a href="javascript:;">
				<span class="glyphicon glyphicon glyphicon-align-justify"></span>
			</a>
		</div>
		<ul>
			<li><a href="{{ url('/#home') }}" class="now">{{ __('Home') }}</a></li>
			<li><a href="{{ url('/#product') }}">{{ __('Product') }}</a></li>
			<li><a href="{{ url('/#architecture') }}">{{ __('Architecture') }}</a></li>
			<li><a href="{{ url('/#team') }}">{{ __('Team') }}</a></li>
			<li><a href="{{ url('/#roadmap') }}">{{ __('Milestone') }}</a></li>
			<li><a href="{{ url('/#consultants') }}">{{ __('Consultant') }}</a></li>
			<li><a href="{{ url('/#partners') }}">{{ __('Partner') }}</a></li>
			@if (app()->getLocale() == 'en')
			<li><a href="{{ url('/locale?language=zh-CN') }}">中文</a></li>
			@else
			<li><a href="{{ url('/locale?language=en') }}">Enginsh</a></li>
			@endif
			
			@guest
			<li><a href="{{ url('/login') }}">{{ __('Login') }}</a></li>
			@else
			<li>
				<a href="{{ url('/logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
			@endguest
		</ul>
	</div>
	<div class="clear"></div>
</div>
<!--.head end-->