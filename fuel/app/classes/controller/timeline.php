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
	public function action_public($offset = 0)
	{

		$wslogs = Model_Wslog::find('all',array(
			'order_by' => array('measured_at' => 'desc', 'created_at' => 'desc'),
			'limit' => 20,	
			'offset' => $offset,	
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
	public function action_private($offset = 0)
	{
		$wslogs = Model_Wslog::find('all',array(
			'where' => array('user_id' => Greepf::get_user_id()),
			'order_by' => array('measured_at' => 'desc', 'created_at' => 'desc'),
			'limit' => 20,	
			'offset' => $offset,	
		));

		$this->template->header = View::forge('timeline/header',array('scope'=>'private'));
		$this->template->contents = View::forge('timeline/private',array('wslogs'=>$wslogs));
	}

}
