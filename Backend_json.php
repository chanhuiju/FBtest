<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Back-end-JSON</title>
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
	
	$strJSON='';
	$intCount=1;
	
	$sqlselect = 'Select * from backend';
	if ( $result = mysqli_query($link,$sqlselect)){
		$strJSON.='{"data":[';
		$intRecords=mysqli_num_rows($result);
		while ($row=mysqli_fetch_assoc($result)){
			$strJSON.='{"ID":"'.$row["ID"].'","From_ID":"'.$row["From_ID"].'","From_Name":"'.$row["From_Name"].'",';
			$strJSON.='"CreateTime":"'.$row["CreateTime"].'","Massage":"'.$row["Massage"].'","Link":"'.$row["Link"].'",';
			$strJSON.='"Picture":"'.$row["Picture"].'","Object_ID":"'.$row["Object_ID"].'"}';
			if ($intCount != $intRecords){ $strJSON.=',';}
			$intCount = $intCount+1;
		}
		$strJSON.=']}';
	}
	
	echo $strJSON;
		
	mysqli_close($link);	//Ãö³¬³sµ²
?>
</body>
</html>
		