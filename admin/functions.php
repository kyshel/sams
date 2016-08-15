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
            $delay_min=$delay / 60;
            echo '
            <script type="text/javascript">console.log("您距离上一次操作相差'.$cha.'秒，即'.$cha_div.' 分钟，超过'.$delay_min.'分钟会强制退出！")</script>  
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

function dynamicCssJsLib(){
	$php_self=php_self();

	if ($php_self == 'add_result.php' || $php_self == 'show_at.php' ){
		// echo '
		// <link rel="stylesheet" href="css/bootstrap-datepicker3.min.css">
		// <script src="js/bootstrap-datepicker.min.js"></script>
		// ';
		
		echo '
		<script language="javascript" type="text/javascript" src="js/tablesort.min.js"></script>
		'; 
	}
	// elseif($php_self == 'add_main.php' || $php_self == 'manage_go.php'){
	// 	echo '
	// 	<link href="css/bootstrap-switch.min.css" rel="stylesheet">
	// 	<script src="js/bootstrap-switch.min.js"></script>
	// 	'; 
	// }

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



function addNewAt($pro_id){
	global $db;
	getProDetail($pro_id,$course_id,$year,$term,$stu_grade,$stu_major,$last_update);
	$course_name=getCourseName($course_id);
	$i=0;
	
	$sql="SELECT * from attend where pro_id= '$pro_id'";
	$result = $db->query($sql);

	if ($result->num_rows == 0) {
		$add_student_link='<a href="set_student.php?pro_id='.$pro_id.'">'.lang('add_student').'</a>';
		echoRed('您课程中没有学生, 请'.$add_student_link.'后，再来点名');
		

		die();
	} else {
		
		echo '<p>您选择的课程为：'.s($year).'学年'.s($term).'学期'.s($course_name).'课,年级为'
		.s($stu_grade).'，专业为'.s($stu_major).'，最后更新日期为'.s($last_update).'：</p>';

		echo '<table class="table-bordered">';
			echo '<tr>';
				echo '<th>';
				echo '学号';
				echo '</th>';

				echo '<th>';
				echo '姓名';
				echo '</th>';

				echo '<th>';
				echo '旷课次数';
				echo '</th>';
		echo '</tr>';

		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$stu_name=getStuName($row['stu_id']);
			echo '<tr>';

				echo '<td>';
				echo $row['stu_id'];
				echo '<input type="text" name="stu_num'.$i.'" value="'.$row['stu_id'].'" style="display: none;">';
				echo '</td>';

				echo '<td>';
				echo $stu_name;
				echo '</td>';
			
				echo '<td>';
				echo '<input type="number" name="no_sum'.$i.'" value="'.$row['no_sum'].'"
				min="'.$row['no_sum'].'" required>';
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

	$array=getArrayFromJsonFile();
	$array_at_meta=$array['at_meta'];
	foreach ($array_at_meta as $key => $value) {
		//noise($key.$value);
		makeOption($value,$value,$selected_value);
	}

	echo '</select>';
}

function makeOption($value,$text,$selected_value = NULL,$ignore_empty=0){

		if ($ignore_empty==1) {
			if (empty($text)) {
				return ;
			}
		}

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



function checkProUnique($pro_array){
	global $db;

	$year=$pro_array['year'];
	$term=$pro_array['term'];
	$course_id=$pro_array['course_id'];
	$stu_grade=$pro_array['stu_grade'];
	$stu_major=$pro_array['stu_major'];

	$sql="SELECT * FROM project WHERE
	    year='$year'
	and term='$term'
	and course_id='$course_id' 
	and stu_grade='$stu_grade' 
	and stu_major='$stu_major' 
	";

	noise($sql);

	$result=$db->query($sql) or die($db->error);
	if ($result->num_rows != 0) {

		echoRed('您要添加的课程已存在！如下所示：');				
		showGrid('project',$sql,'pro_id',1,1,1);
		echo '<a href="set_course.php?op=add">重新添加</a>';

		die();
		return 0;
	} else {		
		return 1;
	}
}

function checkSproUnique($pro_array){
	global $db;

	// should be spro_array, copy paste boy 
	$year=$pro_array['year'];
	$term=$pro_array['term'];
	$course_id=$pro_array['course_id'];
	$stu_grade=$pro_array['stu_grade'];
	$stu_major=$pro_array['stu_major'];

	$sql="SELECT * FROM spro WHERE
	    year='$year'
	and term='$term'
	and course_id='$course_id' 
	and stu_grade='$stu_grade' 
	and stu_major='$stu_major' 
	";

	noise($sql);

	$result=$db->query($sql) or die($db->error);
	if ($result->num_rows != 0) {

		echoRed('您要添加的课程已存在于结课列表中！如下所示：');				
		showGrid('spro',$sql,'pro_id',1,1,1);
		echo '<a href="set_course.php?op=add">重新添加</a>';

		die();
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

function getProDetail($pro_id,&$course_id,&$year,&$term,&$stu_grade,&$stu_major,&$last_update){
	global $db;

	$sql="SELECT * from project where pro_id = '$pro_id'";
	$result = $db->query($sql)  or die($db->error);
	$row = $result->fetch_array(MYSQLI_ASSOC);

	$course_id=$row['course_id'];
	$year=$row['year'];
	$term=$row['term'];
	$stu_grade=$row['stu_grade'];
	$stu_major=$row['stu_major'];
	$last_update=$row['last_update'];

}



function getStuDetail($stu_id){
	global $db;
	$array=[];

	$sql="SELECT *
	from student
	where stu_id = '$stu_id'";
	$result = $db->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);

	$stu_grade=$row['stu_grade'];
	$stu_major=$row['stu_major'];
	$course_id=$row['course_id'];
	$go_time=$row['go_time'];
	$add_time=$row['add_time'];
}

function getArrayFromEntry($table_name,$primary_key_name,$primary_key_value){
	global $db;
	$array=[];

	$sql="SELECT *
	from $table_name
	where $primary_key_name = '$primary_key_value'";
	$result = $db->query($sql);
	if ($result->num_rows == 0) {
		echoRed('no result match your sql');
	} else {
		$row = $result->fetch_array(MYSQLI_ASSOC);
		foreach ($row as $key => $value) {
			$array[$key]=$value;
		}	
	}

	dev_dump($array);
	return $array;	
}


// never use !!!!!!!!!!!!!!!!!!!!!
function getTableColumnName($table_name){
	global $db;

	$array=[];
	$sql = "SHOW COLUMNS FROM $table_name";
	noise($sql);
	$result = $db->query($sql);
	
	while ( $row = $result->fetch_array(MYSQLI_ASSOC)) {
		$array[]=$row['Field'];
		noise($row['Field']);
	}		

	//var_dump($array);
	return $array;

}


function showMenuAccordUserRole(){
	$user_role=$_SESSION['user_role'];

	if ($user_role == 'admin') {
		showAdminMenu();
	}elseif($user_role == 'teacher'){
		showTeacherMenu();
	}

	if (DEV_MODE == 1) {
		echo '
		<span> | </span>
		<a href="dev.php">'.lang('dev').'</a>
		<a href="add.php">'.lang('add').'</a>
		<a href="show_at.php">'.lang('show_at').'</a> 
		';
	}
	
}

function showAdminMenu(){
	echo '
	<a href="index.php">'.lang('index').'</a>  
	<a href="manage_stu.php">'.lang('manage_stu').'</a> 
	<a href="manage_tea.php">'.lang('manage_teacher').'</a> 
	<a href="manage_course.php">'.lang('manage_course').'</a>
	<a href="manage_pro.php">'.lang('manage_pro').'</a>	
<span> | </span>
	<a href="set_course.php">'.lang('set_course').'</a> 

	';
	
}

function showTeacherMenu(){
	echo '
	<a href="index.php">'.lang('index').'</a>
	<a href="manage_pro.php">'.lang('manage_pro').'</a>  
<span> | </span>
	<a href="set_course.php">'.lang('set_course').'</a> 
	';
}





function echoButtonOutGrid($table_name,$no_echo=0,$no_echo_for_special_table = 0){
	if ($no_echo == 1) {
		return ;
	}
	$button_name=NULL;
	switch ($table_name) {
		case 'student':
		$button_name=lang('add_new_stu');
		echo '<a href="'.php_self().'?op=add'.'" '.$is_display_none.' >'.$button_name.'</a>';
		break;

		case 'course':
		$button_name=lang('add_new_course');
		echo '<a href="'.php_self().'?op=add'.'" '.$is_display_none.' >'.$button_name.'</a>';
		break;

		case 'project':
		if ($no_echo_for_special_table == 0) {
			$button_name=lang('add_new_pro');
			echo '<a href="'.php_self().'?op=add'.'">'.$button_name.'</a>';

			echo '<br><br>';
			echo '<a href="'.php_self().'?op=show_off_pro'.'">'.lang('show_saved_pro').'</a>';
		}
		
		break;

		default:
		$button_name=lang('add');
		echo '<a href="'.php_self().'?op=add'.'" '.$is_display_none.' >'.$button_name.'</a>';
		break;
	}

	
}

function showPro($sql,$status= 'on'){
	global $db;
	$php_self=php_self();

	runOperateWithGet('project',$sql,'pro_id');

	

	noise($sql);

	$result = $db->query($sql);
	if ($result->num_rows == 0) {
		$add_course_link='<a href="'.$php_self.'?op=add">'.lang('add_new_pro').'</a>';
		if ($status == 'on') {
			echoRed('未设置考勤课程，请添加！');
		}elseif ($status == 'off') {
			echoRed('目前没有课程结课！');
		}
		
	} else {
		echo "<table class='table-bordered'>";
		echoTableHead('project',$sql,1);		
		while($row = $result->fetch_array(MYSQLI_ASSOC)){

			echo "<tr>";
			foreach($row as $x=>$x_value) {
				echo "<td>" .$x_value."</td>" ;		
			}
			// echo op
			echo "<td>";

			if ($status== 'on') {
				echoButtonIfAddedStudent('pro_id',$row['pro_id']);		

				echo '<a href="set_student.php?'.'pro_id'.'='.$row['pro_id'].'">';
				echoAddStudentOrSet($row['pro_id']);
				echo '</a>';
				echo '&nbsp;';

				echo '<a href="'.$php_self.'?op=off_pro&'.'pro_id'.'='.$row['pro_id'].'" onclick="';
				echo "return confirm('您确定结束此课程? \\n这将保存此课程的考勤记录，并且将此课程从考勤列表中删除！');";
				echo '">'.lang('save_pro').'</a>';
				echo '&nbsp;';

				echo '<a href="'.$php_self.'?op=del&'.'pro_id'.'='.$row['pro_id'].'" onclick="';
				echo "return confirm('您确定删除此课程? \\n这将清空所有与此课程相关的点名记录，而且无法撤销！');";
				echo '">'.red(lang('del')).'</a>';
			}elseif ($status == 'off') {
				//echoButtonIfAddedStudent('pro_id',$row['pro_id']);
				echo '<a href="show_at.php?pro_id='.$row['pro_id'].'">'.lang('show_at').'</a>';
				echo '&nbsp;';
			}
			
			

			echo "</td>" ;
			echo "</tr>";
		}
	}

	echo '</table>';

	
	if ($status== 'on') {
		echo '<a href="'.php_self().'?op=add'.'">'.lang('add_new_pro').'</a>';
		echo '<br><br>';
		echo '<a href="'.php_self().'?op=show_off_pro'.'">'.lang('show_off_pro').'</a>';
	}elseif($status== 'off'){
		echo '<br><br>';
		echo '<a href="'.php_self().'">'.lang('show_on_pro').'</a>';
	}
	
}




function showGrid($table_name,$sql,$primary_key,$no_op_col=0,$no_op_run=0,$no_out_button=0){
if ($no_op_run == 0) {
	runOperateWithGet($table_name,$sql,$primary_key);
}
global $db;
$php_self=php_self();

echoButtonOutGrid($table_name,$no_out_button,1);
echo "<table class='table-bordered'>";
echoTableHead($table_name,$sql,!$no_op_col);

$result = $db->query($sql);
if ($result->num_rows == 0) {
	if ($table_name == 'project') {
		echoRed('未设置课程，请添加！');
	}else{
		echoRed('No result!');
	}	
} else {		
	while($row = $result->fetch_array(MYSQLI_ASSOC)){

		echo "<tr>";
		foreach($row as $x=>$x_value) {
			if( ($x == 'course_id') && ($php_self=='set_course.php'||$php_self=='manage_pro.php'||$php_self=='manage_go.php') && $table_name =='project' ){
				$course_name=getCourseName($x_value);
				echo "<td>" .$x_value."-".$course_name."</td>" ;
			}
			else if (($x == 'tea_id') && ($php_self=='set_course.php'||$php_self=='manage_pro.php'||$php_self=='manage_go.php') && $table_name =='project' ) {
				$tea_name=getTeacherName($x_value);
				echo "<td>" .$x_value."-".$tea_name."</td>" ;
			}
			else if ( ($x == 'stu_id') && ($php_self=='show_at.php') && $table_name =='attend') {
				
				
			}

			
			else{
				echo "<td>" .$x_value."</td>" ;
			}
			
		}
		if ($no_op_col == 0) {
			echo "<td>";

			if ($table_name=='project') {
				echoButtonIfAddedStudent($primary_key,$row[$primary_key]);		

				echo '<a href="set_student.php?'.$primary_key.'='.$row[$primary_key].'">';
				echoAddStudentOrSet($row[$primary_key]);
				echo '</a>';
				echo '&nbsp;';

				echo '<a href="'.$php_self.'?op=save_pro&'.$primary_key.'='.$row[$primary_key].'" onclick="';
				echo "return confirm('您确定结束此课程? \\n这将保存此课程的考勤记录，并且将此课程从考勤列表中删除！');";
				echo '">'.lang('save_pro').'</a>';
				echo '&nbsp;';

				echo '<a href="'.$php_self.'?op=del&'.$primary_key.'='.$row[$primary_key].'" onclick="';
				echo "return confirm('您确定删除此课程? \\n这将清空所有与此课程相关的点名记录，而且无法撤销！');";
				echo '">'.red(lang('del')).'</a>';


			}elseif ($table_name=='spro') {
				echo '<a href="show_at.php?'.$primary_key.'='.$row[$primary_key].'">'.lang('show_at').'</a>';

				// $isAdmin=($_SESSION['user_role'] == 'admin')?1:0;
				// if ($isAdmin) {
				// 	echo '&nbsp;';
				// 	echo '<a href="'.$php_self.'?op=del&'.$primary_key.'='.$row[$primary_key].'" onclick="';
				// 	echo "return confirm('警告！这是已结课课程，您确定要删除？ \\n请务必在删除前做好考勤记录备份工作，删除后考勤记录无法恢复！');";
				// 	echo '">'.red(lang('del')).'</a>';
				// }

			}elseif ($table_name=='student') {
				echo '<a href="manage_stu.php?op=edit&'.$primary_key.'='.$row[$primary_key].'">edit</a>';
				echo '&nbsp;';
				echo '<a href="manage_stu.php?op=del&'.$primary_key.'='.$row[$primary_key].'" onclick="';
				echo "return confirm('Are you sure you want to delete this item?');";
				echo '">del</a>';
			}else{			 
			echo '<a href="'.$php_self.'?op=edit&'.$primary_key.'='.$row[$primary_key].'">edit</a>';
			echo '&nbsp;';
			echo '<a href="'.$php_self.'?op=del&'.$primary_key.'='.$row[$primary_key].'" onclick="';
			echo "return confirm('Are you sure you want to delete this item?');";
			echo '">del</a>';
			}

			

			echo "</td>" ;
		}
			

		echo "</tr>";
	}
}

echo '</table>';
echoButtonOutGrid($table_name,$no_out_button);


}



function runOperateWithGet($table_name,$sql,$primary_key){	
if (isset($_GET['op'])) {
	switch ($_GET['op']) {
		// add & insert
		case 'add':
			if($table_name == 'project'){
				inputNewPro($table_name,$_SESSION['user_role']);				
			}else{
				inputNewEntry($table_name);
			}
			break;

		case 'insert':
			insertEntry($table_name);
			break;

		// edit & update
		case 'edit':
			if($table_name == 'project'){
				//editPro($table_name,$_SESSION['user_role'],$primary_key,$_GET[$primary_key]);			
			}else if ($table_name == 'go') {
				editAt($_GET[$primary_key]);
			}
			else{
				editEntry($table_name,$primary_key,$_GET[$primary_key]);
			}
			break;

		case 'update':
			if($table_name == 'go'){
				//updateAt();			
			}
			else{
				updateEntry($table_name,$primary_key,$_GET[$primary_key]);
			}
			break;
		
		// del
		case 'del':
			if(isset($_GET[$primary_key])){
				$primary_key_value=$_GET[$primary_key];
				delEntry($table_name,$primary_key,$primary_key_value);
				if($table_name == 'project'){
					//del_at_with_go_id($primary_key_value);
					deleteAttendByPro($primary_key_value);
				}
			}
			break;

		case 'off_pro':
			//savePro($_GET[$primary_key]);
			offPro($_GET[$primary_key]);
			break;

		case 'show_off_pro':
			break;
		
		default:
			echo 'wrong op string';
			break;
	}
}
}

function offPro($pro_id){
	$php_self=php_self();
	updateOne("UPDATE project SET status = 'off' where pro_id = '".$pro_id."'",1);
	echoGreen('结课成功！',1);
	echoLink($php_self,'返回');
	die();
}

// abandon
function savePro($pro_id){
	global $db;
	$i=0;
	$php_self=php_self();
	/* step 0 ,check isProHasStudent */
	$no_stu_mes=red('所选课程中没有添加学生，无法结课！<br><a href="'.php_self().'">返回</a>');
	isProHasStudent($pro_id)?:die($no_stu_mes);

	/* step 1 ,insert spro&sat */
	$array_pro=getArrayFromEntry('project','pro_id',$pro_id);
	$year=$array_pro['year'];
	$term=$array_pro['term'];
	$course_id=$array_pro['course_id'];
	$course_name=getCourseName($course_id);
	$stu_grade=$array_pro['stu_grade'];
	$stu_major=$array_pro['stu_major'];
	$tea_id=$array_pro['tea_id'];
	$tea_name=getTeacherName($tea_id);
	$hour=$array_pro['hour'];
	$save_time=getNowTime();

	// insert spro
	$sql_insert_spro="INSERT INTO spro(year,term,course_id,course_name,stu_grade,stu_major,tea_id,tea_name,hour,save_time) VALUES ('".$year."','".$term."','".$course_id."','".$course_name."','".$stu_grade."','".$stu_major."','".$tea_id."','".$tea_name."','".$hour."','".$save_time."')";
	insertOne($sql_insert_spro,1);

	// insert sat
	$spro_id=getLastInsertID();
	noise($spro_id,'spro_id');
	
	$sql_select_stu="SELECT * FROM attend where pro_id='".$pro_id."'";
	$array_attend=getArrayBySql($sql_select_stu);
	//dev_dump($array_attend);

	foreach ($array_attend as $key[] => $array_stu) {
		//noise($key[$i].'->'.$array_stu);
		$stu_id=$array_stu['stu_id'];
		$no_sum=$array_stu['no_sum'];
		$sql_insert_sat="INSERT INTO sat(spro_id,stu_id,no_sum) VALUES ('".$spro_id."','".$stu_id."','".$no_sum."')";
		//noise($sql_insert_sat);
		insertOne($sql_insert_sat,1);
		//dev_dump($array_stu,'array_stu');
		$i++;
	}


	/* step 2 ,delete project&attend  */
	//del pro
	deleteOne("DELETE FROM project WHERE pro_id ='".$pro_id."'");
	//del stu in pro
	deleteOne("DELETE FROM attend WHERE pro_id ='".$pro_id."'");

	/* step 3 ,confirm save ok */
	echoGreen('结课成功！',1);
	echoLink($php_self,'返回');
	die();
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
				echoInput("entry[$x]",$x_value);
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
		echoRed('no result');
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




function updateEntry($table_name,$primary_key,$primary_key_value){
	global $db;
	$php_self=php_self();
	$set_union = '';
	$array_entry = $_POST['entry'];
	$i=0;

	dev_dump($_POST);

	foreach($array_entry as $x=>$x_value) {

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


function inputNewEntry($table_name){
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
				echoInput("entry[$x]");
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

	dev_dump($_POST);

	if ($table_name == 'project') {		
		checkProUnique($_POST);
		//checkSproUnique($_POST);
	}
	

	$array_entry = $_POST['entry'];
	if ($table_name='project') {
		$array_entry_add=array();
		$array_entry_add['course_name']=getCourseName($_POST['entry']['course_id']);
		$array_entry_add['tea_name']=getTeacherName($_POST['entry']['tea_id']);
		$array_entry=array_merge($array_entry, $array_entry_add);
	}
	dev_dump($array_entry,'array_entry');

	$i=0;
	foreach($array_entry as $x=>$x_value) {
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

	//noise($sql);

	$result = $db->query($sql) or die($db->error);
	if ($result == 1) {
		echoGreen('添加成功!'); 
	}

	// auto add students to course , if major&grade has set
	$pro_id=NULL;
	if ($table_name=='project') {
		$pro_id=getLastInsertID();
		//noise($pro_id);
		if ($_POST['stu_major']!='不分专业' && $_POST['stu_grade'] != '不分年级') {
			$sql2="SELECT stu_id FROM student WHERE 1".$stu_major.$stu_grade;
			//noise($sql2);
			$stu_array=getArrayBySql($sql2,'stu_id');
			addStudentToCourse($pro_id,$stu_array);
			//showStudentInCourse($pro_id);
		}		
	}	
}

function getArrayBySql($sql,$key_name = NULL){
	global $db;
	$i=0;
	$array_sql=array();

	$result = $db->query($sql) or die($db->error);
	if ($result->num_rows == 0) {
		if (DEV_MODE == 1) {
			echoRed("<h5>(dev)No result match your sql<h5>");
		}		
	} else {
		if ($key_name != NULL) {
			while($row = $result->fetch_array(MYSQLI_ASSOC)){
				$array_sql[]=$row[$key_name];
			}		
		}else{
			while($row = $result->fetch_array(MYSQLI_ASSOC)){
				foreach ($row as $key => $value) {
					$array_sql[$i][$key]=$value;
				}	
				$i++;			
			}
		}		
		
	}
	return $array_sql;	
}

function delEntry($table_name,$primary_key,$primary_key_value){
	global $db;

	$sql="DELETE FROM $table_name
	WHERE $primary_key = '$primary_key_value' ";
	$result = $db->query($sql) or die($db->error);

	echoGreen("Delete $table_name with $primary_key = $primary_key_value  success!");
	
}



//			<th> </th>
function echoTableHead($table_name,$sql,$need_op = 0){
	global $db;

	$i = 0;
	$result = $db->query($sql) ;
	if ($result->num_rows == 0) {
		if (DEV_MODE == 1) {
			echoRed("<h5>(dev)Table Head No Result!<h5>");
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






function inputNewPro($table_name,$user_role=NULL){
	global $db;
	$php_self=php_self();
	$array_json=getArrayFromJsonFile();

	echo '<span>添加考勤课程：</span>';
	echo '<form method="post" action="'.$php_self.'?op=insert">';	
	echo "<table class='table-bordered'>";
		echo "<tr>";
			echo '<th>'.lang('course_id').'</th>';		
			echo '<th>'.lang('year').'</th>';
			echo '<th>'.lang('term').'</th>';	
			echo '<th>'.lang('hour').'</th>';
			echo '<th>'.lang('stu_grade').'</th>';
			echo '<th>'.lang('stu_major').'</th>';
			echo '<th>'.lang('tea_id').'</th>';
		echo "</tr>";

		echo "<tr>";
			echo '<td>';
			makeSelect("entry[course_id]","SELECT DISTINCT course_id from course");
			echo '</td>';

			

			echo '<td>';
			echo '
			<select name="entry[year]">
				<option>2016-2017</option>
			</select>
			';	
			echo '</td>';

			echo '<td>';
			echo '
			<select name="entry[term]">
				<option>1</option>
				<option>2</option>
			</select>
			';
			echo '</td>';

			echo '<td>';
			echo '<select name="entry[hour]">';			
			foreach ($array_json['course_hour'] as $key => $value) {
				echo '<option>'.$value.'</option>';
			}
			echo '</select>';
			echo '</td>';


			

			echo '<td>';
			makeSelect('entry[stu_grade]',"SELECT DISTINCT stu_grade from student ",'no_selected',1,'不分年级','不分年级');
			echo '</td>';

			echo '<td>';
			makeSelect('entry[stu_major]',"SELECT DISTINCT stu_major from student ",'no_selected',1,'不分专业','不分专业');
			echo '</td>';

			echo '<td>';
			if ($user_role == 'teacher') {
				$tea_id=$_SESSION['tea_id'];				
				makeSelect('entry[tea_id]',"SELECT tea_id from teacher where tea_id='$tea_id' ");
			}else{
				makeSelect('entry[tea_id]',"SELECT DISTINCT tea_id from teacher");				
			}
			echo '</td>';
			


		echo "</tr>";




	echo "</table>";

	
	echoInput('entry[last_update]','从未更新',1,1);
	echoInput('entry[status]','on',1,1);
	echoInput('entry[off_time]','never',1,1);
	echo '<input type="submit" value="提交">';
	echo '&nbsp;';
	echo '<a href="'.$php_self.'">cancel</a>';

	echo "</form>";

	die();
}




function editPro($table_name,$user_role=NULL,$primary_key,$primary_key_value){
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
	echoInput('tea_id',$tea_id,1,1);
	echo '<input type="submit" value="update">';
	echo '&nbsp;';
	echo '<a href="'.$php_self.'">cancel</a>';

	echo "</form>";

	die();
}











function echoInput($name,$value = '',$required = 1,$display_none = 0){
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




function deleteAttendByPro($pro_id,$ignore_msg=0){
	global $db;

	$sql="DELETE FROM attend
	WHERE pro_id = '$pro_id' ";
	$db->query($sql) or die($db->error);

	if($ignore_msg == 0){
		$dev_sql_content=(DEV_MODE == 1)?"($sql)":'';
		echoGreen('Delete attend with pro_id = $pro_id success!'.$dev_sql_content); 
	}

}

function getOneResultByOneQuery($sql){
	global $db;

	$result=$db->query($sql) or die($db->error);

	if ($result->num_rows == 0) {
		$warning=__FUNCTION__.':no result!';
		echoRed($warning);
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




function makeSelect($name,$sql,$selected_value=NULL,$ignore_empty=0,$add_option_text='not_set',$add_option_value='not_set',$add_property='not_set'){
	global $db;
	echo '<select name="'.$name.'" ';
	if ($add_property !='not_set') {
		echo $add_property;
	}
	echo '>';

	//$sql="SELECT DISTINCT stu_grade from student";

	$result = $db->query($sql);
	if ($result->num_rows == 0) {
		echo "<option>no result</option>";
    } else {

    	if($add_option_text != 'not_set'){
    		echo '<option value="'.$add_option_value.'" selected>'.$add_option_text.'</option>';
    	}

		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$first_key_value=reset($row);


			if($name=='entry[course_id]'){
				$course_name=getCourseName($first_key_value);	
				$text=$first_key_value.'-'.$course_name;			
			}elseif ($name=='entry[tea_id]') {
				$tea_name=getTeacherName($first_key_value);
				$text=$first_key_value.'-'.$tea_name;
			}else{
				$text=$first_key_value;
			}

			

			makeOption($first_key_value,$text,$selected_value,$ignore_empty);
		}
	}

	echo '</select>';

}



function getInput($name,$value = '',$required = 1,$display_none = 0){
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


function showAttendTable($pro_id){
	global $db;

	$sql="SELECT * from attend where pro_id= '$pro_id'";
	$result = $db->query($sql);
	if ($result->num_rows == 0) {
		$add_student_link='<a href="set_student.php?pro_id='.$pro_id.'">'.lang('add_student').'</a>';
		echoRed("您课程中没有学生, 请 $add_student_link");
		die();
	} else {

		getProDetail($pro_id,$course_id,$year,$term,$stu_grade,$stu_major,$last_update);
		$course_name=getCourseName($course_id);
		echo '<div>'.s($year).'学年'.s($term).'学期'.s($course_name).'课的点名情况如下所示';
		//echo '<p>(此课程年级为'.s($stu_grade).'，专业为'.s($stu_major).'，最后更新时间为'.s($last_update).')：</p>';
		echo '(最后更新时间'.s($last_update).')：</div>';

		showProStatic($pro_id);
		echo '<table class="table-bordered" id="tablesort">';

		echo '<thead><tr>';
		echo '<th>';
		echo '学号';
		echo '</th>';

		echo '<th class="no-sort">';
		echo '姓名';
		echo '</th>';

		echo '<th>';
		echo '旷课次数';
		echo '</th>';
		echo '</tr></thead>';

		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$stu_name=getStuName($row['stu_id']);
			echo '<tr>';

			echo '<td>';
			echo $row['stu_id'];
			echo '</td>';

			

			echo '<td>';
			echo $stu_name;
			echo '</td>';

			
			if (isReachCancelExam($row['pro_id'],$row['stu_id']) == 1) {
				echo '<td class="danger" data-toggle="tooltip" data-placement="right" title="该生已达到取消考试资格！" >';
				echo $row['no_sum'];
				echo '</td>';
			}else{
				echo '<td>';
				echo $row['no_sum'];
				echo '</td>';
			}
			


			echo '</tr>';

		}
		echo '</table>';
	}
	
}

function showSatTable($spro_id){
	global $db;

	$sql="SELECT * from sat where spro_id= '$spro_id'";
	$result = $db->query($sql);
	if ($result->num_rows == 0) {
		$add_student_link='<a href="set_student.php?spro_id='.$spro_id.'">'.lang('add_student').'</a>';
		echoRed("您课程中没有学生, 请 $add_student_link");
		die();
	} else {
		$array_spro=getArrayFromEntry('spro','spro_id',$spro_id);
		$year=$array_spro['year'];
		$term=$array_spro['term'];
		$course_name=$array_spro['course_name'];
		$save_time=$array_spro['save_time'];
		echo '<p>'.s($year).'学年'.s($term).'学期'.s($course_name).'课的点名情况如下所示';
		//echo '<p>(此课程年级为'.s($stu_grade).'，专业为'.s($stu_major).'，最后更新时间为'.s($last_update).')：</p>';
		echo '(结课时间'.s($last_update).')：</p>';
		echo '<table class="table-bordered" id="tablesort">';

		echo '<thead><tr>';
		echo '<th>';
		echo '学号';
		echo '</th>';

		echo '<th class="no-sort">';
		echo '姓名';
		echo '</th>';

		echo '<th>';
		echo '旷课次数';
		echo '</th>';
		echo '</tr></thead>';

		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$stu_name=getStuName($row['stu_id']);
			echo '<tr>';

			echo '<td>';
			echo $row['stu_id'];
			echo '</td>';

			echo '<td>';
			echo $stu_name;
			echo '</td>';

			echo '<td>';
			echo $row['no_sum'];
			echo '</td>';


			echo '</tr>';

		}
		echo '</table>';
	}
	
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


function delStudentFromCourse($pro_id,$stu_array){
	foreach ($stu_array as $key => $value) {			
		if (isStudentAddedToCourse($pro_id,$value)) {
			$stu_id = $value;
			$sql_delete="DELETE FROM attend where pro_id = '$pro_id' and stu_id = '$stu_id'";
			//noise($sql_delete);
			deleteOne($sql_delete,1);				
		}		
	}
}


function addStudentToCourse($pro_id,$stu_array){
	foreach ($stu_array as $key => $value) {			
		if (isStudentAddedToCourse($pro_id,$value)) {				
		}else{
			$stu_id = $value;
			$sql_insert="INSERT into attend(pro_id,stu_id,no_sum) values('$pro_id','$stu_id',0)";
			//noise($sql_insert);
			insertOne($sql_insert,1);
		}			
	}
}

// why i wrote this func ? 
function isEqual($var,$str){
	if ($var == $str) {
		return 1;
	}
	return 0;
}


function buildFilterStuSql($condition){
	$dep=isset($condition['stu_dep'])?$condition['stu_dep']:'all';
	$major=isset($condition['stu_major'])?$condition['stu_major']:'all';
	$grade=isset($condition['stu_grade'])?$condition['stu_grade']:'all';
	$id=isset($condition['stu_id'])?$condition['stu_id']:'';

	$stu_dep_con=isEqual($dep,'all')?'':" and stu_dep='".$dep."'";
	$stu_major_con=isEqual($major,'all')?'':" and stu_major='".$major."'";
	$stu_grade_con=isEqual($grade,'all')?'':" and stu_grade='".$grade."'";
	$stu_id_con=" and stu_id LIKE '%".$id."%'";

	$sql="SELECT * FROM student WHERE 1 ".$stu_dep_con.$stu_major_con.$stu_grade_con.$stu_id_con;
	noise($sql);

	return $sql;
}


function makeFormForAddStudent($pro_id,$condition=NULL){
	global $db;

	if ($condition==NULL) {
		$sql="SELECT * FROM student";
		echo '<span>所有学生：</span>';
	}else{		
		$sql=buildFilterStuSql($condition);
		echo '<span>过滤结果：</span>';
	}
	echo '<form id="add_form">';
	echo '<table class="table-bordered">';	
	$result = $db->query($sql) or die($db->error);
	if ($result->num_rows == 0) {
		echoRed('结果为空，请重新设置过滤条件！');
		die();
	} else {		
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			echo '<tr>';
			$stu_id=$row['stu_id'];
			$isDisable=isStudentAddedToCourse($pro_id,$stu_id)?'disabled checked':'class="add_check_box"';
				echo '<td>'.'<input type="checkbox" name="stu_id[]" value="'.$stu_id.'" '.$isDisable.'>'.$stu_id.'</td>';
				echo '<td>'.$row['stu_name'].'</td>';
			echo '</tr>'; 
		}		
	}
	echo '</table>';
	echo getInput('pro_id',$pro_id,1,1);
	echo '<input type="checkbox" id="add_check_all"/>全选';
	echo '</form>';
	
	echo '<button onclick="add(this.value)" value="'.$pro_id.'" class="btn-success">添加</button>';
}

function makeFormForDelStudent($pro_id){
	global $db;

	echo '<p>已添加到此课程的学生：</p>';
	echo '<form id="del_form">';
	echo '<table class="table-bordered">';
	$sql="SELECT * FROM attend where pro_id= '$pro_id' ";
	$result = $db->query($sql) or die($db->error);
	if ($result->num_rows == 0) {
		echoRed('您课程中没有学生，请在左侧添加！');
		//die();
	} else {		
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			echo '<tr>';
			$stu_id=$row['stu_id'];
			$stu_name=getStuName($stu_id);
				echo '<td>'.'<input type="checkbox" name="stu_id[]" value="'.$stu_id.'" class="del_check_box" >'.$stu_id.'</td>';
				echo '<td>'.$stu_name.'</td>';
			echo '</tr>'; 
		}
		echo '</table>';
		echo getInput('pro_id',$pro_id,1,1);
		echo '<input type="checkbox" id="del_check_all"/>全选';
		echo '</form>';

		echo '<button onclick="del()" value="" class="btn-danger">删除</button>';		
	}
	
}



function showStudentInCourse($pro_id){
	global $db;

	echo '<table class="table-bordered">';
	$sql="SELECT * FROM attend where pro_id= '$pro_id' ";
	echoTableHead('student','SELECT * from student',0);
	$result = $db->query($sql) or die($db->error);
	if ($result->num_rows == 0) {
		echoRed('您课程中没有学生，请在左侧添加！');
		//die();
	} else {		
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			echo '<tr>';
			$stu_id=$row['stu_id'];
			$stu_array=getArrayFromEntry('student','stu_id',$stu_id);

				echo '<td>'.$stu_id.'</td>';
				echo '<td>'.$stu_array['stu_name'].'</td>';
				echo '<td>'.$stu_array['stu_sex'].'</td>';
				echo '<td>'.$stu_array['stu_grade'].'</td>';
				echo '<td>'.$stu_array['stu_dep'].'</td>';
				echo '<td>'.$stu_array['stu_major'].'</td>';
				echo '<td>'.$stu_array['stu_class'].'</td>';
			
			echo '</tr>'; 
		}			
	}
	echo '</table>';
}

function isStudentAddedToCourse($pro_id,$stu_id){
	global $db;
	$sql="SELECT stu_id FROM attend where pro_id= '$pro_id' and stu_id = '$stu_id' ";
	$result = $db->query($sql) or die($db->error);
	if ($result->num_rows == 0) {
		return 0;
	} else {		
		return 1;
	}
}


function insertOne($sql,$ignore_msg = 0){
	global $db;
	$result = $db->query($sql) or die($db->error);

	if($ignore_msg == 0){
		$dev_sql_content=(DEV_MODE == 1)?"($sql)":'';
		echoGreen('Insert success!'.$dev_sql_content); 
	}
}

function deleteOne($sql,$ignore_msg = 0){
	global $db;
	$result = $db->query($sql) or die($db->error);
	
	if($ignore_msg == 0){
		$dev_sql_content=(DEV_MODE == 1)?"($sql)":'';
		echoGreen('Delete success!'.$dev_sql_content); 
	}
}

function updateOne($sql,$ignore_msg = 0){
	global $db;
	$result = $db->query($sql) or die($db->error);
	
	if($ignore_msg == 0){
		$dev_sql_content=(DEV_MODE == 1)?"($sql)":'';
		echoGreen('Update success!'.$dev_sql_content); 
	}
}

function isProHasStudent($pro_id){
	global $db;

	$sql="SELECT stu_id FROM attend where pro_id = '$pro_id'";
	$result = $db->query($sql) or die($db->error);
	if ($result->num_rows == 0) {
		return 0;
	} else {		
		return 1;
	}
}

function echoAddStudentOrSet($pro_id){
	global $db;

	$sql="SELECT stu_id FROM attend where pro_id= '$pro_id'";
	$result = $db->query($sql) or die($db->error);
	if ($result->num_rows == 0) {
		echo lang('add_student');
	} else {		
		echo lang('set_student');
	}
}

// a compromise func
function echoButtonIfAddedStudent($pro_id,$pro_id_value){
	global $db;

	$sql="SELECT stu_id FROM attend where pro_id= '$pro_id_value'";
	//noise($sql);
	$result = $db->query($sql) or die($db->error);
	if ($result->num_rows == 0) {
		echo '<a href="add_main.php?'.$pro_id.'='.$pro_id_value.'">'.lang('add_at').'</a>';
		echo '&nbsp;';

		echo '<a href="show_at.php?'.$pro_id.'='.$pro_id_value.'">'.lang('show_at').'</a>';
		echo '&nbsp;';
	} else {		
		echo '<a href="add_main.php?'.$pro_id.'='.$pro_id_value.'">'.lang('add_at').'</a>';
		echo '&nbsp;';

		echo '<a href="show_at.php?'.$pro_id.'='.$pro_id_value.'">'.lang('show_at').'</a>';
		echo '&nbsp;';
	}
}





// vital, but forget what means
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
			echoRed('check data.json file');
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






function echoRed($str){
	echo '<span style="color:red;">'.$str.'</span>';
}

function echoGreen($str,$is_block = 0){
	if ($is_block == 0) {
		echo '<span style="color:green;">'.$str.'</span>';
	}elseif ($is_block == 1) {
		echo '<div style="color:green;">'.$str.'</div>';
	}
	
}


// which page use this?
function makeHideItem($info){
	echo '<p class="h">'.$info.'</p>';
}

function echoLink($link,$text){
	echo '<a href="'.$link.'">'.$text.'</a>';
}


function getArrayFromJsonFile(){

	$str = file_get_contents('data.json');
	$array=json_decode($str, true);
	//noise($array);
	//dev_dump($array);

	if(is_array($array)){
		return $array;
	}else{
		echoRed('can not read json file, please check file exists, or file format is correct');
		return ;
	}
	
}

// which page use this?
function getValueFromJsonFile($key1,$key2,$ignore_no_value = 0){


	$str = file_get_contents('data.json');
	$array=json_decode($str, true);

	if(!is_array($array)){
		echoRed('can not read json file, please check file exists, or file format is correct');
		return ;
	}


	if(isset($array[$key1][$key2])){	
		return $array[$key1][$key2];
	}
	else{
		if ($ignore_no_value == 0) {
			echoRed('this value not set');
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
		echoRed('can not read json file, please check file exists, or file format is correct');
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
			echoRed('this value not set');
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
			if(DEV_MODE == 1){
				return $en.red('<-this value not set');
			}
			return $en;	
		}	
	 	return $text;
	}

	//************ wait for add gm , maybe no gm ~
	// else(){
	// 	$array_index=LANG - 1;
	// 	$text = getJsonData('lang',$en,LANG);
	// 	return $text;
	// }
	
	
}


function echoDiv($str){
	echo '<div>'.$str.'</div>';
}


function showProStatic($pro_id){
	echo '该班级旷课总人数:';
	$a=getOneResultByOneQuery("select count(*) from attend where pro_id = '$pro_id' and no_sum != 0");
	echo $a;

	echo '<br>旷课总次数:';
	$b=getOneResultByOneQuery("select sum(no_sum) from attend where pro_id = '$pro_id' ");
	echo $b;

	echo '<br>取消考试资格人数:';
	$c=getOneResultByOneQuery("select count(*) from attend where pro_id = '$pro_id' and no_sum >= (

select hour/2/3 from project where pro_id = '$pro_id' 

)");
	echo $c;

	echo '<br>取消考试资格人数所占比例:';
	$d=getOneResultByOneQuery("select  count(*) from attend where pro_id = '$pro_id' ");
	echo $d.'%';

}

function isReachCancelExam($pro_id,$stu_id){
	$a=getOneResultByOneQuery("select count(*) from attend where pro_id = '$pro_id' and stu_id='$stu_id' and no_sum >= (

		select hour/2/3 from project where pro_id = '$pro_id' 

		)");

	//noise($a);

	if ($a=='1') {
		$reached=1;
	}else{
		$reached=0;
	}

	return $reached;

}












































//---------------  global dev code ,reserve in production environment-------------




function noise($var,$var_name=''){
	if(DEV_MODE == 0){
		return ;
	}
	echo '<div> >>>>>>>>> '.__FUNCTION__.'('.$var_name.')'.'<br>';
	echo '<span style="color:red;">'.$var.'</span>';
	echo '<br> <<<<<<<<< '.__FUNCTION__.'('.$var_name.')'.'</div>';
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

function dev_dump($var,$var_name=''){
	if(DEV_MODE == 0){
		return ;
	}
	echo '<div><span>  >>>>>>>> '.__FUNCTION__.'('.$var_name.')';
	echo '<pre>' . var_export($var, true) . '</pre>';
	echo ' <<<<<<<<< '.__FUNCTION__.'('.$var_name.')</span></div>';

}





