<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h1>Dear {{ $client->user->first_name }},</h1>
        <h2>Feedback Alert</h2>
        <p>
            Coach has reviewed your module {{ $module->module_no }}. {{ $module->module_title }} and submitted the review based on your excercise answer. 
        </p>
        <p>
            You can view the feedback in you life process website account.
        </p>
        <p>
            Let us know if you have any issue.
        </p>
        <p>Thank you<br>
        The Life Process Team</p>
    </body>
</html>