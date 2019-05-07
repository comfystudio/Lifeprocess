<?php

return array(
	'project_name' => 'BugOnEmail! Life',
	'notify_emails' => array(
			// 'preetam@sphererays.net',
			// 'darshika.akhiyaniya@sphererays.net',
			// 'vishal.mehta@sphererays.net'
			),
	'email_template' => "bugonemail.notifyException",
	'notify_environment' => array('live', 'dev'),
	//'prevent_exception' => array(),
	'prevent_exception' => array('Illuminate\Foundation\Validation\ValidationException', 'LogicException', 'Illuminate\Session\TokenMismatchException', 'Illuminate\Http\Exception\HttpResponseException', 'Symfony\Component\HttpKernel\Exception\HttpException', 'Illuminate\Validation\ValidationException', 'Illuminate\Auth\AuthenticationException'),
);
