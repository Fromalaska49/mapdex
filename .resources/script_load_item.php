<?php
	$protocol = 'http://';
	if(isset($_SERVER['HTTPS']) && strlen($_SERVER['HTTPS']) > 0){
		$protocol = 'https://';
	}
	$domain = $_SERVER['HTTP_HOST'];
	$path_get_urlencoded = $protocol.$domain.'/.resources/script_load_item.php?var=false';
	//$path_html = '/<a href="'.$path_get_urlencoded.'">localhost</a>/';
	$url_path_array = array();
	$url_path_array[0] = '';
	for($level = 1; isset($_GET['l'.$level]) && !empty($_GET['l'.$level]); $level++){
		$directory = rawurldecode($_GET['l'.$level]);
		$url_path_array[$level] = $directory;
		//$url_path .= rawurlencode($directory).'/';
		$path_get_urlencoded .= '&l'.$level.'='.rawurlencode($directory);
		//$path_html .= '<a href="'.$path_get_urlencoded.'">'.htmlentities($directory).'</a>/';
	}
	//echo('<h1>'.$path_html.'</h1>');
	$cwd = dirname(getcwd());
	$max_level = $level;
	for($level = 0; $level < $max_level; $level++){
		$cwd .= $url_path_array[$level].'/';
		//echo($cwd.'<br />');
		$item = scandir($cwd, SCANDIR_SORT_ASCENDING);
		$sizeof_item = sizeof($item);
		if($level == $max_level - 1){
			for($i = 0; $i < $sizeof_item; $i++){
				$current_item = $cwd.$item[$i];
				$item_record_class = 'item_record_inactive';
				if($level < $max_level -1){
					if($url_path_array[$level+1] == $item[$i]){
						$item_record_class = 'item_record_active';
					}
				}
				if(substr($item[$i], 0, 1) == '.'){
					//invisible file found
				}
				else if(filetype($current_item) == 'file'){
					//file found
					echo('<a href="'.$path_get_urlencoded.rawurlencode($item[$i]).'" class="item_link file_link"><li id="record_'.$level.'_'.$i.'" class="'.$item_record_class.'"><img src=".resources/img/icons/SidebarGenericFile.png" class="item_record_icon" /><div class="item_record_name">'.htmlentities($item[$i]).'</div><div class="item_record_time">'.date('M n, Y, g:i A', filemtime($current_item)).'</div></li></a>');
				}
				else if(filetype($current_item) == 'dir'){
					//directory found
					echo('<a href="'.$path_get_urlencoded.'&l'.($level+1).'='.rawurlencode($item[$i]).'" class="item_link directory_link"><li id="record_'.$level.'_'.$i.'" class="'.$item_record_class.'"><img src=".resources/img/icons/SidebarGenericFolder.png" class="item_record_icon" /><div class="item_record_name">'.htmlentities($item[$i]).'</div><div class="item_record_time">'.date('M n, Y, g:i A', filemtime($current_item)).'</div></li></a>');
				}
				else{
					//unkown item found
					//echo('<li class="item_record"><div class="item_record_name">Error: cannot detect filetype of <a href="'.$protocol.$domain.'/'.rawurlencode($item[$i]).'">'.htmlentities($item[$i]).'</a></div></li>');
				}
			}
		}
	}
?>