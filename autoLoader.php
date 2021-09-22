<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type"content="text/html;
charset=utf-8"/>
<head>
<title>datalog.php </title>
<!--<link 	rel = "stylesheet"
		type = "text/css"
		href = "datalog.css">
-->
<style type="text/css">
</style>
</head>
<body>
</body>
<?

function autoLoad($data){
	echo $data;
	echo "HI";
	if(array_key_exists("pss_updates", $data)){

		$conn=make_msqli_connection();

		foreach ($data['pss_updates'] as $key) {

			//NOTE: should really check if guids exist before updating
			if($key['roleDescription']==="View service users information including reports, charts and processes"){
				$roleDescription = $key['role'];
			}else{
				continue;
			}
			$full_name=explode(',',$key['workerName']);
			echo $full_name;
			echo "HERE";
			if($full_name && count($full_name)>1){
				$first = $full_name[0];
				$last = $full_name[1];
			}else if (count($full_name)==1){
				$first = $full_name[0];
				$last = $full_name[0];
			}else{
				continue;
			}
			$gender="N";
			$privilegekey=$_SESSION['personaldatakey'];
			$Target_Population = $_SESSION['Target_Population'];
			$house = $key['communityID'];

			$password = $role['guid'];
			$date = date("Y,m,d");

			//if resident
			if($key['role']=="resident"){
				$Target_Population=mysqli_real_escape_string($conn,$Target_Population);
				mysqli_query($conn, "INSERT INTO residentpersonaldata VALUES(null,'$first','$last',null,'$gender','$privilegekey','$Target_Population','$house')");
			}
			// if carer
				if($key['role']=="carer"){
				mysqli_query($conn,"INSERT INTO personaldata VALUES(null,'$date','$password','$accesslevel','$first','$last',null,null,null,null,null,null,null,null,'$privilegekey','$Target_Population','$house')");
			}

			// if admin
				if($key['role']=="admin"){
				mysqli_query($conn,"INSERT INTO personaldata VALUES(null,'$date','$password','$accesslevel','$first','$last',null,null,null,null,null,null,null,null,'$privilegekey','$Target_Population','$house')");
			}
		}

		mysqli_close($conn);
?>


HELLO


</body>
</html>
<?

	}
	// else{
	// 	$nextfile=$_SESSION['HOME'];
	// }
}

?>