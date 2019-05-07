<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		@if(isset($first_name) &&!empty($first_name))<h1>Dear {{$first_name}}</h1>@endif
		<h2>Welcome to Life Process</h2>
		<p>You have been invited as a read only only coach in the Life Process system
		    <br>Password - <strong>{{$tempPassword}}</strong>
			<br>To login enter your email and password at the following link:</p>

		<h3><a href="{{route('login')}}"> Login...</a></h3>

		<p>We recommend when you login you change your email.
		</p>

        <p>Your email address: {{$email }} </p>
        <p>Thank you<br>
        The Life Process Team</p>
    </body>
</html>