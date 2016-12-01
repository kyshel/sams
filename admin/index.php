<?php
require_once("header.php");
?>




<div class="panel panel-default"> 
	<div class="panel-heading"> 
		<h3 class="panel-title">Welcome</h3> 
	</div> 
	<div class="panel-body" >
		<div id="pad" class="markdown-body"></div>
	</div> 
</div>



<script type="text/javascript">
function readTextFile(file)
{
	var rawFile = new XMLHttpRequest();
	rawFile.open("GET", file, false);
	rawFile.onreadystatechange = function ()
	{
		if(rawFile.readyState === 4)
		{
			if(rawFile.status === 200 || rawFile.status == 0)
			{
				var allText = rawFile.responseText;
				//below is showdown work
				var converter = new showdown.Converter(),
				text      = allText,
				html      = converter.makeHtml(text);
				document.getElementById('pad').innerHTML=html;
			}
		}
	}
	rawFile.send(null);
}
readTextFile("index.md");
</script>






<?php

$tip ='no tip';

makeHideItem('$tip');


































?>

<?php
//require_once("footer.php");
?>


