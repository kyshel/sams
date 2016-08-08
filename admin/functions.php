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
	$course_id=NULL;
	echo '<select name="pro_id" required onchange="showAddedDate(this.value)" id="course_list">';

	$sql="SELECT pro_id,stu_grade,stu_major,course_id 
	from project
	where tea_id = $tea_id";

	// ** test if_condition is work ir not
	// $sql="SELECT course_name from course where course_id in(
	// SELECT course_id from project where tea_id = 0
	// )";

	$result = $db->query($sql);
	if ($result->num_rows == 0) {
		echo "<option>Please set your course first</option>";
    } else {
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$course_id = $row['course_id'] ;

			$sql2="SELECT course_name
			from course
			where course_id = '$course_id' ";
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

// generate a select course list that belong to logined teacher
function echoMyCourseSelect($tea_id){
	global $db;
	$course_id=NULL;
	echo '<select name="pro_id" required  id="course_list">';

	$sql="SELECT pro_id,course_id,year,term
	from project
	where tea_id = $tea_id";

	$result = $db->query($sql);
	if ($result->num_rows == 0) {
		echo "<option>Please add your course first</option>";
    } else {
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$course_id = $row['course_id'] ;
			$course_name = getCourseName($course_id);
			
			echo "<option value='".$row['pro_id']."'>" 
			. $course_name."，"
			. $row['year']."学年，第"
			. $row['term']."学期"
			. "</option>";
		}
	}

	echo '</select>';
}



// generate a select course list that belong to logined teacher
function makeSelectForPro(){
	global $db;

	echo '<select name="pro_id" required>';

	$sql="SELECT pro_id,stu_grade,stu_major,course_id,tea_id
	from project
	order by pro_id";

	// ** test if_condition is work ir not
	// $sql="SELECT course_name from course where course_id in(
	// SELECT course_id from project where tea_id = 0
	// )";

	$result = $db->query($sql);
	if ($result->num_rows == 0) {
		echo "<option>Please add pro first</option>";
    } else {
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$course_name=getCourseName($row['course_id']);
			$tea_name=getTeacherName($row['tea_id']);

			echo "<option value='".$row['pro_id']."'>" 
			. $row['stu_grade']."-"
			. $row['stu_major']."-"
			. $course_name ."-"
			. $tea_name
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
	if ($php_self == 'add.php' || $php_self == 'show_at.php' ){
		echo '
		<link rel="stylesheet" href="css/bootstrap-datepicker3.min.css">
		<script src="js/bootstrap-datepicker.min.js"></script>
		'; 
	}
	elseif($php_self == 'add_main.php' || $php_self == 'manage_go.php'){
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
				make_a_switch('check'.$i,$row['stu_id'],$row['stu_name'],'42px','✔','✘','success','danger');
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
function make_a_switch($name,$value,$label_text,$label_width,$on_text,$off_text,$on_color,$off_color,$switch_checked = NULL){
    echo '<input type="checkbox" 
    name="'.$name.'" 
    value="'.$value.'" 

    data-label-text="'.$label_text.'" 
    data-label-width="'.$label_width.'"

    data-on-text="'.$on_text.'" 
    data-off-text="'.$off_text.'" 

    data-on-color="'.$on_color.'" 
    data-off-color="'.$off_color.'" 
    
    data-size="mini" ';
   
    if($switch_checked == '1'){
    	echo 'checked ';
    }elseif ($switch_checked == '0') {
    	echo '';
    }else{
    	echo 'checked ';
    }

    echo '>';

}


function make_a_select_for_at_meta($name,$selected_value = NULL){
	echo '<select name="'.$name.'" required>';

	$array=getArrayFromJsonFile('data.json');
	$array_at_meta=$array['at_meta'];
	foreach ($array_at_meta as $key => $value) {
		//noise($key.$value);
		makeOption($value,$value,$selected_value);
	}

	echo '</select>';
}

function makeOption($value,$text,$selected_value = NULL){

		echo '<option value="'.$value.'"';
		if ($selected_value == $value) {
				echo 'selected ';
		}
		echo '>'.$text.'</option>';

}


// !!!!!!!!!!!this func has bug, !!!!!!!!!!
// o_id should not get by pro_id&go_time 
//	when a user modify project, he maybe insert a same go_time,
// with original pro_id
// -> go_id should get by multi col: 
function bug_get_go_id($pro_id,$go_time){
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

	//echo "<h1>---wait to add check code---</h1>";
}

function checkGoUnique($stu_grade,$stu_major,$course_id,$go_time){
	global $db;
	$sql="SELECT * FROM go WHERE
	stu_grade='$stu_grade' 
	and stu_major='$stu_major' 
	and course_id='$course_id' 
	and go_time='$go_time' ";

	$result=$db->query($sql) or die($db->error);
	if ($result->num_rows != 0) {
		$warn=red('record exists!');
		$warn.='<br><a href="add.php">back to rechoose</a>';
		die($warn);
		return 0;
	} else {		
		return 1;
	}

}



function getCourseName($course_id){
	global $db;

	$sql="SELECT course_name
	from course
	where course_id = '$course_id' ";
	$result = $db->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);

	return $row['course_name'];
}

function getTeacherName($tea_id){
	global $db;

	$sql="SELECT tea_name
	from teacher
	where tea_id = '$tea_id' ";
	$result = $db->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);

	return $row['tea_name'];
}

function getStuName($stu_id){
	global $db;

	$sql="SELECT stu_name
	from student
	where stu_id = '$stu_id' ";
	$result = $db->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);

	return $row['stu_name'];
}

function getProDetail($pro_id,&$stu_grade,&$stu_major,&$course_id){
	global $db;

	$sql="SELECT stu_grade,stu_major,course_id
	from project
	where pro_id = '$pro_id'";
	$result = $db->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);

	$stu_grade=$row['stu_grade'];
	$stu_major=$row['stu_major'];
	$course_id=$row['course_id'];
}

function getGoDetail($go_id,&$stu_grade,&$stu_major,&$course_id,&$go_time,&$add_time){
	global $db;

	$sql="SELECT *
	from go
	where go_id = '$go_id'";
	$result = $db->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);

	$stu_grade=$row['stu_grade'];
	$stu_major=$row['stu_major'];
	$course_id=$row['course_id'];
	$go_time=$row['go_time'];
	$add_time=$row['add_time'];
}


function showMenuAccordUserRole(){
	$user_role=$_SESSION['user_role'];

	if ($user_role == 'admin') {
		showAdminMenu();
	}elseif($user_role == 'teacher'){
		showTeacherMenu();
	}

	if (DEV_MODE == 1) {
		echo '<a href="dev.php">'.lang('dev').'</a>';
	}
	
}

function showAdminMenu(){
	echo '
	<a href="index.php">'.lang('index').'</a>  
	<a href="manage_stu.php">'.lang('manage_stu').'</a> 
	<a href="manage_tea.php">'.lang('manage_teacher').'</a> 
	<a href="manage_course.php">'.lang('manage_course').'</a>	
	<a href="manage_pro.php">'.lang('manage_pro').'</a>
	<a href="manage_go.php">'.lang('manage_go').'</a> 

	<a href="show_at.php">'.lang('show_at').'</a> 
	';
	
}

function showTeacherMenu(){
	echo '
	<a href="index.php">'.lang('index').'</a>  
	<a href="add.php">'.lang('add').'</a>
	<a href="show_at.php">'.lang('show_at').'</a> 
	<a href="set_course.php">'.lang('set_course').'</a> 
	';
}


function showGrid($table_name,$sql,$primary_key){
global $db;

$i = 0;
$php_self=php_self();

runOperateWithGet($table_name,$sql,$primary_key);


echo "<table class='table-bordered'>";
echoTableHead($table_name,$sql,1);

$result = $db->query($sql);
if ($result->num_rows == 0) {
	if ($table_name == 'project') {
		echo_red('未设置课程，请添加！');
	}else{
		echo "<h1>No Result</h1>";
	}	
} else {		
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		if($i == 0){
			// echo "<tr>";
			// foreach($row as $x=>$x_value) {
			// 	echo "<th>";
			// 	echo $x;
			// 	echo "</th>";
			// }
			// 	echo "<th>";
			// 	echo "op";
			// 	echo "</th>";
			// echo "</tr>";			
		}
		echo "<tr>";
		foreach($row as $x=>$x_value) {
			if( ($x == 'course_id') && ($php_self=='set_course.php'||$php_self=='manage_pro.php'||$php_self=='manage_go.php') ){
				$course_name=getCourseName($x_value);
				echo "<td>" .$x_value."-".$course_name."</td>" ;
			}
			else if (($x == 'tea_id') && ($php_self=='set_course.php'||$php_self=='manage_pro.php'||$php_self=='manage_go.php')) {
				$tea_name=getTeacherName($x_value);
				echo "<td>" .$x_value."-".$tea_name."</td>" ;
			}
			else{
				echo "<td>" .$x_value."</td>" ;
			}
			
		}

			if ($table_name=='project') {
				echo "<td>"; 
				echo '<a href="set_student.php?&'.$primary_key.'='.$row[$primary_key].'">';
				echoAddStudentOrSet($row[$primary_key]);
				echo '</a>';
				echo '&nbsp;';
			}else{
			echo "<td>"; 
			echo '<a href="'.$php_self.'?op=edit&'.$primary_key.'='.$row[$primary_key].'">edit</a>';
			echo '&nbsp;';
			}

			echo '<a href="'.$php_self.'?op=del&'.$primary_key.'='.$row[$primary_key].'" onclick="';
			echo "return confirm('Are you sure you want to delete this item?');";
			echo '">del</a>';

			echo "</td>" ;
		echo "</tr>";
		$i=1;
	}
}

echo '</table>';

if ($table_name == 'go') {
	echo '';
}
else{
	echo '<a href="'.php_self().'?op=add'.'">add</a>';
}



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
			if($table_name == 'project'){
				editProEntry($table_name,$_SESSION['user_role'],$primary_key,$_GET[$primary_key]);			
			}else if ($table_name == 'go') {
				editAt($_GET[$primary_key]);
			}
			else{
				editEntry($table_name,$primary_key,$_GET[$primary_key]);
			}
		}elseif ($_GET['op'] == 'update') {

			if($table_name == 'go'){
				updateAt();			
			}
			else{
				updateEntry($table_name,$primary_key,$_GET[$primary_key]);
			}
			
		}

		elseif ($_GET['op'] == 'add') {
			if($table_name == 'project'){
				inputNewPro($table_name,$_SESSION['user_role']);				
			}else{
				addNewEntry($table_name);
			}
			
		}elseif ($_GET['op'] == 'insert') {
			insertEntry($table_name);
		}


	}
}

function editEntry($table_name,$primary_key,$primary_key_value){
	global $db;

	$sql="SELECT * from $table_name where $primary_key = '$primary_key_value' ";
	$php_self=php_self();

	noise($primary_key.'='.$primary_key_value);

	echo '<form method="post" action="'.$php_self.'?op=update&'.$primary_key.'='.$primary_key_value.'"">';
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
				makeAnInput($x,$x_value);
				echo "</td>" ;
			}

			echo "</tr>";

			// just one line !
			break;
		}
	}

	echo "</table>";

	echo '<input type="submit" value="update">';
	echo '&nbsp;';
	echo '<a href="'.$php_self.'">cancel</a>';

	echo "</form>";

	die();

}



function editGoEntry($table_name,$primary_key,$primary_key_value){
	global $db;

	$sql="SELECT * from $table_name where $primary_key = '$primary_key_value' ";
	$php_self=php_self();

	noise($primary_key.'='.$primary_key_value);

	echo '<form method="post" action="'.$php_self.'?op=update&'.$primary_key.'='.$primary_key_value.'"">';
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
				makeAnInput($x,$x_value);
				echo "</td>" ;
			}

			echo "</tr>";

			// just one line !
			break;
		}
	}

	echo "</table>";

	echo '<input type="submit" value="update">';
	echo '&nbsp;';
	echo '<a href="'.$php_self.'">cancel</a>';

	echo "</form>";

	die();
}


function editAt($go_id = '28'){
	global $db;
	$php_self=php_self();
	$stu_grade=NULL;
	$stu_major=NULL;
	$course_id=NULL;
	$go_time=NULL;
	$add_time=NULL;

	$i=0;

	getGoDetail($go_id,$stu_grade,$stu_major,$course_id,$go_time,$add_time);
	$course_name=getCourseName($course_id);
	echo  '<p>您正在修改的点名记录，年级为'.$stu_grade.'级，专业为'.$stu_major.'，课程为'.$course_id.'-'.$course_name.'，点名日期为'.$go_time.'，录入时间为'.$add_time.'</p>';

	echo '<form method="post" action="'.$php_self.'?op=update&'.'go_id'.'='.$go_id.'"">';
	
	//echo '<input name="pro_id" value="'.$_POST["pro_id"].'" style="display:none;" >';
	echo '<input name="date" value="'.$go_time.'" style="display:none;" >';
	echo '<input name="go_id" value="'.$go_id.'" style="display:none;" >';


	$sql="SELECT *
	from attend
	where go_id = $go_id ";
	$result = $db->query($sql);

	if ($result->num_rows == 0) {
		echo_red('no result');
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
				$stu_name=getStuName($row['stu_id']);
				make_a_switch('check'.$i,$row['stu_id'],$stu_name,'42px','✔','✘','success','danger',$row['at_yes']);
				echo '</td>';

				echo '<td>';
				make_a_select_for_at_meta('at_meta'.$i,$row['at_meta']);
				echo '</td>';

			echo '</tr>';
			$i++;
		}
		echo '</table>';
	}

	echo '<input name="stu_sum" value="'.$i.'" style="display:none;" >';

	echo '<input type="submit" name="update_at_submit" value="保存修改">';
	echo '&nbsp;';
	echo '<a href="'.$php_self.'">cancel</a>';
	echo '</form>';

	//echo '';

	die();
	

}




function updateAt(){
	global $db;
	dev_var_dump('post');

	if(isset($_POST['update_at_submit'])){
		$go_id=$_POST['go_id'];
		$stu_sum=$_POST["stu_sum"];
		noise('$go_id is:'.$go_id);
	}


	for($j=0;$j<$stu_sum;$j++){
		// make string that match the var name
		$stu_num_j="stu_num".$j;
		$check_j="check".$j;
		$at_meta_j="at_meta".$j;

		$stu_id=$_POST[$stu_num_j];
		if (isset($_POST[$check_j])) {
			$is_online=1;		
		}else{
			$is_online=0;
		}
		$at_meta=$_POST[$at_meta_j];

		//echo $stu_id."-".$is_online.'<br>';

		// $sql="INSERT attend(go_id, stu_id, at_yes, at_meta) VALUES
		// ('$go_id','$stu_id','$is_online','$at_meta')";

		$sql="UPDATE attend
		SET 
		at_yes='$is_online',
		at_meta= '$at_meta'
		WHERE go_id = '$go_id' and stu_id = '$stu_id' ";

		$result=$db->query($sql) or die($db->error);	
	}

	if ($result == 1) {
		echoGreen('update success');
	}

}

function updateGo(){
	global $db;

	
}





function updateEntry($table_name,$primary_key,$primary_key_value){
	global $db;
	$php_self=php_self();
	$set_union = '';
	$post_array = $_POST;
	$i=0;

	dev_var_dump('post');

	foreach($post_array as $x=>$x_value) {

		// echo "$x";
		// echo ".";
		// echo "$x_value";
		// echo "<br>";

		if($i==0){
			$set_union =$set_union . $x ."='".$x_value."'";
		}else{
			$set_union =$set_union .",". $x ."='".$x_value."'";
		}
		$i++;
	}

	//noise($set_union);

	$sql='UPDATE '.$table_name.
	' SET '.$set_union.
	' WHERE '.$primary_key.'=';
	$sql=$sql."'$primary_key_value'";

	noise($sql);



	$result = $db->query($sql) or die($db->error);
	if ($result == 1) {
		echoGreen('Update success!');
		//echo "<h5>update success!</h5>";
	}

}

function addNewEntry($table_name){
	global $db;
	$sql="SELECT * from $table_name";
	$php_self=php_self();

	echo '<form method="post" action="'.$php_self.'?op=insert">';
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
				// echo "<td>"; 
				
				// echo "</td>" ;
			echo "</tr>";

			// just one line !
			break;
		}
	}

	echo "</table>";

	echo '<input type="submit" value="insert">';
	echo '&nbsp;';
	echo '<a href="'.$php_self.'">cancel</a>';

	echo "</form>";

	die();
}

//must have POST , all the column must char
function insertEntry($table_name){
	global $db;
	$php_self=php_self();

	$col_name_str='';
	$val_name_str='';

	dev_var_dump('post');
	$post_array = $_POST;

	$i=0;
	foreach($post_array as $x=>$x_value) {

		// echo "$x";
		// echo ".";
		// echo "$x_value";
		// echo "<br>";

		if($i==0){
			$col_name_str = $col_name_str.$x;
			$val_name_str = $val_name_str."'".$x_value."'";
		}else{
			$col_name_str = $col_name_str.",".$x;
			$val_name_str = $val_name_str.",'".$x_value."'";
		}
		$i++;
	}

	//noise($col_name_str);
	//noise($val_name_str);

	$sql='INSERT INTO '.$table_name.'('.$col_name_str.')VALUES
	('.$val_name_str.')';

	noise($sql);

	$result = $db->query($sql) or die($db->error);
	if ($result == 1) {
		echoGreen('Insert success!'); 
		//echo '<a href="'.$php_self.'">cancel</a>';
	}
	
}


function execDel($table_name,$primary_key,$primary_key_value){
	global $db;

	$sql="DELETE FROM $table_name
	WHERE $primary_key = '$primary_key_value' ";
	$result = $db->query($sql) or die($db->error);

	echoGreen("<p>delete $table_name with $primary_key = $primary_key_value  success!</p>");
	
}



//			<th> </th>
function echoTableHead($table_name,$sql,$need_op = 0){
	global $db;

	$i = 0;
	$result = $db->query($sql) ;
	if ($result->num_rows == 0) {
		if (DEV_MODE == 1) {
			echo_red("Table Head No Result");
		}		
	} else {		
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			if($i == 0){
				echo "<tr>";
				foreach($row as $x=>$x_value) {
					echo "<th>";
					//echo $x;
					dev_echo_col_name($table_name,$x);
					echo "</th>";
				}
				if($need_op == 1){
					echo "<th>";
					// echo "op";
					dev_echo_col_name('op','op');
					echo "</th>";
				}
					
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
				echoSelectList('course_id','course');
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






function inputNewPro2($table_name,$user_role=NULL){
	global $db;
	//$sql="SELECT stu_grade,stu_major,course_id from $table_name";
	$php_self=php_self();
	$tea_id=$_SESSION['tea_id'];

	if ($user_role=='admin') {
		$sql="SELECT stu_grade,stu_major,course_id,tea_id from $table_name";
	}elseif($user_role=='teacher'){
		$sql="SELECT stu_grade,stu_major,course_id from $table_name";
	}

	//$sql="SELECT stu_grade,stu_major,course_id from $table_name";

	echo '<form method="post" action="'.$php_self.'?op=insert">';
	echo "<table class='table-bordered'>";
	echoTableHead($table_name,$sql);

	$result = $db->query($sql);
	if ($result->num_rows == 0) {
		echo "<h1>No Result</h1>";
	} else {		
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			
			echo "<tr>";
			foreach($row as $x=>$x_value) {
				
				$PK_name=getPrimaryKeyName($table_name);
				//noise('$x is:'.$x);
				//noise('$PK_name is:'.$PK_name);
				if($x==$PK_name){
					;
				}else{
					echo "<td>";
					switch ($x) {
						case 'stu_grade':
							$query="SELECT DISTINCT stu_grade from student ";
							break;

						case 'stu_major':
							$query="SELECT DISTINCT stu_major from student ";
							break;

						case 'course_id':
							$query="SELECT DISTINCT course_id from course ";
							break;

						case 'tea_id':
							$query="SELECT DISTINCT tea_id from teacher ";
							break;

						default:
							
							break;
					}

					//$query="SELECT DISTINCT $x from course ";
					
					makeSelect($x,$query);

					echo "</td>" ;
				}
				
			}

			echo "</tr>";

			// just one line !
			break;
		}
	}

	echo "</table>";

	makeAnInput('tea_id',$tea_id,1,1);
	echo '<input type="submit" value="insert">';
	echo '&nbsp;';
	echo '<a href="'.$php_self.'">cancel</a>';

	echo "</form>";

	die();
}

function inputNewPro($table_name,$user_role=NULL){
	global $db;
	$php_self=php_self();

	echo '<form method="post" action="'.$php_self.'?op=insert">';
	echo "<table class='table-bordered'>";
		echo "<tr>";
			echo '<th>course_id</th>';
			echo '<th>year</th>';
			echo '<th>term</th>';			
			echo '<th>stu_grade</th>';
			echo '<th>stu_major</th>';
			echo '<th>tea_id</th>';
		echo "</tr>";

		echo "<tr>";
			echo '<td>';
			makeSelect('course_id',"SELECT DISTINCT course_id from course");
			echo '</td>';

			echo '<td>';
			echo '
			<select name="year">
				<option>2016-2017</option>
			</select>
			';
			
			echo '</td>';

			echo '<td>';
			echo '
			<select name="term">
				<option>1</option>
				<option>2</option>
			</select>
			';

			echo '</td>';

			

			echo '<td>';
			makeSelect('stu_grade',"SELECT DISTINCT stu_grade from student ",'','不分年级');
			echo '</td>';

			echo '<td>';
			makeSelect('stu_major',"SELECT DISTINCT stu_major from student ",'','不分专业');
			echo '</td>';

			echo '<td>';
			if ($user_role == 'teacher') {
				$tea_id=$_SESSION['tea_id'];				
				makeSelect('tea_id',"SELECT tea_id from teacher where tea_id='$tea_id' ");
			}else{
				makeSelect('tea_id',"SELECT DISTINCT tea_id from teacher");				
			}
			echo '</td>';
			


		echo "</tr>";




	echo "</table>";

	

	echo '<input type="submit" value="insert">';
	echo '&nbsp;';
	echo '<a href="'.$php_self.'">cancel</a>';

	echo "</form>";

	die();
}




function editProEntry($table_name,$user_role=NULL,$primary_key,$primary_key_value){
	global $db;
	//$sql="SELECT stu_grade,stu_major,course_id from $table_name";
	$php_self=php_self();
	$tea_id=$_SESSION['tea_id'];

	if ($user_role=='admin') {
		$sql="SELECT pro_id,stu_grade,stu_major,course_id,tea_id from $table_name 
		where $primary_key = '$primary_key_value' ";
	}elseif($user_role=='teacher'){
		$sql="SELECT pro_id,stu_grade,stu_major,course_id from $table_name
		where $primary_key = '$primary_key_value' ";
	}

	echo '<form method="post" action="'.$php_self.'?op=update&'.$primary_key.'='.$primary_key_value.'"">';
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

				$PK_name=getPrimaryKeyName($table_name);
				//noise('$x is:'.$x);
				//noise('$PK_name is:'.$PK_name);
				if($x==$PK_name){
					echo $x_value;
				}else{					
					switch ($x) {
						case 'stu_grade':
							$query="SELECT DISTINCT stu_grade from student ";
							break;

						case 'stu_major':
							$query="SELECT DISTINCT stu_major from student ";
							break;

						case 'course_id':
							$query="SELECT DISTINCT course_id from course ";
							break;

						case 'tea_id':
							$query="SELECT DISTINCT tea_id from teacher ";
							break;

						default:
							
							break;
					}

					//$query="SELECT DISTINCT $x from course ";
					
					makeSelect($x,$query,$x_value);

					echo "</td>" ;
				}
				
			}

			echo "</tr>";

			// just one line !
			break;
		}
	}

	echo "</table>";

	//make a hidden post_var for tea_id
	makeAnInput('tea_id',$tea_id,1,1);
	echo '<input type="submit" value="update">';
	echo '&nbsp;';
	echo '<a href="'.$php_self.'">cancel</a>';

	echo "</form>";

	die();
}











function makeAnInput($name,$value = '',$required = 1,$display_none = 0){
	echo '<input type="text" name="'.$name.'" ';
	if( $value != ''){
		echo 'value="'.$value.'" ';
	}
	if ($required == 1) {
		echo 'required ';
	}
	if($display_none == 1){
		echo 'style="display: none;" ';
	}
	echo '>';
}






function del_at_with_go_id($go_id){
	global $db;

	$sql="DELETE FROM attend
	WHERE go_id = '$go_id' ";
	$db->query($sql) or die($db->error);

	echoGreen("<p>del at with go_id = $go_id success!</p>");
}

function getOneResultByOneQuery($sql){
	global $db;

	$result=$db->query($sql) or die($db->error);

	if ($result->num_rows == 0) {
		$warning=__FUNCTION__.':no result!';
		echo_red($warning);
		return $warning;
	} else {
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$first_key_value=reset($row);
		return $first_key_value;
	}
	
}





function getPrimaryKeyName($table_name){
	global $db;

	$sql="SHOW KEYS FROM $table_name WHERE Key_name = 'PRIMARY'";
	$result = $db->query($sql) or die($db->error);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	return $row['Column_name'];
}


function getLastInsertID(){
	global $db;

	// $sql="INSERT INTO `go`(`pro_id`, `go_time`, `go_meta`) VALUES ('100','1','1')";

	$sql="SELECT LAST_INSERT_ID()";
	$result = $db->query($sql) or die($db->error);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$first_key_value=reset($row);
	return $first_key_value;
}




function makeSelect($name,$sql,$selected_value=NULL,$empty_tip='not_set'){
	global $db;
	echo '<select name="'.$name.'" >';

	//$sql="SELECT DISTINCT stu_grade from student";

	$result = $db->query($sql);
	if ($result->num_rows == 0) {
		echo "<option>no result</option>";
    } else {

    	if($empty_tip != 'not_set'){
    		echo '<option value="'.$empty_tip.'" selected>'.$empty_tip.'</option>';
    	}

		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$first_key_value=reset($row);

			if($name=='course_id'){
				$course_name=getCourseName($first_key_value);	
				$text=$first_key_value.'-'.$course_name;			
			}elseif ($name=='tea_id') {
				$tea_name=getTeacherName($first_key_value);
				$text=$first_key_value.'-'.$tea_name;
			}else{
				$text=$first_key_value;
			}

			

			makeOption($first_key_value,$text,$selected_value);
		}
	}

	echo '</select>';

}

function echoSelectForAddedGo($name,$sql){
	global $db;
	echo '<select name="'.$name.'" >';

	//$sql="SELECT DISTINCT stu_grade from student";

	$result = $db->query($sql);
	if ($result->num_rows == 0) {
		echo "<option>no result</option>";
    } else {
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$stu_grade=$row['stu_grade'];
			$stu_major=$row['stu_major'];
			$course_id=$row['course_id'];
			$course_name=getCourseName($course_id);

			if (isset($row['tea_id'])) {
				$tea_name=getTeacherName($row['tea_id']);
				$text=$stu_grade.'-'.$stu_major.'-'.$course_name.'-'.$tea_name;
			}else{
				$text=$stu_grade.'-'.$stu_major.'-'.$course_name;
			}
			
			
			$go_id=getOneResultByOneQuery("SELECT go_id from go where stu_grade ='$stu_grade' and stu_major = '$stu_major' and course_id = '$course_id'");

			makeOption($go_id,$text);





		}
	}
	echo '</select>';

}

function makeInput($name,$value = '',$required = 1,$display_none = 0){
	$str= '<input type="text" name="'.$name.'" ';
	if( $value != ''){
		$str.= 'value="'.$value.'" ';
	}
	if ($required == 1) {
		$str.= 'required ';
	}
	if($display_none == 1){
		$str.= 'style="display: none;" ';
	}
	$str.= '>';

	return $str;
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
				$course_name=getCourseName($row[$column]);
				echo '<option value="'.$row[$column].'" selected>'.$row[$column].'-'.$course_name.'</option>';
			}elseif($table_name == 'teacher'){
				$tea_name=getTeacherName($row[$column]);
				echo '<option value="'.$row[$column].'" selected>'.$row[$column].'-'.$tea_name.'</option>';
			}
			
		}
	}

	echo '</select>';
}



function showAtDetail(){
	global $db;

	$tea_id=NULL;
	dev_var_dump('post');

	$stu_grade=NULL;
	$stu_major=NULL;
	$course_id=NULL;
	if (isset($_POST["pro_id"])) {
		getProDetail($_POST["pro_id"],$stu_grade,$stu_major,$course_id);
	}elseif (isset($_POST["go_id"])) {
		$go_time=NULL;
		$add_time=NULL;
		getGoDetail($_POST["go_id"],$stu_grade,$stu_major,$course_id,$go_time,$add_time);
	}elseif (isset($_GET["pro_id"])) {
		getProDetail($_GET["pro_id"],$stu_grade,$stu_major,$course_id);
	}
	
	$course_name=getCourseName($course_id);
	echo s($stu_grade).'级，'.s($stu_major).'专业，'.s($course_name).'课的所有点名如下所示：';


	$sql="SELECT go_id,go_time from go where 
	stu_grade='$stu_grade' AND
	stu_major='$stu_major' AND
	course_id='$course_id' ORDER BY go_time ASC
	";


	echo '<table class="table-bordered">';
	// >>>>>>>>>>>>>>  table head >>>>>>>>>>>>>>>>>>>>>>>>>>
	$result = $db->query($sql) or die($db->error);
	if ($result->num_rows == 0) {
		echo_red('no result!');
		die();
	} else {
		echo '<tr>';
			echo '<th>'.'学号-姓名'.'</th>';		
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$go_id=$row['go_id'];
			$go_time=$row['go_time'];
			echo '<th>'.$go_time.'</th>';
		}
		echo '</tr>'; 
	}// go_id,go_time 
	// <<<<<<<<<<<<<<  table head  <<<<<<<<<<<<<<<<<





 
	// >>>>>>>>>>    table body  >>>>>>>>>>>>>>
	$sql2="SELECT * from student where stu_grade = '$stu_grade' AND stu_major = '$stu_major'";
	$result2 = $db->query($sql2) or die($db->error);
	if ($result2->num_rows == 0) {
		echo_red('no result!');
	} else {		
		while($row2 = $result2->fetch_array(MYSQLI_ASSOC)){
			echo '<tr>';

			echo '<td>';
			$stu_id = $row2['stu_id'];
			$stu_name = $row2['stu_name'];
			echo $stu_id.'-'.$stu_name;
			echo '</td>';


			$result = $db->query($sql) or die($db->error);
			if ($result->num_rows == 0) {
				echo_red('no result!');
			} else {		
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
					$go_id=$row['go_id'];
					$go_time=$row['go_time'];


					$sql3="SELECT at_yes from attend where stu_id = '$stu_id' and go_id = '$go_id' ";
					$at_yes=getOneResultByOneQuery($sql3);
					echo '<td>';
					paintResult($at_yes);
					// check fianla data is correct or not
					makeHideItem('go_time is:'.$go_time.',stu_id is:'.$stu_id.'-'.$stu_name.',at_yes is:'.$at_yes);
					echo '</td>';

				}
			}// go_id,go_time



			echo '</tr>';
		}
	}//stu_id
	// <<<<<<<<<<<<     table body       <<<<<<<<<<<<<<<<<
	echo '</table>';

	echo '<a href="show_at.php">继续查询</a>';
}









function getNowTime(){
	date_default_timezone_set('Asia/Shanghai');
	$now = date('Y-m-d H:i:s', time());
	return $now;
}

function paintResult($result){
	if ($result == '1') {
		echo '<div style="background:#5cb85c;color:white;text-align:center;">✔</div>';
	}elseif ($result == '0') {
		echo '<div style="background:red;color:white;text-align:center;">✘</div>';
	}
}



function makeTableForAddStudent($pro_id){
	global $db;

	echo '<table class="table-bordered">';
	$sql="SELECT * FROM student";
	$result = $db->query($sql) or die($db->error);
	if ($result->num_rows == 0) {
		echo_red('no result!Please add students');
		die();
	} else {		
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			echo '<tr>';
			$stu_id=$row['stu_id'];
			echo '<td>'.$stu_id.'</td>';
			echo '<td>'.$row['stu_name'].'</td>';
			
			checkAddedStu($pro_id,$stu_id);
			
			echo '</tr>'; 
		}		
	}
	echo '</table>';
}

function checkAddedStu($pro_id,$stu_id){
	global $db;
	$sql="SELECT stu_id FROM attend where pro_id= '$pro_id' and stu_id = '$stu_id' ";
	$result = $db->query($sql) or die($db->error);
	if ($result->num_rows == 0) {
		echo '<td>'.'<button onclick="add(this.value)" value="'.$stu_id.'">add</button>'.'</td>';
	} else {		
		echo '<td>'.'<button disabled>added</button>'.'</td>';
	}

}

function makeTableForAddedStudent($pro_id){
	global $db;

	echo '<table class="table-bordered">';
	$sql="SELECT stu_id FROM attend where pro_id= '$pro_id'";
	$result = $db->query($sql) or die($db->error);
	if ($result->num_rows == 0) {
		echo_red('no result!please add students to here');
		//die();
	} else {		
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			echo '<tr>';
			$stu_id=$row['stu_id'];
			$stu_name=getStuName($stu_id);
			echo '<td>'.$stu_id.'</td>';
			echo '<td>'.$stu_name.'</td>';
			echo '<td>'.'<button onclick="del(this.value)" value="'.$stu_id.'">del</button>'.'</td>';
			echo '</tr>'; 
		}		
	}
	echo '</table>';
}



function insertOne($sql){
	global $db;
	$result = $db->query($sql) or die($db->error);
	if ($result == 1) {
		echoGreen('Insert success!'); 
	}
}

function delOne($sql){
	global $db;
	$result = $db->query($sql) or die($db->error);
	if ($result == 1) {
		echoGreen('Delete success!'); 
	}
}

function echoAddStudentOrSet($pro_id){
	global $db;

	$sql="SELECT stu_id FROM attend where pro_id= '$pro_id'";
	$result = $db->query($sql) or die($db->error);
	if ($result->num_rows == 0) {
		echo 'add_student';
	} else {		
		echo 'set_student';
	}
}





//---------------  global dev code ,reserve in production environment-------------

function dev_var_dump($var){
	if(DEV_MODE == 0){
		return ;
	}
	echo '<p> >>>>>>>>> '.__FUNCTION__.'<br>';

	if($var == 'get'){
		echo 'var_dump_get:';
		var_dump($_GET);		
	}
	else if($var == 'post'){
		echo 'var_dump_post:';
		var_dump($_POST);
	}else{
		var_dump($var);
	}

	echo '<br> <<<<<<<<< '.__FUNCTION__.'</p>';

}


function noise($var){
	if(DEV_MODE == 0){
		return ;
	}

	echo '<p> >>>>>>>>> '.__FUNCTION__.'<br>';

	echo 'see here -> <span style="color:red;">'.$var.'</span>   ';

	echo '<br> <<<<<<<<< '.__FUNCTION__.'</p>';
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

function dev_echo_col_name($table_name,$col_name){
	if(LANG == 0){
		echo $col_name;
		return;
	}else{
		$str = file_get_contents('data.json');
		$col_name_array=json_decode($str, true);

		if(isset($col_name_array[$table_name][$col_name])){
			echo $col_name_array[$table_name][$col_name];
		}else{
			echo $col_name;
			echo_red('check data.json file');
		}

	}
}


function red($str){
	return '<span style="color:red;">'.$str.'</span>';
}

function s($str){
	//return '<strong style="color:green;">'.$str.'</strong>';
	return '<strong>'.$str.'</strong>';
}






function echo_red($str){
	echo '<span style="color:red;">'.$str.'</span>';
}

function echoGreen($str){
	echo '<span style="color:green;">'.$str.'</span>';
}



function makeHideItem($info){
	echo '<p class="h">'.$info.'</p>';
}


function getArrayFromJsonFile($file_name){

	$str = file_get_contents($file_name);
	$array=json_decode($str, true);
	//noise($array);
	//dev_var_dump($array);

	if(is_array($array)){
		return $array;
	}else{
		echo_red('can not read json file, please check file exists, and file format is correct');
		return ;
	}
	
}


function getValueFromJsonFile($key1,$key2,$ignore_no_value = 0){


	$str = file_get_contents('data.json');
	$array=json_decode($str, true);

	if(!is_array($array)){
		echo_red('can not read json file, please check file exists, or file format is correct');
		return ;
	}


	if(isset($array[$key1][$key2])){	
		return $array[$key1][$key2];
	}
	else{
		if ($ignore_no_value == 0) {
			echo_red('this value not set');
			return ;
		}	
		
		return 'not_set';
	}


}


// json deep is 3
// !!!!!!!!!!! bug !!!!!!!!!!!!!
// if $key3 is num, it will return unknown value
//>>>>repair : make sure data.json acurrate index array ,  never no complete
function getJsonData($key1,$key2 = NULL,$key3 = NULL,$ignore_no_value = 0){
	$str = file_get_contents('data.json');
	$array=json_decode($str, true);

	if(!is_array($array)){
		echo_red('can not read json file, please check file exists, or file format is correct');
		return ;
	}

	if(isset($array[$key1][$key2][$key3]) && $key3 !== NULL){
		//noise('bug');
		return $array[$key1][$key2][$key3];
	}
	else if(isset($array[$key1][$key2]) && $key2 !== NULL && $key3 === NULL){	
		return $array[$key1][$key2];
	}
	else if( isset($array[$key1]) && $key2 === NULL && $key3 === NULL){
		return $array[$key1];
	}

	else{
		if ($ignore_no_value == 0) {
			echo_red('this value not set');
		}			
		return 'not_set';
	}


}




function lang($en){
	if(LANG == 0){
		return $en;
	}else{
		$text = getJsonData('lang',$en,NULL,1);
		if ($text == 'not_set') {
			return $en;
		}
	 	return $text;
	}

	//************wait for add gm
	// else(){
	// 	$array_index=LANG - 1;
	// 	$text = getJsonData('lang',$en,LANG);
	// 	return $text;
	// }
	
	
}


