<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Dear {{Auth::user()->first_name}} </h2>
        <p>Your Session {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $start_at, $client->user->timezone)->format('D dS F Y \a\t h:i a')}} has Booked with your coach {{$client->coach->user->name}}</p>
        <p>Thank you<br>
        The Life Process Team</p>
    </body>
</html>