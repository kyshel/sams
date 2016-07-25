<?php
// use session judge is logged
function exec_login_if_not(){	
	if (!(isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] == 1)) {
		header("Location:login.php?redirect_to=" . urlencode($_SERVER['REQUEST_URI']));
		die();
	}
}

//judge last_opreate to now opreate_time distance, 
//if greater than 60 , logout
function exec_kickout_if_timeout(){	
    if(!(isset($_SESSION['timeout']))){
            //echo "setting time ~~~~~~~~~~~~~</ br>";
         $_SESSION['timeout'] = time();
    }
        //set delay time,secs
    else{
        $delay=dev_delay();
        if ($_SESSION['timeout'] + $delay < time()) {
            $_SESSION = array();
            session_destroy();
            //return a little feeedback message
            //echo "<div class='alert alert-success' role='alert'>由于长时间未操作，您已退出系统，请重新登录！</div>";

            header("Location:login.php?timeout&redirect_to=" . urlencode($_SERVER['REQUEST_URI']));
            die();
            
        } else {
            $cha=time()-$_SESSION['timeout'];
            $cha_div=$cha / 60 ;
            echo '
            <script type="text/javascript">console.log("您距离上一次操作相差'.$cha.'秒，即'.$cha_div.' 分钟，超过60分钟会强制退出！")</script>  
            ';
            $_SESSION['timeout'] = time();
            // session ok
        }
    }
}


// checking for minimum PHP version
function check_phpversion_for_hash(){
	if (version_compare(PHP_VERSION, '5.3.7', '<')) {
	    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
	} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
	    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
	    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
	    require_once("password_compatibility_library.php");
	}
}

// generate a select course list that belong to logined teacher
function teacher_get_own_course($tea_id){
	global $db;
	$course_code=NULL;
	echo '<select name="pro_id" required>';

	$sql="SELECT pro_id,stu_grade,stu_major,course_code 
	from project
	where tea_id = $tea_id";

	// ** test if_condition is work ir not
	// $sql="SELECT course_name from course where course_code in(
	// SELECT course_code from project where tea_id = 0
	// )";

	$result = $db->query($sql);
	if ($result->num_rows == 0) {
		echo "<option>Please set your course first</option>";
    } else {
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$course_code = $row['course_code'] ;

			$sql2="SELECT course_name
			from course
			where course_code = '$course_code' ";
			$result2 = $db->query($sql2);
			$row2 = $result2->fetch_array(MYSQLI_ASSOC);

			echo "<option value='".$row['pro_id']."'>" 
			. $row['stu_grade']."-"
			. $row['stu_major']."-"
			. $row2['course_name'] 
			. "</option>";
		}
	}

	echo '</select>';
}


function php_self(){
    $php_self=substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1);
    return $php_self;
}

function dynamic_css_js_lib(){
	$php_self=php_self();
	if ($php_self == 'add.php' ){
		echo '
		<link rel="stylesheet" href="css/bootstrap-datepicker3.min.css">
		<script src="js/bootstrap-datepicker.min.js"></script>
		'; 
	}
	elseif($php_self == 'add_main.php'){
		echo '
		<link href="css/bootstrap-switch.min.css" rel="stylesheet">
		<script src="js/bootstrap-switch.min.js"></script>
		'; 
	}
}



function get_major_grade($pro_id,&$stu_grade,&$stu_major){
	global $db;

	$sql="SELECT stu_grade,stu_major
	from project
	where pro_id = $pro_id";
	$result = $db->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$stu_grade = $row['stu_grade'];
	$stu_major = $row['stu_major'];
	//echo '<br>'.$stu_major.$stu_grade.'<br>';
}


function generate_stu_list($pro_id){
	global $db;
	$stu_grade=NULL;
	$stu_major=NULL;
	$i=0;

	get_major_grade($pro_id,$stu_grade,$stu_major);
	$sql="SELECT stu_id,stu_name 
	from student
	where stu_grade = '$stu_grade'
	and stu_major = '$stu_major' ";
	$result = $db->query($sql);

	if ($result->num_rows == 0) {
		echo "<h1>no students in your course, 
		check student table, name must match</h1>";
	} else {

		echo '<table class="table-bordered">';
			echo '<tr>';
				echo '<th>';
				echo '学号';
				echo '</th>';

				echo '<th>';
				echo '点名';
				echo '</th>';

				echo '<th>';
				echo '备注';
				echo '</th>';
		echo '</tr>';

		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			echo '<tr>';

				echo '<td>';
				echo $row['stu_id'];
				echo '<input type="text" name="stu_num'.$i.'" value="'.$row['stu_id'].'" readonly style="display: none;">';
				echo '</td>';

				// echo '<td>';
				// echo $row['stu_name'];
				// echo '</td>';
			
				echo '<td>';
				make_a_switch('check'.$i,$row['stu_id'],$row['stu_name'],'42px','√','×','success','danger');
				echo '</td>';

				echo '<td>';
				make_a_select_for_at_meta('at_meta'.$i);
				echo '</td>';

			echo '</tr>';
			$i++;
		}
		echo '</table>';
	}

	echo '<input name="stu_sum" value="'.$i.'" style="display:none;" >';

}

// label_widht can set 'auto'
function make_a_switch($name,$value,$label_text,$label_width,$on_text,$off_text,$on_color,$off_color){
    echo '<input type="checkbox" 
    name="'.$name.'" 
    value="'.$value.'" 

    data-label-text="'.$label_text.'" 
    data-label-width="'.$label_width.'"

    data-on-text="'.$on_text.'" 
    data-off-text="'.$off_text.'" 

    data-on-color="'.$on_color.'" 
    data-off-color="'.$off_color.'" 
    
    data-size="mini" 
    checked>';
}


function make_a_select_for_at_meta($name){
	echo '<select name="'.$name.'" required>';
		echo '<option value="无" selected>无</option>';
		echo '<option value="请假">请假</option>';
	echo '</select>';
}

function get_go_id($pro_id,$go_time){
	global $db;

	$sql="SELECT go_id
	from go
	where pro_id = '$pro_id'
	and go_time= '$go_time' ";
	$result = $db->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	return $row['go_id'];
}

function check_go_unique($pro_id,$go_time){
	global $db;

	echo "<h1>---wait to add check code---</h1>";
}



function get_course_name_with_code($course_code){
	global $db;

	$sql="SELECT course_name
	from course
	where course_code = '$course_code' ";
	$result = $db->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);

	return $row['course_name'];
}

function get_teacher_name_with_id($tea_id){
	global $db;

	$sql="SELECT tea_name
	from teacher
	where tea_id = '$tea_id' ";
	$result = $db->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);

	return $row['tea_name'];
}

function get_stu_name_with_id($stu_id){
	global $db;

	$sql="SELECT stu_name
	from student
	where stu_id = '$stu_id' ";
	$result = $db->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);

	return $row['stu_name'];
}

function get_pro_detail_with_id($pro_id,&$stu_grade,&$stu_major,&$course_name){
	global $db;

	$sql="SELECT stu_grade,stu_major,course_code
	from project
	where pro_id = '$pro_id'";
	$result = $db->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);

	$stu_grade=$row['stu_grade'];
	$stu_major=$row['stu_major'];
	$course_name=get_course_name_with_code($row['course_code']);
}

function showMenuAccordUserRole(){
	$user_role=$_SESSION['user_role'];

	if ($user_role == 'admin') {
		showAdminMenu();
	}elseif($user_role == 'teacher'){
		showTeacherMenu();
	}

	
}

function showAdminMenu(){
	echo '
	<a href="index.php">index</a>  
	<a href="manage_stu.php">manage_stu</a> 
	<a href="manage_tea.php">manage_teacher</a> 
	<a href="manage_course.php">manage_course</a> 

	<a href="dev.php">dev</a> 
	';

	
}

function showTeacherMenu(){
	echo '
	<a href="index.php">index</a>  
	<a href="add.php">add</a> 
	<a href="set_course.php">set_course</a> 

	<a href="dev.php">dev</a> 
	';
	
}


function showGrid($table_name,$sql,$primary_key){
global $db;

$i = 0;
$php_self=php_self();

runOperateWithGet($table_name,$sql,$primary_key);


echo "<table class='table-bordered'>";
$result = $db->query($sql);
if ($result->num_rows == 0) {
	echo "<h1>No Result</h1>";
} else {		
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		if($i == 0){
			echo "<tr>";
			foreach($row as $x=>$x_value) {
				echo "<th>";
				echo $x;
				echo "</th>";
			}
				echo "<th>";
				echo "op";
				echo "</th>";
			echo "</tr>";			
		}
		echo "<tr>";
		foreach($row as $x=>$x_value) {
			echo "<td>" .$x_value."</td>" ;
		}
			echo "<td>"; 
			echo '<a href="'.$php_self.'?op=edit&'.$primary_key.'='.$row[$primary_key].'">edit</a>';
			echo '&nbsp;';
			echo '<a href="'.$php_self.'?op=del&'.$primary_key.'='.$row[$primary_key].'">del</a>';
			echo "</td>" ;
		echo "</tr>";
		$i=1;
	}
}

echo '</table>';

	
}



function runOperateWithGet($table_name,$sql,$primary_key){
	
	if(isset($_GET['op'])){
		
		if ($_GET['op'] == 'del') {
			if(isset($_GET[$primary_key])){
				$primary_key_value=$_GET[$primary_key];
				execDel($table_name,$primary_key,$primary_key_value);
				if($table_name == 'go'){
					del_at_with_go_id($primary_key_value);
				}
			}

		}elseif ($_GET['op'] == 'edit') {
			echo "this is edit";
			if($table_name == 'project'){
				editProjectEntry($table_name,$primary_key,$_GET[$primary_key]);
			}
		}elseif ($_GET['op'] == 'add') {
			addNewEntry($table_name);
		}elseif ($_GET['op'] == 'save') {
			saveNewEntry($table_name);
		}

	}
}



function execDel($table_name,$primary_key,$primary_key_value){
	global $db;

	$sql="DELETE FROM $table_name
	WHERE $primary_key = '$primary_key_value' ";
	$result = $db->query($sql) or die($db->error);

	echo "<h1>del $table_name with $primary_key = $primary_key_value  success!</h1>";
}

//			<th> </th>
function echoTableHead($table_name,$sql){
	global $db;

	$i = 0;
	$result = $db->query($sql) ;
	if ($result->num_rows == 0) {
		echo "<h1>No Result</h1>";
	} else {		
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			if($i == 0){
				echo "<tr>";
				foreach($row as $x=>$x_value) {
					echo "<th>";
					echo $x;
					echo "</th>";
				}
				echo "<th>";
				echo "op";
				echo "</th>";
				echo "</tr>";			
			}

			$i=1;
		}
	}

}

function editProjectEntry($table_name,$primary_key,$primary_key_value){
	global $db;
	$php_self=php_self();

	echo "<form>";
	echo "<table class='table-bordered'>";
	echoTableHead($table_name,"SELECT * from $table_name");

	$sql="SELECT * from $table_name where $primary_key = '$primary_key_value' ";
	$result = $db->query($sql) or die($db->error);
	if ($result->num_rows == 0) {
		echo "<h1>No Result</h1>";
	} else {		
		while($row = $result->fetch_array(MYSQLI_ASSOC)){

			echo "<tr>";

				echo "<td>"; 
				echo $row[$primary_key];
				echo "</td>" ;

				echo "<td>"; 
				echoSelectList('stu_grade','student');
				echo "</td>" ;

				echo "<td>"; 
				echoSelectList('stu_major','student');
				echo "</td>" ;

				echo "<td>"; 
				echoSelectList('course_code','course');
				echo "</td>" ;

				echo "<td>"; 
				echoSelectList('tea_id','teacher');
				echo "</td>" ;

				echo "<td>"; 
				
				echo "</td>" ;



			echo "</tr>";

		}
	}

	echo '</table>';
	
}

function echoSelectList($column,$table_name){
	global $db;
	$course_name=NULL;

	$sql = "SELECT DISTINCT $column FROM $table_name";
	echo '<select>';

	$result = $db->query($sql) or die($db->error);
	if ($result->num_rows == 0) {
		echo "<option>No Result</option>";
	} else {		
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			if ($table_name == 'student') {
				echo '<option value="'.$row[$column].'" selected>'.$row[$column].'</option>';
			}
			elseif($table_name == 'course'){
				$course_name=get_course_name_with_code($row[$column]);
				echo '<option value="'.$row[$column].'" selected>'.$row[$column].'-'.$course_name.'</option>';
			}elseif($table_name == 'teacher'){
				$tea_name=get_teacher_name_with_id($row[$column]);
				echo '<option value="'.$row[$column].'" selected>'.$row[$column].'-'.$tea_name.'</option>';
			}
			
		}
	}

	echo '</select>';
}


function addNewEntry($table_name){
	global $db;
	$sql="SELECT * from $table_name";
	$php_self=php_self();

	echo '<form method="post" action="'.$php_self.'?op=save">';
	echo "<table class='table-bordered'>";
	echoTableHead($table_name,$sql);

	$result = $db->query($sql);
	if ($result->num_rows == 0) {
		echo "<h1>No Result</h1>";
	} else {		
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			
			echo "<tr>";
			foreach($row as $x=>$x_value) {
				echo "<td>";
				makeAnInput($x);
				echo "</td>" ;
			}
				echo "<td>"; 
				echo '<input type="submit" value="save">';
				echo '&nbsp;';
				echo '<a href="'.$php_self.'">cancel</a>';
				echo "</td>" ;
			echo "</tr>";

			// just one line !
			break;
		}
	}

	echo "</table></form>";
}

function makeAnInput($name){
	echo '<input type="text" name="'.$name.'" require>';
}

//must have POST , all the column must char
function saveNewEntry($table_name){
	global $db;
	$sql="INSERT INTO()VALUES
	()";
	$col_name_str='';
	$val_name_str='';

	dev_var_dump('post');
	$post_array = $_POST;

	$i=0;
	foreach($post_array as $x=>$x_value) {

		echo "$x";
		echo ".";
		echo "$x_value";
		echo "<br>";


		if($i==0){
			$col_name_str = $col_name_str.$x;
			$val_name_str = $val_name_str."'".$x_value."'";
		}else{
			$col_name_str = $col_name_str.",".$x;
			$val_name_str = $val_name_str.",'".$x_value."'";
		}
		$i++;
	}

	noise($col_name_str);
	noise($val_name_str);


}









function del_at_with_go_id($go_id){
	global $db;

	$sql="DELETE FROM attend
	WHERE go_id = '$go_id' ";
	$db->query($sql) or die($db->error);

	echo "<h1>del at with go_id = $go_id success!</h1>";
}


//****************** below are dev func *********************************

function del_go($go_id){
	global $db;

	$sql="DELETE FROM go
	WHERE go_id = '$go_id' ";
	$db->query($sql) or die($db->error);

	echo "<h1>del go with go_id success!</h1>";
}








//---------------  global dev code ,reserve in production environment-------------

function dev_var_dump($type){
	if(DEV_MODE == 0){
		return ;
	}
	echo '<p> >>>>>>>>> WARNING! dev_mode is open!<br>';

	if($type == 'get'){
		echo 'var_dump_get:';
		var_dump($_GET);		
	}
	else if($type == 'post'){
		echo 'var_dump_get:';
		var_dump($_POST);
	}


	echo '<br> <<<<<<<<<< WARNING! dev_mode is open!</p>';


}


function noise($var){
	if(DEV_MODE == 0){
		return ;
	}
	echo '<p> >>>>>>>>> WARNING! dev_mode is open!<<<<<<<<<<<<<<<<<<p>';

	echo "<h1> see here -> $var <-   </h1>";

	echo '<p> >>>>>>>>> WARNING! dev_mode is open!<<<<<<<<<<<<<<<<<<p>';
}


function dev_delay(){
	if(DEV_MODE == 0){
		//1 hour
		return 3600;
	}

	//10 days
	return 3600*24*10;

	// 3 seconds
	//return 3;

}



	





