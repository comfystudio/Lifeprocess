<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Dear {{ $client_name }}, </h2>
        <p> Your Session {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->setTimezone($timezone)->format('m/d/Y H:i') }} with {{$coach_name}} is completed successfully</p>
        <p>Thank you<br>
        The Life Process Team</p>
    </body>
</html>