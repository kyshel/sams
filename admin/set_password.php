<?php
require_once("header.php");

$tea_active=9;// means not 0
if ((isset($_SESSION['tea_active']) AND $_SESSION['tea_active'] == 0)) {
	$tea_active=0;
}



if (isset($_POST['set_confirm'])) {
	if (!empty($_POST['old_password']) && !empty($_POST['new_password1']) && !empty($_POST['new_password2'])){

		$tea_id = $_SESSION['tea_id'];
		$tea_name = $_SESSION['user_name'];
		$sql1 = "SELECT tea_password
		FROM teacher
		WHERE tea_id = '" . $tea_id . "';";
		$result1=getOneResultByOneQuery($sql1);

		if ( $_POST['old_password'] == $result1 ){
			if ($_POST['new_password1'] != $result1) {			
				if ($_POST['new_password1'] == $_POST['new_password2']) {
					updateOne("UPDATE teacher SET tea_password = '".$_POST['new_password1']."' where tea_id = '".$tea_id."'",1);
					echoGreen('密码修改成功！请牢记您的新密码！&nbsp; <a href="index.php">完成</a>',1);
					if ($tea_active===0) {
						$_SESSION['user_role'] = 'teacher';
						$_SESSION['tea_active'] = 1;
						updateOne("UPDATE teacher SET tea_active = '1' where tea_id='".$tea_id."'",1);
					}
					die();
				}else{
					echo '<div class="alert alert-danger">两次输入的新密码不匹配！请重新输入！</div>';
				}
			}else{
				echo '<div class="alert alert-danger">新密码与旧密码相同！请重新输入！</div>';
			}
		}else{
			echo '<div class="alert alert-danger">旧密码输入错误！</div>';
		}

	}else{
		echo '<div class="alert alert-danger">请三项都输入后再提交</div>';
	}
}

if ($tea_active===0) {
	echo '<div class="alert alert-danger">';
	echo '您是第一次登录系统，为确保安全，请先修改密码！';
	echo '</div>';
}


?>

<form class="form" method="post" action="set_password.php">

	<label>旧密码</label>
	<input type="password" name="old_password" id="inputEmail" class="form-control" placeholder="旧密码" required="" autofocus="">

	<label>新密码(字母与数字组合，至少8个字符)</label>
	<input type="password" name="new_password1" id="inputPassword" class="form-control" placeholder="新密码" required="" minlength="8">
	<label>再输入一次新密码</label>
	<input type="password" name="new_password2" id="inputPassword" class="form-control" placeholder="再输入一次新密码" required="" minlength="8">

	<br>
	<button class="btn btn-lg btn-primary btn-block" name="set_confirm" type="submit">提交</button>

</form>

