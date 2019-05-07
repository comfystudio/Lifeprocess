<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <style type="text/css">
            table, tr, td, th{
                border: 1px solid black;
            }
        </style>
    </head>
    <body>
        <h1>Dear {{ $user }},</h1>
        <h3>Details of previous month</h3>
        <table cellspacing="0" width="50%">
            <tr>
                <th>Description</th>
                <th>Amount</th>
            </tr>
            <tr>
                <td>Total client Subscription</td>
                <td>{{ $subscription }}</td>
            </tr>
            <tr>
                <td>Total client Sesstion Credit spend</td>
                <td>{{ $session_credit_spend }}</td>
            </tr>
            <tr>
                <td>Total Coach Payment Module Review</td>
                <td>{{ $payment_module_review }}</td>
            </tr>
            <tr>
                <td>Total coach Session Payments</td>
                <td>{{ $session_payments }}</td>
            </tr>
        </table>
        <p>Thank you<br>
        The Life Process Team</p>
    </body>
</html>