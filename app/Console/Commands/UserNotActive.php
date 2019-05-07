<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use Mail;
use App\Models\UserModuleProgress;

class UserNotActive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:user-notactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Since client not signin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $inactive_user = User::where('status','active')->where('user_type','client')->with('client.program')->where('deleted','0')->where('stripe_sub_id','!=','')->get();
        foreach ($inactive_user as $user) {

            $day = Carbon::now()->diffInDays(Carbon::createFromFormat('Y-m-d H:i:s',$user->created_at));

            $moduleprogress=UserModuleProgress::where('user_id',$user->id)->count();

            if($moduleprogress==0)
            {

                if($day == 3 || $day == 5 || $day == 7)
                {
                    $email_template = EmailTemplate::where('slug','user-not-completed-setup')->first()->toArray();
                    if(isset($email_template))
                    {
                        $tag = ['[client-email]','[client-name]','[program-name]','[days]'];
                        $replace_tag = [$user->email,$user->first_name,$user->client->program->program_name,$day];
                        $to = str_replace($tag,$replace_tag,$email_template['to']);
                        $subject = str_replace($tag,$replace_tag,$email_template['subject']);
                        $content = str_replace($tag,$replace_tag,$email_template['content']);
                        Mail::send(
                        'email_template.comman', ['content' => $content],function ($message) use($to,$subject){
                            $message->to($to)
                            ->subject($subject);
                            $bcc = explode(',', config('srtpl.bccmail'));
                            if (!empty($bcc)) {
                            $message->bcc($bcc);
                            }
                        });
                    }
                    else
                    {
                        Mail::send(
                        'email_template.client_not_complete_setup_alert', ['client_name' => $user->first_name,'day' => $day],function ($message) use ($user){
                            $message->to($user->email)
                                ->subject("Please sign-in to complete your Life Process ". $user->client->program->program_name ." Program Profile");
                            $bcc = explode(',', config('srtpl.bccmail'));
                            if (!empty($bcc)) {
                                $message->bcc($bcc);
                            }
                        });
                    }
                }
            }
        }
    }
}
