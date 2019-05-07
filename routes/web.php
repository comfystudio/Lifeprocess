<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middle ware group. Now create something great!
|
 */

// Route::get('/', function () {
//     return view('dashboard');
// });
Route::get('/', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::get('/program/{program}', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);

//Auth::routes();
// Authentication Routes...
Route::get('backtoadmin', 'Auth\LoginController@backToAdmin')->name('back.to.admin');
Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm'])->name('login');
Route::post('login', ['as' => 'login', 'uses' => 'Auth\LoginController@login']);
Route::post('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

// Registration Routes...
Route::get('register', ['as' => 'auth.register.form', 'uses' => 'Auth\RegisterController@showRegistrationForm']);

Route::get('register/terms', ['as' => 'auth.register.terms', 'uses' => 'Auth\RegisterController@terms']);
Route::post('register', ['as' => 'auth.register.attempt', 'uses' => 'Auth\RegisterController@register']);
Route::get('register/subscription/{user_id}', ['as' => 'register.subscription', 'uses' => 'Auth\RegisterController@paypalSubscriptionResponse']);
Route::get('register/subscription/cancel/{user_id}', ['as' => 'register.subscription.cancel', 'uses' => 'Auth\RegisterController@paypalSubscriptioncancel']);
// Password Reset Routes...
Route::get('password/request', ['as' => 'password.request', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
Route::post('password/email', ['as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);
Route::get('password/reset/{token}', ['as' => 'password.reset', 'uses' => 'Auth\ResetPasswordController@showResetForm']);
Route::post('password/reset/', ['as' => 'password.reset.attempt', 'uses' => 'Auth\ResetPasswordController@reset']);
// User Activation Routes
Route::get('activate/{code}', ['as' => 'auth.activation.attempt', 'uses' => 'Auth\RegisterController@getActivate']);

//Role Route
Route::resource('roles', 'RoleController');

//dashboard
Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
Route::get('/allsession', ['as' => 'allsession', 'uses' => 'AllSessionController@index']);
Route::get('/session/update_status', ['as' => 'update_session_status.create', 'uses' => 'AllSessionController@create']);
Route::post('/session/update_status', ['as' => 'update_session_status.store', 'uses' => 'AllSessionController@store']);
Route::get('/getsessionpdf', ['as' => 'allsessionpdf', 'uses' => 'AllSessionController@getPDF']);

//Users Routes
Route::resource('users', 'UserController');
Route::get('auto-login/{user_id}', ['as' => 'users.auto_login', 'uses' => 'UserController@autoLoginUserById']);
Route::get('user_profile', ['as' => 'users.update.profile', 'uses' => 'UserController@getProfile']);
Route::post('user_profile', ['as' => 'users.store.profile', 'uses' => 'UserController@updateProfile']);
Route::match(array('GET', 'POST'), '/client/add-read-only-coach', 'ClientController@addReadOnlyCoach');

//Country Routes
Route::any('ajax/country', ['as' => 'ajax.country', 'uses' => 'CountryController@ajaxCountries']);
Route::resource('countries', 'CountryController');

Route::any('ajax/alluser', ['as' => 'ajax.alluser', 'uses' => 'UserController@ajaxAllUsers']);

//Coach Schedule claender
Route::resource('schedule', 'CoachScheduleController');
Route::any('schedule.store', 'CoachScheduleController@store');
Route::any('week.store', 'CoachWeekController@store');
Route::resource('week', 'CoachWeekController');
Route::resource('adjust_schedule', 'CoachWeekMonthController');
Route::any('ajax/store', ['as' => 'ajax.store', 'uses' => 'CoachWeekMonthController@store']);
Route::resource('free_session', 'CoachFreeSessionController');
Route::resource('gratuate_session', 'CoachGratuateSessionController');

//State Routes
Route::any('ajax/state', ['as' => 'ajax.state', 'uses' => 'StateController@ajaxStates']);
Route::any('ajax/allstate', ['as' => 'ajax.allstate', 'uses' => 'StateController@ajaxAllStates']);
Route::any('ajax/formstate', ['as' => 'ajax.formstate', 'uses' => 'StateController@ajaxLangStates']);
Route::resource('states', 'StateController');

//Program Routes
Route::any('ajax/program', ['as' => 'ajax.program', 'uses' => 'ProgramController@ajaxPrograms']);
Route::any('ajax/coach_program', ['as' => 'ajax.coachPrograms', 'uses' => 'ProgramController@ajaxCoachPrograms']);
Route::resource('programs', 'ProgramController');
Route::resource('resource_library', 'ResourceLibraryController');


//Module Routes
Route::any('ajax/module', ['as' => 'ajax.module', 'uses' => 'ModuleController@ajaxModules']);
Route::any('ajax/allmodule', ['as' => 'ajax.allmodule', 'uses' => 'ModuleController@ajaxAllModules']);
Route::any('ajax/formmodule', ['as' => 'ajax.formmodule', 'uses' => 'ModuleController@ajaxLangModules']);
// Route::resource('modules', 'ModuleController');

Route::get('program/{program_id}/modules', ['as' => 'modules.index', 'uses' => 'ModuleController@index']);
Route::get('program/{program_id}/module/create', ['as' => 'modules.create', 'uses' => 'ModuleController@create']);
Route::post('program/{program_id}/module/create', ['as' => 'modules.store', 'uses' => 'ModuleController@store']);
Route::get('program/{program_id}/module/{id}/edit', ['as' => 'modules.edit', 'uses' => 'ModuleController@edit']);
Route::patch('program/{program_id}/module/{id}', ['as' => 'modules.update', 'uses' => 'ModuleController@update']);
Route::delete('program/{program_id}/module/{id}', ['as' => 'modules.destroy', 'uses' => 'ModuleController@destroy']);
Route::get('program/{program_id}/module/{id}', ['as' => 'modules.show', 'uses' => 'ModuleController@show']);
//view feedback
Route::get('/view-feedback/{module_id}/{excercise_id}', ['as' => 'view.feedback', 'uses' => 'ClientProgramModulesController@viewCoachFeedback']);
Route::get('/download-feedback/{module_id}/{excercise_id}/{user_id?}', ['as' => 'download.feedback', 'uses' => 'ClientProgramModulesController@downloadCoachFeedback']);
//Coach Routes
Route::get('adminnote/{id}', ['as' => 'coaches.note', 'uses' => 'CoachController@getnote']);
Route::post('adminnote/{id}', ['as' => 'coaches.note', 'uses' => 'CoachController@savenote']);
Route::any('ajax/coach', ['as' => 'ajax.coach', 'uses' => 'CoachController@ajaxCoaches']);
Route::any('ajax/coachmodule', ['as' => 'ajax.ajaxcoachmodule', 'uses' => 'ModuleController@ajaxcoachmodule']);

Route::resource('coaches', 'CoachController');
Route::get('coach_profile', ['as' => 'coach.update.profile', 'uses' => 'CoachController@getProfile']);
Route::post('coach_profile', ['as' => 'coach.store.profile', 'uses' => 'CoachController@updateProfile']);
// Route::resource('coach-rates', 'CoachModuleRateController');
Route::get('coach-rates/{coach_id}/create', ['as' => 'coach-rates.create', 'uses' => 'CoachModuleRateController@create']);
Route::post('coach-rates/{coach_id}/create', ['as' => 'coach-rates.store', 'uses' => 'CoachModuleRateController@store']);

Route::get('coach-rates/{coach_id}/edit', ['as' => 'coach-rates.edit', 'uses' => 'CoachModuleRateController@edit']);
Route::patch('coach-rates/{coach_id}/', ['as' => 'coach-rates.update', 'uses' => 'CoachModuleRateController@update']);

Route::get('coach/{coach_id}/unlock-modules/', ['as' => 'coach.unlock-modules.index', 'uses' => 'ClientProgramModulesController@getModulesReviewedWithIn90Days']);
Route::get('unlock-module-to-reEdit-feedback/{reviewed_module_id}', ['as' => 'unlock.module.reEdit', 'uses' => 'ClientProgramModulesController@unlockModuleToEditFeedback']);
//Agents Routes
Route::resource('agents', 'AgentController');
Route::get('clientManager_profile', ['as' => 'agent.update.profile', 'uses' => 'AgentController@getProfile']);
Route::post('clientManager_profile', ['as' => 'agent.store.profile', 'uses' => 'AgentController@updateProfile']);
Route::match(array('GET', 'POST'), 'agent/add-card', 'AgentController@addCard');
Route::match(array('GET', 'POST'), 'agent/edit-theme', 'AgentController@editTheme');
//Client Routes
Route::get('client/adminnote/{id}', ['as' => 'clients.note', 'uses' => 'ClientController@getnote']);
Route::post('client/adminnote/{id}', ['as' => 'clients.note', 'uses' => 'ClientController@savenote']);
//route for client payment detail update in client-side
Route::get('client/update_payment',['as' => 'clients.update_payment', 'uses'=>'ClientController@updatePayment']);

Route::any('ajax/contactDetailByMethod', ['as' => 'ajax.contact-detail', 'uses' => 'ClientController@ajaxContactByContactMethod']);
Route::any('ajax/check-client-credit', ['as' => 'ajax.checkClientCredit', 'uses' => 'ClientController@ajaxCheckClientCredit']);
Route::get('clients/send-mail', ['as' => 'clients.send-mail.create', 'uses' => 'ClientController@createSendMail']);
Route::post('clients/send-mail', ['as' => 'clients.send-mail.send', 'uses' => 'ClientController@broadcastMail']);
Route::resource('clients', 'ClientController');
Route::get('client_profile', ['as' => 'clients.update.profile', 'uses' => 'ClientController@getProfile']);
Route::post('client_profile', ['as' => 'clients.store.profile', 'uses' => 'ClientController@updateProfile']);

Route::post('client_details/{id}', ['as' => 'clientdetails.update', 'uses' => 'ClientDetailController@update']);
Route::post('client_notedetails/{id}', ['as' => 'clientdetails.updatenote', 'uses' => 'ClientDetailController@updatenote']);
Route::get('client_coaching', ['as' => 'clients.dashboard.coaching', 'uses' => 'ClientController@coaching']);
Route::get('client_details/{id}',['as' => 'client_details.show', 'uses' => 'ClientController@show']);


//ModuleExcercise routes
Route::get('program/{program_id}/module/{module_id}/module-exercise', ['as' => 'module_exercise.index', 'uses' => 'ModuleExerciseController@index']);
Route::get('program/{program_id}/module/{module_id}/module-exercise/create', ['as' => 'module_exercise.create', 'uses' => 'ModuleExerciseController@create']);
Route::post('program/{program_id}/module/{module_id}/module-exercise/create', ['as' => 'module_exercise.store', 'uses' => 'ModuleExerciseController@store']);
Route::get('program/{program_id}/module/{module_id}/module-exercise/{id}/edit', ['as' => 'module_exercise.edit', 'uses' => 'ModuleExerciseController@edit']);
Route::patch('program/{program_id}/module/{module_id}/module-exercise/{id}', ['as' => 'module_exercise.update', 'uses' => 'ModuleExerciseController@update']);
Route::delete('program/{program_id}/module/{module_id}/module-exercise/{id}', ['as' => 'module_exercise.destroy', 'uses' => 'ModuleExerciseController@destroy']);
Route::get('program/{program_id}/module/{module_id}/module-exercise/{id}', ['as' => 'module_exercise.show', 'uses' => 'ModuleExerciseController@show']);

//Module exercise Question Route
Route::get('program/{program_id}/module/{module_id}/exercise/{exercise_id}/question/create', ['as' => 'exercise_questions.create', 'uses' => 'ModulesExercisesQuestionController@create']);
Route::post('program/{program_id}/module/{module_id}/exercise/{exercise_id}/question/create', ['as' => 'exercise_questions.store', 'uses' => 'ModulesExercisesQuestionController@store']);
Route::get('program/{program_id}/module/{module_id}/exercise/{exercise_id}}/question/{id}/edit', ['as' => 'exercise_questions.edit', 'uses' => 'ModulesExercisesQuestionController@edit']);
Route::patch('program/{program_id}/module/{module_id}/exercise/{exercise_id}}/question/{id}', ['as' => 'exercise_questions.update', 'uses' => 'ModulesExercisesQuestionController@update']);
Route::delete('program/{program_id}/module/{module_id}/exercise/{exercise_id}}/question/{id}', ['as' => 'exercise_questions.destroy', 'uses' => 'ModulesExercisesQuestionController@destroy']);

/*  Client Panel routes start */
//Client dashboard
// danish.richhi@gmail.com
Route::get('/client-dashboard', ['as' => 'client.dashboard', 'uses' => 'ClientDashboardController@index']);
Route::get('client/subscription', ['as' => 'client.subscription', 'uses' => 'ClientDashboardController@paypalSubscriptionResponse']);

Route::get('/messages-view/{user}', 'MessageController@messages_view');
Route::get('/program-view/{user}', 'ProgramController@program_view');

Route::group(['middleware' => 'check_subscription'], function () {
	//Message Routes
	Route::get('messages', ['as' => 'messages.index', 'uses' => 'MessageController@index']);
	Route::get('messages/{role}/{id}', ['as' => 'messages.admindata', 'uses' => 'MessageController@admin_data']);
	Route::get('messages/{role}/', ['as' => 'messages.getrole', 'uses' => 'MessageController@get_roles'])->middleware('check_coachIsAssigned');
	Route::post('messages/{id}', ['as' => 'messages.save', 'uses' => 'MessageController@store']);
	Route::get('contact-admin', ['as' => 'messages.contact-admin', 'uses' => 'MessageController@contact_admin']);
	Route::post('contact-admin', ['as' => 'messages.admin-store', 'uses' => 'MessageController@contactAdminStore']);
	Route::get('my-coach', ['as' => 'clients.mycoach', 'uses' => 'MessageController@myCoach']);

	Route::get('message/client-admin',['as' => 'messages.client-admin', 'uses' => 'MessageController@client_admin']);
	Route::post('message/client-admin', ['as' => 'messages.adminclient-store', 'uses' => 'MessageController@contactAdminClientStore']);
	// Route::resource('messages', 'MessageController');
	//MyLifeStory Routes
	Route::get('lifestory/{id}/edit', 'MylifestoryController@edit');
	Route::resource('mylifestory', 'MylifestoryController');
	//PDF genrator
	Route::get('/getpdf', 'MylifestoryController@getPDF');

	//Coach Schedule booked
	Route::any('ajax/cancelBookedSchedule', ['as' => 'ajax.cancelBookedSchedule', 'uses' => 'CoachSceduleBookedController@cancelBookedSchedule']);
	Route::get('scheduled-session-problem/{scheduled_session_id}/create', ['as' => 'scheduled-session-problem.create', 'uses' => 'ClientScheduledSessionProblemController@create']);
	Route::post('scheduled-session-problem/{scheduled_session_id}/store', ['as' => 'scheduled-session-problem.store', 'uses' => 'ClientScheduledSessionProblemController@store']);
	Route::resource('bookschedule', 'CoachSceduleBookedController');
	Route::any('bookschedule/edit/{id}', ['as' => 'bookschedule.edit', 'uses' => 'CoachSceduleBookedController@renderEvent']);
	Route::get('bookschedule/{id}', 'CoachSceduleBookedController@index');
	Route::any('ajax/render-event', ['as' => 'ajax.renderEvent', 'uses' => 'CoachSceduleBookedController@index']);
	Route::any('ajax/render-timeslot', ['as' => 'ajax.renderTimeslot', 'uses' => 'CoachSceduleBookedController@renderTimeslot']);
	Route::any('ajax/setFreeFYI', ['as' => 'ajax.setFreeFYI', 'uses' => 'CoachFreeSessionBookedController@setFreeFYI']);
	Route::any('ajax/setFYI', ['as' => 'ajax.setFYI', 'uses' => 'CoachSceduleBookedController@setFYI']);
	Route::resource('bookfreeschedule', 'CoachFreeSessionBookedController');
	Route::resource('bookgratuateschedule', 'CoachGratuateSessionBookedController');
	Route::any('ajax/render-Freeevent', ['as' => 'ajax.renderFreeEvent', 'uses' => 'CoachFreeSessionBookedController@index']);
	Route::any('bookfreeschedule/edit/{id}', ['as' => 'bookfreeschedule.edit', 'uses' => 'CoachFreeSessionBookedController@renderEvent']);
	Route::any('bookgratuateschedule/edit/{id}', ['as' => 'bookgratuateschedule.edit', 'uses' => 'CoachFreeSessionBookedController@renderEvent']);

	Route::any('ajax/render-Gratuateevent', ['as' => 'ajax.renderGratuateEvent', 'uses' => 'CoachGratuateSessionBookedController@index']);
	Route::any('bookgratuateschedule/edit/{id}', ['as' => 'bookgratuateschedule.edit', 'uses' => 'CoachGratuateSessionBookedController@renderEvent']);

	Route::any('ajax/render-freetimeslot', ['as' => 'ajax.renderFreeTimeslot', 'uses' => 'CoachFreeSessionBookedController@renderFreeTimeslot']);
	Route::any('ajax/render-gratuatetimeslot', ['as' => 'ajax.renderGratuateTimeslot', 'uses' => 'CoachGratuateSessionBookedController@renderGratuateTimeslot']);


	Route::get('/my-credits', ['as' => 'client.myCredits', 'uses' => 'ClientMyCreditController@index']);
	Route::any('/my-credits/buy-credits', ['as' => 'client.myCredits.purchase', 'uses' => 'ClientMyCreditController@purchaseCredits']);
	Route::match(['get', 'post'], '/my-credits/buy-credits/confirmation', ['as' => 'client.myCredits.confirmation', 'uses' => 'ClientMyCreditController@purchaseCredits_confirm']);
	Route::get('/my-credits/buy-credits/success', ['as' => 'client.myCredits.purchase.success', 'uses' => 'ClientMyCreditController@purchaseCredits_success']);
	Route::get('client/program/{program_id}/modules', ['as' => 'client.program_modules.index', 'uses' => 'ClientProgramModulesController@index']);
	Route::get('client/program/{program_id}/test', ['as' => 'client.program_modules.test', 'uses' => 'ClientProgramModulesController@test']);
	Route::get('client/program/{program_id}/gratuate_modules', ['as' => 'client.program_modules.gratuate_modules', 'uses' => 'ClientProgramModulesController@gratuateModules']);

	Route::get('client/modules/loadVideo_and_updateModuleProgress', ['as' => 'client.program_modules.loadVideo_and_updateModuleProgress', 'uses' => 'ClientProgramModulesController@loadVideo_and_updateModuleProgress']);
	Route::get('client/modules/check_delay_between_modules', ['as' => 'ajax.check_delay_between_modules', 'uses' => 'ClientProgramModulesController@check_delay_between_modules']);
	Route::get('client/modules/unlock_module', ['as' => 'ajax.unloack_module', 'uses' => 'ClientProgramModulesController@unlock_module']);
	// Route::resource('client-exercises', 'ClientProgramModuleExerciseQuestionController');
	Route::get('client-exercises/{module_id}/exercise/{exercise_id}', ['as' => 'client-exercises.create', 'uses' => 'ClientProgramModuleExerciseQuestionController@create']);
	Route::post('client-exercises/{module_id}/exercise/{exercise_id}', ['as' => 'client-exercises.store', 'uses' => 'ClientProgramModuleExerciseQuestionController@store']);
});

/*  Client Panel routes end */

/*  Coach Panel routes start */
//coach dashboard
Route::get('/coach-dashboard', ['as' => 'coach.dashboard', 'uses' => 'CoachDashboardController@index']);
//Coach Respond
Route::get('/coach-respond/{module_id}/{client_id}/{excercise_id}', ['as' => 'coach.respond', 'uses' => 'CoachRespondController@create']);
Route::post('/coach-respond/{module_id}/{client_id}/{excercise_id}', ['as' => 'coach.respond.store', 'uses' => 'CoachRespondController@store']);
/*  Coach Panel routes end */
//Client detail routes
Route::get('/client-detail/{client_id}', ['as' => 'client.detail', 'uses' => 'ClientDetailController@index']);
//Coach download feedback
Route::get('/download-feedback/{module_id}/client/{client_id}/{excercise_id}', ['as' => 'coach.download.feedback', 'uses' => 'ClientDetailController@downloadCoachFeedback']);
Route::get('/download-module-feedback/{module_id}/client/{client_id}/{excercise_id}', ['as' => 'coach.module.feedback', 'uses' => 'ClientDetailController@downloadCoachmoduleFeedback']);


// Coach notes route
Route::get('coach-notes/{client_id}/create', ['as' => 'coach-notes.create', 'uses' => 'CoachNoteController@create']);
Route::post('coach-notes/{client_id}/create', ['as' => 'coach-notes.store', 'uses' => 'CoachNoteController@store']);
Route::get('coach-notes/{client_id}/note/{id}', ['as' => 'coach-notes.show', 'uses' => 'CoachNoteController@show']);
Route::get('coach-notes/{client_id}/note/{id}/edit', ['as' => 'coach-notes.edit', 'uses' => 'CoachNoteController@edit']);
Route::patch('coach-notes/{client_id}/note/{id}/', ['as' => 'coach-notes.update', 'uses' => 'CoachNoteController@update']);

// Coach Notifications/Alerts
Route::get('alerts', ['as' => 'coach.alerts', 'uses' => 'NotificationController@index']);
Route::get('system_alerts', ['as' => 'messages.coach.system_alerts', 'uses' => 'NotificationController@get_alerts']);

//coach_detail_page
Route::get('coaches/show_details/{id}', ['as' => 'coaches.show_details', 'uses' => 'CoachController@show']);
Route::post('coaches/show_details/{coach_id}/', ['as' => 'coaches.update_save', 'uses' => 'CoachController@update_save']);

/*  Coach Panel routes end */

// Coach transaction log
Route::get('transaction-history', ['as' => 'transaction.history', 'uses' => 'CoachTransactionLogController@index'])->middleware('auth');
Route::get('transaction-receipt/{token}', ['as' => 'transaction.receipt', 'uses' => 'CoachTransactionLogController@get_transactionReceipt']);

//Sites Pages Route
Route::resource('pages', 'PageController');
Route::get('/group-meeting', 'PageController@group');
//FAQ Rotes
Route::resource('faqs', 'FaqController');
//Agent Dashboard
Route::get('/agent-dashboard', ['as' => 'agent.dashboard', 'uses' => 'AgentDashboardController@index']);

//Refer Friend
Route::resource('referfriend', 'ReferFriendController');

//Credit Package
Route::resource('creditpackage', 'CreditPakageController');

// Site settings
Route::get('site/settings', array('as' => 'users.settings', 'uses' => 'SiteSettingController@usersSettings'));
Route::post('site/settings', array('as' => 'users.settingsStore', 'uses' => 'SiteSettingController@usersSettingsStore'));

//report
Route::get('coachingreport', array('as' => 'report.coaching', 'uses' => 'ReportController@coaching_report'));
Route::get('/financialreport', ['as' => 'financialreport', 'uses' => 'FinancialReportController@index']);
Route::get('/financialreportpdf', ['as' => 'financialreportpdf', 'uses' => 'FinancialReportController@getPDF']);
Route::get('/financialreportxls', ['as' => 'financialreportxls', 'uses' => 'FinancialReportController@getXls']);
Route::get('/financialreportcsv', ['as' => 'financialreportcsv', 'uses' => 'FinancialReportController@getCsv']);

Route::get('/signupreport', ['as' => 'signupreport', 'uses' => 'SignupReportController@index']);
Route::get('/signupreportpdf', ['as' => 'signupreportpdf', 'uses' => 'SignupReportController@getPDF']);
Route::get('/refer-friend-report', ['as' => 'referfriendreport', 'uses' => 'ReferFriendsReportController@index']);
Route::get('/refer-friend-report-pdf', ['as' => 'referfriendreportpdf', 'uses' => 'ReferFriendsReportController@getPDF']);

//Forums
Route::get('/forum-categories', 'ForumController@category_index');
Route::delete('/forum-categories/delete/{id}', ['as' => 'forum-categories/delete/{id}', 'uses' => 'ForumController@category_destroy']);
Route::match(array('GET', 'POST'), '/forum-categories/create', 'ForumController@category_create');
Route::match(array('GET', 'POST'), '/forum-categories/edit/{category}', 'ForumController@category_edit');

Route::get('/forum-topics', 'ForumController@topic_index');
Route::delete('/forum-topics/delete/{id}', ['as' => 'forum-topics/delete/{id}', 'uses' => 'ForumController@topic_destroy']);
Route::match(array('GET', 'POST'), '/forum-topics/create', 'ForumController@topic_create');
Route::match(array('GET', 'POST'), '/forum-topics/edit/{topic}', 'ForumController@topic_edit');

Route::get('/forum-posts', 'ForumController@post_index');
Route::delete('/forum-posts/delete/{id}', ['as' => 'forum-posts/delete/{id}', 'uses' => 'ForumController@post_destroy']);
Route::match(array('GET', 'POST'), '/forum-posts/create', 'ForumController@post_create');
Route::match(array('GET', 'POST'), '/forum-posts/edit/{post}', 'ForumController@post_edit');


//Terms & condition
Route::get('terms', array('as' => 'terms', 'uses' => 'StaticPageControlller@terms'));
//Contactus
Route::get('contact', array('as' => 'contact', 'uses' => 'ContactusController@create'));
Route::post('contact', array('as' => 'contact.store', 'uses' => 'ContactusController@store'));
//Certificate
Route::get('certificate', array('as' => 'certificate', 'uses' => 'CertificateController@index'));
Route::get('/client-certificate', ['as' => 'client.certificate', 'uses' => 'ClientDashboardController@getPDF']);
Route::post('/client-popup', ['as' => 'clients.program.statuschange', 'uses' => 'ClientDashboardController@getPopupDisable']);
Route::post('/client-gratuate-popup', ['as' => 'clients.program.gratuatestatuschange', 'uses' => 'ClientDashboardController@getGratuatePopupDisable']);
Route::post('/client-gratuate-question', ['as' => 'clients.program.gratuatequestion', 'uses' => 'ClientDashboardController@getGratuateQuestion']);
Route::get('cancelbooking/{id}', ['as' => 'cancel.schedule', 'uses' => 'CoachSceduleBookedController@getReson']);
Route::post('cancelbooking/{id}', ['as' => 'cancel.schedule', 'uses' => 'CoachSceduleBookedController@saveReson']);
Route::get('transection-report/{id}', ['as' => 'client.transaction', 'uses' => 'ClientTransactionController@getTransaction']);
Route::get('coach-transection-report/{id}', ['as' => 'coach.transaction', 'uses' => 'ClientTransactionController@getCoachTransaction']);
Route::get('coach-transection-reportpdf/{id}', ['as' => 'coach.transaction.pdf', 'uses' => 'ClientTransactionController@getPDF']);
Route::get('coach-transection-reportxls/{id}', ['as' => 'coach.transaction.xls', 'uses' => 'ClientTransactionController@getXls']);
Route::get('coach-transection-reportcsv/{id}', ['as' => 'coach.transaction.csv', 'uses' => 'ClientTransactionController@getCsv']);


Route::get('add-manual-transection/{id}', ['as' => 'add.manual.transaction', 'uses' => 'ClientTransactionController@addManualTransaction']);
Route::post('add-manual-transection/{id}', ['as' => 'add.manual.transaction', 'uses' => 'ClientTransactionController@storeManualTransaction']);

Route::get('transection-report-pdf/{id}', ['as' => 'client.transaction.pdf', 'uses' => 'ClientTransactionController@getPDF']);
//Email-template
Route::resource('email-template', 'EmailTemplateController');
Route::any('ajax/notes', ['as' => 'ajax.notes', 'uses' => 'CoachNoteController@ajaxnotes']);
Route::any('ajax/canclesession', ['as' => 'ajax.canclesession', 'uses' => 'CoachSceduleBookedController@canclesession']);

//Stripe Webhook route all stripe events will be detected at here
Route::post('stripe/webhook','\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook');
Route::any('/resourcelibrary', ['as' => 'resource.show', 'uses' => 'Clientresourcelibrary@index']);
Route::any('/resourcelibrary/{id}', ['as' => 'resource.view', 'uses' => 'Clientresourcelibrary@show']);
Route::get('buy_credit_stripe', ['as' => 'buycredit', 'uses' => 'ClientMyCreditController@buy_credit_bystripe']);
Route::post('/stripepayment',['as' => 'stripepayment', 'uses' => 'ClientMyCreditController@buy_credit_bystripe']);

Route::get('meeting',['as' => 'messages.meeting', 'uses' => 'MessageController@meeting']);
Route::post('createmeeting',['as' => 'messages.createmeeting', 'uses' => 'MessageController@createmeeting']);
Route::get('credithistory',['as' => 'client.credithistory', 'uses' => 'ClientController@credithistory']);
Route::get('client/modules/watch_video', ['as' => 'ajax.watch_video', 'uses' => 'ClientProgramModulesController@watch_video']);
Route::any('bookschedule/update/', ['as' => 'bookschedule.update', 'uses' => 'CoachSceduleBookedController@scheduleupdate']);
Route::any('client-dashboard/assigncoach/', ['as' => 'assign.coach', 'uses' => 'ClientDashboardController@assign_coach']);

Route::any('coach_schedule/timezone/', ['as' => 'settimezone', 'uses' => 'ClientDashboardController@settimezone']);
// Route::any('coach_schedule/setterms/', ['as' => 'setterms', 'uses' => 'ClientDashboardController@setterms']);

Route::get('/view-excercise/{module_id}/{excercise_id}', ['as' => 'view.excercise', 'uses' => 'ClientProgramModulesController@viewexcercise']);

Route::post('registermodel', ['as' => 'registermodel', 'uses' => 'Auth\RegisterController@registermodel']);

Route::post('registermodelfirst', ['as' => 'registermodelfirst', 'uses' => 'Auth\RegisterController@registermodelfirst']);


Route::any('register/{id}', ['as' => 'registerthirdstep', 'uses' => 'Auth\RegisterController@registerthirdstep']);

Route::any('registration-complete', ['as' => 'registration-completed', 'uses' => 'Auth\RegisterController@registrationCompleted']);
Route::any('settimezonestep', ['as' => 'settimezonestep', 'uses' => 'Auth\RegisterController@settimezone']);
Route::any('setcoach', ['as' => 'setcoach', 'uses' => 'Auth\RegisterController@setcoach']);
Route::any('setterm', ['as' => 'setterms', 'uses' => 'Auth\RegisterController@setterms']);

Route::any('registerprocess', ['as' => 'registerprocess', 'uses' => 'Auth\RegisterController@registerprocess']);

Route::any('download.certificate', ['as' => 'download.certificate', 'uses' => 'ClientProgramModulesController@downloadcertificate']);

Route::get('credithistory/{id}',['as' => 'coach.credithistory', 'uses' => 'CoachController@credithistory']);

Route::get('/coach-payment', ['as' => 'coach.payment', 'uses' => 'CoachDashboardController@payment']);
Route::post('/coach-payment', ['as' => 'coach.payment.withdraw', 'uses' => 'CoachDashboardController@payment_withdraw']);

Route::get('add-client-manual-transection/{id}', ['as' => 'client.add.manual.transaction', 'uses' => 'ClientTransactionController@addclientManualTransaction']);
Route::post('add-client-manual-transection/{id}', ['as' => 'client.add.manual.transaction', 'uses' => 'ClientTransactionController@storeclientManualTransaction']);
Route::get('client-transection-report/{id}', ['as' => 'client.transaction.report', 'uses' => 'ClientTransactionController@getTransaction']);