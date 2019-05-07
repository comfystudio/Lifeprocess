<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h1>Dear Daithi</h1>
		<h2>Monthly Report For Client Managers is in!</h2>
		@foreach($report as $key => $rep)
		    <br>
		    <p>
		        <br>Client Manager - {{$rep['name']}}
		        <br> has {{$rep['pp_coach_normal']}} clients using non-fast tracked with their own coaches
		        <br> has {{$rep['pp_coach_fast']}} clients using fast tracked with their own coaches
                <br> has {{$rep['pp_llpcoach_normal']}} clients using non-fast tracked with our LLP coaches
                <br> has {{$rep['pp_llpcoach_fast']}} clients using fast tracked with our LLP coaches

                <br><strong>Total: &#36;{{$rep['total']}}</strong>
		    </p>
		@endforeach
        <p>All The Best<br>
        </p>
    </body>
</html>