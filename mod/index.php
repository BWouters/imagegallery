<?php
session_start();
require_once("../classes/User.class.php");
require_once("../classes/Database.class.php");

$db = new Database();
$user = new User();
include_once('../picture.php');
include('createThumb.php');


$picture = array();
$output = "";
$link = "";
$notice = "";
$counter = 0;
$dir = '../galleries';
if($user->isLoggedIn()){
	if ($handle = opendir($dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file != "index.php") {
					$picture[$counter] = new Picture($dir."/".$file);
					if(!$picture[$counter]){
						echo "Error: Picture.php is missing or has an error.";
					}
					
					if(!file_exists('../../download.visionsandviews.net/img/zip/'.$file.'.zip')){
						$link .= "<a href='zip.php?download={$file}'>Create ZIP-archive for {$file}</a><br />";
						
					}
					if(!is_dir($dir."/".$file."/thumb/")){
						$link .= "<p class='thumbs' id='".$file."'>Create thumbnails for {$dir}/{$file}</p>";
					}
					else{
						$row = $db->getTitle($file);
						foreach($row as $r){
							if(isset($r['directory_nice'])){
								$outputDir = $r['directory_nice'];
							}else{
								$outputDir = $file;
							}
						}
						$randImg = $picture[$counter]->getRandomImg();
						if(file_exists("../galleries/".$file."/thumb/tn_".$randImg)){
							$thumbImg = "./thumb/tn_".$randImg;
						}else{
							
							$thumbImg = $randImg;
						}
						$output .= "<div class='imagefield_album'>";
						$output .= "<div class='image'><a href='../galleries/".$file."'>";
						$output .= "<img src='../galleries/".$file."/".$thumbImg."' alt='thumb1' /></a></div>";
						$output .= "<div class='image_album_name'>
						<p class='tags'>Tags</p><p class='zip'>Zip directory</p>
						<p class='thumbs' id='".$file."'>Recreate thumbnails</p>							
						<p class='gallerynameUpdate'>Update filename</p>
						<p class='galleryname'>".$outputDir."</p>
						</div></div>";
					}
	
			}
			$counter++;
		}
	}
	?>
	<!DOCTYPE html>
	<html>
	  <head>
	    <meta charset="iso-8859-1">
			<title>Gallery index</title>
			<link rel="stylesheet" href="../css/gallery.css" type="text/css" media="screen" />
	        <link rel="stylesheet" href="http://rsrc.visionsandviews.net/jquery/css/custom-theme/jquery-ui-1.8.23.custom.css" media="screen" />
	        
		</head>
		<body>
			<div id="topNav">
				<p class="createNewGallery">Create new gallery</p>
	        	<p class="updateTemplate">Copy new template from /demo</p>
				<p class="uploadImages">Upload images to server</p>
			</div>
			<div id="mainContent">
				<div id="errorContent">
				<?php
				echo $notice;
				echo $error;
				?>
				</div>
				<div id="linkContent">
				
				<?php
				echo $link;
				?>
				</div>
				<div id="imageContent">
				<?php
				echo $output;
				?>
				</div>
			</div>
		</body>
	</html>
	<div id="dialog-tag" title="Add new tags">
	    <div id="tagcloud"><?php
	    $tags = $db->getAllTags();
		foreach($tags as $t){
			?>
	        <input type='checkbox' name="tags[]" value="<?php echo $t['tag_id']; ?>" id='tagid<?php echo $t['tag_id'] ?>'/><label for='tagid<?php echo $t['tag_id'] ?>'><?php echo $t['tag_name'] ?></label>
	    <?php
		}
		?>
	    </div>
		<div class="ui-widget" style="margin-top:2em; font-family:Arial">
		Result:
		<div id="log" style="height: 200px; width: 300px; overflow: auto;" class="ui-widget-content"></div>
		</div>
	</div>
	<div id="dialog-thumb" title="Creating thumbnails">
	    <p>Creating thumbnails</p>
	    <div id="message" style="display: none;"></div>
	</div>
	<div id="dialog-thumbok" title="Thumbnails created">
	    <p>Thumbnails successfully created.</p>
	    
	</div>
	<div id="dialog-updateTemplate" title="Update templates">
		<p>Updating template</p>
	</div>
	<div id="dialog-uploadImages" title="Upload images">
		<p>There are two options:</p>
		<ol><li>Upload the images using FTP</li>
			<li>Upload the images using the built-in uploader</li>
		</ol>
		<p>Once you've uploaded the images, press 'Next'</p>
	</div>
	<div id="dialog-newGallery" title="Create new gallery">
		<form class='formGallery' method="post" action='index.php?createdirectory'>
			Create new directory
			<input type="text" value="directoryname" name='newdir' />
		</form>
	</div>
	<div id="dialog-fileName" title="New filename">
		<label for="humanName">New galleryname</label><input type="text" value="" name="humanName" />
	</div>
	<div id="imageUploaderWindow" title="Upload images">
	</div>
	<div id="dialog-zipDirectory" title="Zip directory">
		<div id="progressbar"></div>
	</div>
	<script src="http://rsrc.visionsandviews.net/jquery/js/jquery-1.8.0.min.js"></script>
	<script src="http://rsrc.visionsandviews.net/jquery/js/jquery-ui-1.8.23.custom.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		var dirname;
		$('#tagcloud').buttonset();
		$('p.thumbs').click(function(){
			dirname = $(this).attr('id');
		});
		$('p.tags').click(function(){
			dirname = $(this).next('p.thumbs').attr('id');
		});
		
		$("p.zip").button().click(function(){
			$( "#progressbar" ).progressbar("destroy");
			$("#dialog-zipDirectory").dialog("open");
		       /* start DB operation */
				dirname = $(this).next('p.thumbs').attr('id');
		       	$.ajax({
		             url: "ajax/zip.php",
		             data: {"download": dirname},
		             type: "GET"
		       	});
		       /* hide link to prevent repeated click */
		       /* initial value for progressbar */
		       var progressvalue = 0;
		       /* every 2 seconds progress status will be obtained */
		      data = 0;
		       var myInterval = setInterval(function(){
		       	
		             /* get progress status */
		             $.ajax({
		             	url: "ajax/track.php",
		             	type: "GET",
		             	dataType: 'html',
		             	success: function(data) {
			             	if(data == "Close" || data == "Not closed"){
	    						/* close progressbar */
			                    /* reset interval */
			                    clearInterval(myInterval);
			                    data = 100;	               	
			               	}
			               	data = parseInt(data);
			               	$( "#progressbar" ).progressbar({
		                        value: data,
		                	/* onComplete actions */
		               		});
			            }
		                /* update jQueryUI progressbar */
		               
		              });
		              
		    /* 2 seconds*/
		    }, 500);
		        /* prevent from page reload */
		        return false;
		    });

		
		
		function refreshName(dirname){
			$.ajax({
				type: "GET",
				url: "ajax/search.php",
				data: {fileName: dirname},
				dataType: 'json',
				success: function(data){
					galleryName = data.msg;
					$("#"+dirname).next("p").next("p").text(galleryName);
				}
			})
		}
		$("p.gallerynameUpdate").click(function(){
			dirname = $(this).prev("p.thumbs").attr('id');
			
			$.ajax({
				type: "GET",
				url: "ajax/search.php",
				data: {fileName: dirname},
				dataType: 'json',
				success: function(data){
					galleryName = data.msg;
					$("#dialog-fileName input").val(galleryName);
				}
			})
			refreshName(dirname);
			
		});
		var name = $( "#tagname" ),
			allFields = $( [] ).add( name ),
			tips = $( ".validateTips" );
	
		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}
	
		function checkLength( o, n, min, max ) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass( "ui-state-error" );
				updateTips( "Length of " + n + " must be between " +
					min + " and " + max + "." );
				return false;
			} else {
				return true;
			}
		}
	
		function checkRegexp( o, regexp, n ) {
			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass( "ui-state-error" );
				updateTips( n );
				return false;
			} else {
				return true;
			}
		}
		function log( message ) {
			$( "<div/>" ).text( message ).prependTo( "#log" );
			$( "#log" ).scrollTop( 0 );
		}
		$( "#tagname" ).autocomplete({
			source: "search.php",
			minLength: 2,
			select: function( event, ui ) {
				log( ui.item ?
					"Selected: " + ui.item.value + " aka " + ui.item.label :
					"Nothing selected, input was " + this.value );
			}
		});
		$("#dialog-zipDirectory").dialog({
			autoOpen: false,
			height: "auto",
			modal: true,
		})
		$("#dialog-fileName").dialog({
			autoOpen: false,
			height: "auto",
			width: 450,
			modal: true,
			buttons: {
				"Add new name: ":function(){
					newGalleryname = $("#dialog-fileName input").val();
					$.ajax({
						type: "POST",
						data: {
							galleryname: dirname, newName: newGalleryname
						},
						url: 'ajax/gallery.php',
						dataType: 'json',
						beforeSend: function(data){
							$("#dialog-fileName input, label").remove();
							$("#dialog-fileName").after("<img src='../../images/loading.gif' id='loader'>");
						},
						success: function(data){
							$('img#loader').remove();
							$("#dialog-fileName").append("<label for='humanName'>New galleryname</label><input type='text' value='"+data.directory_nice+"' name='humanName' />");
							refreshName(dirname);
						}
					})
				}
			}
		})
		
		$("#dialog-updateTemplate").dialog({
			autoOpen: false,
			height: "auto",
			width: 450,
			modal: true,
			buttons:{
				"Copy new template": function(){
					$("#dialog-updateTemplate p").remove();
					$.ajax({
						type: "GET",
						url: "ajax/util.php?newtemplate",
						dataType: 'json',
						beforeSend: function(data){
							$("#dialog-updateTemplate").after("<img src='../../images/loading.gif' id='loader'>");
						},
						success: function(data){
							$('img#loader').remove();
							$("#dialog-updateTemplate").append("<p>"+data.msg+"</p>");
						}
					})
				}
			}
		});
		$( "#dialog-tag" ).dialog({
			autoOpen: false,
			height: "auto",
			width: 350,
			modal: true,
			open: function(){
				$.ajax({
					type: 'GET',
					url : 'ajax/search.php',
					dataType : 'json',
					data: {
						dir : dirname
					},
					beforeSend: function (data) {
						$("#tagcloud").after("<img src='../../images/loading.gif' id='loader'>");
					
					},
					success : function(data){
						$('img#loader').remove();
						
						$.each(data, function(index, data){
							$('#tagcloud input').map(function(){
								if(parseInt(this.id.split('id')[1], 10) == data.value){
	
									$("#" + this.id).attr("checked", true);
								}
							});
						});
						$("#tagcloud").buttonset();
						
					},
					error : function(XMLHttpRequest, textStatus, errorThrown) {
					
						$('#message').removeClass().addClass('error')
							.text('There was an error.').show(500);
					}
				}); 
			},
			buttons: {
				"Add tag": function() {
					var data = { 'tags[]' : []};
					$("#tagcloud input:checked").each(function() {
						data['tags[]'].push($(this).val());
						data['dirname'] = dirname;
					});
					$.post("ajax/addtag.php", data, function(data){
						alert(data);
					});
	
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
				$('#tagcloud input').map(function(){
					$("#" + this.id).attr("checked", false);
				});
			}
		});
		$("#dialog-thumbok").dialog({
			autoOpen: false,
			height: "auto",
			width: 250,
			modal: true,
			close: function(){
				$('#dialog-thumb').dialog("close");
			},
			buttons: {
				Ok: function(){
					$(this).dialog("close");
				}
			}	
		});
		$( "#dialog-thumb" ).dialog({
			autoOpen: false,
			height: "auto",
			width: 350,
			modal: true,
			close: function(){
				$('#message').text('');
			},
			buttons: {
				"Create thumbnails" : function(){
					$.ajax({
						type : 'GET',
						url : 'ajax/util.php',
						dataType : 'json',
						data: {
							createThumb : dirname
						},
						beforeSend: function (data) {
							$("#message").after("<img src='../../images/loading.gif' id='loader'>");
	
						},
						success : function(data){
							$('img#loader').remove();
							$('#message').removeClass().addClass((data.error === true) ? 'error' : 'success')
								.text(data.msg).show(500);
							$("#dialog-thumbok").dialog("open");
						},
						error : function(XMLHttpRequest, textStatus, errorThrown) {
	
							$('#message').removeClass().addClass('error')
								.text('There was an error.').show(500);
						}
					});
				},
				Cancel : function(){
					$(this).dialog("close");
				}
			}
		});
		$("#imageUploaderWindow").dialog({
			autoOpen: false,
			height: "auto",
			width: 350,
			modal: true,
			open: function(){
				$.ajax({
					type: "GET",
					url: "ajax/util.php",
					data: {
						action: "loadUploader"
					},
					dataType: 'html',
					beforeSend: function(){
						$("#form").remove();
						$(this).append("<img src='../images/loading.gif' id='loader'>");
	
					},
					success : function(data){
						$('img#loader').remove();
						$("#imageUploaderWindow").append(data);
						
						/*
						 * How to:
						 * Use JS to get all input elements (or all with a value)
						 * Send them to backend
						 */
					},
					error : function(XMLHttpRequest, textStatus, errorThrown) {
						$('#imageUploaderWindow').removeClass().addClass((data.error === true) ? 'error' : 'success')
							.text(data.msg).show(500);
					}
			
				})
			},
			buttons: {
				"Upload images":function(){
					var inputs = $('#imageUploaderWindow').serialize();
					alert(inputs);
					/*$.ajax({
						type: "GET",
						url: "ajax/uploader.php",
						data: {
							action: 'upload', uploaden: true, map: dirname
						},
						dataType: 'json',
						beforeSend: function (data) {
							$(".formGallery").remove();
							$(this).append("<img src='../../images/loading.gif' id='loader'>");
	
						},
						success : function(data){
							$('img#loader').remove();
							$('#dialog-newGallery').removeClass().addClass((data.error === true) ? 'error' : 'success')
								.text(data.msg).show(500);
							if(data.error === true){
								$(this).append("<img src='../../images/fail.gif' id='fail')");
							}else if(data.error === false){
								$("#dialog-uploadImages").dialog("open");
								$("#dialog-newGallery").dialog('close');
							}
						},
						error : function(XMLHttpRequest, textStatus, errorThrown) {
							$('#dialog-newGallery').removeClass().addClass((data.error === true) ? 'error' : 'success')
								.text(data.msg).show(500);
						}
					})*/
				}
			}
		});
		$("#dialog-uploadImages").dialog({
			autoOpen: false,
			height: "auto",
			width: 350,
			modal: true,
			buttons:{
				"Upload": function(){
					$("#imageUploaderWindow").dialog("open");
				},
				"Next": function(){
					$("#dialog-thumb").dialog("open");
					$(this).dialog("close");
				},
				Cancel: function(){
					$(this).dialog("close");
				}
			}
		});
		
		$( "#dialog-newGallery" ).dialog({
			autoOpen: false,
			height: "auto",
			width: 350,
			modal: true,
			buttons: {
				"Create new gallery" : function(){
					dirname = $(".formGallery input").val();
					$.ajax({
						type : 'POST',
						url : 'ajax/util.php?createdirectory',
						dataType : 'json',
						data: {
							galleryname : dirname
						},
						beforeSend: function (data) {
							$(".formGallery").remove();
							$(this).append("<img src='../../images/loading.gif' id='loader'>");
	
						},
						success : function(data){
							$('img#loader').remove();
							$('#dialog-newGallery').removeClass().addClass((data.error === true) ? 'error' : 'success')
								.text(data.msg).show(500);
							if(data.error === true){
								$(this).append("<img src='../../images/fail.gif' id='fail')");
							}else if(data.error === false){
								$("#dialog-uploadImages").dialog("open");
								$("#dialog-newGallery").dialog('close');
							}
						},
						error : function(XMLHttpRequest, textStatus, errorThrown) {
							$('#dialog-newGallery').removeClass().addClass((data.error === true) ? 'error' : 'success')
								.text(data.msg).show(500);
						}
					});
				},
				Cancel : function(){
					$(this).dialog("close");
				}
			}
			
		});
		$(".gallerynameUpdate")
			.button()
			.click(function(){
				$("#dialog-fileName").dialog("open");
			});
		$( ".tags" )
			.button()
			.click(function() {
				$( "#dialog-tag" ).dialog( "open" );
			});
		$( ".thumbs" )
			.button()
			.click(function() {
				$( "#dialog-thumb" ).dialog( "open" );
			});
		$(".createNewGallery")
			.button()
			.click(function(){
				$("#dialog-newGallery").dialog("open");
			})
			
		$(".updateTemplate").button().click(function(){
			$("#dialog-updateTemplate").dialog("open");
		});
		$(".uploadImages").button().click(function(){
			$("#dialog-uploadImages").dialog("open");
		});
	});
	</script>
	<?php
	closedir($handle);
}
else{
	
	$goto = "login.php";
	header('Location: '.$goto);	
}

?>