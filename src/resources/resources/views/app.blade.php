<?php
	/**
	 * Don't change anything in this file as this can be overridden by artisan oliv:install
	 * please refer to https://inertiajs.com/title-and-meta if you wanna add something to your page head
	 */
?>

<!DOCTYPE html>
<html>
	<head>
		@if(isAdminRoute())
			<meta charset="utf-8" />
			<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
			
			<?php
				/**
				 * OLIV admin uses fontawesome, Bootstrap 5, and TinyMCE while you can use anything you want in the client area.
				 */
			?>
			<link href="{{asset('/fontawesome-free-6.4.2-web/css/all.css')}}" rel="stylesheet">
			<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
			<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900|Roboto:300,400,500,600,700,800,900|Lato:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
			<script src="{{asset('/assets/tinymce/tinymce.min.js')}}"></script>
			@vite('resources/css/admin.app.scss')
		@else
			@vite('resources/css/client.app.scss')
		@endif

		<?php 
			/** 
			 * this is ziggy routes, because we dont want to expose admin url at public area  
			 * @routes
			 */ 
		?>
		@if(isAdminRoute())
			@olivroutesadmin
		@else
			@olivroutes
		@endif
		
		@vite('resources/js/app.js')
		@inertiaHead
	</head>
	<body>
		@inertia
		@if(isAdminRoute())
			<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
		@endif
	</body>
</html>