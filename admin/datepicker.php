<input type="text" id="datepicker" name="date" value="<?php echo date("Y-m-d");?>">

<script type="text/javascript">
	$('#datepicker').datepicker({
		format: "yyyy-mm-dd",
		autoclose: true,
	});
</script>