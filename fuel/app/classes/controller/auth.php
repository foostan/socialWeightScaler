<?php
class Controller_Auth extends Controller{
	public function action_login(){
		$greepf = new Greepf();
		$greepf->validate_request();
	    Response::redirect(Auth::forge('quickauth')->quick_login($greepf->get_request_param('opensocial_viewer_id')));
	}

	public function action_logout(){
		Response::redirect(Auth::forge('quickauth')->quick_logout());
	}
}
