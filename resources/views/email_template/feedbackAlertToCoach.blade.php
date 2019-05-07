<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h1>Dear {{ $coach->user->name }},</h1>
        <h2>Submitted feedback Alert</h2>
        <p>
            You have reviewed the module {{ $module->module_no }}. {{ $module->module_title }} and it has been sent to the client successfully. 
        </p>
        <p>
            Let us know if you have any issue.
        </p>
        <p>Thank you<br>
        The Life Process Team</p>
    </body>
</html>