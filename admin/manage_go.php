<?php
require_once("header.php");
?>

<script type="text/javascript">
$( document ).ready(function() {
    $('[type="checkbox"]').bootstrapSwitch();
});  
</script>

<?php
showGrid('go','SELECT * from go','go_id'); 

?>



