<?php

/**
 * The Input Controller.
 * Input weight, activites and more.
 *
 * @package  app
 * @extends  Controller
 */

class Controller_Input extends Controller_Template
{
	public $template = 'template_dialog';

	public function before(){
		 parent::before();
		if (!Greepf::check()){ Response::redirect('auth/login'); }
	}

	/**
	 * Menu 
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_menu()
	{
		$this->template->title = 'Input menu';
		$this->template->contents = View::forge('input/menu');
	}

	/**
	 * Weight 
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_weight(){

		if(Input::method() == 'POST'){
			if(Input::post('submit') == 'submit'){
				$val = Model_Wslog::validate('add');
				if ($val->run()){
					$wslog = Model_Wslog::forge(array(
						'username' => Session::get('username'),
						'measured_at' => Date::create_from_string(
							Input::post('select-choice-year')
							.'-'.Input::post('select-choice-month')
							.'-'.Input::post('select-choice-day')
							.' '.Input::post('select-choice-hour')
							.':00:00', "mysql")->get_timestamp()-Session::get('timezone')*60,
						'weight' => Input::post('weight'),
						'body_fat' => Input::post('body-fat'),
						'comments' => Input::post('comments'),
						'share_with_friends_is' => Input::post('share-with-friends-is'),
						'share_with_everyone_is' => Input::post('share-with-everyone-is'),
						'created_at' => Date::forge()->get_timestamp(),
						'modified_at' => Date::forge()->get_timestamp(),
					));
					if ($wslog and $wslog->save()){
						Session::set_flash('success', 'success');
						Response::redirect('timeline/private');
					}else{
						Session::set_flash('error', 'error');
					}
				}else{
					Session::set_flash('error', $val->show_errors());
					echo 'hoge';
				}
			}elseif(Input::post('submit') == 'cansel'){
				Response::redirect('timeline/public'); 
			}
		}

		$this->template->header = '';
		$this->template->contents = View::forge('input/weight');
	}

}
