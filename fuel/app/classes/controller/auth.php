<?php
class Controller_Auth extends Controller{
	public function action_login(){
		Response::redirect(Greepf::login_redirect());
	}

	public function action_logout(){
		Response::redirect(Greepf::logout_redirect());
	}
}
