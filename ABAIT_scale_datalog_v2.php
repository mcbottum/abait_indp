<?
session_start();
include("ABAIT_function_file.php");

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

</head>
<body class="container">

<?
$names = build_page_pg();
?>								
<h3 align='center'><label>Scale Data Log</label></h3>
		
<?
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'], $_SESSION['db']) or die(mysqli_error());
	
	if(isset($_REQUEST['date'])){
		$date_bool=$_REQUEST['date'];
	}else{
		$date_bool=null;
	}

	if($date_bool=="NOW"){
		$raw_date=date("Y-m-d H:i:s");
		$date_time = explode(" ", $raw_date);
		$date = $date_time[0];
		$time = $date_time[1];
		
	}else{
		$raw_date=$_REQUEST['datetimepicker'];
		$date_time = explode("T", $raw_date);
		$date = $date_time[0];
		$time = $date_time[1];
		$seconds='00';
		$time = $time.':'.$seconds;
	}

	$duration=$_REQUEST['duration'];
	if($duration=="More than 5"){
		$duration=10;
	}


	$behavior_description=$_REQUEST['behavior_description'];
	if($behavior_description=='Enter specific description of behavior which required PRN here.'){
		$behavior_description='';
	}

	$intensity_before=$_REQUEST['intensityB'];
	$trig=1;
	$mapkey=$_SESSION['trigger'];
	$residentkey=$_SESSION['residentkey'];
		
	for($i=1;$i<7;$i++){
		//NOTE intensityA= intensity after intensityB =intensity before any intervention
		$intervention[]=$_REQUEST['intervention'.$i];
		if(isset($_REQUEST['intensityA'.$i])){
			$intensityA[]=$_REQUEST['intensityA'.$i];
		}else{
			$intensityA[]=0;
		}
	}
	if($intervention[5]==1){
		$PRN=1;
	}else{
		$PRN=0;
	}
	$behavior=$_SESSION['scale_name'];

	// $datacheck=array($date,$hour,$minute,$time,$trigger,$duration,$intensity_before,$intervention[0],
	// $intervention[1],$intervention[2],$intervention[3],$intervention[4],$intervention[5],$intensityA[0],$intensityA[1],$intensityA[2],$intensityA[3],$intensityA[4],$intensityA[5],$PRN);

	$intervention_score_0 = 0;
	$intervention_score_1 = 0;
	$intervention_score_2 = 0;
	$intervention_score_3 = 0;
	$intervention_score_4 = 0;
	$intervention_score_5 = 0;
	$intervention_score_6 = 0;

	${'intervention_score_'.$intervention[0]}=($intensity_before-$intensityA[0]);
	
	if(isset($intensityA[1])){
		${'intervention_score_'.$intervention[1]}=$intensityA[0]-$intensityA[1];
	}
	elseif(isset($intensityA[5])){
		$intervention_score_6=$intensityA[0]-$intensityA[5];
	}
	
	if(isset($intensityA[2])){
		${'intervention_score_'.$intervention[2]}=$intensityA[1]-$intensityA[2];
	}
	elseif(isset($intensityA[5])){
		$intervention_score_6=$intensityA[1]-$intensityA[5];
	}
	
	if(isset($intensityA[3])){
	${'intervention_score_'.$intervention[3]}=$intensityA[2]-$intensityA[3];
	}
	elseif(isset($intensityA[5])){
		$intervention_score_6=$intensityA[2]-$intensityA[5];
	}
	
	if(isset($intensityA[4])){
	${'intervention_score_'.$intervention[4]}=$intensityA[3]-$intensityA[4];
	}
	elseif(isset($intensityA[5])){
		$intervention_score_6=$intensityA[3]-$intensityA[5];
	}

	if (isset($_POST['onstaff'])) {
	    $onstaff = implode(",",$_POST['onstaff']);
	}else{
		$onstaff="";
	}

	if (isset($_POST['presentincident'])) {
	    $presentincident = implode(",",$_POST['presentincident']);
	}else{
		$presentincident="";
	}
	if (isset($_POST['presentintervention'])) {
	    $presentintervention = implode(",",$_POST['presentintervention']);
	}else{
		$presentintervention="";
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
						$carer=$row['first']." ".$row['last'];
						$r_sql = "SELECT * from residentpersonaldata where residentkey ='$residentkey'";
						$r_session=mysqli_query($conn,$r_sql);
						$row=mysqli_fetch_assoc($r_session);
						$resident=$row['first']." ".$row['last'];


//// GATHERING INFO ABOUT PRN OR EMERGENCY SERVICES AND SENDING EMAIL


						$PRN=$_REQUEST['PRN'];

						if($PRN){
							$pre_PRN_observation=$_REQUEST['PRN'];
							$pre_PRN_observation = implode(',',$_POST['emergency_intervention']);
							$service = $_POST['emergency_intervention'];
						}else{
							$pre_PRN_observation = Null;
							$service = Null;							
						}


						if($PRN){
							$sender='admin@abehave.com';
							$recipient='michael@abehave.com';

							//get episode contact
							$sql_contact = "SELECT * from episode_contact  WHERE contact_category='during'";
							$session_contact = mysqli_query($conn,$sql_contact);
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
							$body .= "<p><b>The episode occured at: </b>".$time. " on ".$date."</p><p><b>Duration: </b>".$duration." minutes.</p><p><b>Behavior Descriptions: </b>".$behavior." ,".$behavior_description."</p><p><b>Trigger: </b>".$trigger."</p><p><b>Additional services  required to manage the episode: </b>".$contact_string;

							// echo $body;

							mysqli_query($conn, "INSERT INTO notification_queue VALUES(null,'$body','$recipients',null)");
						}


	$post_PRN_observation = null;
	
	//mysqli_select_db($_SESSION['database'],$conn);	

	mysqli_query($conn, "INSERT INTO behavior_map_data VALUES(null,'$mapkey','$_SESSION[residentkey]','$behavior','$date','$time','$intervention_score_1','$intervention_score_2','$intervention_score_3','$intervention_score_4','$intervention_score_5','$intervention_score_6','$duration','$PRN','$behavior_description','$onstaff','$presentincident','$presentintervention','$intensity_before','$pre_PRN_observation',null,'$_SESSION[personaldatakey]')");
	$first=$_SESSION['first'];
	$last=$_SESSION['last'];
	
	if($date&&$time){
		print "<h4 align='center'> Scale Data for $first $last has been Logged</h4>\n";
	}else{
		print "<h4>Some information was missing from the Scale form, please return to the previous page.</h4>\n";
	}
	print "<div id='submit'>";
					print "<input	
								type = 'button'
								name = ''
								id = 'backButton'
								value = \"Log Another '$behavior' Episode \"
								onClick=\"backButton('ABAIT_scale_v2.php','$behavior')\"/>\n";

	print "</div>"

?>

	
	</form>
<?build_footer_pg()?>
</body>
</html>