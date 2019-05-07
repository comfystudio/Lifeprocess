<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Helper;

use Mail;

/**
 * Description of BugeException
 *
 * @author Dinesh Rabara <dinesh.rabara@gmail.com>
 */
class BugeException {

    public $env = [];
    public $config = [];

    public function __construct() {
        $this->config = config('bugonemail');
        $this->config['project_name'] .='-'.config('app.name');
        $this->env = config('bugonemail.notify_environment', []);
    }

    public function notifyException($exception) {        
        if (!empty($this->env) && in_array(config('app.env'), $this->env)) {
            $request = array();
            $request['fullUrl'] = request()->fullUrl();
            $request['input_get'] = $_GET;
            $request['input_post'] = $_POST;
            $request['input_old'] = [];

            $request['cookie'] = [];
            $request['file'] = [];
            $request['header'] = [];
            $request['server'] = [];
            $request['json'] = [];
            $request['request_format'] = [];
            $request['error'] = $exception->getTraceAsString();
            $request['subject_line'] = $exception->getMessage();
            $request['class_name'] = get_class($exception);

            if (!in_array($request['class_name'], $this->config['prevent_exception'])) {
                Mail::send("{$this->config['email_template']}", $request, function($message) use ($request) {
                    $message->to($this->config['notify_emails'])->subject("{$this->config['project_name']} On Url " . $request['fullUrl']);
                });
            }
        }
        return $exception;
    }

}
