<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use Carbon\Carbon;
use App\Models\Mylifestory;
use App\Models\EmailTemplate;

class LifeStoreyUpdateNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:lifestorey-update-notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'When user not Update or create Lifestorey in 20 days';

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
        $lifestorys = Mylifestory::with('user')->orderBy('created_at', 'DESC')->get()->unique('created_user_id');
        foreach ($lifestorys as $lifestory)
        {
            $day = Carbon::now()->diffInDays(Carbon::createFromFormat('Y-m-d H:i:s',$lifestory->created_at));   
            if($day == 20)
            {
                $email_template = EmailTemplate::where('slug','no-updates-to-life-story')->first()->toArray();
                if(isset($email_template))
                {
                    $tag = ['[client-email]','[client-name]','[days]'];
                    $replace_tag = [$lifestory->user->email,$lifestory->user->name,$day];
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
                        'email_template.lifestory_update_notify', ['client_name' => $lifestory->user->name,'days' => $day],function ($message) use ($lifestory){
                            $message->to($lifestory->user->email)
                                ->subject("Is it time to update your life story?");
                            $bcc = explode(',', config('srtpl.bccmail'));
                            if (!empty($bcc)) {
                                $message->bcc($bcc);
                            }
                        });
                } 
                  //$this->info($lifestory->user->name);
            }
        }
    }
}
