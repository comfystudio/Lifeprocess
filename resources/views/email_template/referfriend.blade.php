<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		    <h2>Dear {{$name}},</h2>
            @if($use_name == 'Yes')
                <p>Your Friend/associate {{Auth::user()->name}} has referred you to <a href="http://dev.life.srtpl.com">Life Process Program</a>
            @else
                <p>Your Friend/associate has referred you to <a href="http://dev.life.srtpl.com">Life Process Program</a>
            @endif
           {{$messages}}
		<p>
		<br>If you have any queries please feel free to contact us.</p>
        <p>Warm Regards,<br>
        The Life Process Team</p>
    </body>
</html>