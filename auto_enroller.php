<?
// Script to auto update backend database with json encoded list of enrollees 

$insert_count = 0;
$update_count = 0;

// User prompts
$_SESSION['hosting_service'] = readline("Please enter the db hosting_service exactly as it appears in the config.json file (e.g. azure_UK):   ");
$member = strtolower(readline("Please enter 'resident' or 'staff' for type of member to update:  "));
$houses = [readline("Please enter community name from which to load $member. Note: Entry is case sensitive:  ")];

// Required to determine DB connection
// $_SESSION['hosting_service'] = 'azure_UK';

// Houses to load (array)
	// $houses = ["Mount Pleasant","Old House","Westwood", "Howard","Oxford","Pembroke"];
	// $houses = ['Westwood/Training'];

// ****** READ IN DB CONNECTIONS SETTINGS USING config.json ******* //
	$string = file_get_contents("config.json");
	if ($string === false) {
	    $nextfile="ABAIT_adminhome_v2.php";
	}
	$db_configs = json_decode($string, true);
	$db = $db_configs['db_connections'][$_SESSION['hosting_service']]['db'];
	$_SESSION['db'] = $db;
	$host = $db_configs['db_connections'][$_SESSION['hosting_service']]['host'];
	$db_user = $db_configs['db_connections'][$_SESSION['hosting_service']]['db_user'];
	$db_pwd = $db_configs['db_connections'][$_SESSION['hosting_service']]['db_pwd'];
	$use_ssl = $db_configs['db_connections'][$_SESSION['hosting_service']]['use_ssl'];


// FOR TESTING DB LOAD USING LOCAL JSON FILE

	// $live_updates = file_get_contents("update_test.json");
	// $decoded_update = json_decode($live_updates, true);
	// $houses = ["House"];


// For DB LOAD OF REAL DATA


	if($member==='resident'){
	// // RESIDENTS
		$live_updates = file_get_contents('https://pcspublicfiles.blob.core.windows.net/integration-poc/abait/IndependencePathways/AllResidents.json?sv=2020-08-04&st=2021-11-15T08%3A08%3A33Z&se=2022-05-31T07%3A08%3A00Z&sr=b&sp=r&sig=u1KlVWDgm%2FUVeDNqq8TNkMXaB%2B2ocn5w%2B%2F8%2B0BZKGDc%3D');
	}else{
		// // STAFF
		$live_updates = file_get_contents('https://pcspublicfiles.blob.core.windows.net/integration-poc/abait/IndependencePathways/AllStaff.json?sv=2020-08-04&st=2021-11-15T08%3A09%3A14Z&se=2022-05-31T07%3A09%3A00Z&sr=b&sp=r&sig=TDzHWFcVbonGImgGWx7a21ZoXOLywz7aATCkSmI8zv8%3D');
	}


	$decoded_update = json_decode($live_updates, true);
	

	if($use_ssl){
		$conn = mysqli_init();
		mysqli_ssl_set($conn,NULL,NULL, "/var/www/html/BaltimoreCyberTrustRoot.crt.pem", NULL, NULL);
		mysqli_real_connect($conn, $host, $db_user, $db_pwd, $db, 3306, MYSQLI_CLIENT_SSL);
		if (mysqli_connect_errno($conn)) {
			die('Failed to connect to MySQL: '.mysqli_connect_error());
		}else{
		echo "\r\n";
		echo "Connection to database: ".$db." succeeded.";
		echo "\r\n...";
		}
	}else{

		$conn=mysqli_connect($host,$db_user,$db_pwd,$db);
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}else{
		echo "connection succeeded";
		}
	}

	//$privilegekey=$_SESSION['personaldatakey'];
	//$Target_Population = $_SESSION["Target_Population"];
	$Target_Population = "Dementia";
	$privilegekey = "228";
	$gender="N";
	$date=date("Y,m,d");


	foreach($decoded_update as $value){
		// iterate through json records

		$house_match=false;
		foreach($houses as $house){
			// search records for house match - note different community name fields for staff and residents
			if($member==='staff'){
				$location = $value['communityName'];
			}else{
				$location = $value['location'];
			}

		   if(strpos($location,$house)!==false){
		   		$raw_house = explode("/", $location);
		   		$house_match=$raw_house[0];
		   		if($raw_house[1]){
		   			$community = $raw_house[1];
		   		}else{
		   			$community = "Staff";
		   		}
		   		break;
			}
		}

		if($house_match){
			$resident=false;
			if ($member==="resident"){
				$sql="SELECT * FROM residentpersonaldata WHERE guid='$value[personID]' ORDER by first";

			}else{
				// NOTE - should take entire GUID going forward then match like in passcheck
				$name = explode(" ", $value["workerName"]);
				if(count($name)<1){
					$first="";
					$last="";
				}elseif(count($name)==1){
					$first=$name[0];
					$last="";
				}else{
					$first=$name[0];
					$last=$name[1];
				}
				$pwd = $value["connectionID"];
				
				$sql="SELECT * FROM personaldata WHERE password LIKE '$pwd' OR (first='$first' AND last='$last')";

			}

			$check=mysqli_query($conn,$sql);

			$accesslevel="";
			if(!$check || mysqli_num_rows($check) == 0){

				if(stripos(strtolower($value['jobTitle']),"manager")!==false){
					$accesslevel="admin";
				}else if(stripos(strtolower($value['jobTitle']),"carer")!==false){
					$accesslevel='caregiver';
				}
				
				if($member==='resident'){
					$name = explode(" ", $value["workerName"]);
					if(count($name)<2){
						$name[]="";
					}else{
						$first=$name[0];
						$last=$name[1];
					}
					$first = $value['firstName'];
					$last = $value['lastName'];
					$gender = $value['gender'];
					$house = $value['location'];
					$guid = $value['personID'];
					$community = $value['community'];

					mysqli_query($conn, "INSERT INTO residentpersonaldata VALUES(null,'$value[firstName]','$value[lastName]',null,'$value[gender]','$privilegekey','$Target_Population','$house_match','$value[personID]','$value[community]','$value[personID]')");
					$insert_count++;
				}else{

					mysqli_query($conn,"INSERT INTO personaldata VALUES(null,'$date','$pwd',null,'$accesslevel','$first','$last',null,null,null,null,null,null,null,null,null,'$privilegekey','$Target_Population','$house_match','$community')");
					$insert_count++;
				}
			}elseif(mysqli_num_rows($check) > 0){
				
				$row1=mysqli_fetch_assoc($check);
				$row_id = $row1['personaldatakey'];

				//mysqli_query($conn,"UPDATE personaldata SET password='$full_pwd' WHERE password LIKE '$pwd%'");

				 if($row1['house']!='all'){

				 	if($row1['house']!=$house_match){
				 		if($member==='resident'){
				 			mysqli_query($conn,"UPDATE residentpersonaldata SET house='all' WHERE person_id='$value[personID]'");
				 		}else{
				 			mysqli_query($conn,"UPDATE personaldata SET house='all' WHERE password LIKE '$pwd'");
				 		}
						
				 			$update_count++;
				 	}
				 }
				/// Do this only once !!!!! 1/29/2021
				// mysqli_query($conn,"UPDATE personaldata SET password='$connection_pwd' WHERE personaldatakey='$row_id'"); 
				// echo "Reset";

			}
		}
		
	}
// }
echo "\r\n";
echo $insert_count." ". $members. "   loaded";
echo "\r\n";
echo $update_count." ". $members. "   updated";
echo "\r\n";
?>
