<?
ob_start();
include("ABAIT_function_file.php");
session_start();
if($_SESSION['passwordcheck']!='pass'){
	header("Location:logout.php");
	print $_SESSION['passwordcheck'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;
	charset=utf-8" />
<title>
<?
print $_SESSION['SITE']
?>
</title>
<?
	set_css()
?>

</head>
<?	
	$names = build_page_pg();
?>
		<form 	action = "ABAIT_adminhome_v2.php"
				method = "post">
									
			<h2 align='center'><label>Member Enrollment Log</label></h2>

<?
// Script to auto update backend database with json encoded list of enrollees 

$insert_count = 0;
$update_count = 0;

	$devapikey=$_REQUEST['devapikey'];
	$apikey=$_REQUEST['apikey'];
	$member=$_REQUEST['member'];
	$organization=$_REQUEST['organization'];
	$organization_db_key=$_REQUEST['organization_db_key'];


	$conn = make_msqli_connection();
	$devapikey=mysqli_real_escape_string($conn,$devapikey);
	$apikey=mysqli_real_escape_string($conn,$apikey);
	$organization=mysqli_real_escape_string($conn,$organization);
	$organization_db_key=mysqli_real_escape_string($conn,$organization_db_key);

	// To Get Communities in given Organization
	// $community_request = "https://care.personcentredsoftware.com/mcm/api/v1/".$apikey."/organisationapi/communities";
	// $community_request = "https://care.personcentredsoftware.com/mcm/api/v1/0c0ed636-c1de-44b4-9833-a8101b050987/organisationapi/communities";

	$community_request = "https://care.personcentredsoftware.com/mcm/api/v1/".$organization_db_key."/organisationapi/communities";


	//$community_blob = curl_init("https://care.personcentredsoftware.com/mcm/api/v1/0c0ed636-c1de-44b4-9833-a8101b050987/organisationapi/communities");
	$community_blob = curl_init($community_request);
	curl_setopt($community_blob, CURLOPT_RETURNTRANSFER, true);

	$html = curl_exec($community_blob);

	$community_decoded = json_decode($html, true);
	curl_close($community_blob);

	// $community_blob = file_get_contents($community_request);
	
	// print_r($community_blob);
		//TESTING
		//$organization = "181d26cd-e8f4-4750-a7bb-04eef2773c4a";
	
	$communities = array();
	// print_r($community_decoded);
	foreach($community_decoded as $raw_community){
		if($raw_community['OrganisationID']==$organization){
			$communities[$raw_community['CommunityID']] = $raw_community['Name'];
		}
	}
	$serialize_communities = serialize($communities);
	// echo $serialize_communities;
	// echo "\n";
	// print_r(unserialize($serialize_communities));

	if($member==='resident'){
	// // RESIDENTS
		$request = "https://care.personcentredsoftware.com/integration/api/GenericAPI/ServiceUsers?DevApikey=".$devapikey."&Apikey=".$apikey;
		$live_updates = file_get_contents($request);
	}else{
		// // STAFF
		$request = "https://care.personcentredsoftware.com/integration/api/GenericAPI/Workers?DevApikey=".$devapikey."&Apikey=".$apikey;
		$live_updates = file_get_contents($request);
	}

	$decoded_update = json_decode($live_updates, true);

	$Target_Population = $_SESSION['default_target_Population'];
	$privilegekey = "228";
	$gender="N";
	$date=date("Y,m,d");

	foreach($decoded_update as $value){
		// iterate through json records

		$community_match = false;
		if(array_key_exists($value['communityID'], $communities)){
			$community_match = true;
		}

		if($community_match){

			if ($member==="resident"){
				$sql="SELECT * FROM residentpersonaldata WHERE guid='$value[personID]' ORDER by first";

			}else{
				$first=$value['firstName'];
				$last=$value['lastName'];
				$pwd = $value['connectionID'];
				
				$sql="SELECT * FROM personaldata WHERE password LIKE '$pwd' OR (first='$first' AND last='$last')";

			}

			$check=mysqli_query($conn,$sql);

			$accesslevel="";
			if(!$check || mysqli_num_rows($check) == 0){

				if(stripos(strtolower($value['role']),"manager")!==false){
					$accesslevel="admin";
				}else if(stripos(strtolower($value['role']),"carer")!==false || stripos(strtolower($value['role']),"nurse")!==false){
					$accesslevel='caregiver';
				}

				$house = $communities[$value['communityID']];
				$guid = $value['personID'];
				$community = serialize(array($value['communityID']=>$house));
				
				if($member==='resident'){
					mysqli_query($conn, "INSERT INTO residentpersonaldata VALUES(null,'$value[firstName]','$value[lastName]',null,'$value[gender]','$privilegekey','$Target_Population','$house','$value[personID]','$community','$value[personID]')");
					$insert_count++;
				}else{
					mysqli_query($conn,"INSERT INTO personaldata VALUES(null,'$date','$pwd',null,'$accesslevel','$value[firstName]','$value[lastName]',null,null,null,null,null,null,null,null,null,'$privilegekey','$Target_Population','$house','$community')");
					$insert_count++;
				}
			}elseif(mysqli_num_rows($check) > 0){
				
				$row1=mysqli_fetch_assoc($check);
				$row_id = $row1['personaldatakey'];

				//mysqli_query($conn,"UPDATE personaldata SET password='$full_pwd' WHERE password LIKE '$pwd%'");

				 if($row1['house']!='all'){

				 	$community_check = unserialize($row1['community']);

				 	//echo $communities[$value['communityID']];
				 	if(!is_array($community_check)){
				 		$community_insert = serialize($community_check);
				 		$house_insert = $row1['house'].",".$communities[$value['communityID']];
				 		if($member==='resident'){
				 			mysqli_query($conn,"UPDATE residentpersonaldata SET house='$house_insert', community='$community_insert' WHERE person_id='$value[personID]'");
				 		}else{
				 			mysqli_query($conn,"UPDATE personaldata SET house='$house_insert', community='$community_insert' WHERE password LIKE '$pwd'");
				 		}
				 			$update_count++;

				 	}elseif(!array_key_exists($value['communityID'], $community_check)) {
				 		$house_insert = $row1['house'].",".$communities[$value['communityID']];
				 		$community_check += array($value['communityID'],$communities[$value['communityID']]);
				 		$community_insert = serialize($community_check);

				 		if($member==='resident'){
				 			mysqli_query($conn,"UPDATE residentpersonaldata SET house='$house_insert', community='$community_insert' WHERE person_id='$value[personID]'");
				 		}else{
				 			mysqli_query($conn,"UPDATE personaldata SET house='$house_insert', community='$community_insert' WHERE password LIKE '$pwd'");
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
print "<h4 align='center'>$insert_count  $member  loaded.</h4>\n";
print "<h4 align='center'>$update_count  $member   updated.</h4>\n";


?>					
	
				<div id = "submit">
				<input 	type = "submit"
						name = "submit"
						value = "Return to Administrator Home"/>
				</div>

	</form>
<?build_footer_pg()?>
</body>
</html>



