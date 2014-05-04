<?
	session_start();
	date_default_timezone_set("Europe/Kiev");
	
	if(!isset($_COOKIE['login'])) $_COOKIE['login'] = NULL;
	if(!isset($_COOKIE['uid'])) $_COOKIE['uid'] = null;
	if(!isset($_COOKIE['check'])) $_COOKIE['check'] = null;
	
	setcookie("login", $_COOKIE['login'], time()+(60*60*24*7));
	setcookie("uid", $_COOKIE['uid'], time()+(60*60*24*7));
	setcookie("check", $_COOKIE['check'], time()+(60*60*24*7));
	
	define('ROOTDIR', getcwd());
	require(ROOTDIR."/conf/bdconnect.php");
	require(ROOTDIR."/functions.php");
	
	if(!check_session()){
		header("Location: login.php");
	}
	
	if(!isset($_GET['err'])) $_GET['err'] = NULL;
	$mess = $_GET['err'];

	update_duty_status();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="Author" CONTENT="Lesyuk Sergiy">
		<link type="text/css" href="stylesheet.css" rel="stylesheet">
		<link type="text/css" href="calendar/tcal.css" rel="stylesheet" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
		<script type="text/javascript" src="calendar/tcal.js"></script> 
		<script type="text/javascript" src="js/forindex.js"></script> 
		<title>Dutylist</title>
	</head>
	<body>
		<div class="user_menu right">
			<div class="user_name left"><?=$_SESSION['login']?></div>
			<form method="post" action="call_method.php" class="right">
				<input id="exit_button" class="button" name="logout" type="submit" value="Вийти" />
			</form>
			<div class="clear"></div>
		</div>
		
		<div class="wrapper">
			<div class="main_content">
				<input id="add_duty" class="button" name="add_duty" type="button" value="Додати справу" />
				<div class="add_duty_window window_padding">
					<h3 class="window_title">Форма додавання справи</h3>
					<div class="message2"><?=$mess?></div>
					<form class="add_duty_form" action="call_method.php" method="POST" >
						<div class="form_row">
							<label for="duty_content">Справа:</label>
							<textarea id="duty_content" class="duty_content" name="content"></textarea>
						</div>					
						<div class="form_row right">
							<label for="time_to_done">Зробити до:</label>
							<input id="time_to_done" class="time_to_done tcal" name="time_to_done" type="text" value="" />
						</div>
						<div class="form_row buttons">
							<input id="cancel" class="button left" type="button" value="Скасувати" />
							<input class="adding_duty button right" name="adding_duty" type="submit" value="Додати" />
						</div>
						<div class="clear"></div>
					</form>
				</div>
				
				
				
				<div class="duty_list">
<?
					foreach(get_list_duties($_SESSION['login'], $_SESSION['uid']) as $duty){
						if($duty['status'] == 'NEW') $st = "не виконано";
						elseif($duty['status'] == 'OLD') $st = "прострочено";
						elseif($duty['status'] == 'DONE') $st = "виконано";
						else $st = "undefined";
						
?>
						<div class="duty_item <?=$duty['status']?>">
							<div class="status">
								<span>статус:</span> <?=$st?>
							</div>
							<div class="must_be_done">
								<span>зробити до:</span> <?=$duty['time_to_done']?>
							</div>
							<div class="left">
								<input id="check_duty_<?=$duty['id']?>" class="done_or_not left" name="done_or_not" type="checkbox" value="1" title="Справа зроблена" <?=($duty['status'] == 'DONE')?'checked':''?> />
								<label for="check_duty_<?=$duty['id']?>" class="duty_text">
									<?=$duty['content']?>
								</label>
							</div>
							<a class="dell_duty right" href="index.php?dell=<?=$duty['id']?>" title="Видалити справу">x</a>
							<input class="duty_id" name="id" type="hidden" value="<?=$duty['id']?>" />
							<div class="clear"></div>
						</div>
<?
					}
?>
				</div>
			</div>
		</div>
	</body>
</html>