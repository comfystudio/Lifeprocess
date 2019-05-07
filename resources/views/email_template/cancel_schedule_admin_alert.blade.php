<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Dear {{ $admin_name }}, </h2>
        <p>{{ $client }} Session {{$schedule}} has cancel because of {{$reson}} and cancel by {{Auth::user()->name}} </p>
        <p>Thank you<br>
        The Life Process Team</p>
    </body>
</html>