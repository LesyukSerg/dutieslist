<?
	function randomtext($i){
		$x = rand(33, 126);
		if(in_array($x, array(34, 39, 60, 92)))
			$x++;
		
		//echo $x." = ".chr($x)."<br />";
		
		if($i > 0)
			return chr($x).randomtext($i-1);
		else 
			return "";
	}
	
	function authorization($p){
		if(!isset($p['remember'])) $p['remember'] = null;
		
		$login = mysql_real_escape_string($p['login']);
		$passmd5 = md5($p['password']);
		
		if($login && $p['password']){
			$q = mysql_query('SELECT id, login FROM users WHERE login="'.$login.'" AND password="'.$passmd5.'"');
			if($user = mysql_fetch_assoc($q)){
				if($p['remember']){
					
					$rand = mysql_real_escape_string(randomtext(20));
					setcookie("login", $user['login'], time()+(60*60*24*7));
					setcookie("uid", $user['id'], time()+(60*60*24*7));
					setcookie("check", $rand, time()+(60*60*24*7));
					$q = 'INSERT INTO accept_cookies (login, ipaddress, randomtext) VALUES("'.$user['login'].'", "'.$_SERVER['REMOTE_ADDR'].'", "'.$rand.'") ON DUPLICATE KEY UPDATE ipaddress="'.$_SERVER['REMOTE_ADDR'].'", randomtext="'.$rand.'"';
					mysql_query($q) or die(mysql_error());
				}else{
					$_SESSION['login'] = $user['login'];
					$_SESSION['uid'] = $user['id'];
				}
				
				header("Location: index.php");
			}else{
				header('Location: login.php?err='.'Не правильно введений логін або пароль');
			}
		}else{
			header('Location: login.php?err='.'Поля не заповнені');
		}
	}
	
	function user_exist($user){
		$user = mysql_real_escape_string($user);
		$q = mysql_query('SELECT id FROM users WHERE login="'.$user.'" ') or die(mysql_error());
		if($user = mysql_fetch_assoc($q)){
			return true;
		}else{
			return false;
		}
	}
	
	function registration($p){
		$login = mysql_real_escape_string($p['login']);
		$email = mysql_real_escape_string($p['email']);
		$passmd5 = md5($p['password']);
		$pass_confirm = md5($p['pass_confirm']);
		if($login && $p['password'] && $p['pass_confirm']){
			if(!user_exist($login)){
				if($passmd5 == $pass_confirm){
					$params = ' (id, login, email, password) ';
					$values = ' VALUES (NULL, "'.$login.'","'.$email.'","'.$passmd5.'") ';
				
					//mysql_real_escape_string();
					$i = 'INSERT INTO users '.$params.$values;
					
					mysql_query($i) or die(mysql_error());
					$p['remember'] = 1;
					authorization($p);
				}else{
					header('Location: login.php?err='.'Неправильно введено підтверждення пароля');
				}
			}else{
				header('Location: login.php?err='.'Користувач з таким логіном вже зареєстрований');
			}
		}else{
			header('Location: login.php?err='.'Поля не заповнені');
		}
	}
	
	function get_list_duties($user, $id){
		if(user_exist($user)){
			$q = 'SELECT id, content, time_to_done, status FROM duties WHERE user_id="'.$id.'" ORDER BY time_to_done ASC, id ASC';
			$qq = mysql_query($q) or die(mysql_error());
			$duties = array();
			while($duty = mysql_fetch_assoc($qq)){
				$duty['content'] = stripcslashes($duty['content']);
				$duties[] = $duty;
			}
			return $duties;
		}else{
			header('Location: login.php?err='.'Користувач не знайдений');
		}
	}
	
	function add_new_duty($user, $p){
		$p['status'] = 'NEW';
		$p['user_id'] = $_SESSION['uid'];
		if($p['content'] && $p['time_to_done'] && $p['user_id']){
			if(user_exist($user)){
				$fields = array('user_id', 'content', 'time_to_done','status');
				
				$params = array();
				$values = array();
				foreach($fields as $field){
					$params[] = $field;
					$values[] = mysql_real_escape_string($p[$field]);
				}
				$params = ' ('.implode(', ',$params).') ';
				$values = ' VALUES ("'.implode('", "',$values).'") ';
					
				$ins = 'INSERT INTO duties '.$params.$values;
				mysql_query($ins) or die(mysql_error());
				header('Location: index.php');
			}else{
				header('Location: index.php?err='.'Користувач не знайдений');
			}
		}else{
			header('Location: index.php?err='.'Поля не заповнені');
		}
	}	
	
	function update_duty_status(){
		$date = date("Y-m-d");
		mysql_query('UPDATE duties SET status = "OLD" WHERE time_to_done < "'.$date.'" and status !="DONE"') or die(mysql_error());
	}
		
	function check_done_duty($user, $uid, $p){
		if(in_array($p['new_status'], array('NEW','DONE'))){
			$status = $p['new_status'];
		}
		$d_id = mysql_real_escape_string($p['id_duty']);
		if(user_exist($user)){
			$u = 'UPDATE duties SET status = "'.$status.'" WHERE id="'.$d_id.'" AND user_id="'.$uid.'"';
			mysql_query($u) or die(mysql_error());
			die("OK");
		}
	}
	
	function delete_duty($user, $id, $duty_id){
		if(user_exist($user)){
			$d = 'DELETE FROM duties WHERE id = "'.$duty_id.'" AND user_id ="'.$id.'"';
			mysql_query($d) or die(mysql_error());
			die("DELETED");
		}
	}
	
	function logout(){
		if(!isset($_COOKIE['login'])) $_COOKIE['login'] = null;
		if(!isset($_SESSION['login'])) $_SESSION['login'] = null;
		if($_COOKIE['login']){
			mysql_query('DELETE FROM accept_cookies WHERE login = "'.$_COOKIE['login'].'" ') or die(mysql_error());
			setcookie("login", '', time()-1);
			setcookie("uid", '', time()-1);
			setcookie("check", '', time()-1);
			session_destroy();
			header("Location: index.php");
		}elseif($_SESSION['login']){
			session_destroy();
		}else{
			return false;
		}
	}
	
	function check_session(){
		if(!isset($_COOKIE['login'])) $_COOKIE['login'] = null;
		if(!isset($_SESSION['login'])) $_SESSION['login'] = null;
		
		if($_COOKIE['login']){
			$_COOKIE['check'] = mysql_real_escape_string($_COOKIE['check']);
			
			$q = 'SELECT ipaddress, randomtext FROM accept_cookies WHERE ipaddress="'.$_SERVER['REMOTE_ADDR'].'" AND randomtext="'.$_COOKIE['check'].'" AND login="'.$_COOKIE['login'].'"';
			$qq = mysql_query($q) or die(mysql_error());
			if($user = mysql_fetch_assoc($qq)){
				
				$q = mysql_query('SELECT id, login FROM users WHERE login="'.$_COOKIE['login'].'"');
				if($user = mysql_fetch_assoc($q)){
					$_SESSION['login'] = $user['login'];
					$_SESSION['uid'] = $user['id'];
				}
				return true;
			}else{
				return false;
			}
		}
		elseif($_SESSION['login']){
			return true;
		}else{
			return false;
		}
	}
?>