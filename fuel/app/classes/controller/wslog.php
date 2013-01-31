<?php

/**
 * The Wslog Controller.
 * Edit Model Wslog 
 *
 * @package  app
 * @extends  Controller
 */

class Controller_Wslog extends Controller_Template
{
	public $template = 'template_dialog';

	public function before()
	{
		parent::before();
		if (!Greepf::check()){ Response::redirect('auth/login'); }
	}


	/**
	 * add 
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_add()
	{
		if(Input::method() == 'POST')
		{
			$val = Model_Wslog::validate('add');
			if($val->run())
			{
				$measured_at = Date::create_from_string(
					Input::post('select-choice-year')
					.'-'.Input::post('select-choice-month')
					.'-'.Input::post('select-choice-day')
					.' '.Input::post('select-choice-hour')
					.':00:00', "mysql")->get_timestamp()-Greepf::get_user_difftime();

				// update diff of next data
				$next_wslog = Model_Wslog::find('first',array(
					'where' => array(
						array('user_id',Greepf::get_user_id()),
						array('measured_at','>=',$measured_at),
					),
					'order_by' => array('measured_at' => 'asc', 'created_at' => 'asc'),
				));
				if(!is_null($next_wslog))
				{
					$next_wslog->weight_diff = $next_wslog->weight - Input::post('weight');
					$next_wslog->body_fat_diff = $next_wslog->body_fat - Input::post('body-fat');
					$next_wslog->modified_at = Date::forge()->get_timestamp();
					$next_wslog->save();
				}

				// calculate diff from prev data
				$weight_diff = 0;
				$body_fat_diff = 0;
				$prev_wslog = Model_Wslog::find('first',array(
					'where' => array(
						array('user_id',Greepf::get_user_id()),
						array('measured_at','<=',$measured_at),
					),
					'order_by' => array('measured_at' => 'desc', 'created_at' => 'desc'),
				));
				if(!is_null($prev_wslog))
				{
					$weight_diff = Input::post('weight') - $prev_wslog->weight;
					$body_fat_diff = Input::post('body-fat') - $prev_wslog->body_fat;
				}

				$wslog = Model_Wslog::forge(array(
					'user_id' => Greepf::get_user_id(),
					'measured_at' => $measured_at,
					'weight' => Input::post('weight'),
					'weight_diff' => $weight_diff,
					'body_fat' => Input::post('body-fat'),
					'body_fat_diff' => $body_fat_diff,
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
			}
		}

		$last_wslog = Model_Wslog::find('first',array(
			'where' => array('user_id' => Greepf::get_user_id()),
			'order_by' => array('measured_at' => 'desc', 'created_at' => 'desc'),
		));


		$this->template->header = '';
		$this->template->contents = View::forge('input/weight',array('last_wslog'=>$last_wslog));
	}

	/**
	 * delete 
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_delete($id)
	{
		$wslog = Model_Wslog::find('first',array(
			'where' => array(
				array('user_id',Greepf::get_user_id()),
				'and' => array('id',$id),
			),
		));
		if($wslog)
		{
			// update diff of next data
			$next_wslog = Model_Wslog::find('first',array(
				'where' => array(
					array('user_id',Greepf::get_user_id()),
					array('id','!=',$id),
					array('measured_at','>=',$wslog->measured_at),
				),
				'order_by' => array('measured_at' => 'asc', 'created_at' => 'asc'),
			));
			if($next_wslog)
			{
				// get diff from prev data
				$weight_diff   = 0;
				$body_fat_diff = 0;
				$prev_wslog = Model_Wslog::find('first',array(
					'where' => array(
						array('user_id',Greepf::get_user_id()),
						array('id','!=',$id),
						array('measured_at','<=',$wslog->measured_at),
					),
					'order_by' => array('measured_at' => 'desc', 'created_at' => 'desc'),
				));

				if($prev_wslog)
				{
					$next_wslog->weight_diff     = $next_wslog->weight   - $prev_wslog->weight;
					$next_wslog->body_fat_diff   = $next_wslog->body_fat - $prev_wslog->body_fat;
				}else{
					$next_wslog->weight_diff   = 0; 
					$next_wslog->body_fat_diff = 0;  
				}

				$next_wslog->modified_at   = Date::forge()->get_timestamp();
				$next_wslog->save();
			}


			$wslog->user=null;
			$wslog->delete();
		}

		Response::redirect('timeline/private');
	}
}
