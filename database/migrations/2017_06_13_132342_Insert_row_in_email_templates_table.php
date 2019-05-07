<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertRowInEmailTemplatesTable extends Migration
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
                ['template_name' => 'Initial Sign up', 'slug' => 'initial-signup', 'trigger' => 'When new user Register','tags'=>'[client-email],[activation-link],[program-name],[first-name],[coach-name]','to' => '[client-email]','subject'=>'Welcome to the Life Process [program-name] Program','content'=>'<h2>Welcome to Life Process</h2> <p>Thanks for joining Life Process. We listed your sign in details below, make sure you keep them safe.<br>To verify your email address, please follow this link:</p><h3>[activation-link]</h3><p>Link does not work? Copy the following link to your browser address bar:<br>[activation-link]</p><p>Your email address: [client-email] </p><p>Thank you<br>The Life Process Team</p>'],
                ['template_name' => 'Complete Setup', 'slug' => 'complete-setup', 'trigger' => 'When new user Register Completed','tags'=>'[client-email],[client-name]','to' => '[client-email]','subject'=>'Congratulations - You are ready to start','content'=>'<h2>Dear [client-name], </h2><p>Congratulation client you are successfully activate In Life Process Program </p><p>Thank you<br>The Life Process Team</p>'],
                ['template_name' => 'User Not completed setup', 'slug' => 'user-not-completed-setup', 'trigger' => 'User has signed up but hasn’t completed set-up','tags'=>'[client-email],[client-name],[program-name],[days]','to' => '[client-email]','subject'=>'Please sign-in to complete your Life Process [program-name] Program Profile','content'=>'<h2>Dear [client-name] </h2> <p> You can not sign up last [days] days please sign-up and complete your profile</p><p>Thank you<br> The Life Process Team</p>'],

                ['template_name' => 'User not booked coach schedule', 'slug' => 'user-not-booked-coach-schedule', 'trigger' => 'User has signed up but has not booked an initial consultation','tags'=>'[client-email],[client-name],[coach-name],[days]','to' => '[client-email]','subject'=>'Book your FREE initial 1-1 consultation with [coach-name]','content'=>'<h2>Dear [client-name], </h2><p> You can not book schedule after [days],</p><p>Please book your schedule with coach [coach-name] </p><p>Thank you<br>The Life Process Team</p>'],

                ['template_name' => 'User Inactive Last 13 day', 'slug' => 'user-inactive-last-13-day', 'trigger' => 'User Inactive','tags'=>'[client-email],[first-name],[days]','to' => '[client-email]','subject'=>'How are you getting on [first-name]?','content'=>'<h2>Dear [client-name] </h2><p>You can not login last [days] Please login and complete your Program</p><p>Thank you<br>The Life Process Team</p>'],

                ['template_name' => 'User Inactive Last 27 day', 'slug' => 'user-inactive-last-27-day', 'trigger' => 'User Inactive','tags'=>'[client-email],[first-name],[days]','to' => '[client-email]','subject'=>'We Miss You [first-name], please come visit us again!','content'=>'<h2>Dear [client-name] </h2><p>You can not login last [days] Please login and complete your Program</p><p>Thank you<br>The Life Process Team</p>'],

                ['template_name' => 'User booked a session', 'slug' => 'user-booked-a-session', 'trigger' => 'User Inactive','tags'=>'[client-email],[first-name],[coach-name] [booking-date-time]','to' => '[client-email]','subject'=>'Congratulations [first-name] - Your coaching session with [coach-name] has been confirmed','content'=>'<h2>Dear [first-name] </h2><p>Your Session [booking-date-time] has Booked with your coach [coach-name] </p><p>Thank you<br>The Life Process Team</p>'],
                ['template_name' => 'User canceled a session', 'slug' => 'user-canceled-a-session', 'trigger' => 'User has canceled a session','tags'=>'[client-email],[first-name],[coach-name],[booking-date-time],[client-name],[reason]','to' => '[client-email]','subject'=>'Your Session has been canceled','content'=>'<h2>Dear [coach-name] </h2> <p>Your Session [booking-date-time] and has cancel because of [reason] and cancel by [client-name]'],
                ['template_name' => 'coach canceled a session', 'slug' => 'coach-canceled-a-session', 'trigger' => 'Coach has canceled a session','tags'=>'[client-email],[first-name],[coach-name],[booking-date-time],[reason]','to' => '[client-email]','subject'=>'Important information regarding your upcoming coaching session','content'=>'<h2>Dear [first-name], </h2> <p>Your Session [booking-date-time] has cancel because of [reason] and cancel by [coach-name]  <strong>  And your credit is refunded</strong> </p> <p>Thank you<br> The Life Process Team</p>'],
                ['template_name' => 'User has completed a session', 'slug' => 'user-completed-a-session', 'trigger' => 'User has completed a session','tags'=>'[client-email],[first-name],[coach-name],[booking-date-time]','to' => '[client-email]','subject'=>'How was your session?','content'=>'<h2>Dear,[first-name]</h2> <p> Your Session [booking-date-time] with [coach-name] is completed successfully</p> <p>Thank you<br>The Life Process Team</p>'],
                ['template_name' => 'Reminder upcoming session', 'slug' => 'reminder-upcoming-session', 'trigger' => 'Reminder re upcoming session','tags'=>'[client-email],[client-name],[coach-name],[booking-date-time]','to' => '[client-email]','subject'=>'Reminder - Your Coaching Session with [coach-name] tomorrow','content'=>'<h2>Dear [client-name], </h2> <strong>Your upcoming session details as below:</strong>
                    <table style="width: auto;" border="0" cellpadding="3" cellspacing="3"><tr>
                    <th>Coach: </th>
                    <td> [coach-name] </td>
                    </tr>
                    <tr>
                    <th>Coach Timezone: </th>
                    <td> [coach-timezone] </td>
                    </tr>
                    <tr>
                    <th> Scheduled on: </th>
                    <td> [booking-date-time] </td>
                    </tr>
                    </table>
                    <p>Link to fill a form if session doesn’t complete: [session-problem-link] </p>
                    <p></p>
                    <p>Thank you<br>The Life Process Team</p>'],
                ['template_name' => 'User buys credits', 'slug' => 'user-buys-credits', 'trigger' => 'User buys credits','tags'=>'[client-email],[client-name],[buy-credit],[total-credit]','to' => '[client-email]','subject'=>'Purchase Confirmation - Coaching Credits ','content'=>'<h2>Dear [client-name], </h2>
                    <p>You Buy Credit [buy-credit] successfully on Account<strong> your total credit is[total-credit]</strong> </p>
                    <p>Thank you<br>
                    The Life Process Team</p>'],
                ['template_name' => 'User Submits initial assessment', 'slug' => 'user-submits-initial-assessment', 'trigger' => 'User Submits initial assessment','tags'=>'[client-email],[client-name],[life-story-link]','to' => '[client-email]','subject'=>'It is time to get started with your Life Story','content'=>'<h2>Dear [client-name] </h2>
                    <p>You are submitted Your In-ital Module successfully Now You can start Your [life-story-link]</p>
                    <p>Thank you<br>The Life Process Team</p>'],
                ['template_name' => 'No updates to Life story ', 'slug' => 'no-updates-to-life-story', 'trigger' => 'No updates to Life story AND user still has not graduated','tags'=>'[client-email],[client-name],[days]','to' => '[client-email]','subject'=>'Is it time to update your life story?','content'=>'<h2>Dear [client-name] </h2>
                    <p>Your Life story Not update in last [days] days Please Update Your Life Story</p><p>Thank you<br>The Life Process Team</p>'],

                ['template_name' => 'Coach leaves a message within the system', 'slug' => 'coach-leaves-a-message-within-the-system', 'trigger' => 'Coach leaves a message within the system','tags'=>'[client-email],[client-name],[coach-name]','to' => '[client-email]','subject'=>'You have a new message from [coach-name]','content'=>'<h4>Dear [client-name]</h4><p>
                    <br>you have Some message from [coach-name] Please replay</p><p>Thank you<br>The Life Process Team</p>'],
                ['template_name' => 'Admin leaves a message within the system', 'slug' => 'admin-leaves-a-message-within-the-system', 'trigger' => 'Admin leaves a message within the system','tags'=>'[client-email],[admin-name],[coach-name]','to' => '[client-email]','subject'=>'You have a new message from Life Process Program Admin','content'=>'<h4>Dear [client-name]</h4><p>
                    <br>you have Some message from [admin-name] Please replay</p><p>Thank you<br>The Life Process Team</p>'],
                ['template_name' => 'Graduation / User Submits Final Module', 'slug' => 'graduate-submit-final-module','trigger' => 'Graduation / User Submits Final Module','tags'=>'[client-email],[first-name],[program-name]','to' => '[client-email]','subject'=>'Congratulations [first-name] - You have successfully completed the  Life Process [program-name] Program','content'=>' <h2>Dear [first-name] </h2>
                    <p>You are submitted Your Last Module successfully </p>
                    <p>Thank you<br>
                    The Life Process Team</p>'],
                ['template_name' => 'User Submits Exercise','slug' => 'user-submits-exercise','trigger' => 'User Submits Exercise','tags'=>'[client-email],[first-name],[coach-name],[exercise-name]','to' => '[client-email]','subject' => 'Congratulation [first-name] - You have successfully submitted your exercise to [coach-name]','content'=>'<h2>Dear [first-name]</h2>
                    <p>You can submitted exercise [exercise-name] for review to the [coach-name]</p>
                    <p>Thank you<br>
                    The Life Process Team</p>'],
                ['template_name' => 'Coach has submitted feedback','slug' => 'coach-has-submitted-feedback','trigger'=>'Coach has submitted feedback','tags'=>'[client-email],[first-name],[coach-name],[module-no],[module-title]','to' => '[client-email]','subject'=>'Good News! [coach-name] has left you some feedback on your  work','content'=>' <h1>Dear [first-name],</h1>
                    <h2>Feedback Alert</h2>
                    <p>
                        Coach has reviewed your module [module-no],[module-title] and submitted the review based on your exercise answer.
                    </p>
                    <p>
                        You can view the feedback in you life process website account.
                    </p>
                    <p>
                        Let us know if you have any issue.
                    </p>
                    <p>Thank you<br>
                    The Life Process Team</p>'],
                ['template_name' => 'Coach feedback has not been read/opened','slug' => 'coach-feedback-has-not been-read','trigger'=>'Coach feedback has not been read/opened','tags'=>'[client-email],[first-name],[coach-name],[module-no],[module-title]','to' => '[client-email]','subject'=>'[coach-name] feedback is still waiting for you','content'=>'<h4>Dear [first-name] <h4> <br>
                    <p>You have not view or download the feedback of the module [module-no][module-title] you submitted till the date.</p> <br>Thank you.Life process Alcohol Program']

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
            App\Models\EmailTemplate::where('slug', 'initial-signup')->delete();
            App\Models\EmailTemplate::where('slug', 'complete-setup')->delete();
            App\Models\EmailTemplate::where('slug', 'user-not-completed-setup')->delete();
            App\Models\EmailTemplate::where('slug', 'user-not-booked-coach-schedule')->delete();
            App\Models\EmailTemplate::where('slug', 'user-inactive-last-13-day')->delete();
            App\Models\EmailTemplate::where('slug', 'user-inactive-last-27-day')->delete();
            App\Models\EmailTemplate::where('slug', 'user-booked-a-session')->delete();
            App\Models\EmailTemplate::where('slug', 'user-canceled-a-session')->delete();
            App\Models\EmailTemplate::where('slug', 'coach-canceled-a-session')->delete();
            App\Models\EmailTemplate::where('slug', 'user-completed-a-session')->delete();
            App\Models\EmailTemplate::where('slug', 'reminder-upcoming-session')->delete();
            App\Models\EmailTemplate::where('slug', 'user-buys-credits')->delete();
            App\Models\EmailTemplate::where('slug', 'user-submits-initial-assessment')->delete();
            App\Models\EmailTemplate::where('slug', 'no-updates-to-life-story')->delete();
            App\Models\EmailTemplate::where('slug', 'coach-leaves-a-message-within-the-system')->delete();
            App\Models\EmailTemplate::where('slug', 'admin-leaves-a-message-within-the-system')->delete();
            App\Models\EmailTemplate::where('slug', 'user-submits-exercise')->delete();
            App\Models\EmailTemplate::where('slug', 'coach-has-submitted-feedback')->delete();
            App\Models\EmailTemplate::where('slug', 'coach-feedback-has-not been-read')->delete();
            \Cache::flush();
        });
    }
}
