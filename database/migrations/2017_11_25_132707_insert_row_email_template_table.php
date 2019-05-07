<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertRowEmailTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $rows = [
            ['template_name' => 'User churns', 'slug' => 'user-churns', 'trigger' => 'When user cancelled there membership','tags'=>'[client-name][client-first-name][program-name][coach-name]','to' => '[coach-email]','subject'=>'Drat… [client-name] has cancelled their membership.','content'=>'Hi  [coach-name],
                    A client has cancelled their membership:
                    Name: [client-name]
                    Program: [program-name]
                    [client-first-name] will no longer have access to their account and will not be able to receive any more messages.
                    '],

            ['template_name' => 'User Submits Exercise', 'slug' => 'user-submit-excercise-coach', 'trigger' => 'client submit excercise mail to coach','tags'=>'[client-name],[coach-email],[program-name],[excercise-name],[coach-name]','to' => '[coach-email]','subject'=>'LPP Alert -  [client-name]  has submitted an exercise','content'=>'<p>Hi [coach-name]<br><br>A client has submitted an exercise for review:<br><br>Name: [client-name]<br>Program: [program-name]<br>Exercise: [excercise-name]</p><p><br>The exercise will be available for you to view within your coach dashboard</p>'],

            ['template_name' => 'User accesses your feedback for first time', 'slug' => 'user-feedback-first-time-coach', 'trigger' => 'User accesses your feedback for first time','tags'=>'[client-name],[coach-email],[coach-name],[program-name],[excercise-name]','to' => '[coach-email]','subject'=>'LPP Alert - [client-name] has read your feedback','content'=>'read feedback'],

            ['template_name' => 'User has booked a session on the system', 'slug' => 'user-book-session-coach', 'trigger' => 'client book session mail to coach','tags'=>'[client-name],[coach-name],[client-name],[date],[time],[format],[session]','to' => '[coach-email]','subject'=>'Congratulations [client-name] has booked a 1-1 session with you ','content'=>'<p><b style="font-weight:normal;" id="docs-internal-guid-a6c1ec1e-1706-b784-4119-135bc6b24f4a"></b></p><p dir="ltr" style="line-height:1.2;margin-top:5pt;margin-bottom:14pt;background-color:#ffffff;"><b style="font-weight:normal;" id="docs-internal-guid-a6c1ec1e-1706-b784-4119-135bc6b24f4a"><span style="font-size:9.5pt;font-family:Arial;color:#222222;background-color:#ffffff;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">Hi [coach-name],</span></b></p><p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:14pt;background-color:#ffffff;"><b style="font-weight:normal;" id="docs-internal-guid-a6c1ec1e-1706-b784-4119-135bc6b24f4a"><span style="font-size:9.5pt;font-family:Arial;color:#222222;background-color:#ffffff;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">A client has booked a session with </span></b></p><p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:14pt;background-color:#ffffff;"><b style="font-weight:normal;" id="docs-internal-guid-a6c1ec1e-1706-b784-4119-135bc6b24f4a"><span style="font-size:9.5pt;font-family:Arial;color:#222222;background-color:#ffffff;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">Client</span><span style="font-size:9.5pt;font-family:Arial;color:#222222;background-color:#ffffff;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">: [client-name]</span></b></p><p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:14pt;background-color:#ffffff;"><b style="font-weight:normal;" id="docs-internal-guid-a6c1ec1e-1706-b784-4119-135bc6b24f4a"><span style="font-size:9.5pt;font-family:Arial;color:#222222;background-color:#ffffff;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">Date:</span></b></p><p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:14pt;background-color:#ffffff;"><b style="font-weight:normal;" id="docs-internal-guid-a6c1ec1e-1706-b784-4119-135bc6b24f4a"><span style="font-size:9.5pt;font-family:Arial;color:#222222;background-color:#ffffff;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">Time:</span></b></p><p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:14pt;background-color:#ffffff;"><b style="font-weight:normal;" id="docs-internal-guid-a6c1ec1e-1706-b784-4119-135bc6b24f4a"><span style="font-size:9.5pt;font-family:Arial;color:#222222;background-color:#ffffff;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">Format:</span></b></p><p dir="ltr" style="line-height:1.2;margin-top:0pt;margin-bottom:14pt;background-color:#ffffff;"><b style="font-weight:normal;" id="docs-internal-guid-a6c1ec1e-1706-b784-4119-135bc6b24f4a"><span style="font-size:9.5pt;font-family:Arial;color:#222222;background-color:#ffffff;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;"><br></span><b style="font-weight:normal;" id="docs-internal-guid-a6c1ec1e-1706-d267-9469-99aa25e514d1"><span style="font-size:9.5pt;font-family:Arial;color:#222222;background-color:#ffffff;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre-wrap;">You will initiate the session through you coach control panel.</span></b></b></p><br class="Apple-interchange-newline"><p></p>'],

            ['template_name' => 'User has cancelled a session', 'slug' => 'user-cancel-session-coach', 'trigger' => 'User has cancelled a session','tags'=>'[coach-email],[coach-name],[client-name],[date],[time],[format],[session]','to' => '[coach-email]','subject'=>'Drat… [client-name] has cancelled their coaching session','content'=>'Hi  [coach-name],
                A client has cancelled their upcoming session:
                Client: [client-name]
                Date:[date]
                Time:[time]
                Format:[format]
                Session:[session]
                '],

            ['template_name' => 'Coaching Session scheduled (24-hour)', 'slug' => 'coaching-session-scheduled-24hr ', 'trigger' => 'when Coaching Session scheduled Coaching(session before-24hrs)','tags'=>'[coach-email],[coach-name],[client-name],[date],[time],[format],[session]','to' => '[coach-email]','subject'=>'LPP Alert  - You have a coaching session with [client-name] tomorrow','content'=>'Hi  [coach-name],
                Just a quick reminder that you have a coaching session scheduled for tomorrow:
                Client: [client-name]
                   Date:[date]
                                Time:[time]
                                Format:[format]
                                Session:[session]
                 '],

            ['template_name' => 'Coaching Session scheduled (1-hr)', 'slug' => 'coaching-session-scheduled-1hr ', 'trigger' => 'when Coaching Session scheduled Coaching(session before-24hrs)','tags'=>'[coach-email],[coach-name],[client-name],[date],[time],[format],[session],[coach-timezone-time]','to' => '[coach-email]','subject'=>'LPP Alert - Your coaching session with [client-name] is due to start at [coach-timezone-time] ','content'=>'Hi  [coach-name],
                Just a quick reminder that your coaching session is due to begin in one hour:
                Client: [client-name]
                   Date:[date]
                                Time:[time]
                                Format:[format]
                                Session:[session]'],

            ['template_name' => 'User Requests Cancellation', 'slug' => 'user-requests-cancellation', 'trigger' => 'User Requests Cancellation(when admin update profile)','tags'=>'[client-name],[client-name]','to' => '[client-email]','subject'=>'Goodbye [client-name], We hope we have helped','content'=>'Hi [client-name],
                    This email is to confirm your cancellation from the Life Process Program.
                    We have de-activated your profile so you are no longer subscribed to the program and will not be charged any further.
                    As part of our customer service, we are doing a follow-up to enquire if we could have done anything different for you to continue on with the program?
                    We would really appreciate if you could find the time to provide us with your feedback.
                    What were you hoping to achieve with the program?
                    Did you find the program beneficial?
                    Would you have been more inclined to remain on the program if it was structured differently?
                    Please let us know your thoughts on the Life Process Program so we can continue to enhance our addiction recovery program.
                    '],

            ['template_name' => 'Final Payment Attempt Fails', 'slug' => 'final-payment-attempt-fails', 'trigger' => 'Final Payment Attempt Fails','tags'=>'[client-name],[client-email],[amount]','to' => '[client-email]','subject'=>'Your account has been suspended','content'=>'Hi  [client-name],
                Unfortunately we have been unable to collect the monthly subscription about of [amount] from your account.
                Your account has therefore been temporarily suspended.
                To reactivate your account please update your credit card details using the following link  <LINK TO UPDATE CARD – NOTE USER SHOULD BE ABLE TO ACCESS THIS WHEN SUSPENDED>/LINK>
                NOTE – THIS TRIGGER SHOULD ALSO SET THE USERS ACCOUNT TO INACTIVE AND SHOULD DIRECT THEM TO UPDATE CREDIT CARD DETAILS IF THEY ATTEMPT TO LOGIN
            '],

            ['template_name' => 'Reminder re upcoming session-24hour', 'slug' => 'reminder-upcoming-session-24hr', 'trigger' => 'Reminder re upcoming session-24hr','tags'=>'[client-email],[client-name],[coach-name],[booking-date-time],[date],[time],[session]','to' => '[client-email]','subject'=>'Reminder - Your Coaching Session with [coach-name] tomorrow','content'=>' Hi  [client-name],
                Just a quick email to remind you of your upcoming session with [coach-name] tomorrow. Details of your session are as follows:
                  Date:[date]
                                Time:[time]
                                Format:[format]
                                Session:[session]
                '],

            ['template_name' => 'Reminder re upcoming session-1hour', 'slug' => 'reminder-upcoming-session-1hr', 'trigger' => 'Reminder re upcoming session-1hr','tags'=>'[client-email],[client-name],[coach-name],[booking-date-time],[start-time-in-client-timezone],[date],[time],[format],[session]','to' => '[client-email]','subject'=>'Reminder - Your Coaching Session with [coach-name] is due to start at [start-time-in-client-timezone]','content'=>' Hi  [client-name],
                Your session will begin shortly. Details of your session are as follows:
                 Date:[date]
                                Time:[time]
                                Format:[format]
                                Session:[session]
                                Your Coach:[coach-name]'
            ],

            ['template_name' => 'User has booked first session ', 'slug' => 'user-booked-first-session', 'trigger' => 'Reminder re upcoming session-1hr','tags'=>'[coach-name],[client-email],[client-name]','to' => '[client-email]','subject'=>'Important Information regarding your coaching session','content'=>'Hi  [client-name],
                    step to install zoom'],

            ['template_name' => 'User has signed up but has not booked an initial consultation', 'slug' => 'user-not-booked-initial-27d', 'trigger' => 'User has signed up but has not booked an initial consultation for 27 day','tags'=>'[coach-name],[client-name],[client-email],[link]','to' => '[client-email]','subject'=>'Book your FREE initial 1-1 consultation with [coach-name]','content'=>'Hi  [client-name],
                We contacted you recently to remind you to book your initial consultation with your coach.
                This initial consultation is included free of charge and it is a great opportunity for you to connect with [coach-name], your dedicated coach. Our experience shows that clients who avail of the free consultation go on to have better communications with their coach for the remainder of their journey through the Life Process Program.
                To schedule your initial consultation simply visit our website [link].'],

            ['template_name' => 'User has signed up but has not booked an initial consultation', 'slug' => 'user-not-booked-initial-13d', 'trigger' => 'User has signed up but has not booked an initial consultation for 13 day','tags'=>'[coach-name],[client-name],[client-email],[link]','to' => '[client-email]','subject'=>'Book your FREE initial 1-1 consultation with [coach-name]','content'=>'Hi  [client-name],
                We contacted you recently to remind you to book your initial consultation with your coach.
                This initial consultation is included free of charge and it is a great opportunity for you to connect with [coach-name], your dedicated coach. Our experience shows that clients who avail of the free consultation go on to have better communications with their coach for the remainder of their journey through the Life Process Program.
                To schedule your initial consultation simply visit our website [link].'],

            ['template_name' => 'User has signed up but has not booked an initial consultation', 'slug' => 'user-not-booked-initial-8d', 'trigger' => 'User has signed up but has not booked an initial consultation for 8 day','tags'=>'[coach-name],[client-name],[client-email],[link]','to' => '[client-email]','subject'=>'Book your FREE initial 1-1 consultation with [coach-name]','content'=>'Hi  [client-name],
                We contacted you recently to remind you to book your initial consultation with your coach.
                This initial consultation is included free of charge and it is a great opportunity for you to connect with [coach-name], your dedicated coach. Our experience shows that clients who avail of the free consultation go on to have better communications with their coach for the remainder of their journey through the Life Process Program.
                To schedule your initial consultation simply visit our website [link].'],

            ['template_name' => 'User has signed up but has not booked an initial consultation', 'slug' => 'user-not-booked-initial-4d', 'trigger' => 'User has signed up but has not booked an initial consultation for 4 day','tags'=>'[coach-name],[client-name],[client-email],[link]','to' => '[client-email]','subject'=>'Book your FREE initial 1-1 consultation with [coach-name]','content'=>'Hi  [client-name],
                We contacted you recently to remind you to book your initial consultation with your coach.
                This initial consultation is included free of charge and it is a great opportunity for you to connect with [coach-name], your dedicated coach. Our experience shows that clients who avail of the free consultation go on to have better communications with their coach for the remainder of their journey through the Life Process Program.
                To schedule your initial consultation simply visit our website [link].'],

            ['template_name' => 'Coach feedback has not been read/opened', 'slug' => 'coach-feedback-not-open-3day', 'trigger' => 'Coach feedback has not been read/opened','tags'=>'[coach-name],[client-name],[client-email],[excercise-name]','to' => '[client-email]','subject'=>'[coach-name] feedback is still waiting for you','content'=>'
                Hi  [client-name],
                This is just a quick follow-up note to let you know that [coach-name] has left you some feedback for exercise [excercise-name].
                You can review your feedback by logging in here
            '],
             ['template_name' => 'Coach feedback has not been read/opened', 'slug' => 'coach-feedback-not-open-7day', 'trigger' => 'Coach feedback has not been read/opened','tags'=>'[coach-name],[client-name],[client-email],[excercise-name]','to' => '[client-email]','subject'=>'[coach-name] feedback is still waiting for you','content'=>'
                Hi  [client-name],
                This is just a quick follow-up note to let you know that [coach-name] has left you some feedback for exercise [excercise-name].
                You can review your feedback by logging in here
            '],
            ];
            App\Models\EmailTemplate::insert($rows);
            \Cache::flush();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('email_templates', function (Blueprint $table) {
            App\Models\EmailTemplate::where('slug', 'user-churns')->delete();
            App\Models\EmailTemplate::where('slug', 'user-submit-excercise-coach')->delete();
            App\Models\EmailTemplate::where('slug', 'user-feedback-first-time-coach')->delete();
            App\Models\EmailTemplate::where('slug', 'user-book-session-firsttime-coach')->delete();
            App\Models\EmailTemplate::where('slug', 'user-cancel-session-coach')->delete();
            App\Models\EmailTemplate::where('slug', 'coaching-session-scheduled-24hr')->delete();
            App\Models\EmailTemplate::where('slug', 'coaching-session-scheduled-1hr')->delete();
            App\Models\EmailTemplate::where('slug', 'user-requests-cancellation')->delete();
            App\Models\EmailTemplate::where('slug', 'final-payment-attempt-fails')->delete();
            App\Models\EmailTemplate::where('slug', 'reminder-upcoming-session-24hr')->delete();
            App\Models\EmailTemplate::where('slug', 'reminder-upcoming-session-1hrn')->delete();
            App\Models\EmailTemplate::where('slug', 'user-booked-first-session')->delete();
            App\Models\EmailTemplate::where('slug', 'user-not-booked-initial-27d')->delete();
            App\Models\EmailTemplate::where('slug', 'user-not-booked-initial-13d')->delete();
            App\Models\EmailTemplate::where('slug', 'user-not-booked-initial-8d')->delete();
            App\Models\EmailTemplate::where('slug', 'user-not-booked-initial-4d')->delete();
            App\Models\EmailTemplate::where('slug', 'coach-feedback-not-open')->delete();
             App\Models\EmailTemplate::where('slug', 'coach-feedback-not-open-3day')->delete();
              App\Models\EmailTemplate::where('slug', 'coach-feedback-not-open-7day')->delete();
            \Cache::flush();
        });
    }
}
