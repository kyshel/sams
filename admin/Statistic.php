<?php	

function getProjectCount() {
	global $db;
	$sql="SELECT COUNT(*) from project";
	$result = $db->query($sql)  or die($db->error);
	if ($result->num_rows == 0) {
		echo "project num is empty!";
	} else {
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$first_key_value=reset($row);
		return $first_key_value;
	}
}

