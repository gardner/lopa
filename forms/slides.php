	<div id="slides">
<?php
	$files = glob(dirname(__FILE__) . '/../img/cats/*.{jpg,png,gif}', GLOB_BRACE);
	foreach($files as $file) {
  	  echo "\t\t<img width=\"450\" src=\"/lopa/img/cats/" . basename($file) . "\" />\n";
	}
?>
	</div>

	<script type="text/javascript">
		$.fn.cycle.defaults.speed   = 900;
		$.fn.cycle.defaults.timeout = 6000;
		$('#slides').cycle();
	</script>
