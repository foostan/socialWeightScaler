<?php
class Controller_Auth extends Controller{
	public function action_login(){
		Response::redirect(Auth::forge('quickauth')->quick_login((Greepf::setup())));
	}

	public function action_logout(){
		Response::redirect(Auth::forge('quickauth')->quick_logout());
	}
}
