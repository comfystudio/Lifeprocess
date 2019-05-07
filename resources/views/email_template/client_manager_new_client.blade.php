<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		@if(isset($first_name) &&!empty($first_name))<h2>Dear {{$first_name}}</h2>@endif
		<p>Welcome to the Life Process Program</p>
		<p>Thanks for joining Life Process Program. We listed your sign in details below, make sure you keep them safe.
		    <br><strong>Email: {{$email}}</strong>
            <br><strong>Password: {{$password}}</strong>
            <br>Please change your password ASAP.

            <br>
			<br>To Login please use the following link:</p>

        <h3><a href="{!! route('login') !!}"> Login...</a></h3>

        <p>Your email address: {{ $email }} </p>
        <p>Thank you<br>
        The Life Process Program Team</p>
    </body>
</html>