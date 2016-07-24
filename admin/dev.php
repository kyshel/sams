<?php
require_once("header.php");

dev_var_dump('get');


if(isset($_GET['op'])){
	if ($_GET['op'] == 'del') {
		if(isset($_GET['go_id'])){
			echo $_GET['op'].$_GET['go_id'];

			$go_id=$_GET['go_id'];
			del_go($go_id);
			del_at_with_go_id($go_id);
		}
	}
}









$i = 0;
$php_self=php_self();
echo "<table class='table-bordered'>";

$sql="SELECT * 
from go";
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
			echo '<a href="'.$php_self.'?op=del&go_id='.$row['go_id'].'">del</a>';
			echo "</td>" ;
		echo "</tr>";
		$i=1;
	}
}

echo '</table>';