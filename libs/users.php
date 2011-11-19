<?php
class User {
	
	private $facebook;
	public $uid, $display_name, $created, $updated, $deleted;
	
	public function __construct($facebook) {
		$this->facebook = $facebook;
		$this->uid = $facebook->getUser();
		
		if ($this->uid){
			$this->populate_user();
			if ((time() - $this->updated) > 1000){
				$this->update_user($this);
			}
		}else{
			//nope no user
			$this->set_anon();
		}
		
	}
	
	private function parse_session(){
		if (isset($_SESSION) ){
			$this->uid = '0';
		}
	}
	
	
	private function set_anon(){
		$this->display_name = 'Anon';
	}
	
	private function populate_user(){
		global $db;
		$db->query('SELECT * FROM users WHERE uid = ' . $this->uid);
		$record = $db->fetch_all();
		if (!empty($record)){
			$this->display_name = $record[0]['display_name'];
			$this->created = $record[0]['created'];
			$this->deleted = $record[0]['deleted'];
			$this->updated = $record[0]['updated'];
		}
		
		
	}
	
	function update_user(){
	
		$fb_response = $this->facebook->api('/me');
		$this->display_name = $fb_response['name'];
		
		global $db;
		if (user_exists($this)){
			$this->updated = time();
			$db->query('UPDATE users SET display_name = "'.$this->display_name.'", updated = '.$this->updated.' WHERE uid = ' . $this->uid);	
		}else{
			create_user($this);
		}	
		
	}
	
	public function login(){
		$url = $this->facebook->getLoginUrl();
		redirect($url);
	}
	
	public function logout(){
		$url = $this->facebook->getLogoutUrl();
		unset($_SESSION);
		redirect($url);
	}
	
}




function create_user(&$user){
	global $db;
	$user->created = time();
	$user->updated = time();
	$user->deleted = 0;
	
	$db->query('INSERT INTO users VALUES ('.$user->uid.', "'.$user->display_name.'", '.$user->created.','.$user->deleted.','.$user->updated.')');
}

function user_exists($uid){
	global $db;
	if (is_object($uid)){
		$uid = $uid->uid;
	}
	
	$db->query('SELECT uid FROM users WHERE uid = ' . $uid);
	$result = $db->fetch_all();
	return !empty($result);
	
}

?>