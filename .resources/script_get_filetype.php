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
	$is_file = 0;
	$mime_type_index = 0;
	if(filetype($target) == 'file'){
		$is_file = 1;
		//file found
		$mime_array = explode('/', mime_content_type($target));
		$file_type = $mime_array[0];
		/*
			application		328
			audio			66
			chemical		2
			drawing			1
			image			70
			i-world			1
			message			3
			model			7
			multipart		3
			music			3
			paleovu			1
			text			99
			video			46
			windows			1
			xgl				2
			x-conference	1
			x-world			9
			www				1
		*/
		$known_types = array(
			'application',
			'audio',
			'chemical',
			'drawing',
			'image',
			'i-world',
			'message',
			'model',
			'multipart',
			'music',
			'paleovu',
			'text',
			'video',
			'windows',
			'xgl',
			'x-conference',
			'x-world',
			'www'
		);
		if(in_array($file_type, $known_types)){
			if($file_type == 'image'){
				$mime_type_index = 1;
			}
			else if($file_type == 'video'){
				$mime_type_index = 2;
			}
			else if($file_type == 'audio' || $file_type == 'music'){
				$mime_type_index = 3;
			}
			else if($file_type == 'text'){
				$mime_type_index = 4;
			}
			else if($file_type == 'multipart'){
				$mime_type_index = 5;
			}
			else if($file_type == 'application'){
				$mime_type_index = 6;
			}
			else{
				//unsupported type found
				$mime_type_index = 0;
			}
		}
		else if(empty($file_type)){
			//no type given
			$mime_type_index = 0;
		}
		else{
			//unkown type found
			$mime_type_index = 0;
		}
		echo($file_type);
	}
	else if(filetype($target) == 'dir'){
		//dir found
		$is_file = 0;
	}
	else{
		//undefined item found
		$is_file = 1;
	}
	echo($is_file . ':' . $mime_type_index);
?>