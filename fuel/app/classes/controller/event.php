<?php
class Controller_Event extends Controller{
	private $_config = null;

	public function action_addapp(){
		$greepf = new Greepf();
		if($greepf->validate_request()){
			$id = $greepf->get_request_param('id');
			$event_log_msg = '';
			try{
	    		if(Auth::forge('quickauth')->create_user($id)){
					$event_log_msg = "Created user [$id]";
				}else{
					$event_log_msg = "Failed create user: Could not insert to the DB";
				}
			} catch(Exception $e){
				$event_log_msg = 'Failed create user: '.$e->getMessage();
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
		$greepf = new Greepf();
		if($greepf->validate_request()){
			$id = $greepf->get_request_param('id');
			$event_log_msg = '';
			try{
	    		if(Auth::forge('quickauth')->delete_user($id)){
					$event_log_msg = "Deleted user [$id]";
				}else{
					$event_log_msg = "Failed delete user: Could not delete from the DB";
				}
			} catch(Exception $e){
				$event_log_msg = 'Failed delete user: '.$e->getMessage();
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

}
