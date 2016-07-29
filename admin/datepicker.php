<input type="text" id="datepicker" name="date" value="<?php echo date("Y-m-d");?>">

<script id="dp_run">
var active_dates = [
<?php
/* 
//Below is format
"2016-04-10",
"2016-04-17",
*/
//must has the db connection before

$sql="SELECT DISTINCT go_time FROM go WHERE
 stu_grade='$stu_grade' and stu_major='$stu_major' and course_id='$course_id' ";

$result = $db->query($sql) or die($db->error);
while($row = $result->fetch_array(MYSQLI_ASSOC)){
        echo '"' .$row['go_time'].'",' ;
}
?>
];


$('#datepicker').datepicker({
	format: "yyyy-mm-dd",
	autoclose: true,
     /*todayHighlight: true,*/

// >>>>>>>>>>>>>Below is tomorrow code
//      endDate: "<?php $datetime = new DateTime('tomorrow');
// echo $datetime->format('Y-m-d');?>",

endDate: "<?php echo date("Y-m-d");?>",


beforeShowDay: function(date){
	var d = date;
	var curr_date = d.getDate();
         var curr_month = d.getMonth() + 1; //Months are zero based
         if(curr_month<10){
         	curr_month="0"+curr_month
         }
         if(curr_date<10){
         	curr_date="0"+curr_date
         }
         var curr_year = d.getFullYear();
         var formattedDate = curr_year + "-" + curr_month + "-" + curr_date

         if ($.inArray(formattedDate, active_dates) != -1){
         	return {
         		classes: 'activeClass'
         	};
         }
         return;

     }

 });




</script>


<style type="text/css">
	.activeClass{
		background: #5CB85C; 
	}
</style>