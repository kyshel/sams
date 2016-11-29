<?php
require_once("auth.php"); // include functions.php
?>

<?php

// pretty
function getPre($str){
	return '<pre>'.$str.'</pre>';
}

// ensure result.json is writeable
// why make json to a file ?
// just keep json readable,touchable,persistent
// result.json can be seen a middle ware
// it connect php and javascript
function makeJson(){
	global $db;

	$project = array();
	$attend = array();
	$response = array();

	$sql="SELECT * from project";


	$result = $db->query($sql);
	if ($result->num_rows == 0) {
		echo "table project is empty!";
    } else {
		while($row = $result->fetch_array(MYSQLI_ASSOC)){

			$pro_id = $row['pro_id'];
			$sql2 = "SELECT * from attend WHERE pro_id = ".$pro_id;
			$result2 = $db->query($sql2);
			if ($result2->num_rows == 0) {
				echo "table attend is empty!";
			} else {
				while($row2 = $result2->fetch_array(MYSQLI_ASSOC)){
					$attend[] = array(
						'at_id' => $row2['at_id'],
						'stu_id' => $row2['stu_id'],
						'stu_name' => getStuName($row2['stu_id']),
						'no_sum' => $row2['no_sum']
					);
				}
			}

			$project[] = array(
				'pro_id' => $row['pro_id'], 
				'year' => $row['year'],
				'term' => $row['term'],
				'course_id' => $row['course_id'],
				'course_name' => $row['course_name'],
				'stu_grade' => $row['stu_grade'],
				'stu_major' => $row['stu_major'],
				'tea_id' => $row['tea_id'],
				'tea_name' => $row['tea_name'],
				'hour' => $row['hour'],
				'last_update' => $row['last_update'],
				'status' => $row['status'],
				'off_time' => $row['off_time'],
				'attend' => $attend
			);

			$attend = array(); // empty attend
		}
	}

	$response['time'] = getNowTime() ;
	$response['info'] = 'this is info';
	$response['data'] = $project ;

	$fp = fopen('result.json', 'w');
	$json = json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	//echo getPre($json);
	fwrite($fp, $json);
	fclose($fp);
}


makeJson(); // silent, or next won't render
require_once("render_static.html");














?>