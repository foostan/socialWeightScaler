<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    
 * @version    
 * @author     foostan 
 * @license    MIT License
 * @copyright  2013 foostan 
 * @link       https://github.com/foostan
 */

namespace Auth;

class QuickUserUpdateException extends \FuelException {}

/**
 * QuickAuth basic login driver
 */
class Auth_Login_QuickAuth extends \Auth_Login_Driver
{

	public static function _init()
	{
		\Config::load('quickauth', true, true, true);
	}

	/**
	 * @var  Database_Result  when login succeeded
	 */
	protected $user = null;

	/**
	 * Check the user exists before logging in
	 *
	 * @return  bool
	 */
	public function validate_user($username = '')
	{
		if(! is_null($username)){
			$this->user = \DB::select_array(\Config::get('quickauth.table_columns', array('*')))
				->where('username', '=', $username)
				->from(\Config::get('quickauth.table_name'))
				->execute(\Config::get('quickauth.db_connection'))->current();
			return $this->user ?: false;
		}else{
			return false;	
		}
	}

	/**
	 * Login user
	 *
	 * @param   string
	 * @return  bool
	 */
	public function login($username = '')
	{
		if ( ! ($this->user = $this->validate_user($username)))
		{
			\Session::delete('username');
			\Session::delete('login_hash');
			return false;
		}

		\Session::set('username', $this->user['username']);
		\Session::set('login_hash', $this->create_login_hash());
		\Session::instance()->rotate();

		return true;
	}

	/**
	 * Login user quickly
	 * return redirect url
	 *
	 * @param   string
	 * @return  string 
	 */
	public function quick_login($username = '')
	{
		return $this->login($username)
			? \Config::get('quickauth.redirect_url_after_login')
			: \Config::get('quickauth.redirect_url_after_failed_login');
	}

	/**
	 * Logout user
	 *
	 * @return  bool
	 */
	public function logout()
	{
		\Session::delete('username');
		\Session::delete('login_hash');
		return true;
	}

	/**
	 * Logout user quickly
	 * return redirect url
	 *
	 * @return string 
	 */
	public function quick_logout()
	{
		$this->logout();
		return \Config::get('quickauth.redirect_url_after_logout');
	}


	/**
	 * Create new user
	 *
	 * @param   string
	 * @return  bool
	 */
	public function create_user($username)
	{

		if (empty($username))
		{
			throw new \QuickUserUpdateException('Username is not given', 1);
		}

		$same_users = \DB::select_array(\Config::get('quickauth.table_columns', array('*')))
			->where('username', '=', $username)
			->from(\Config::get('quickauth.table_name'))
			->execute(\Config::get('quickauth.db_connection'));

		if ($same_users->count() > 0)
		{
			throw new \QuickUserUpdateException('This user already exists', 2);
		}

		$user = array(
			'username'        => (string) $username,
			'last_login'      => 0,
			'login_hash'      => '',
			'created_at'      => \Date::forge()->get_timestamp()
		);
		$result = \DB::insert(\Config::get('quickauth.table_name'))
			->set($user)
			->execute(\Config::get('quickauth.db_connection'));

		return ($result[1] > 0) ? $result[0] : false;
	}

	/**
	 * Deletes a given user
	 *
	 * @param   string
	 * @return  bool
	 */
	public function delete_user($username)
	{
		if (empty($username))
		{
			throw new \QuickUserUpdateException('Cannot delete user with empty username', 9);
		}

		$same_users = \DB::select_array(\Config::get('quickauth.table_columns', array('*')))
			->where('username', '=', $username)
			->from(\Config::get('quickauth.table_name'))
			->execute(\Config::get('quickauth.db_connection'));

		if ($same_users->count() === 0)
		{
			throw new \QuickUserUpdateException("$username already not exists", 2);
		}

		$affected_rows = \DB::delete(\Config::get('quickauth.table_name'))
			->where('username', '=', $username)
			->execute(\Config::get('quickauth.db_connection'));

		return $affected_rows > 0;
	}

	/**
	 * Creates a temporary hash that will validate the current login
	 *
	 * @return  string
	 */
	public function create_login_hash()
	{
		if (empty($this->user))
		{
			throw new \QuickUserUpdateException('User not logged in, can\'t create login hash.', 10);
		}

		$last_login = \Date::forge()->get_timestamp();
		$login_hash = sha1(\Config::get('quickauth.login_hash_salt').$this->user['username'].$last_login);

		\DB::update(\Config::get('quickauth.table_name'))
			->set(array('last_login' => $last_login, 'login_hash' => $login_hash))
			->where('username', '=', $this->user['username'])
			->execute(\Config::get('quickauth.db_connection'));

		$this->user['login_hash'] = $login_hash;

		return $login_hash;
	}

	/**
	 * Get the user's ID
	 *
	 * @return  Array  containing this driver's ID & the user's ID
	 */
	public function get_user_id()
	{
		if (empty($this->user))
		{
			return false;
		}

		return array($this->id, (int) $this->user['id']);
	}

	/**
	 * Disabled methods 
	 */
	public function get_groups(){ return array(); }
	protected function perform_check(){ return false; }
	public function get_email(){ return ''; }
	public function get_screen_name(){ return ''; }
}

// end of file quickauth.php
