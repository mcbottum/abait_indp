<?
ob_start();
include("ABAIT_function_file.php");
session_start();
if($_SESSION['passwordcheck']!='pass'){
    header("Location:logout.php");
    print $_SESSION['passwordcheck'];
}

echo date("Y-m-d h:i:sa"). "\n";

if (isset($argc)) {
    for ($i = 0; $i < $argc; $i++) {
        if(isset($argv[1])){
            $hosting_service=$argv[1];
            $_SESSION['hosting_service']=$hosting_service;
        }
        if(isset($argv[2])){
            $apikey=$argv[2];
        }
        echo "Argument #" . $i . " -  received\n";
    }
}
else {
    echo "argc and argv disabled\n";
}

// Script to auto update backend database with json encoded list of enrollees 
$return_message = null;
$staff_insert_count = 0;
$staff_update_count = 0;
$resident_insert_count = 0;
$resident_update_count = 0;
$member_array = array();
$member_array[] = 'staff';
$member_array[] = 'resident';

    // get Target Population from configs;
    $Target_Population = get_default_target_population();

    // get log file location or create if not exist
    $logfile=get_log_file();
    if($logfile && !file_exists($logfile)){
        touch($logfile);
    }

    // get DB connection configs
    $conn = make_msqli_connection();

    // Security - check string
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

    // Covert to json object
    $community_blob = curl_init($community_request);
    curl_setopt($community_blob, CURLOPT_RETURNTRANSFER, true);
    $html = curl_exec($community_blob);
    $community_decoded = json_decode($html, true);
    curl_close($community_blob);

        //TESTING
        //$organization = "181d26cd-e8f4-4750-a7bb-04eef2773c4a";
    
    // Create list of communities
    $communities = array();
    if (isset($community_decoded)&&$community_decoded){
        foreach($community_decoded as $raw_community){
            // Since client community db will only be for their organization, do not need this if in pcs
            // if($raw_community['OrganisationID']==$organization){
            //  $communities[$raw_community['CommunityID']] = $raw_community['Name'];
            // }
            $communities[$raw_community['LocationID']] = $raw_community['Name'];
        }
    }else{
        $return_message = $return_messge." Could not collect communities from DB";
    }
    // Community Check
    //print_r($communities);
    $serialize_communities = serialize($communities);

    // For Legacy DB structure
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
                        
                        //$sql="SELECT * FROM personaldata WHERE password LIKE '$pwd' OR (first='$first' AND last='$last')";
                        // Need to do this since some entries have same name
                        $sql="SELECT * FROM personaldata WHERE password LIKE '$pwd'";

                    }

                    $check=mysqli_query($conn,$sql);

                    if(!$check || mysqli_num_rows($check) == 0){

                        $house = str_replace(" ","-",$communities[$value['locationID']]);
                        
                        $community = serialize(array($value['locationID']=>$house));
                        
                        if($member==='resident'){
                            // Need to do this because of bad data 
                            $first_name = str_replace("'","",$value['firstName']);
                            $last_name = str_replace("'","",$value['lastName']);
                            mysqli_query($conn, "INSERT INTO residentpersonaldata VALUES(null,'$first_name','$last_name',null,'$gender','$privilegekey','$Target_Population','$house','$value[connectionID]','$community','$value[connectionID]')");
                            $resident_insert_count++;
                        }elseif($accesslevel){//Some entries did not have roles assigned in PCS data
                            // if(stripos(strtolower($value['role']),"manager")!==false){
                            //  $accesslevel="admin";
                            // }else if(stripos(strtolower($value['role']),"carer")!==false || stripos(strtolower($value['role']),"nurse")!==false){
                            //  $accesslevel='caregiver';
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

                            }elseif($accesslevel&&$member!=='resident'&&$row1['accesslevel']!==$accesslevel){
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

// Logging
if($return_message){
    echo $return_message;
}else{
    if(isset($_SESSION['personaldatakey'])){
        $personaldatakey=$_SESSION['personaldatakey'];
    }else{
        $personaldatakey='auto';
    }
    mysqli_query($conn, "INSERT INTO db_sync_log (id, organization_id,personaldatakey) VALUES(null,'$apikey','$personaldatakey')");
    echo $resident_insert_count." Residents loaded. ";
    echo $resident_update_count." Residents updated. ";
    echo $staff_insert_count." Staff loaded. ";
    echo $staff_update_count." Staff updated. ";
}

?>                  




