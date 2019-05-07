<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		@if(isset($receiver_name) &&!empty($receiver_name))<h1>Dear {{$receiver_name}}</h1>@endif
		<p>
			<br>Some message from {{$sender_name}} Please replay
                        </p>
        <p>Thank you<br>The Life Process Team</p>
    </body>
</html>