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
        $delay=3600;
        if ($_SESSION['timeout'] + $delay < time()) {
            $_SESSION = array();
            session_destroy();
            // return a little feeedback message
            //echo "<div class='alert alert-success' role='alert'>由于长时间未操作，您已退出系统，请重新登录！</div>";
            // session timed out

            header("Location: login.php?timeout");
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
     
    checked>';
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

	echo "---wait to add check code---";
}





