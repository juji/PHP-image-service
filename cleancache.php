<?php
	
	$d = opendir(CACHEDIR);
	while($f=readdir($d)){
		if($f=='.'||$f=='..') continue;
		unlink(CACHEDIR.$f);
	}
	
	exit(0);
	
?>

