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
	$cwd = dirname(getcwd());
	/*
	$item = scandir($cwd, SCANDIR_SORT_ASCENDING);
	$sizeof_item = sizeof($item);
	$current_item = $cwd.$item[$i];
	$item_record_class = 'item_record_inactive';
	*/
	$target = $cwd . $path;
	echo('<!-- $target = '.$target.' -->');
	/*
	if(substr($item[$i], 0, 1) == '.'){
		//invisible file found
	}
	*/
	if(filetype($target) == 'file'){
		//file found
		echo('<a href="'.$protocol.$domain.'/'.$path.'" class="item_link file_link"><li id="record_" class="item_record_inactive"><img src=".resources/img/icons/SidebarGenericFile.png" class="item_record_icon" /><div class="item_record_name">'.htmlentities($path_files[$path_len - 1]).'</div><div class="item_record_time">'.date('M n, Y, g:i A', filemtime($target)).'</div></li></a>');
	}
	else if(filetype($target) == 'dir'){
		//directory found
		
		$item = scandir($target, SCANDIR_SORT_ASCENDING);
		$sizeof_item = sizeof($item);
		echo('<div id="filesystem_container">');
		echo('<ul class="directory_container" style="left:'.($level*800).'px;">');
		for($i = 0; $i < $sizeof_item; $i++){
			$current_item = $target.$item[$i];
			$item_record_class = 'item_record_inactive';
			/*
			if($level < $max_level -1){
				if($url_path_array[$level+1] == $item[$i]){
					$item_record_class = 'item_record_active';
				}
			}
			*/
			if(substr($item[$i], 0, 1) == '.'){
				//invisible item found
			}
			else if(filetype($current_item) == 'file'){
				//file found
				echo('<a href="'.$path.$item[$i].'" class="item_link file_link"><li id="record_'.$level.'_'.$i.'" class="'.$item_record_class.'"><img src=".resources/img/icons/SidebarGenericFile.png" class="item_record_icon" /><div class="item_record_name">'.htmlentities($item[$i]).'</div><div class="item_record_time">'.date('M n, Y, g:i A', filemtime($current_item)).'</div></li></a>');
			}
			else if(filetype($current_item) == 'dir'){
				//directory found
				echo('<a href="'.$path.$item[$i].'" class="item_link directory_link"><li id="record_'.$level.'_'.$i.'" class="'.$item_record_class.'"><img src=".resources/img/icons/SidebarGenericFolder.png" class="item_record_icon" /><div class="item_record_name">'.htmlentities($item[$i]).'</div><div class="item_record_time">'.date('M n, Y, g:i A', filemtime($current_item)).'</div></li></a>');
			}
			else{
				//unkown item found
				//echo('<li class="item_record"><div class="item_record_name">Error: cannot detect filetype of <a href="'.$protocol.$domain.'/'.rawurlencode($item[$i]).'">'.htmlentities($item[$i]).'</a></div></li>');
			}
		}
	}
	else{
		//unkown item found
		echo('<li class="item_record"><div class="item_record_name">Error: cannot detect filetype of <a href="'.$protocol.$domain.'/'.$path.'">'.htmlentities($path_files[$path_len - 1]).'</a></div></li>');
	}
?>