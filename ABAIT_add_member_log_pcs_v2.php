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
$return_message = null;
$staff_insert_count = 0;
$staff_update_count = 0;
$resident_insert_count = 0;
$resident_update_count = 0;
$member_array = array();
$member_array[] = 'staff';
$member_array[] = 'resident';

	$apikey=$_REQUEST['apikey'];

	$conn = make_msqli_connection();

	$apikey=mysqli_real_escape_string($conn,$apikey);
	//$organization=mysqli_real_escape_string($conn,$organization);
	// Per instructions, apikey is the same as organizationd_db_key for a client

	// Get DevAPIKey from configs
	$string = file_get_contents("configfiles/config.json");
	if ($string === false) {
	    $return_message=$return_message." Could not read DevAPIKey from configs";
	}
	$configs = json_decode($string, true);
	$devapikey = $configs['db_connections'][$_SESSION['hosting_service']]['devapikey'];

	// Use this for testing, do not use for PCS!!
	//$organization_db_key = $configs['db_connections'][$_SESSION['hosting_service']]['organization_db_key'];

	// GET ORGANIZATION DB FOR MATCHING COMMUNITY IDS TO THEIR NAMES
	// FOR TESTING
	//$community_request = "https://care.personcentredsoftware.com/mcm/api/v1/".$organization_db_key."/organisationapi/communities";
	// For PCS
	$community_request = "https://care.personcentredsoftware.com/mcm/api/v1/".$apikey."/organisationapi/communities";

	$community_blob = curl_init($community_request);
	curl_setopt($community_blob, CURLOPT_RETURNTRANSFER, true);
	$html = curl_exec($community_blob);
	$community_decoded = json_decode($html, true);
	curl_close($community_blob);

		//TESTING
		//$organization = "181d26cd-e8f4-4750-a7bb-04eef2773c4a";
	
	$communities = array();
	if (isset($community_decoded)&&$community_decoded){
		foreach($community_decoded as $raw_community){
			// Since client community db will only be for their organization, do not need this if in pcs
			// if($raw_community['OrganisationID']==$organization){
			// 	$communities[$raw_community['CommunityID']] = $raw_community['Name'];
			// }
			$communities[$raw_community['LocationID']] = $raw_community['Name'];
		}
	}else{
		$return_message = $return_messge." Could not collect communities from DB";
	}
	// Community Check
	//print_r($communities);

	$serialize_communities = serialize($communities);

	$Target_Population = $_SESSION['default_target_Population'];
	$privilegekey = "228";
	$gender="N";
	$date=date("Y,m,d");

	foreach ($member_array as $key => $member) {
		if($member==='resident'){
			// RESIDENTS
			$request = "https://care.personcentredsoftware.com/integration/api/GenericAPI/ServiceUsers?DevApikey=".$devapikey."&Apikey=".$apikey;
			$live_updates = file_get_contents($request);
			
		}else{
			// STAFF
			$request = "https://care.personcentredsoftware.com/integration/api/GenericAPI/Workers?DevApikey=".$devapikey."&Apikey=".$apikey;
			$live_updates = file_get_contents($request);
		}

		$decoded_update = json_decode($live_updates, true);
		if($decoded_update){
			// iterate through json records
			foreach($decoded_update as $value){

				if(array_key_exists($value['locationID'], $communities)){
					$community_match = true;

					if ($member==="resident"){
						$sql="SELECT * FROM residentpersonaldata WHERE guid='$value[connectionID]' ORDER by first";

					}else{
						$first=$value['firstName'];
						$last=$value['lastName'];
						$pwd = $value['connectionID'];

						if(stripos(strtolower($value['role']),"manager")!==false || stripos(strtolower($value['role']),"activities")!==false || stripos(strtolower($value['role']),"owner")!==false){
							$accesslevel="admin";
						}else if(stripos(strtolower($value['role']),"carer")!==false || stripos(strtolower($value['role']),"nurse")!==false){
							$accesslevel='caregiver';
						}else{
							$accesslevel="";
						}
						
						$sql="SELECT * FROM personaldata WHERE password LIKE '$pwd' OR (first='$first' AND last='$last')";

					}

					$check=mysqli_query($conn,$sql);

					if(!$check || mysqli_num_rows($check) == 0){

						$house = str_replace(" ","-",$communities[$value['locationID']]);
						
						$community = serialize(array($value['locationID']=>$house));
						
						if($member==='resident'){
							mysqli_query($conn, "INSERT INTO residentpersonaldata VALUES(null,'$value[firstName]','$value[lastName]',null,'$value[gender]','$privilegekey','$Target_Population','$house','$value[connectionID]','$community','$value[connectionID]')");
							$resident_insert_count++;
						}else{
							// if(stripos(strtolower($value['role']),"manager")!==false){
							// 	$accesslevel="admin";
							// }else if(stripos(strtolower($value['role']),"carer")!==false || stripos(strtolower($value['role']),"nurse")!==false){
							// 	$accesslevel='caregiver';
							// }
							mysqli_query($conn,"INSERT INTO personaldata VALUES(null,'$date','$pwd',null,'$accesslevel','$value[firstName]','$value[lastName]',null,null,null,null,null,null,null,null,'$apikey','$privilegekey','$Target_Population','$house','$community')");
							$staff_insert_count++;
						}
					}elseif(mysqli_num_rows($check) > 0){
						
						$row1=mysqli_fetch_assoc($check);
						$row_id = $row1['personaldatakey'];

						 if($row1['house']!='all'){

						 	$community_check = unserialize($row1['community']);

						 	if(!is_array($community_check)){
						 		$community_insert = serialize($community_check);
						 		$house_insert = $row1['house'].",".$communities[$value['locationID']];
						 		if($member==='resident'){
						 			mysqli_query($conn,"UPDATE residentpersonaldata SET house='$house_insert', community='$community_insert' WHERE person_id='$value[connectionID]'");
						 			$resident_update_count++;
						 		}else{
						 			mysqli_query($conn,"UPDATE personaldata SET house='$house_insert', community='$community_insert' WHERE password LIKE '$pwd'");
						 			$staff_update_count++;
						 		}
						 			

						 	}elseif(!array_key_exists($value['locationID'], $community_check)) {
						 		$house_insert = $row1['house'].",".str_replace(" ","-",$communities[$value['locationID']]);
						 		$community_check += array($value['locationID'],str_replace(" ","-",$communities[$value['locationID']]));
						 		$community_insert = serialize($community_check);

						 		if($member==='resident'){
						 			mysqli_query($conn,"UPDATE residentpersonaldata SET house='$house_insert', community='$community_insert' WHERE person_id='$value[connectionID]'");
						 			$resident_update_count++;
						 		}else{
						 			mysqli_query($conn,"UPDATE personaldata SET house='$house_insert', community='$community_insert' WHERE password LIKE '$pwd'");
						 			$staff_update_count++;
						 		}	
						 	}elseif($member!=='resident'&&!$row1['notify']){
						 		mysqli_query($conn,"UPDATE personaldata SET notify='$apikey' WHERE password LIKE '$pwd'");
						 		$staff_update_count++;

						 	}elseif($member!=='resident'&&$row1['accesslevel']!==$accesslevel){
						 		mysqli_query($conn,"UPDATE personaldata SET accesslevel='$accesslevel' WHERE password LIKE '$pwd'");
						 		$staff_update_count++;

						 	}
						 }
					}
				}
			}
		}else{
			$return_message = $return_message." Could not connect to User Database";
		}
	}
if($return_message){
	print "<h4 align='center'>$return_message</h4>\n";
}else{
	print "<h4 align='center'>$resident_insert_count  Residents  loaded.</h4>\n";
	print "<h4 align='center'>$resident_update_count  Residents   updated.</h4>\n";
	print "<h4 align='center'>$staff_insert_count  Staff  loaded.</h4>\n";
	print "<h4 align='center'>$staff_update_count  Staff   updated.</h4>\n";
}


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



