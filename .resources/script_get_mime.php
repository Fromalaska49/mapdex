<?php
	$path = $_POST['path'];
	$cwd = implode('/', explode('/', dirname(getcwd()), -1));
	$target = $cwd . $path;
	$mime_type_index = 0;
	if(filetype($target) == 'file'){
		$mime_array = explode('/', mime_content_type($target));
		$file_type = $mime_array[0];
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
	}
	echo($mime_type_index);
?>