<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Dear {{ $client->user->name }}, </h2>
        <p>Your Session {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $schedule, $client->user->timezone)->format('D dS F Y \a\t h:i a')}} has cancel because of {{$reson}} and cancel by {{Auth::user()->name}} <strong>  And your credit is refunded</strong> </p>
        <p>Thank you<br>
        The Life Process Team</p>
    </body>
</html>