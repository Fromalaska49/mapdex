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
			.item_record_deactivated{
				list-style-type:none;
				padding:5px;
				text-align:left;
				background-color:#D0D0D0;
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
				width:200px;
				overflow:hidden;
			}
			.item_record_time{
				height:18px;
				padding:6px;
				font-size:16px;
				text-align:right;
				display:inline-block;
				margin:0px;
				width:100px;
				overflow:hidden;
			}
			.item_link{
				margin-left:-39px;
			}
			a.item_link, a.item_link:hover, a.item_link:visited{
				color:black;
				text-decoration:none;
			}
			.directory_container{
				position:absolute;
				top:100px;
				bottom:0px;
				width:400px;
				border-style:solid solid solid none;
				border-color:#CCCCCC;
				border-width:1px;
				overflow-y:scroll;
				overflow-x:hidden;
			}
		</style>
		<script src=".resources/jquery-2.2.0.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				window.path = '';
				window.active_record_id = "";
				window.level = 0;
				$.ajax({
					type: "POST",
					url: ".resources/script_load_item.php",
					data: {
						"path": "",
					}
				}).done(function(contents){
					var $ul = $("<ul>", {
						"id": "level-"+window.level,
						"class": "directory_container",
						"style": "left:"+window.level*800+"px;",
					});
					$ul.append(contents);
					$("#filesystem_container").append($ul);
					window.level++;
					loadItem();
				});
				/*
				function scrollToResult(){
					var options = {};
					var scrollLeft = $("#<?php echo($target_record_id); ?>-td-<?php echo($target_field_id); ?>").offset().left-(window.innerWidth+200-$("#<?php echo($target_record_id); ?>-td-<?php echo($target_field_id); ?>").width())/2;
					var scrollTop = $("#<?php echo($target_record_id); ?>-td-<?php echo($target_field_id); ?>").offset().top-(window.innerHeight+45-$("#<?php echo($target_record_id); ?>-td-<?php echo($target_field_id); ?>").height())/2;
					options["scrollTop"] = scrollTop;
					options["scrollLeft"] = scrollLeft;
					var distance=Math.pow((scrollTop*scrollTop+scrollLeft*scrollLeft),(1/2));
					duration=1000*(Math.log(distance+1097)-7);//1097 ~ e^7
					$('html, body').animate(options, duration);
				}
				*/
				function loadItem(){
					$(".item_record_inactive").off("mousedown");
					$(".item_record_active").off("mousedown");
					$(".item_record_inactive").on("mousedown",function(){
						var oldPathArray = window.active_record_id.split("/");//$(this).attr("id");
						var newPathArray = $(this).attr("id").split("/");
						var oldPath = "";
						var newPath = "";
						for(var i = 1; i < newPathArray.length; i++){
							if(i < oldPathArray.length){
								oldPath += "/" + oldPathArray[i];
							}
							newPath += "/" + newPathArray[i];
							if(oldPath != newPath){
								$("li[id='"+oldPath+"']").removeClass("item_record_active");
								$("li[id='"+oldPath+"']").removeClass("item_record_deactivated");
								$("li[id='"+oldPath+"']").addClass("item_record_inactive");
							}
						}
						//had to split into two for loops because .item_record_deactivated was being removed immediately after being added (due to a slight delay)
						newPath = "";
						for(var i = 1; i < newPathArray.length; i++){
							newPath += "/" + newPathArray[i];
							$("li[id='"+newPath+"']").removeClass("item_record_active");
							$("li[id='"+newPath+"']").removeClass("item_record_inactive");
							$("li[id='"+newPath+"']").addClass("item_record_deactivated");
						}
						$("li[id='"+newPath+"']").removeClass("item_record_deactivated");
						$("li[id='"+newPath+"']").removeClass("item_record_inactive");
						$("li[id='"+newPath+"']").addClass("item_record_active");
						window.active_record_id = $(this).attr("id");
					});
					$(".item_record_deactivated").on("mousedown",function(){
						var oldPathArray = window.active_record_id.split("/");//$(this).attr("id");
						var newPathArray = $(this).attr("id").split("/");
						var oldPath = "";
						var newPath = "";
						for(var i = 1; i < newPathArray.length; i++){
							if(i < oldPathArray.length){
								oldPath += "/" + oldPathArray[i];
							}
							newPath += "/" + newPathArray[i];
							if(oldPath != newPath){
								$("li[id='"+oldPath+"']").removeClass("item_record_active");
								$("li[id='"+oldPath+"']").removeClass("item_record_deactivated");
								$("li[id='"+oldPath+"']").addClass("item_record_inactive");
							}
						}
						//had to split into two for loops because .item_record_deactivated was being removed immediately after being added (due to a slight delay)
						newPath = "";
						for(var i = 1; i < newPathArray.length; i++){
							newPath += "/" + newPathArray[i];
							$("li[id='"+newPath+"']").removeClass("item_record_active");
							$("li[id='"+newPath+"']").removeClass("item_record_inactive");
							$("li[id='"+newPath+"']").addClass("item_record_deactivated");
						}
						$("li[id='"+newPath+"']").removeClass("item_record_deactivated");
						$("li[id='"+newPath+"']").removeClass("item_record_inactive");
						$("li[id='"+newPath+"']").addClass("item_record_active");
						window.active_record_id = $(this).attr("id");
					});
					$(".item_link").off("click");
					var newLevel = false;
					$(".item_link").on("click", function(){
						var path = $(this).attr("id");
						var pathArray = path.split("/");
						var level = pathArray.length - 1;
						if(level + 1 == window.level){
							for(var i = window.level; i > level; i--){
								$("#level-"+i).remove();
							}
							newLevel = false;
						}
						else{
							for(var i = window.level; i >= level; i--){
								$("#level-"+i).remove();
							}
							newLevel = true;
						}
						window.level = level;
						$.ajax({
							type: "POST",
							url: ".resources/script_load_item.php",
							data: {
								"path": path,
							}
						}).done(function(contents){
							if(newLevel){
								var $ul = $("<ul>", {
									"id": "level-" + window.level,
									"class": "directory_container",
									"style": "left:"+window.level*440+"px;",
								});
								$ul.append(contents);
								$("#filesystem_container").append($ul);
								$('html, body').animate({
									scrollLeft: $("#level-"+window.level).offset().left
								}, 100);
							}
							else{
								$("#level-"+window.level).html(contents);
							}
							window.level++;
							loadItem();
						});
					});
				}
				$("a.item_link").on("dblclick", function(){
					window.location.assign($(this).attr("href"));
				});
			});
		</script>
	</head>
	<body>
		<h1>
			<?php
				echo($path_html);
			?>
		</h1>
		<div id="filesystem_container">
		</div>
	</body>
</html>