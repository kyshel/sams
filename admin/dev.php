<?php
require_once("header.php");

//dev_var_dump('get');

// echoSelectList('stu_grade','student');
// echoSelectList('stu_major','student');
// echoSelectList('course_code','course');
// echo '<input type="submit" value="save">';
?>




<?php



// $sql="INSERT INTO `go`(`pro_id`, `go_time`, `go_meta`) VALUES ('100','1','1')";

// $result = $db->query($sql) or die($db->error);

// if ($result == 1) {
// 	echo "<h5>insert success!</h5>";
// 	//echo '<a href="'.$php_self.'">cancel</a>';
// }




echo getNowTime();



?>

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
