<?php

namespace App\Http\Controllers;

class CertificateController extends Controller {

	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->title = "Certificate";
		view()->share('title', $this->title);
	}
	public function index() {
		return view('certificate.index');
	}
}
