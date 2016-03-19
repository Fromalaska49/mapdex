<?php
$protocol = 'http://';
if(isset($_SERVER['HTTPS']) && strlen($_SERVER['HTTPS']) > 0){
	$protocol = 'https://';
}
$domain = $_SERVER['HTTP_HOST'];
$path_get_urlencoded = $protocol.$domain.'/.resources/script_load_item.php?var=false';
$path_html = '/<a href="'.$path_get_urlencoded.'">localhost</a>/';
$url_path_array = array();
$url_path_array[0] = '';
for($level = 1; isset($_GET['l'.$level]) && !empty($_GET['l'.$level]); $level++){
	$directory = rawurldecode($_GET['l'.$level]);
	$url_path_array[$level] = $directory;
	//$url_path .= rawurlencode($directory).'/';
	$path_get_urlencoded .= '&l'.$level.'='.rawurlencode($directory);
	$path_html .= '<a href="'.$path_get_urlencoded.'">'.htmlentities($directory).'</a>/';
}
$cwd = getcwd();
$max_level = $level;
?>
<html>
	<head>
		<style type="text/css">
			body{
				font-family:arial;
			}
			.item_record_inactive{
				list-style-type:none;
				padding:5px;
				text-align:left;
				background-color:#FFFFFF;
				color:#000000;
			}
			.item_record_active{
				list-style-type:none;
				padding:5px;
				text-align:left;
				background-color:#003399;
				color:#FFFFFF;
			}
			.item_record_icon{
				width:28px;
				height:28px;
				display:inline-block;
				border-style:none;
				margin:0px 5px 0px 0px;
				vertical-align:bottom;
			}
			.item_record_name{
				height:18px;
				padding:6px;
				font-size:16px;
				display:inline-block;
				margin:0px;
				width:400px;
				overflow:hidden;
			}
			.item_record_time{
				height:18px;
				padding:6px;
				font-size:16px;
				display:inline-block;
				margin:0px;
				width:200px;
				overflow:hidden;
			}
			a.item_link, a.item_link:hover, a.item_link:visited{
				color:black;
				text-decoration:none;
			}
			.directory_container{
				position:absolute;
				top:100px;
				bottom:0px;
				width:800px;
				border-style:solid;
				border-color:#CCCCCC;
				border-width:1px;
				overflow-y:scroll;
				overflow-x:hidden;
			}
		</style>
		<script src=".resources/jquery-2.2.0.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				window.active_record_id;
				window.max_level = <?php echo($max_level); ?>;
				$('body').on('click','#a.link',function(e){
					e.preventDefault()
				});
				$("a.item_link").on("click", function(){
					/*
					if($(this).hasClass("file_link")){
						event.preventDefault();
					}
					*/
					event.preventDefault();
					//else{
						$.ajax({
							"method": "GET",
							"url": $(this).attr("href"),
						}).done(function(contents){
							var $ul = $("<ul>", {
								"id": "l"+window.max_level,
								"class": "directory_container",
								"style": "left:"+window.max_level*800+"px;",
							});
							$ul.append(contents);
							$("#filesystem_container").append($ul);
							window.max_level++;
							
						});
					//}
				});
				$("a.item_link").on("dblclick", function(){
					window.location.assign($(this).attr("href"));
				});
				$(".item_record_inactive").on("mousedown",function(){
					$("#"+window.active_record_id).removeClass("item_record_active");
					$("#"+window.active_record_id).addClass("item_record_inactive");
					$(this).addClass("item_record_active");
					$(this).removeClass("item_record_inactive");
					window.active_record_id = this.id;
				});
			});
		</script>
	</head>
	<body>
		<?php
		echo('<h1>'.$path_html.'</h1>');
		for($level = 0; $level < $max_level; $level++){
			$cwd .= $url_path_array[$level].'/';
			echo($cwd.'<br />');
			$item = scandir($cwd, SCANDIR_SORT_ASCENDING);
			$sizeof_item = sizeof($item);
			echo('<div id="filesystem_container">');
			echo('<ul class="directory_container" style="left:'.($level*800).'px;">');
			for($i = 0; $i < $sizeof_item; $i++){
				$current_item = $cwd.$item[$i];
				$item_record_class = 'item_record_inactive';
				if($level < $max_level -1){
					if($url_path_array[$level+1] == $item[$i]){
						$item_record_class = 'item_record_active';
					}
				}
				if(substr($item[$i], 0, 1) == '.'){
					//invisible item found
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
			echo('</ul>');
			echo('</div>');
		}
		?>
	</body>
</html>