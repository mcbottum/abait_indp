<?
// Script to auto update backend database with json encoded list of enrollees 
// function autoEnroller($updates){


// $live_updates='[{
// "communityID": "1aa2b85d-e42b-401a-a725-176091514777",
// "communityName": "Pembroke House ",
// "workerName": "Paul Williams ",
// "jobDescription": "Assistant Manager",
// "jobTitle": "Manager",
// "roleDescription": "View limited information about service users",
// "connectionID": "df11a632-81a7-421b-8e67-04db9b4d691b",
// "personID": "cb810996-0e0e-4210-825e-b63f5d9ee86a"
// },
// {
// "communityID": "1b14fbdf-8148-4647-a0ef-1d5b29faed52",
// "communityName": "Mount Pleasant",
// "workerName": "Paul Williams ",
// "jobDescription": "Assistant Manager",
// "jobTitle": "Manager",
// "roleDescription": "View service users information including reports, charts and processes",
// "connectionID": "df11a632-81a7-421b-8e67-04db9b4d691b",
// "personID": "cb810996-0e0e-4210-825e-b63f5d9ee86a"
// },
// {
// "communityID": "713fd960-c015-4dc5-94b4-48e1bd55a4dd",
// "communityName": "Old House /Training",
// "workerName": "Jenny Jones",
// "jobDescription": "Carer",
// "jobTitle": "Carer",
// "roleDescription": "Change communities/sites and organisation customisation",
// "connectionID": "20845b67-d018-496f-b319-2cc4e06504df",
// "personID": "d2764e20-c13a-44e3-a135-d8bb8ff12542"
// },
// {
// "communityID": "7cb23cde-65c9-4a34-9711-d1ceefe1e835",
// "communityName": "Westwood/Training",
// "workerName": "Katy Payne ",
// "jobDescription": "Team leader",
// "jobTitle": "Senior carer",
// "roleDescription": "Allowed to Enrol Devices for carers to use",
// "connectionID": "743da373-882b-4c10-ad74-64af265df02c",
// "personID": "2ee8c596-1e66-4afd-8514-ca205bb383c7"
// }]';





	$houses = ["Mount Pleasant","Old House","Westwood", "Howard","Oxford","Pembroke"];

    $live_updates = file_get_contents('https://pcspublicfiles.blob.core.windows.net/integration-poc/abait/IndependencePathways/AllStaff.json?sv=2019-10-10&se=2021-04-30T23%3A00%3A00Z&si=ABAIT&sr=b&sig=t%2FR2JTchJK2Sug7SV8pp7Lu%2FiS6xzQpLLx1QU7vmm20%3D');

	$decoded_update = json_decode($live_updates, true);



// FOR LOCAL TESTING
	//$db = 'agitation_indp';
	//$db_pwd = 'abait123!';
	//$host = 'localhost';
	//$db_user = 'abait';

// FOR DREAMHOST LIVE
    $db = 'agitation_indp';
    $db_pwd = 'h1$6T#5IWx';
    $host = 'mysqlindp.abaitscale.com';
    $db_user = 'abaitindp';
	
	$conn=mysqli_connect($host,$db_user,$db_pwd, $db);

	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}else{
		echo "connection succeeded";
	}

	$privilegekey=$_SESSION['personaldatakey'];
	$Target_Population = $_SESSION["Target_Population"];
	$Target_Population = "child services";
	$privilegekey = "228";
	$gender="N";
	$date=date("Y,m,d");

	// $sql_test = "SELECT * from personaldata WHERE personaldatakey=230";
	// $check=mysqli_query($conn,$sql_test);
	// while($row=mysqli_fetch_assoc($check)){
	// 	print_r($row);
	// }



	foreach($decoded_update as $value){

		$house_match=false;
		foreach($houses as $house){
		   if(strpos($value['communityName'],$house)!==false){
		   		$house_match=$house;
		   		break;
			}
		}

		if($house_match){
			$resident=false;
			if ($value["jobTitle"]=="resident"){
				$sql="SELECT * FROM residentpersonaldata WHERE guid='$value[guid]' ORDER by first";
				$resident=true;
			}else{
				// NOTE - should take entire GUID going forward then match like in passcheck
				$name = explode(" ", $value["workerName"]);
				if(count($name)<1){
					$first="";
					$last="";
				}elseif(count($name)==1)){
					$first=$name[0];
					$last="";
				}else{
					$first=$name[0];
					$last=$name[1];
				}
				$pwd = $value["connectionID"];

				$sql="SELECT * FROM personaldata WHERE password LIKE '$pwd' OR (first='$first' AND last='$last')";
				// $full_pwd = $value["personID"];
				// $connection_pwd = $value["connectionID"];

			}

			$check=mysqli_query($conn,$sql);

			$accesslevel="";
			if(!$check || mysqli_num_rows($check) == 0){
				if(stripos($value['jobTitle'],"manager")!==false){
					$accesslevel="admin";
				}else if(stripos($value['jobTitle'],"carer")!==false){
					$accesslevel='caregiver';
				}
				
				if($resident){
					$name = explode(" ", $value["workerName"]);
					if(count($name)<2){
						$name[]="";
					}else{
						$first=$name[0];
						$last=$name[1];
					}
					mysqli_query($conn, "INSERT INTO residentpersonaldata VALUES(null,'$name[0]','$name[1]',null,'$gender','$privilegekey','$Target_Population','$facility[0]','$value[guid]')");
				}else{
					// echo $date,"  ";
					// echo $pwd;
					// echo ", ,";
					// echo $accesslevel,"  ";
					// echo $house;
					// echo ",  ,";
					// echo $first,$last;
					// echo ",  ";
					// echo $privilegekey,"  ";
					// echo $Target_Population,"   ";

					mysqli_query($conn,"INSERT INTO personaldata VALUES(null,'$date','$pwd','$accesslevel','$first','$last',null,null,null,null,null,null,null,null,null,'$privilegekey','$Target_Population','$house_match')");
				}
			}elseif(mysqli_num_rows($check) > 0){
				$row1=mysqli_fetch_assoc($check);
				$row_id = $row1['personaldatakey'];

				//mysqli_query($conn,"UPDATE personaldata SET password='$full_pwd' WHERE password LIKE '$pwd%'");

				 if($row1['house']!='all'){

				 	if($row1['house']!=$house_match){
					
				 			mysqli_query($conn,"UPDATE personaldata SET house='all' WHERE password LIKE '$pwd'");
						
				 			// echo "updated  ";
				 	}
				 }
				/// Do this only once !!!!! 1/29/2021
				// mysqli_query($conn,"UPDATE personaldata SET password='$connection_pwd' WHERE personaldatakey='$row_id'"); 
				// echo "Reset";

			}
		}
		
	}
// }
?>
