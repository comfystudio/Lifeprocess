<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Dear {{ $adminUser->name }},</h2>
        <h4>Client {{ ucwords($scheduled_session->client->user->name) }} has problem in scheduled session, details as below:</h4>
        <table style="width: auto;" border="0" cellspacing="3" cellpadding="2">
            <tr>
                <th>Coach name: </th>
                <td> {{ $scheduled_session->coach_schedule->user->name }} </td>
            </tr>
            <tr>
                <th>Session Time: </th>
                <td>
                    @php
                        $timezone = 'UTC';
                        if(isset($scheduled_session->client->user->timezone)) {
                            $timezone = $scheduled_session->client->user->timezone;
                        }
                       echo $session_time = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $scheduled_session->coach_schedule->start_datetime, $timezone)->format('m/d/Y H:i');
                    @endphp
                </td>
            </tr>
            <tr>
                <th>Problem: </th>
                <td>{{ $problem_added->problem }}</td>
            </tr>
            @if($problem_added->other != '')
                <tr>
                    <th>Other problem:</th>
                    <td>{{ $problem_added->other }}</td>
                </tr>
            @endif
        </table>
        <p>Thank you<br>
        The Life Process Team</p>
    </body>
</html>