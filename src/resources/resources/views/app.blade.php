<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		
		<link href="{{asset('/fontawesome-free-6.4.2-web/css/all.css')}}" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900|Roboto:300,400,500,600,700,800,900|Lato:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
		
		@if(isAdminRoute())
			<script src="{{asset('/assets/tinymce/tinymce.min.js')}}"></script>
			@vite('resources/css/admin.app.scss')
		@else
			@vite('resources/css/client.app.scss')
		@endif

		<?php 
		/** 
		 * this is ziggy routes, because we dont want to expose admin url at public area  
		 */ 
		?>
		
		@if(isAdminRoute())
			@olivroutesadmin
		@else
			@olivroutes
		@endif
		<script src="{{asset('/assets/tightenco.ziggy.2.4.2.js')}}"></script>
		
		<?php 
		/** 
		 * @routes
		 */ 
		?>
		
		@vite('resources/js/app.js')
		@inertiaHead
	</head>
	<body>
		@inertia

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	</body>
</html>