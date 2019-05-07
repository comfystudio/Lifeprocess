<?php

namespace Permissions;

/**
 * This class is for Permissions.
 */
class Permissions {

	private $modules = [
		'auto_login' => [
			'auto_login.can_login',
		],
		'users' =>
		[
			'users.create',
			'users.view',
			'users.update',
			'users.delete',
		],
		'roles' =>
		[
			'roles.create',
			'roles.view',
			'roles.update',
			'roles.delete',
		],
		'countries' =>
		[
			'countries.create',
			'countries.view',
			'countries.update',
			'countries.delete',
		],
		// 'states' =>
		// [
		//     'states.create',
		//     'states.view',
		//     'states.update',
		//     'states.delete',
		// ],
		'programs' =>
		[
			'programs.create',
			'programs.view',
			'programs.update',
			'programs.delete',
		],
		'coaches' =>
		[
			'coaches.create',
			'coaches.view',
			'coaches.update',
			'coaches.delete',
		],
		'schedule' =>
		[
			'schedule.create',
			'schedule.view',
			'schedule.delete',
		],
		'clients' =>
		[
			'clients.create',
			'clients.view',
			'clients.update',
			'clients.delete',
		],
		'messages' =>
		[
			'messages.create',
			'messages.view',
			'messages.update',
			'messages.delete',
		],
		'notifications' => [
			'notifications.view',
		],
		'mylifestory' =>
		[
			'my_lifestory.create',
			'my_lifestory.view',
			'my_lifestory.update',
			'my_lifestory.delete',
		],
		'book_schedule' =>
		[
			'book_schedule.create',
			'book_schedule.view',
			'book_schedule.update',
			'book_schedule.delete',
		],
		'myCredits' =>
		[
			'myCredits.view',
		],
		'agents' =>
		[
			'agents.create',
			'agents.view',
			'agents.update',
			'agents.delete',
		],
		'pages' =>
		[
			'pages.create',
			'pages.view',
			'pages.update',
			'pages.delete',
		],
		'faqs' =>
		[
			'faqs.create',
			'faqs.view',
			'faqs.update',
			'faqs.delete',
		],
		'all_session' =>
		[
			'all_session.view',
		],
		'refer_friend' =>
		[
			'refer_friend.create',
			'refer_friend.view',
		],
		'credit_package' =>
		[
			'credit_package.create',
			'credit_package.view',
			'credit_package.update',
			'credit_package.delete',
		],
		'scheduled_session_problem' => [
			'scheduled_session_problem.create',
		],
		'report' => [
			'report.coach_report',
			'report.financial_report.view',
			'report.signup_report.view',
			'report.refer_friend_report.view',
		],
		'contact' => [
			'contact.create',
		],
		'email_template' =>
		[
			'email_template.update',
			'email_template.view',
		],
        'forum' =>
        [
            'forum.create'
        ]


	];

	public function getPermissions() {
		return $this->modules;
	}

}
