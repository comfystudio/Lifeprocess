<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		@if(isset($first_name) &&!empty($first_name))<h1>Dear {{$first_name}}</h1>@endif
		<h2>Welcome to Life Process</h2>
		<p>Thanks for joining Life Process. We listed your sign in details below, make sure you keep them safe.
			<br>To verify your email address, please follow this link:</p>

		<h3><a href="{!! route('auth.activation.attempt', $code) !!}"> Finish your registration...</a></h3>

		<p>Link doesn't work? Copy the following link to your browser address bar:
		   <br>{!! route('auth.activation.attempt', $code) !!}</a>
		</p>

        <p>Your email address: {{ $email }} </p>
        <p>Thank you<br>
        The Life Process Team</p>
    </body>
</html>