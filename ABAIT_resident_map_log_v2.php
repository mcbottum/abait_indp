<?
include("ABAIT_function_file.php");
session_start();
if($_SESSION['passwordcheck']!='pass'){
	header("Location:".$_SESSION['logout']);
	print $_SESSION['passwordcheck'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? print"<link rel='shortcut icon' href='$_SESSION[favicon]' type='image/x-icon'>";?>
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

<script>
	function backButton(location, behavior, id) {
		if(location=='ABAIT_caregiverhome_v2.php'){
			self.location=location;
		}else if(location=='ABAIT_resident_map_v2.php'){
			self.location=location+'?scale_name='+behavior;
		}
		
	}
</script>
<style>
	ul{
		list-style-type:none;
	}
	#submit input{
		color:#A65100 !important;
	}
</style>
</head>
<div class='content'>
<body class="container">

	<?
	$names = build_page_pg();
    if($_SESSION['country_location']=='UK'){
        $behavior_spelling = 'Behaviour';
        $vocalization_spelling = 'Vocalisation';
        $characterization_spelling = 'Characterisation';
        $date_format = 'dd-mm-yyyy';
    }else{
        $behavior_spelling = 'Behavior';
        $vocalization_spelling = 'Vocalization';
        $characterization_spelling = 'Characterization';
    }


	
		
	print "<h3 class='m-4' align='center'>$behavior_spelling $characterization_spelling Session</h3>";
		
		$conn=make_msqli_connection();

		$raw_date=$_REQUEST['datetimepicker'];
		## new datepicker
		$date_time = explode("T", $raw_date);
		$date = $date_time[0];
		$time = $date_time[1].":00";

$dt = new DateTime($raw_date);




		## old datepicker
		// $date_time = explode(" ", $raw_date);
		// $date = $date_time[0];
		// $date = str_replace('/',',',$date);
		// $time = $date_time[1];




		$time_stamp = str_replace("T", " ", $raw_date).":00";

// Check for daylight saving time in England !!!!!
if(date('m-d',strtotime('2022-03-27' )) < date('m-d') && date('m-d',strtotime('2022-10-30' )) > date('m-d')){
	$dt = new DateTime($raw_date);
	$dt->sub(new DateInterval('PT1H'));
	$time_stamp = $dt->format('Y-m-d H:i:s');
}


		$duration=$_REQUEST['duration'];

		if($duration=="More than 5"){
			$duration=10;
		}


		$trigger=$_REQUEST['trigger'];
		$trigger=str_replace('_',' ',$trigger);
		if($trigger=='other'){
			$trigger=$_REQUEST['custom_trigger'];
		}

		// if($trigger==)

		$intervention=$_REQUEST['intervention'];
		$behavior=str_replace('_',' ',$_REQUEST['scale_name']);

		if($behavior=="Slow Trigger"){
			$slow_trigger=1;
		}else{
			$slow_trigger=0;
		}

		if($_REQUEST['intensity']!=""){
			$intensity=$_REQUEST['intensity'];
		}else{
			$intensity = 0;
		}


		if($_REQUEST['behave_class']!=""){
			$behave_class=$_REQUEST['behave_class'];
		}else{
			$behave_class = 0;
		}


		
		$behavior_description=$_REQUEST['specific_behavior_description'];
		$intervention_avoid=$_REQUEST['intervention_avoid'];

		if(!$intervention_avoid){
			$intervention_avoid='none';
		}


		// New way (checkbox per staff member)
		$onstaff = [];
		$presentincident = [];
		$presentintervention = [];

		// assumes reporting staff is present !!!!!
		array_push($onstaff, $_SESSION['personaldatakey']);
		array_push($presentincident, $_SESSION['personaldatakey']);
		array_push($presentintervention, $_SESSION['personaldatakey']);

		if(isset($_REQUEST['staff_present_1'])){
			$staff1 = $_REQUEST['staff_present_1'];
			if($staff1=='-2'){
				$staff1 = $_REQUEST['temp_staff_present_1'];
			}

			elseif($staff1=='-1'){
				$staff_name = explode(' ',$_REQUEST['alternative_staff_1_name']);
				$check = mysqli_query($conn, "SELECT * FROM personaldata WHERE first='$staff_name[0]' AND last='$staff_name[1]'");
				if(mysqli_num_rows($check)>0){
					$temp_carers=$check->fetch_all(MYSQLI_ASSOC);
					$staff1=$temp_carers[0]['personaldatakey'];
				}else{
				mysqli_query($conn,"INSERT INTO personaldata VALUES(null,'$date','temp','temp','$staff_name[0]','$staff_name[1]',null,null,null,null,null,null,null,null,null,'0','$Target_Population','temp')");
					$sql="SELECT personaldatakey FROM personaldata WHERE first='$staff_name[0]' AND last='$staff_name[1]'";
					if ($result = $mysqli -> query($conn, $sql)) {
						while ($row = $result -> fetch_row()) {
							$staff1=$row['personaldatakey'];
						}
						$result -> free_result();
					}
				}
			}



			if(isset($_REQUEST['onstaff1'])){
				array_push($onstaff, $staff1);
			}
			if(isset($_REQUEST['presentincident1'])){
				array_push($presentincident, $staff1);
			}
			if(isset($_REQUEST['presentintervention1'])){
				array_push($presentintervention, $staff1);
			}
		}
		if(isset($_REQUEST['staff_present_2'])){
			$staff2 = $_REQUEST['staff_present_2'];
			if($staff2=='-2'){
				$staff2 = $_REQUEST['temp_staff_present_2'];
			}


			elseif($staff2=='-1'){
				$staff_name = explode(' ',$_REQUEST['alternative_staff_2_name']);
				$check = mysqli_query($conn, "SELECT * FROM personaldata WHERE first='$staff_name[0]' AND last='$staff_name[1]'");
				if(mysqli_num_rows($check)>0){
					$temp_carers=$check->fetch_all(MYSQLI_ASSOC);
					$staff2=$temp_carers[0]['personaldatakey'];
				}else{
				mysqli_query($conn,"INSERT INTO personaldata VALUES(null,'$date','temp','temp','$staff_name[0]','$staff_name[1]',null,null,null,null,null,null,null,null,null,'0','$Target_Population','temp')");
					$sql="SELECT personaldatakey FROM personaldata WHERE first='$staff_name[0]' AND last='$staff_name[1]'";
					if ($result = $mysqli -> query($conn, $sql)) {
						while ($row = $result -> fetch_row()) {
							$staff2=$row['personaldatakey'];
						}
						$result -> free_result();
					}
				}
			}

			if(isset($_REQUEST['onstaff2'])){
				array_push($onstaff, $staff2);
			}
			if(isset($_REQUEST['presentincident1'])){
				array_push($presentincident, $staff2);
			}
			if(isset($_REQUEST['presentintervention1'])){
				array_push($presentintervention, $staff2);
			}
		}
		if(isset($_REQUEST['staff_present_3'])){
			$staff3 = $_REQUEST['staff_present_3'];
			if($staff3=='-2'){
				$staff3 = $_REQUEST['temp_staff_present_3'];
			}


			elseif($staff3=='-1'){
				$staff_name = explode(' ',$_REQUEST['alternative_staff_3_name']);
				$check = mysqli_query($conn, "SELECT * FROM personaldata WHERE first='$staff_name[0]' AND last='$staff_name[1]'");
				if(mysqli_num_rows($check)>0){
					$temp_carers=$check->fetch_all(MYSQLI_ASSOC);
					$staff3=$temp_carers[0]['personaldatakey'];
				}else{
				mysqli_query($conn,"INSERT INTO personaldata VALUES(null,'$date','temp','temp','$staff_name[0]','$staff_name[1]',null,null,null,null,null,null,null,null,null,'0','$Target_Population','temp')");
					$sql="SELECT personaldatakey FROM personaldata WHERE first='$staff_name[0]' AND last='$staff_name[1]'";
					if ($result = $mysqli -> query($conn, $sql)) {
						while ($row = $result -> fetch_row()) {
							$staff3=$row['personaldatakey'];
						}
						$result -> free_result();
					}
				}
			}

			if(isset($_REQUEST['onstaff3'])){
				array_push($onstaff, $staff3);
			}
			if(isset($_REQUEST['presentincident3'])){
				array_push($presentincident, $staff3);
			}
			if(isset($_REQUEST['presentintervention3'])){
				array_push($presentintervention, $staff3);
			}
		}


		$onstaff = implode(",",$onstaff);
		$presentincident = implode(",",$presentincident);
		$presentintervention = implode(",",$presentintervention);

		$residentkey=$_SESSION['residentkey'];
		$personaldatakey=$_SESSION['personaldatakey'];

		$pd_sql = "SELECT * from personaldata WHERE personaldatakey='$personaldatakey'";
		$pd_session=mysqli_query($conn,$pd_sql);
		$row=mysqli_fetch_assoc($pd_session);
		$carer_apikey = $row['notify'];
		$carer=$row['first']." ".$row['last'];
		$r_sql = "SELECT * from residentpersonaldata where residentkey ='$residentkey'";
		$r_session=mysqli_query($conn,$r_sql);
		$row=mysqli_fetch_assoc($r_session);
		$resident=$row['first']." ".$row['last'];

		$resident_first = $row['first'];
		$resident_last = $row['last'];
		$resident_PersonID = $row['person_id'];

		if(check_for_vowel($behavior)){
			$identifier = "An";
		}else{
			$identifier = "A";
		}

		$ActionText = $identifier." ".$behavior." episode occured at ".$time. " on ".$date.", lasting ".$duration." minutes. The ".lcfirst($behavior_spelling)." was described as; ".$behavior_description.", caused by ".$trigger.".";


		$PRN=$_REQUEST['PRN'];

		if($PRN){
			$pre_PRN_observation=$_REQUEST['pre_PRN_observation'];
			
			if (isset($_REQUEST['emergency_intervention'])){
				$emergency_intervention = $_REQUEST['emergency_intervention'];
				$pre_PRN_observation = implode(',',$emergency_intervention);
				$service = $_POST['emergency_intervention'];
				$emergency_intervention_text = "An emergency_intervention was required due to: ".$pre_PRN_observation;
				$ActionText .=" ".$emergency_intervention_text;
			}else{
				$emergency_intervention = [];
				$ActionText .=" Medication was required due to; ".$pre_PRN_observation;
			}


			
			
		}else{
			$pre_PRN_observation = Null;
			$service = Null;							
		}



		if($PRN){
			$sender='admin@abehave.com';
			$recipient='michael@abehave.com';
			if($_SESSION['population_type']=='behavioral'){
				//get episode contact
				$sql_contact = "SELECT * from episode_contact  WHERE contact_category='during'";
				$session_contact = mysqli_query($conn,$sql_contact);
				if($session_contact !== false){
					$contact_data=$session_contact->fetch_all(MYSQLI_ASSOC);

					//get notification recipients
					$sql_notify = "SELECT * from personaldata  WHERE Target_Population='$_SESSION[Target_Population]' AND notify='1'";
					$session_notify = mysqli_query($conn, $sql_notify);
					$notify_data=$session_notify->fetch_all(MYSQLI_ASSOC);
					$recipients = [];
					foreach ($notify_data as  $value) {
						array_push($recipients, $value['personaldatakey']);
					}
					$recipients = implode(',',$recipients);

					$contact_string="";
					foreach ($contact_data as $row) {
						foreach ($service as $key) {	
							if($key==$row[id]){
								$contact_string .=$row[contact_type].", ";
							}
						}
					}

					$body = "<h3><p>".$carer. " managed an episode involving ".$resident."</p></h3>";
					$body .= "<p><b>The episode occured at: </b>".$time. " on ".$date."</p><p><b>Duration: </b>".$duration." minutes.</p><p><b>Behavior Descriptions: </b>".$behavior." ,".$behavior_description."</p><p><b>Caused by: </b>".$trigger."</p><p><b>Additional services  required to manage the episode: </b>".$contact_string;

					// echo $body;

					if($contact_string){
						$ActionText .= " Additional services  required to manage the episode: ".$contact_string;
					}

					mysqli_query($conn, "INSERT INTO notification_queue VALUES(null,'$body','$recipients',null)");


					///// THE BELOW DONE IN ABAIT_mailer.php AND CRONJOB  ////////
					// $conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'], $_SESSION['db']) or die(mysqli_error());
					// $sql_check = "SELECT * from notification_queue WHERE date_sent IS null";
					// $session_check = mysqli_query($conn, $sql_check);
					// $notify_data=$session_check->fetch_all(MYSQLI_ASSOC);

					// // Set default time zone
					// date_default_timezone_set('London');

					// // Then call the date functions
					// $date = date('Y-m-d H:i:s');

					// if($notify_data){

					// 	foreach ($notify_data as $value) {

					// 		$message_sent = False;
					// 		$sql_recipients = "SELECT mail FROM personaldata WHERE personaldatakey IN($value[recipients])";
					// 		$session_recipients = mysqli_query($conn, $sql_recipients);
					// 		$recipient_data=$session_recipients->fetch_all(MYSQLI_ASSOC);
					// 		foreach ($recipient_data as $value2['mail']) {
					// 			$message_sent = sendMail($sender,$value2['mail'],$value['message_body']);
					// 		}
					// 		if($message_sent){
					// 			mysqli_query($conn,"UPDATE notification_queue SET date_sent='$date' WHERE id='$value[id]'");
					// 		}
							
					// 	}
					// }
				}
			}elseif ($_SESSION['population_type']=='cognitive') {
				$body = "<h3><p>".$carer. " managed an episode involving ".$resident."</p></h3>";
				$body .= "<p><b>The episode occured at: </b>".$time. " on ".$date."</p><p><b>Duration: </b>".$duration." minutes.</p><p><b>Behavior Descriptions: </b>".$behavior." ,".$behavior_description."</p><p><b>Caused by: </b>".$trigger."</p>";
			}
			mysqli_query($conn, "INSERT INTO notification_queue VALUES(null,'$body','$recipients',null)");

		}

		$ActionText .= ". ".ucfirst($intervention)." seemed to work best to manage the ".lcfirst($behavior_spelling).".";
					
			

$configs = get_db_configs();
###### to send  Curl to MCS for care notes
	if($configs['send_care_note']){
		$RecordUUID = make_guid();

		if($PRN){
			$ActionIconID = '5003';
		}else{
			$ActionIconID = '5003';
		}

		$data_array = array(
		    'RecordUUID' => $RecordUUID,
		    'ConnectionID' => $resident_PersonID,
		    'FirstNames' => '',
		    'LastName' => '',
		    'ExternalPersonID' => '',
		    'DateOfBirth' => '',
		    'NHSNumber' => '',
		    'UTCDateTime' => $time_stamp,
		    'TimeZone' => 'Europe/London',
		    'ActionIconID' => $ActionIconID,
		    'ShortText' => 'ABAIT Note',
		    'ActionText' => $ActionText,
		    'IsHandover' => 'false',
		    'ExternalNoteID' => '',
		    'Measure1' => '',
		    'Measure2' => '',
		    'Sliders' => array('' => ''),
		);

		// $data_array = array(
		//     'RecordUUID' => $RecordUUID,
		//     'PersonID' => $resident_PersonID,
		//     'ExternalPersonID' => '',
		//     'NHSNumber' => '',
		//     'UTCDateTime' => $time_stamp,
		//     'TimeZone' => 'Europe/London',
		//     'ActionIconID' => $ActionIconID,
		//     'ShortText' => 'ABAIT Note',
		//     'ActionText' => $ActionText,
		//     'IsHandover' => 'false',
		//     'ExternalNoteID' => '',
		//     'Measure1' => '',
		//     'Measure2' => '',
		//     'Sliders' => array('' => ''),
		// );

		//$data=json_encode($data_array,JSON_FORCE_OBJECT);
		$data=json_encode($data_array);
		// echo $data;

		$devapikey = $configs['devapikey'];
		$care_note_url = "https://care.personcentredsoftware.com/integration/api/GenericAPI/insertcarenote?DevApikey=".$devapikey."&Apikey=".$carer_apikey;
	

		//$care_note_url = 'https://care.personcentredsoftware.com/integration/api/GenericAPI/insertcarenote?DevApikey=8de7a68c-f962-4fb1-a98a-1d08e3263dd9&Apikey=a09a69a2-dbe0-4a47-bf9c-9d5cc92e8434';

		$url = $care_note_url;

		$ch = curl_init($url);
		//$postString = http_build_query($data, '', '&');

		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));


		# Form data string
		////$postString = http_build_query($data, '', '&');

		////curl_setopt($ch, CURLOPT_POST, 1);
		////curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_VERBOSE, true);

		curl_setopt($ch, CURLOPT_FAILONERROR, true); // Required for HTTP error codes to be reported via our call to curl_error($ch)
		//...
		curl_exec($ch);
		if (curl_errno($ch)) {
		    $error_msg = curl_error($ch);
		}

		//// For Error Handling
		// if (isset($error_msg)) {
		//     print_r($error_msg);
		//     // TODO - Handle cURL error accordingly
		// }

		////Get the response
		// $response = curl_exec($ch);
		// print_r($response);
		// curl_close($ch);	

		mysqli_query($conn, "INSERT INTO care_notes VALUES(null, '$RecordUUID','$resident_PersonID','$ActionText','$time_stamp','$ActionIconID')");

	}								
					
						$sql="SELECT Target_Population FROM residentpersonaldata WHERE residentkey='$residentkey'";
						$session=mysqli_query($conn,$sql);
						$row=mysqli_fetch_assoc($session);
						$Target_Population=mysqli_real_escape_string($conn,$row['Target_Population']);

if($_SESSION['population_type']=='behavioral'){
						mysqli_query($conn, "INSERT INTO resident_mapping VALUES(null,'$residentkey','$Target_Population','$date','$time','$duration','$trigger','$slow_trigger','$intervention','$intervention_avoid','$behavior','$intensity','$behave_class','$behavior_description','$onstaff','$presentincident','$presentintervention','$PRN','$pre_PRN_observation',null,'$_SESSION[personaldatakey]',0)");
}else{

						mysqli_query($conn, "INSERT INTO resident_mapping VALUES(null,'$residentkey','$Target_Population','$date','$time','$duration','$trigger',null,'$intervention','$intervention_avoid','$behavior','$intensity','$behave_class','$behavior_description',null,null,null,'$PRN','$pre_PRN_observation',null,'$_SESSION[personaldatakey]',0)");
						// echo "INSERT INTO resident_mapping VALUES(null,'$residentkey','$Target_Population','$date','$time','$duration','$trigger',null,'$intervention','$intervention_avoid','$behavior','$intensity','$behave_class','$behavior_description',null,null,null,'$PRN','$pre_PRN_observation',null,'$_SESSION[personaldatakey]',0)";
}

						mysqli_close($conn);
						print "<h3 align='center'>  $date Mapping Data for $_SESSION[first] $_SESSION[last] has been Logged</h3>\n";
						print  "<h5 align='center'>$ActionText</h5>\n";
						print "<div id='submit'>";
						print "<ul align='center'>";
							print "<li>";
								print "<input	type = 'button'
											name = 'submit'
											id = 'backButton'
											value = \"Log Another '$behavior' Episode \"
											onClick=\"backButton('ABAIT_resident_map_v2.php','$behavior')\"/>\n";
							print "</li>";
						print "</ul>";	
						print "</div>";			
	?>	

	<?build_footer_pg()?>
</body>
</html>