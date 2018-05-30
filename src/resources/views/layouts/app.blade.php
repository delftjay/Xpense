<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title') Xpense - Blockchain consumption record rewarding platform</title>
	<link href="/static/css/style.css?v=1302"" rel="stylesheet" type="text/css" />
	<link href="/static/css/font.css"" rel="stylesheet" type="text/css" />
	<link href="/static/css/animate.css"" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/static/js/jquery.min.js""></script>
	<script type="text/javascript" src="/static/js/underscore-min.js""></script>
	<script type="text/javascript" src="/static/js/init.js""></script>
	<script type="text/javascript" src="/static/js/wow.min.js""></script>

	@stack('head')

</head>
<body id="index">
	@include('layouts.header')
	
	@yield('content')
	
	@include('layouts.footer')

	@stack('script')
</body>
</html>