<?php
require_once("header.php");

//dev_var_dump('get');

// echoSelectList('stu_grade','student');
// echoSelectList('stu_major','student');
// echoSelectList('course_code','course');
// echo '<input type="submit" value="save">';
?>
<link href="css/bootstrap-switch.min.css" rel="stylesheet">
<script src="js/bootstrap-switch.min.js"></script>


<form data-toggle="validator" role="form">



  <div class="form-group has-feedback">  
    <input type="text" class="form-control" pattern="\d{10}" data-error="格式错误" required>
    
    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
    <div class="help-block with-errors">十位数</div>
  </div>


 
  <div class="form-group">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</form>




<?php


die();

//160815,19:43:14

$a=array (
  'course_id' => 'B03051100',
  'year' => '2016-2017',
  'term' => '1',
  'hour' => '32',
  'stu_grade' => '不分年级',
  'stu_major' => '不分专业',
  'tea_id' => '1'
);

$b_n='aaaaaaaa';

$b=array();
$b['course_name']=$b_n;




// $b=array (
//   'course_name' => '11111111111',

//   'tea_name' => '22222'
// );



$c=array_merge($a, $b);

dev_dump($c);

die();

//160814,11:06:56

isProHasStudent(5)?:die('');

die();



//160813,16:56:42

$a=NULL;
$b='';
$c=0;

if (isset($a)) {
  echo 'a is set<br>';
}

if (isset($b)) {
  echo 'b is set<br>';
}

if (empty($a)) {
  echo 'a is empty<br>';
}
if (empty($b)) {
  echo 'b is empty<br>';
}
if (empty($c)) {
  echo 'c is empty<br>';
}

if ($a != NULL ) {
  echo 'a is not equal to NULL<br>';
}
if ($b != NULL ) {
  echo 'b is not equal to NULL<br>';
}


die();












//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>160812,21:37:29

//getTableDetail('student','stu_id');
//getTableColumnName('student');
getArrayFromEntry('student','stu_id','1023010115');








die();

//echoSelectForAddedGo('a','SELECT distinct stu_grade,stu_major,course_id from go');

//>>>>>>>>>>>160729,10:17:38
echo "<br>";
getProDetail(1,$stu_grade,$stu_major,$course_id);
$sql="SELECT DISTINCT go_time FROM go WHERE
 stu_grade='$stu_grade' and stu_major='$stu_major' and course_id='$course_id' ";
 echo $sql; 






//********get json data 160728,20:56:02
echo "<br>";
echo getJsonData('_comment2');
echo "<br>";
echo getJsonData('go','go_id');
echo "<br>";
echo getJsonData('lang','index');
echo "<br>";
//!!!!!!!!!!!!!!!!bug!!!!!!!!!!!!!!!
//echo getJsonData('lang','index',2);
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
echo "<br>";


//var_dump(getJsonData('cn','index',1));

//echo en2cn('add');

// $str = file_get_contents('data.json');
// $array=json_decode($str, true);
// //noise($array);
// noise($array['cn']['index'][0]);


echo lang('manage_stu');
//echo getJsonData('lang','add');







//updateAt();

//editAt();

// $a='66';

// echo '<br>';
// echo 'this is a'.qq($a).' test';

// echo '<br>';
// echo 'this is a'.ww($a).' test';





// $a=getOneResultByOneQuery("SELECT at_yes from attend where stu_id = '2308020101' and go_id = '22'");
// noise($a);


// $sql="INSERT INTO `go`(`pro_id`, `go_time`, `go_meta`) VALUES ('100','1','1')";

// $result = $db->query($sql) or die($db->error);

// if ($result == 1) {
// 	echo "<h5>insert success!</h5>";
// 	//echo '<a href="'.$php_self.'">cancel</a>';
// }

//echo getNowTime();

//makeAnInput('a','',1,1);

// $a=getPrimaryKeyName('project');
// noise($a);



//dev_var_dump('post');
//inputNewPro('project');

//paintResult('1');

?>

<script type="text/javascript">
  $('[type="checkbox"]').bootstrapSwitch();
</script>






















<div class="modal"><!-- Place at bottom of page --></div>
<style type="text/css">
/* Start by setting display:none to make this hidden.
   Then we position it in relation to the viewport window
   with position:fixed. Width, height, top and left speak
   for themselves. Background we set to 80% white with
   our animation centered, and no-repeating */
.modal {
    display:    none;
    position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url('http://i.stack.imgur.com/FhHRx.gif') 
                50% 50% 
                no-repeat;
}

/* When the body has the loading class, we turn
   the scrollbar off with overflow:hidden */
body.loading {
    overflow: hidden;   
}

/* Anytime the body has the loading class, our
   modal element will be visible */
body.loading .modal {
    display: block;
}	
</style>

<script type="text/javascript">
	$body = $("body");

$(document).on({
    ajaxStart: function() { $body.addClass("loading");    },
     ajaxStop: function() { $body.removeClass("loading"); }    
});
</script>



