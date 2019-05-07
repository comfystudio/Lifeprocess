<?php
namespace App\Http\Controllers;

use App;
use AppHelper;
use App\Models\Module;
use App\Models\Program;
use App\Models\Setting;
use App\Models\User;
use Cache;
use Flash;
use Illuminate\Http\Request;

class SiteSettingController extends Controller {

	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		view()->share('module_title', 'Settings');
		view()->share('title', 'settings');
		//{{ajaxObjectAssign}}
	}

	public function usersSettings() {
		// dump(Cache::get('settings'));
		$settings = $this->getSiteSettings();
		$programs = Program::with('modules')->get();
		$users = User::where('is_login', 1)->get();
		view()->share('users', $users);
		view()->share('programs', $programs);
		return view("settings.adminSettings", compact('settings'));
	}
	public function usersSettingsStore(Request $request) {
		$input = AppHelper::getTrimmedData($request->except('save', 'save_exit', '_token', 'default_rate'));
		$default_rate = $request->get('default_rate');
		$rules = [
//			'allow_booking_hour'=>'required|integer|min:1',
            'allow_booking_hour'=>'required',
            'cancel_booking_within' => 'required|integer|min:1',
			'coach_credit_threshold' => 'required|integer|min:1',
			'review_per_billing_cycle' => 'required|integer|min:1',
			'hasnot_viewed_feedback_after' => 'required|integer|min:1',
			'reviewed_within_last_days' => 'required|integer|min:1',
			'maintenance_mode_message' => 'required_if:maintenance_mode,On',
			'max_exercise_can_complete_per_day' => 'required|integer|min:1',
			'default_delay_between_modules' => 'required|integer|min:0',
			'contact_us_email' => 'required|email',
			'per_page' => 'required',
			'standard_subscription_cost'=>'required',
			'graduate_subscription_cost'=>'required',
			'initial_min_consulatation'=>'required',
			'standard_1hr_session'=>'required',
			'graduate_session_20min'=>'required',
			'min_slots_availibility_per_week'=>'required'
		];
		$result = $this->validate($request, $rules);

		// add checkbox value if unchecked..
		if (!isset($input['maintenance_mode'])) {
			$input['maintenance_mode'] = 'Off';
		}
		// dump($input); exit();
		foreach ($input as $name => $value) {
			$this->checkSettingName($name);
			$this->updateByName($name, $value);
		}
		if(count($default_rate))
		{
			foreach ($default_rate as $key => $program) {
				foreach ($program as $module_id => $rate) {
					$module = Module::find($module_id);
					$module->update(['default_rate' => $rate]);
				}
			}
		}
		// AppHelper::log("setting updated", $input);
		Cache::flush();
		Flash::success('Setting updated successfully.');
		return redirect()->route('users.settings');
	}
	public function get_index($filters, $sort_order) {
		$object = Setting::select(array(
			"settings.*",
		));
		$object->orderBy('settings.id', 'DESC');
		return $object->get();
	}
	public function getSiteSettings() {
		$object = Setting::select(array(
			"settings.name as name",
			"settings.value as value",
		));
		$result = $object->get()->toArray();
		$data = array();
		if (!empty($result)) {
			foreach ($result as $name => $value) {
				$data[$value['name']] = $value['value'];
			}
		}
		return $data;
	}
	public function settingUpdate($id, $input) {
		return Setting::find($id)->update($input);
	}
	public function updateByName($name, $value) {
		return Setting::where('name', $name)->update(array('value' => $value));
	}
	public function checkSettingName($name)
	{
		$setting = Setting::where('name',$name)->get()->first();
		if(is_null($setting)){
			$title = ucwords(str_replace("_", " ", $name));
			$setting = Setting::create(['name' => $name, 'title' => $title]);
		}
		return $setting;
	}
}
