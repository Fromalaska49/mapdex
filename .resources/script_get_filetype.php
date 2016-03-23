<?php
	$path = $_POST['path'];
	$path_files = explode('/', $path);
	$path_len = count($path_files);
	$protocol = 'http://';
	if(isset($_SERVER['HTTPS']) && strlen($_SERVER['HTTPS']) > 0){
		$protocol = 'https://';
	}
	$domain = $_SERVER['HTTP_HOST'];
	$path_get_urlencoded = $protocol.$domain.'/.resources/script_load_item.php?var=false';
	//echo('<h1>'.$path_html.'</h1>');
	$cwd = implode('/', explode('/', dirname(getcwd()), -1));
	/*
	$item = scandir($cwd, SCANDIR_SORT_ASCENDING);
	$sizeof_item = sizeof($item);
	$current_item = $cwd.$item[$i];
	$item_record_class = 'item_record_inactive';
	*/
	$target = $cwd . $path;
	/*
	if(substr($item[$i], 0, 1) == '.'){
		//invisible file found
	}
	*/
	if(filetype($target) == 'file'){
		//file found
		$mime_array = explode('/', mime_content_type($target));
		$file_type = $mime_array[0];
		echo($file_type);
	}
	else if(filetype($target) == 'dir'){
		//dir found
		echo('dir');
	}
	else{
		//unkown item found
		echo('undefined');
	}
?>