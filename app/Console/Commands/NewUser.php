<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use DB;

class NewUser extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'srtpl:newuser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create New User';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->info('press any digit listed below');
        while (1) {
            $this->info('--------------------------------------------');
            $this->question('1. Run All Migrations');
            $this->question('2. to create new administrator user');
            $this->question('0. for Exit');
            $ask = $this->ask('What is your ans?');
            switch ($ask) {
                case 0:
                    $this->info('Gud Bye......:P');
                    exit(0);
                    break;
                case 1:
                    //migrate package
                    $this->call('migrate');
                    break;
                case 2:
                    $first_name = $this->ask('What is your first name?');
                    $last_name = $this->ask('What is your last name?');
                    $email = $this->ask('What is your email?');
                    $password = $this->secret('enter the password?');
                    $mobile = $this->ask('What is your mobile number?');
                    if ($this->confirm('Do you wish to continue? [yes|no]')) {
                        $this->createUser($first_name, $last_name, $email, $password, $mobile);
                        $this->info("New User created Successfully");
                    }
                    break;
                default:
                    break;
            }
        }
    }

    private function createUser($first_name, $last_name, $email, $password, $mobile) {
        try {
            $name = $first_name . ' ' . $last_name;
            $password = bcrypt($password);
            DB::beginTransaction();
            $user = User::create([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'mobile_no' => $mobile,
                'status' => "active"
            ]);

            $administratorRole = Role::where('slug', "administrator")->first();
            if(empty($administratorRole)) {
                // Create Roles
                $administratorRole = Role::create(array(
                    'role_name' => 'Administrator',
                    'slug' => 'Administrator',
                    'permission' => json_encode(array(
                        'users.create' => true,
                        'users.update' => true,
                        'users.view' => true,
                        'users.delete' => true,
                        'roles.create' => true,
                        'roles.update' => true,
                        'roles.view' => true,
                        'roles.delete' => true,
                    )),
                ));
            }
            User::where('id', $user->id)->update(['role_id' => $administratorRole->id]);
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollback();
            $this->error( 'User with the email you entered may already be exists Or ' );
            $this->error('User Table Not Found.');
            $this->error($e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();
            $this->error( $e->getMessage() );
        }
    }

}
