<?php #file_upload.php 2009-11-06
	$file_path = 'uploads/';
	$file_up = $file_path.basename($_FILES['upload']['name']);
	if(move_uploaded_file($_FILES['upload']['tmp_name'],$file_up)){
		echo 'Public/DemoForPHP/'.$file_up;
		//echo 'success';
	}else{
		echo 'fail';	
	}
?>