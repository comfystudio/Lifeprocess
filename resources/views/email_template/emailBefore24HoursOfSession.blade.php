<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Dear {{ $scheduled_session->client->user->name }}, </h2>
        <strong>Your upcoming session details as below:</strong>
        <table style="width: auto;" border="0" cellpadding="3" cellspacing="3">
            <tr>
                <th>Coach: </th>
                <td> {{ $scheduled_session->client->coach->user->name }} </td>
            </tr>
            <tr>
                <th>Coach's Timezone: </th>
                <td> {{ $scheduled_session->client->coach->user->timezone }} </td>
            </tr>
            <tr>
                <th> Scheduled on: </th>
                <td> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $scheduled_session->coach_schedule->start_datetime)->setTimezone($scheduled_session->client->user->timezone)->format('m/d/Y H:i') }} </td>
            </tr>             
        </table>
        <p>Link to fill a form if session doesn’t complete: {!! link_to_route('scheduled-session-problem.create', 'Click here to Submit report to Admin', ['scheduled_session_id' => Crypt::encryptString($scheduled_session->id)]) !!} </p>
        <p></p>
        <p>Thank you<br>
        The Life Process Team</p>
    </body>
</html>