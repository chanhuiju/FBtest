<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Back-end</title>
</head>
<body>
<?php
	header("Access-Control-Allow-Origin: *");
	
	//connect DB
	$link = @mysqli_connect( 
		'localhost', 	
		'FBtest',	
		'123456',	
		'fbtest'	
	);
	mysqli_query($link,'SET CHARACTER SET utf-8');
	mysqli_query($link,"SET collation_connection ='utf8_unicode_ci'");
	
	//access JSON
	$url="https://graph.facebook.com/123103684386135/posts?fields=id,from,message,picture,link,type,status_type,object_id,created_time,likes.limit(1).summary(true),shares,comments.limit(1).summary(true)&access_token=260801967406003|e2voA1kekIT3PZ68dz_98CZAcTc";
	$ch = curl_init();
	$this_header = array("content-type: application/x-www-form-urlencoded;charset=UTF-8");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$resultcurl=curl_exec($ch);
	$myArray = json_decode($resultcurl, true);
	
	//output & storage to DB
	echo "<table border='1' style='border-collapse:collapse'>";
	echo "<tr><th>id</th><th>created_time</th><th>Post.from_name</th><th>Post.from_id</th>";
	echo "<th>Post.likes_count</th><th>Post.shares_count</th><th>Post.comments_count</th>";
	echo "<th>message</th><th>picture</th><th>link</th><th>type</th><th>status_type</th>";
	echo "<th>object_id</th><th>Popular</th></tr>";
	
	for ($i=0; $i<count($myArray["data"]); $i++){
		$likecount=0;
		$sharecount=0;
		$commentcount=0;
		$message='';
		$picture='';
		$thelink='';
		$statutype='';
		$objectid='';
		echo "<tr><td>" . $myArray["data"][$i]["id"] . "</td>";
		echo "<td>" . $myArray["data"][$i]["created_time"] . "</td>";
		echo "<td>" . $myArray["data"][$i]["from"]["name"] . "</td>";
		echo "<td>" . $myArray["data"][$i]["from"]["id"] . "</td>";
		if (($myArray["data"][$i]["type"]) == "status"){
			echo "<td></td><td></td><td></td>";
			echo "<td></td><td></td><td></td>";
			echo "<td>".$myArray["data"][$i]["type"]."</td><td></td><td></td>";
			echo "<td>". (1*$likecount+5*$sharecount+2*$commentcount) ."</td></tr>";
			
			$sqlselect = "Select * from backend where ID='".$myArray["data"][$i]["id"]."'";
			if ( $result = mysqli_query($link,$sqlselect)){
				if (mysqli_num_rows($result)==0){
					$sqlinsert = "INSERT INTO backend (ID,From_ID,From_Name,CreateTime) VALUES ('".$myArray["data"][$i]["id"]."','".$myArray["data"][$i]["from"]["id"]."','".$myArray["data"][$i]["from"]["name"]."','".$myArray["data"][$i]["created_time"]."')";
					mysqli_query($link,$sqlinsert);
				}
			}
		} else{
			if (isset($myArray["data"][$i]["likes"]["summary"]["total_count"])){
				$likecount=$myArray["data"][$i]["likes"]["summary"]["total_count"];}
			if (isset($myArray["data"][$i]["shares"]["count"])){
				$sharecount=$myArray["data"][$i]["shares"]["count"];}
			if (isset($myArray["data"][$i]["comments"]["summary"]["total_count"])){
				$commentcount=$myArray["data"][$i]["comments"]["summary"]["total_count"];}
			if (isset($myArray["data"][$i]["message"])){
				$message=$myArray["data"][$i]["message"];}
			if (isset($myArray["data"][$i]["picture"])){
				$picture=$myArray["data"][$i]["picture"];}
			if (isset($myArray["data"][$i]["link"])){
				$thelink=$myArray["data"][$i]["link"];}
			if (isset($myArray["data"][$i]["status_type"])){
				$statutype=$myArray["data"][$i]["status_type"];}
			if (isset($myArray["data"][$i]["object_id"])){
				$objectid=$myArray["data"][$i]["object_id"];}
			echo "<td>".$likecount."</td><td>".$sharecount."</td><td>".$commentcount."</td>";
			echo "<td>".$message."</td><td>".$picture."</td><td>".$thelink."</td>";
			echo "<td>".$myArray["data"][$i]["type"]."</td><td>".$statutype."</td><td>".$objectid."</td>";
			echo "<td>". (1*$likecount+5*$sharecount+2*$commentcount) ."</td></tr>";
			
			$sqlselect = "Select * from backend where ID='".$myArray["data"][$i]["id"]."'";
			if ( $result = mysqli_query($link,$sqlselect)){
				if (mysqli_num_rows($result)==0){
					$sqlinsert="INSERT INTO backend (ID,From_ID,From_Name,CreateTime,Massage,Link,Picture,Object_ID) VALUES ";
					$sqlinsert.="('".$myArray["data"][$i]["id"]."','".$myArray["data"][$i]["from"]["id"]."','".$myArray["data"][$i]["from"]["name"]."',";
					$sqlinsert.="'".$myArray["data"][$i]["created_time"]."','".$message."','".$thelink."','".$picture."','".$objectid."')";
					mysqli_query($link,$sqlinsert);
				}
			}
		}
	}
	echo "</table>";

	mysqli_close($link);	//Ãö³¬³sµ²
?>
</body>
</html>
		