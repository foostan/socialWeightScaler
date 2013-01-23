<?php

/**
 * The Timeline Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 * 
 * @package  app
 * @extends  Controller
 */
class Controller_Timeline extends Controller_Template
{
	public $template = 'template';
	private $greepf = null;

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
		$this->template->header = View::forge('timeline/header',array('scope'=>'public'));
		$this->template->contents = ViewModel::forge('timeline/public');
	}

	/**
	 * The basic timeline message
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_private()
	{
		$this->template->header = View::forge('timeline/header',array('scope'=>'private'));
		$this->template->contents = ViewModel::forge('timeline/private');
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
