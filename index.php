<?php
mysql_connect("localhost","root", "");
mysql_select_db("hsr");

$login_value='test';
$password_value=123;

$login = ''; 
$password = '';
$auth=0;

if(!isset($_COOKIE['login'])&&(!isset($_COOKIE['password'])))
{
	if(isset($_POST['log_in']))
	{
		$login = $_POST['login']; 
		$password = $_POST['password'];
		if(($login==$login_value)&&($password==$password_value))
		{
			setcookie ("login", $login, time() + 50000); 						
			setcookie ("password", $password, time() + 50000); 	
			$auth=1;			
		}
	}
}
else
{
	if(($_COOKIE['login']==$login_value)&&($_COOKIE['password']==$password_value))
	{
		$auth=1;
	}
}

if(!$auth)
{
	?>
	<form action="" method="post">
	Логин: <input type="text" name="login" />

	Пароль: <input type="password" name="password" />
	<input type="submit" value="Войти" name="log_in" />

	</form>
	<?php
}
else
{
	$route = $_GET['route'];
	$somevar = explode('/', $route);
	$result = '';

	switch ($somevar[0]) {
		
		case 'currencies':
			
			$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;

			$on_page = 10;

			$shift = ($page - 1) * $on_page;
			
			$result=mysql_query("SELECT name, rate FROM currency ORDER BY id ASC LIMIT ".$shift.", ".$on_page."");
			$string='';
			
			while ($row=mysql_fetch_array($result))
			{ 
				$string.= ''.$row['name'].' '.$row['rate'].'<br>';	
			}

			$res = mysql_query("SELECT COUNT(*) FROM currency");
			$row = mysql_fetch_row($res);
			$count = $row[0]; 
			
			$pages = ceil($count / $on_page);

			for ($i = 1; $i <= $pages; $i++) {
				if($i == $page){
					echo " [$i] ";
				} else {
					echo "<a href='index.php?route=currencies&page=$i'>$i</a> ";
				}
			}
			echo '<br><br>';
			
			echo json_encode(['response' => $string]);
		break;
		
		case 'currency':
			$id=$_GET['id'];
			
			$result=mysql_query("SELECT name, rate FROM currency WHERE id=".$id."");
			$string='';
			while ($row=mysql_fetch_array($result))
			{ 
				$string.= ''.$row['name'].' '.$row['rate'].'<br>';
			}
			echo json_encode(['response' => $string]);
		break;
	}

}



mysql_close();
?>
