<?php
class Controller_Event extends Controller{
	private $_config = null;

	public function before(){

	}

	public function action_addapp(){
		$user_request = Greepf::validate_api_response();
		if($id = $user_request->get_parameter('id')){
			$event_log_msg = "Appadd [$id]";

			try{

				$new_user = new Model_User(array(
					'id'=>$id,
					'created_at' => \Date::forge()->get_timestamp(),
					'modified_at' => \Date::forge()->get_timestamp(),
				));
				$new_user->save();
			}catch(Exception $e){
				$event_log_msg = $e->getMessage();
			}

			// logging to the DB
			DB::insert('event_logs')
			->set(array(
				'event_name' => 'addapp',
				'message' => $event_log_msg,
				'created_at' => \Date::forge()->get_timestamp(),
				))
			->execute();
		}

		return Response::forge('success');
	}

	public function action_suspendapp(){
	}

	public function action_resumeapp(){
	}

	public function action_removeapp(){
		$user_request = Greepf::validate_api_response();
		if($id = $user_request->get_parameter('id')){
			$event_log_msg = "Removeapp [$id]";

			try{
				Model_User::find($id)->delete();
			}catch(Exception $e){
				$event_log_msg = $e->getMessage();
			}

			// logging to the DB
			DB::insert('event_logs')
			->set(array(
				'event_name' => 'removeapp',
				'message' => $event_log_msg,
				'created_at' => \Date::forge()->get_timestamp(),
				))
			->execute();
		}

		return Response::forge('success');

	}

	public function action_test(){
		//Greepf::test();
	}
}
