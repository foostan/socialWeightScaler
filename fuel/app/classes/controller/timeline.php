<?php

/**
 * The Timeline Controller.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Timeline extends Controller_Template
{
	public $template = 'template';

	public function before(){
		parent::before();
		if (!Greepf::check()){ Response::redirect('auth/login'); }
	}

	/**
	 * The basic timeline message
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_public()
	{

		$wslogs = Model_Wslog::find('all',array(
			'join' => array('users'),
			'on' => array('users.user_id','=','wslogs.user_id'),
			'where' => array('share_with_everyone_is' => '1'),
			'order_by' => array('measured_at' => 'desc'),
		));
		
		$this->template->header = View::forge('timeline/header',array('scope'=>'public'));
		$this->template->contents = View::forge('timeline/public',array('wslogs'=>$wslogs));
	}

	/**
	 * The basic timeline message
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_private()
	{
		$wslogs = Model_Wslog::find('all',array(
			'where' => array('user_id' => Greepf::get_user_id()),
			'order_by' => array('measured_at' => 'desc'),
		));

		$this->template->header = View::forge('timeline/header',array('scope'=>'private'));
		$this->template->contents = View::forge('timeline/private',array('wslogs'=>$wslogs));
	}

	/**
	 * The 404 action for the application.
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		return Response::forge(ViewModel::forge('timeline/404'), 404);
	}
}
