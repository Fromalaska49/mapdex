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
		$time_modified = filemtime($target);
		$date_modified = '';
		$time = time();
		if($time - $time_modified < ($time % 86400) + 86400){
			if(($time % 86400) > ($time_modified % 86400) && ($time - $time_modified) < 86400){
				$date_modified = date('g:i a', $time_modified);
			}
			else{
				$date_modified = 'Yesterday';
			}
		}
		echo('<a href="'.$protocol.$domain.implode('/', explode('/', dirname($_SERVER['PHP_SELF']), -2)).$path.'" class="item_link file_link"><img src=".resources/img/icons/SidebarGenericFile.png" class="item_record_icon" style="width:200px;height:auto;" /><br /><div class="item_record_name">'.htmlentities($path_files[$path_len - 1]).'</div><br /><div class="item_record_time">'.$date_modified.'</div></a>');
	}
	else if(filetype($target) == 'dir'){
		//directory found
		
		$item = scandir($target, SCANDIR_SORT_ASCENDING);
		$sizeof_item = sizeof($item);
		for($i = 0; $i < $sizeof_item; $i++){
			$current_item = $target.'/'.$item[$i];
			$current_path = $path.'/'.$item[$i];
			$item_record_class = 'item_record_inactive';
			/*
			if($level < $max_level -1){
				if($url_path_array[$level+1] == $item[$i]){
					$item_record_class = 'item_record_active';
				}
			}
			*/
			$time_modified = filemtime($current_item);
			$date_modified = '';
			$time = time();
			if($time - $time_modified < ($time % 86400) + 86400){
				if(($time % 86400) > ($time_modified % 86400) && ($time - $time_modified) < 86400){
					$date_modified = date('g:i a', $time_modified);
				}
				else{
					$date_modified = 'Yesterday';
				}
			}
			else{
				$date_modified = date('M j', $time_modified);
			}
			if(substr($item[$i], 0, 1) == '.'){
				//invisible item found
			}
			else if(filetype($current_item) == 'file'){
				//file found
				echo('<li id="'.$current_path.'" class="item_link file_link '.$item_record_class.'"><img src=".resources/img/icons/SidebarGenericFile.png" class="item_record_icon" /><div class="item_record_name">'.htmlentities($item[$i]).'</div><div class="item_record_time">'.$date_modified.'</div></li>');
			}
			else if(filetype($current_item) == 'dir'){
				//directory found
				echo('<li id="'.$current_path.'" class="item_link directory_link '.$item_record_class.'"><img src=".resources/img/icons/SidebarGenericFolder.png" class="item_record_icon" /><div class="item_record_name">'.htmlentities($item[$i]).'</div><div class="item_record_time">'.$date_modified.'</div></li>');
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