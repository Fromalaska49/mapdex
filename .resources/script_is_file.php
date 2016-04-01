<?php
	$path = $_POST['path'];
	$cwd = implode('/', explode('/', dirname(getcwd()), -1));
	$target = $cwd . $path;
	$is_file = 0;
	$mime_type_index = 0;
	if(filetype($target) == 'file'){
		$is_file = 1;
		//file found
	}
	else if(filetype($target) == 'dir'){
		//dir found
		$is_file = 0;
	}
	else{
		//undefined item found
		$is_file = 1;
	}
	echo($is_file);
?>