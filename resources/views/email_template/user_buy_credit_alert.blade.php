<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Dear {{ Auth::user()->name }}, </h2>
        <p>You Buy Credit {{$buy_credit}} successfully on Account<strong> your total credit is  {{$total_credit}}</strong> </p>
        <p>Thank you<br>
        The Life Process Team</p>
    </body>
</html>