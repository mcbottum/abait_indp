<?
include("ABAIT_function_file.php");session_start();
if($_SESSION['passwordcheck']!='pass'){
	header("Location:".$_SESSION['logout']);
	print $_SESSION['passwordcheck'];
}
date_default_timezone_set("Europe/London");
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
function validate_form()
{

	var scale_name = document.getElementById("scale_name");
	var scale_name_value = scale_name.options[scale_name.selectedIndex].value;

	valid=true;
	var alertstring=new String("");

	if(document.form.scale_name.selectedIndex=="0"){
		alertstring=alertstring+"\n-choose Behavior Scale-";
		document.form.scale_name.style.background = "Yellow";
		valid=false;
	}else{
		document.form.scale_name.style.background = "Lightgrey";
	}//end scale_name check
	
	if(document.form.trigger.selectedIndex=="0"){
		alertstring=alertstring+"\n-choose Behavior Trigger-";
		document.form.trigger.style.background = "Yellow";
		valid=false;
	}else{
		document.form.trigger.style.background = "Lightgrey";
	}//end trigger check

	if(document.form.intensity.selectedIndex=="0" && scale_name_value!='Slow_Trigger'){
		alertstring=alertstring+"\n-choose Behavior Intensity-";
		document.form.intensity.style.background = "Yellow";
		valid=false;
	}else{
		document.form.intensity.style.background = "Lightgrey";
	}//end intensity check
	
	if(document.form.behave_class.selectedIndex=="" && scale_name_value!='Slow_Trigger'){
		alertstring=alertstring+"\n-choose Behavior Classification-";
		document.form.behave_class.style.background = "Yellow";
		valid=false;
	}else{
		document.form.behave_class.style.background = "Lightgrey";
	}//end intensity check
	
	if(document.form.specific_behavior_description.value=="")
	{
		alertstring=alertstring+"\n-enter a Specific Behavior Description-";
		document.form.specific_behavior_description.style.background = "Yellow";
		valid=false;
	}else{
		document.form.specific_behavior_description.style.background = "Lightgrey";
	}//end specific_behavior_description

	if(document.form.custom_trigger.value=="other")
	{
		alertstring=alertstring+"\n-Please provide more specific trigger-";
		document.form.custom_trigger.background = "Yellow";
		valid=false;
	}else{
		document.form.custom_trigger.style.background = "Lightgrey";
	}//end custom_trigger


	if(document.form.trigger.selectedIndex==""){
		alertstring=alertstring+"\n-choose Trigger-";
		document.form.trigger.style.background = "Yellow";
		valid=false;
	}else{
		document.form.trigger.background = "Lightgrey";
	}//end trigger check
	
	if(document.form.intervention.value=="")
	{
		alertstring=alertstring+"\n-enter an Intervention Description-";
		document.form.intervention.style.background = "Yellow";
		valid=false;
	}else{
		document.form.intervention.style.background = "Lightgrey";
	}//end intervention
	

	if (document.form.datetimepicker.value=="" ) { 
		alertstring=alertstring+"\n-date of episode-";
		document.form.datetimepicker.style.background = "Yellow";

		valid=false;
	}else{
		document.form.datetimepicker.style.background = "Lightgrey";	
	}

	
	var numericExpression = /^[0-9]+$/;
	if(!document.form.duration.value.match(numericExpression)){
		document.form.duration.style.background = 'Yellow';
		valid=false;
		alertstring=alertstring+"\n-enter duration of episode (minutes)-";
	}else{
		document.form.duration.style.background = "Lightgrey";
	}//end duration check

	if(valid==false){
		alert("Please enter the following data;" + alertstring);
	}//generate the conncanated alert message

	document.form.submit.style.color = "#A65100";
	return valid;
}//end form validation function	

function reload(form){
	var val=form.scale_name.options[form.scale_name.options.selectedIndex].value;
	self.location='ABAIT_resident_map_v2.php?scale_name='+val;	
}

function show( selTag ) {

	obj1 = document.getElementById("pre_PRN_observation_tag");
	obj = document.getElementById("pre_PRN_observation");
	customTrig = document.getElementById("custom_trigger");
	customSlowTrig = document.getElementById("custom_slow_trigger");

	var scale_name = document.getElementById("scale_name");
	var scale_name_value = scale_name.options[scale_name.selectedIndex].value;
	var all_hide_elements = document.getElementsByClassName("hide");
	var all_show_elements = document.getElementsByClassName("show");


	if ( selTag.value== 'other' ){
		customTrig.style.display = "block";
	}else if ( selTag.value== 'new' ){
		customSlowTrig.style.display = "block";
	}else if (selTag.id=="PRN_div" && selTag.selectedIndex == 1 ) {
		alert ("here")
		obj1.style.display = "block";
		obj.style.display = "block";
		obj1.style.align="center";
	
	}else if (scale_name_value == "Slow_Trigger") {
		document.getElementById("hide_step3_if_slow").style.display = "none";
		document.getElementById("hide_step4_if_slow").style.display = "none";
		for(var i=0; i < all_hide_elements.length; i++) {
			all_hide_elements[i].style.display = "none";
		 }
		for(var i=0; i < all_show_elements.length; i++) {
			all_show_elements[i].style.display = "block";
		 }
		// document.getElementsByClassName("hide").style.display = "none";

	}else {
		obj1.style.display = "none";
		obj.style.display = "none";
	}
	if(selTag.id=='staff_present_1' && selTag.value > 1){
		document.getElementById("staff_present_checkbox_div_1").style.display = "block";
		document.getElementById("staff_present_2").style.display = "block";
		document.getElementById("alternative_staff1").style.display = "none";
		document.getElementById("temp_staff_present_1").style.display = "none";
	}else if(selTag.id=='staff_present_1' && selTag.value==0){
		document.getElementById("staff_present_checkbox_div_1").style.display = "none";
		document.getElementById("alternative_staff1").style.display = "none";
		document.getElementById("staff_present_checkbox_div_1").style.display = "block";
	}else if(selTag.id=='staff_present_1' && selTag.value==-1){
		document.getElementById("alternative_staff1").style.display = "block";
		document.getElementById("staff_present_checkbox_div_1").style.display = "block";
		document.getElementById("staff_present_2").style.display = "block";
		document.getElementById("temp_staff_present_1").style.display = "none";
	}else if(selTag.id=='staff_present_1' && selTag.value==-2){
		document.getElementById("staff_present_checkbox_div_1").style.display = "block";
		document.getElementById("staff_present_2").style.display = "block";
		document.getElementById("alternative_staff1").style.display = "none";
		document.getElementById("temp_staff_present_1").style.display = "block";
	}

	if(selTag.id=='staff_present_2' && selTag.value > 1){
		document.getElementById("staff_present_checkbox_div_2").style.display = "block";
		document.getElementById("staff_present_3").style.display = "block";
		document.getElementById("alternative_staff2").style.display = "none";
		document.getElementById("temp_staff_present_2").style.display = "none";
	}else if(selTag.id=='staff_present_2' && selTag.value==0){
		document.getElementById("staff_present_checkbox_div_2").style.display = "none";
		document.getElementById("alternative_staff2").style.display = "none";
	}else if(selTag.id=='staff_present_2' && selTag.value==-1){
		document.getElementById("alternative_staff2").style.display = "block";
		document.getElementById("staff_present_checkbox_div_2").style.display = "block";
		document.getElementById("staff_present_3").style.display = "block";
		document.getElementById("temp_staff_present_2").style.display = "none";
	}else if(selTag.id=='staff_present_2' && selTag.value==-2){
		document.getElementById("staff_present_checkbox_div_2").style.display = "block";
		document.getElementById("staff_present_3").style.display = "block";
		document.getElementById("alternative_staff2").style.display = "none";
		document.getElementById("temp_staff_present_2").style.display = "block";
	}

	if(selTag.id=='staff_present_3' && selTag.value > 1){
		document.getElementById("staff_present_checkbox_div_3").style.display = "block";
		// document.getElementById("staff_present_4").style.display = "block";
		document.getElementById("alternative_staff3").style.display = "none";
		document.getElementById("temp_staff_present_3").style.display = "none";
	}else if(selTag.id=='staff_present_3' && selTag.value==0){
		document.getElementById("staff_present_checkbox_div_3").style.display = "none";
		document.getElementById("alternative_staff2").style.display = "none";

	}else if(selTag.id=='staff_present_3' && selTag.value==-1){
		document.getElementById("staff_present_checkbox_div_3").style.display = "none";
		document.getElementById("alternative_staff3").style.display = "block";
		document.getElementById("staff_present_checkbox_div_3").style.display = "block";
		document.getElementById("temp_staff_present_3").style.display = "none";
	}else if(selTag.id=='staff_present_3' && selTag.value==-2){
		document.getElementById("staff_present_checkbox_div_3").style.display = "block";
		// document.getElementById("staff_present_2").style.display = "block";
		document.getElementById("alternative_staff3").style.display = "none";
		document.getElementById("temp_staff_present_3").style.display = "block";
	}
}

function checkDate(){
    var todaysDate = new Date();
    var selDate = new Date(form.datetimepicker.value)
    if(selDate > todaysDate){
        alert("The Selected date may not be in the future (" + todaysDate +")");
        document.form.datetimepicker.value = "";
    }else{
        document.form.datetimepicker.style.background = "White";
    }
}

function check(selTag) {
	if(document.getElementById("presentincident1").checked == true || document.getElementById("presentintervention1").checked == true){
		document.getElementById("onstaff1").checked = true;
	}else{
		document.getElementById("onstaff1").checked = false;
	}
	if(document.getElementById("presentincident2").checked == true || document.getElementById("presentintervention2").checked == true){
		document.getElementById("onstaff2").checked = true;
	}else{
		document.getElementById("onstaff2").checked = false;
	}
	if(document.getElementById("presentincident3").checked == true || document.getElementById("presentintervention3").checked == true){
		document.getElementById("onstaff3").checked = true;
	}else{
		document.getElementById("onstaff3").checked = false;
	}
}

</script>


<style>

	.center {
		width: 70%;
	}
	.btn.btn-lg {
	    background-color: #03DAC5;
	    border-radius: 10px;
	    font-size: 1.5em;
	    color: black;
	}
    .form-control {
        background-color: #03DAC5;
    }
	.form-control-ta {
		background-color: #F5F5F5;
	}

	.btn-lg:hover {
		background-color: #1FC4B4;
		box-shadow: 1px 1px 15px #888888;
	    border-style:solid;
	    border-width:1px;
	    color: black;
	}
	.custom-select {
		background: #03DAC5;
	}
	.custom-select-background {
        background: #03DAC5;
        width:200px;
    }
	.custom-select:hover {
		background: #1FC4B4;
	}

	label {
		padding-left: 10px;
	}
	#datetimepicker2{
		width: auto !important
	}

</style>
</head>
<body id='body' class="container" onload="show(this)">

	<?
	$names = build_page_pg();

	if(isset($_REQUEST['scale_name'])){
		$scale_name=str_replace('_',' ',$_REQUEST['scale_name']); // Use this line or below line if register_global is off
	}else{
		$scale_name=null;
	}
	$conn=make_msqli_connection();
	if(isset($_GET["k"])){
		$residentkey=$_GET["k"];
		$sql1="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey'";
		$resident=mysqli_query($conn,$sql1);
		$row1=mysqli_fetch_assoc($resident);
		$_SESSION['row1']=$row1;
		$_SESSION['residentkey'] = $residentkey;
	}elseif(isset($_REQUEST["resident_choice"])){
		$residentkey=$_REQUEST["resident_choice"];
		$_SESSION['residentkey'] = $residentkey;
	}else{
		$residentkey=null;
	}

	if(isset($_GET["scale_name"])){
		$scale_name=$_GET["scale_name"];
	}

	if(!$scale_name){
		$sql1="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey'";
		$resident=mysqli_query($conn,$sql1);
		$row1=mysqli_fetch_assoc($resident);
		$_SESSION['row1']=$row1;
		$Population_strip=mysqli_real_escape_string($conn,$row1['Target_Population']);
		$sql2="SELECT * FROM scale_table WHERE Target_Population='$Population_strip'";
		$session2=mysqli_query($conn,$sql2);
		$session3="";
	}
	elseif($scale_name){
		$sn=str_replace('_',' ',$scale_name);

		$Target_Population=$_SESSION['row1']['Target_Population'];

		// $_SESSION['Target_Population_holder']=$row1['Target_Population'];
		$Population_strip=mysqli_real_escape_string($conn,$Target_Population);
		$sql3="SELECT * FROM scale_table WHERE Target_Population='$Population_strip' AND scale_name='$sn'";
		$sql2="SELECT * FROM scale_table WHERE Target_Population='$Population_strip'";
		$session2=mysqli_query($conn,$sql2);	
		$session3=mysqli_query($conn,$sql3);
	}

	// For getting caregivers
	$sql_carer = "SELECT * from personaldata WHERE Target_Population='$Population_strip' ORDER BY last";
	$session_carer = mysqli_query($conn,$sql_carer);
	$carer_data=$session_carer->fetch_all(MYSQLI_ASSOC);

	//get episode contact
	$sql_contact = "SELECT * from episode_contact WHERE Target_Population='$Population_strip' AND contact_category='during'";
	$session_contact = mysqli_query($conn,$sql_contact);
	$contact_data=$session_contact->fetch_all(MYSQLI_ASSOC);	

	//Get house Carer Names
	$Population_strip=mysqli_real_escape_string($conn,$Target_Population);
	$sql4="SELECT * FROM personaldata WHERE House='$_SESSION[house]'";
	$session4=mysqli_query($conn,$sql4);

	// Get all Carer Names
	$Population_strip=mysqli_real_escape_string($conn,$Target_Population);
	$sql6="SELECT * FROM personaldata WHERE Target_Population='$Population_strip' ORDER BY last";
	$session6=mysqli_query($conn,$sql6);

	//Get slow triggers
	$sql5="SELECT * FROM scale_table WHERE  scale_name='Slow Trigger'";
	$session5=mysqli_query($conn,$sql5);
	if($session5){
		$row5 = mysqli_fetch_array($session5);
		$slow_triggers = explode(',',$row5['triggers']);
	} 

	$_SESSION['first']=$_SESSION['row1']['first'];
	$_SESSION['last']=$_SESSION['row1']['last'];
		
		?>

	<form	name= 'form'
			onsubmit='return validate_form()'
			action = "ABAIT_resident_map_log_v2.php"
			method = "post">




<div class="container">
  	<div class="row justify-content-md-center">
    	<div class="col col-lg-auto">


				<h3 class="m-4">
					<?
					print"Behavior Episode Characterization Form for $_SESSION[first] $_SESSION[last]";
					?>
				</h3>
		</div>
	</div>
  	<div class="row justify-content-md-center">
    	<div class="col col-lg-auto">
				<h4 class="m-4" style='color:grey'>Behavior and Intervention Information</h4>
		</div>
	</div>	
  	<div class="row justify-content-md-center">
    	<div class="col col-lg-auto">
				<h3 style='color: grey'>STEP 1</h3> 
		</div>
	</div>
	<div class="row justify-content-md-center">
		<div class="col col-lg-auto">
			<h4>General category of behavior</h4>
		</div>
	</div>
  	<div class="row justify-content-md-center">
    	<div class="col col-lg-6">	
			<?
			$scale_name=str_replace('_',' ',$scale_name);
			
						
					print "<select class='custom-select custom-select-lg mb-3' data-width='auto' name='scale_name' id='scale_name' onchange=\"reload(this.form)\"><option value=''>Select a Behavior Classification</option>";
						while($row2 = mysqli_fetch_array($session2)) { 
							$sn=str_replace(' ','_',$row2['scale_name']);

							if($row2['scale_name']==$scale_name){
								
								print "<option selected value='$sn'>$row2[scale_name]</option>";
								}
							else{
								print  "<option value='$sn'>$row2[scale_name]</option>";
							}
						}
					print "</select>";
				
			print"</div>";
					if($session3){
						$row3 = mysqli_fetch_array($session3);
						$triggers = explode(',',$row3['triggers']);
						}
		print "</div>";
		print"<div class='row justify-content-md-center'>";
			print "<div class='col col-lg-auto'>";
				print "<h3 style='color: grey'>STEP 2</h3>";
			print "</div>";
		print "</div>";

		print"<div class='row justify-content-md-center'>";
			print "<div class='col col-lg-auto'>";		
				print"<h4> What caused the behavior?</label></h4>";
			print "</div>";
		print "</div>";

		print"<div class='row justify-content-md-center'>";
			print "<div class='col col-lg-6'>";

					print "<select class='custom-select custom-select-lg mb-3' data-width='auto' name='trigger' id='trigger' onchange='show(this)'><option value=''>Select Cause (trigger)</option>";
						print "<option value='other'>None of the below</option>";
						foreach($triggers as $trigger){
							$trigger_strip=str_replace(' ','_',$trigger);
							print "<option value=$trigger_strip>$trigger</option>";
						}
						
					print "</select>";
					print "<input type = 'text' name ='custom_trigger' id='custom_trigger' class='textBox' style='display: none; background-color: GreenYellow' placeholder='Enter cause' value=''  autofocus='autofocus' onfocus=\"if(this.value==this.defaultValue) this.value='';\"/>";

			
				if($row3){	
					reset($row3);
				}
			print "</div>";
			// print "<div class='col col-lg-3'>";
			
			// 		print "<select class='custom-select custom-select-lg mb-3' data-width='auto' name='slow_trigger' id='slow_trigger' onchange='show(this)'><option value=''>Slow Trigger (optional) </option>";
			// 			print "<option value='new'>None of the below</option>";
			// 			foreach($slow_triggers as $st){
			// 				$st_strip=str_replace(' ','_',$st);
			// 				print "<option value=$st_strip>$st</option>";
			// 			}
						
			// 		print "</select>";
			// 		print "<input type = 'text' name ='custom_slow_trigger' id='custom_slow_trigger' class='textBox' style='display: none; background-color: GreenYellow' placeholder='Enter new slow trigger here' value=''  autofocus='autofocus' onfocus=\"if(this.value==this.defaultValue) this.value='';\"/>";
			
			// 	if($row3){	
			// 		reset($row3);
			// 	}
			// print "</div>";
		print "</div>";
		



	print"<div id='hide_step3_if_slow'>";
		print"<div class='row justify-content-md-center'>";
			print "<div class='col col-lg-auto'>";
				print"<h3 style='color: grey'>STEP 3</h3>";
			print "</div>";
		print "</div>";

		// Behavior class description
		print"<div class='row justify-content-md-center'>";
			print "<div class='col col-lg-auto'>";
				print" <h4> Behavior Description</h4>";
			print "</div>";
		print "</div>";

		print"<div class='row justify-content-md-center'>";
			print "<div class='col col-lg-6'>";
					echo "<select class='custom-select custom-select-lg mb-3' data-width='auto' name='behave_class' id='behave_class'><option value=''>Choose a Behavior Descriptor</option>";
						reset($row3);
						for ($i=1;$i<7;$i++){
							$behave_class_number='behave_class_'.$i;
							if($row3[$behave_class_number]){
								echo  "<option value='$i'>$row3[$behave_class_number]</option>";
							}
						}
					echo "</select>";
			print"</div>";
		print "</div>";
	print "</div>";

	print"<div id='hide_step4_if_slow' style='display:block'>";
		print "<div class='row justify-content-md-center'>";
			print"<div class='col col-lg-auto'>";
				print"<h3 style='color: grey'>STEP 4</h3>";
			print "</div>";
		print "</div>";

		print "<div class='row justify-content-md-center'>";
			print"<div class='col col-lg-auto'>";
				print"<h4> Identify Behavior Intensity</h4>";
			print "</div>";
		print "</div>";

		print "<div class='row justify-content-md-center'>";
			print"<div class='col col-lg-6'>";
					echo "<select class='custom-select custom-select-lg mb-3' data-width='auto' name='intensity' id='intensity'><option value=''>Select a Behavior Intensity</option>";
					
						for ($i=1;$i<6;$i++){
							$comment_number='comment_'.$i;
							if($row3[$comment_number]){
								echo  "<option value='$i'>$row3[$comment_number]</option>";
							}
						}
					echo "</select>";
					
			print"</div>";
				if($row3){	
					reset($row3);
				}
		print "</div>";
	print "</div>";
	?>
		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>
				<div id = "trigger">
					<h3 style='color: grey'><span class='hide'>STEP 5</span> <span class='show' style='display: none'>STEP 3</span></h3>
				</div>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>
					<h4>Any other <span class='hide'>unique</span> comments about the behavior?</h4>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class='col col-lg-8'>
					<textarea class="form-control form-control-ta" placeholder="Required..."  id ="specific_behavior_description" name = "specific_behavior_description"/></textarea>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>
				<div id = "intervention">
					<h3 style='color: grey'><span class='hide'>STEP 6</span> <span class='show' style='display: none'>STEP 4</span></h3>
				</div>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>					
				<h4>How did you manage the episode?</h4>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class='col col-lg-8'>
					<textarea class="form-control form-control-ta" placeholder="Required..."  id ="intervention" name = "intervention"/></textarea>
				</div>
			</div>

		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>
				<div id = "intervention_avoid">
					<h3 style='color: grey'><span class='hide'>STEP 7</span> <span class='show' style='display: none'>STEP 5</span></h3>
				</div>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>
					<h4>Did anything make the behavior more severe?</h4><br>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class='col col-lg-8'>
					<!-- <em style="font-size:10pt; line-height:0pt; color:red">Optional</em><br> -->
					<textarea class="form-control form-control-ta" placeholder="Optional..." id ="interv_a" name = "intervention_avoid"/></textarea>
				</div>
			</div>


		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>
				<div id = "onstaff">
					<h3 style='color: grey'><span class='hide'>STEP 8</span> <span class='show' style='display: none'>STEP 6</span></h3>
				</div>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>
					<h4><label> Other Staff Present?</label></h4>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>
					<?

// for select dropdown for staff 1 present  id='staff_present_1' id='staff_present_checkbox_div_1'
	 	$i=0;
		print"<div class='row justify-content-md-center'>";
			print"<div class='col col-lg-auto pr-0'>";
				print "<select class='custom-select custom-select-lg mb-3' data-width='auto' name='staff_present_1' id='staff_present_1' onchange='show(this)'>";
					print "<option value=''>Select Staff Member</option>";
					foreach ($carer_data as $row) {
						if($row[house]==$_SESSION['house'] && $_SESSION['personaldatakey']!=$row['personaldatakey']){
							print "<option value='$row[personaldatakey]'>$row[first] $row[last]</option>";
						}
					}
					print "<option value='-2'>Temporary Staff</option>";
					print "<option value='-1'>Agency Staff</option>";
				print"</select>";

			print"</div>";

			print"<div class='col col-lg-auto pr-0'>";
				print "<select class='custom-select custom-select-lg mb-3'  data-width='auto' name='temp_staff_present_1' id='temp_staff_present_1' style='display: none;' onchange='show(this)'>";
					print "<option value=''>Select Staff Member</option>";
					foreach ($carer_data as $row) {
						if( $_SESSION['personaldatakey']!=$row['personaldatakey']){
							print "<option value='$row[personaldatakey]'>$row[first] $row[last]</option>";
						}
					}
				print"</select>";
			print"</div>";

			print"<div class='col col-lg-auto ml-0 mb-2' id='alternative_staff1' style='display: none;'>";
				print"<textarea class='form-control form-control-ta' autofocus='autofocus' placeholder='Enter first and last name'  id='alternative_staff_1_name' name='alternative_staff_1_name'/></textarea>";
			print"</div>";

			print"<div class='col col-lg-auto ml-0 mb-2' id='staff_present_checkbox_div_1' style='display: none;'>";		
				print "<table class='table-sm table-hover table-bordered ' align='center'>";
					print"<tr><th style='text-align: center'>Interaction</th></tr>";
					print "<tr>";
						print "<td>";
							print"<input type = 'checkbox'
							name = 'onstaff1'
							id = 'onstaff1'
							value = '1'/>";
							print"<label for='onstaff1'>On Staff</label>";
						print "</td>";
					print "</tr>";
					print "<tr>";
						print "<td>";
							print"<input type = 'checkbox' onchange='check(this)'
							name = 'presentincident1'
							id = 'presentincident1'
							value = '1'/>";
							print"<label for='presentincident1'>Present during incident</label>";
						print "</td>";
					print "</tr>";
					print "<tr>";
						print "<td>";
							print"<input type = 'checkbox' onchange='check(this)'
							name = 'presentintervention1'
							id = 'presentintervention1'
							value = '1'/>";
							print"<label for='presentintervention1'>Present during intervention</label>";
						print "</td>";
					print "</tr>";
				print "</table>";
			print"</div>";

		print"</div>";


// for select dropdown for staff 2 present  id='staff_present_2' id='staff_present_checkbox_div_2'
	 	$i=0;
		print"<div class='row justify-content-md-center'>";
			print"<div class='col col-lg-auto pr-0'>";
				print "<select class='custom-select custom-select-lg mb-3'  data-width='auto' name='staff_present_2' id='staff_present_2' style='display: none;' onchange='show(this)'>";
					print "<option value=''>Select Staff Member</option>";
					foreach ($carer_data as $row) {
						if($row[house]==$_SESSION['house'] && $_SESSION['personaldatakey']!=$row['personaldatakey']){
							print "<option value='$row[personaldatakey]'>$row[first] $row[last]</option>";
						}
					}
					print "<option value='-2'>Temporary Staff</option>";
					print "<option value='-1'>Agency Staff</option>";
				print"</select>";
			print"</div>";


			print"<div class='col col-lg-auto pr-0'>";
				print "<select class='custom-select custom-select-lg mb-3'  data-width='auto' name='temp_staff_present_2' id='temp_staff_present_2' style='display: none;' onchange='show(this)'>";
					print "<option value=''>Select Staff Member</option>";
					foreach ($carer_data as $row) {
						if( $_SESSION['personaldatakey']!=$row['personaldatakey']){
							print "<option value='$row[personaldatakey]'>$row[first] $row[last]</option>";
						}
					}
				print"</select>";
			print"</div>";

			print"<div class='col col-lg-auto ml-0 mb-2' id='alternative_staff2' style='display: none;'>";
				print"<textarea class='form-control form-control-ta' autofocus='autofocus' placeholder='Enter first and last name'  id='alternative_staff_2_name' name='alternative_staff_2_name'/></textarea>";
			print"</div>";

			print"<div class='col col-lg-auto ml-0 mb-2' id='staff_present_checkbox_div_2' style='display: none;'>";		
				print "<table class='table-sm table-hover table-bordered' align='center'>";
					print"<tr><th style='text-align: center'>Interaction</th></tr>";
					print "<tr>";
						print "<td>";
							print"<input type = 'checkbox'
							name = 'onstaff2'
							id = 'onstaff2'
							value = '1'/>";
							print"<label for='onstaff2'>On Staff</label>";
						print "</td>";
					print "</tr>";
					print "<tr>";
						print "<td>";
							print"<input type = 'checkbox' onchange='check(this)'
							name = 'presentincident2'
							id = 'presentincident2'
							value = '1'/>";
							print"<label for='presentincident2'>Present during incident</label>";
						print "</td>";
					print "</tr>";
					print "<tr>";
						print "<td>";
							print"<input type = 'checkbox' onchange='check(this)'
							name = 'presentintervention2'
							id = 'presentintervention2'
							value = '1'/>";
							print"<label for='presentintervention2'>Present during intervention</label>";
						print "</td>";
					print "</tr>";
				print "</table>";
			print"</div>";

		print"</div>";


// for select dropdown for staff 3 present  id='staff_present_3' id='staff_present_checkbox_div_3'
	 	$i=0;
		print"<div class='row justify-content-md-center'>";
			print"<div class='col col-lg-auto pr-0'>";
				print "<select class='custom-select custom-select-lg mb-3' data-width='auto' name='staff_present_3' id='staff_present_3' style='display: none;' onchange='show(this)'>";
					print "<option value=''>Select Staff Member</option>";
					foreach ($carer_data as $row) {
						if($row[house]==$_SESSION['house'] && $_SESSION['personaldatakey']!=$row['personaldatakey']){
							print "<option value='$row[personaldatakey]'>$row[first] $row[last]</option>";
						}
					}
					print "<option value='-2'>Temporary Staff</option>";
					print "<option value='-1'>Agency Staff</option>";
				print"</select>";
			print"</div>";


			print"<div class='col col-lg-auto pr-0'>";
				print "<select class='custom-select custom-select-lg mb-3'  data-width='auto' name='temp_staff_present_3' id='temp_staff_present_3' style='display: none;' onchange='show(this)'>";
					print "<option value=''>Select Staff Member</option>";

					foreach ($carer_data as $row) {
						if( $_SESSION['personaldatakey']!=$row['personaldatakey']){
							print "<option value='$row[personaldatakey]'>$row[first] $row[last]</option>";
						}
					}
				print"</select>";
			print"</div>";

			print"<div class='col col-lg-auto ml-0 mb-2' id='alternative_staff3' style='display: none;'>";
				print"<textarea class='form-control form-control-ta' autofocus='autofocus' placeholder='Enter first and last name'  id='alternative_staff_3_name' name='alternative_staff_3_name'/></textarea>";
			print"</div>";

			print"<div class='col col-lg-auto ml-0 mb-2' id='staff_present_checkbox_div_3' style='display: none;'>";		
				print "<table class='table-sm table-hover table-bordered' align='center'>";
					print"<tr><th style='text-align: center'>Interaction</th></tr>";
					print "<tr>";
						print "<td>";
							print"<input type = 'checkbox'
							name = 'onstaff3'
							id = 'onstaff3'
							value = '1'/>";
							print"<label for='onstaff3'>On Staff</label>";
						print "</td>";
					print "</tr>";
					print "<tr>";
						print "<td>";
							print"<input type = 'checkbox' onchange='check(this)'
							name = 'presentincident3'
							id = 'presentincident3'
							value = '1'/>";
							print"<label for='presentincident3'>Present during incident</label>";
						print "</td>";
					print "</tr>";
					print "<tr>";
						print "<td>";
							print"<input type = 'checkbox' 
							onchange='check(this)'
							name = 'presentintervention3'
							id = 'presentintervention3'
							value = '1'/>";
							print"<label for='presentintervention3'>Present during intervention</label>";
						print "</td>";
					print "</tr>";
				print "</table>";
			print"</div>";

		print"</div>";

	// for select dropdown for staff 1 present  id='staff_present_4' id='staff_present_checkbox_div_4'
	 	$i=0;
		print"<div class='row justify-content-md-center'>";
			print"<div class='col col-lg-auto pr-0'>";
				print "<select class='custom-select custom-select-lg mb-3' data-width='auto' name='staff_present_4' id='staff_present_4' style='display: none;' onchange='show(this)'>";
					print "<option value=''>Select Staff Member</option>";
					foreach ($carer_data as $row) {
						if($row[house]==$_SESSION['house'] && $_SESSION['personaldatakey']!=$row['personaldatakey']){
							print "<option value='$row[personaldatakey]'>$row[first] $row[last]</option>";
						}
					}
					print "<option value='-1'>Temporary Staff</option>";
				print"</select>";

			print"</div>";
			print"<div class='col col-lg-auto ml-0 mb-2' id='staff_present_checkbox_div_4' style='display: none;'>";		
				print "<table class='table-sm table-hover table-bordered' align='center'>";
					print"<tr><th style='text-align: center'>Interaction</th></tr>";
					print "<tr>";
						print "<td>";
							print"<input type = 'checkbox'
							name = 'onstaff4'
							id = 'onstaff4'
							value = '1'/>";
							print"<label for='onstaff4'>On Staff</label>";
						print "</td>";
					print "</tr>";
					print "<tr>";
						print "<td>";
							print"<input type = 'checkbox'
							onchange='check(this)'
							name = 'presentincident4'
							id = 'presentincident4'
							value = '1'/>";
							print"<label for='presentincident4'>Present during incident</label>";
						print "</td>";
					print "</tr>";
					print "<tr>";
						print "<td>";
							print"<input type = 'checkbox'
							onchange='check(this)'
							name = 'presentintervention4'
							id = 'presentintervention4'
							value = '1'/>";
							print"<label for='presentintervention4'>Present during intervention</label>";
						print "</td>";
					print "</tr>";
				print "</table>";
			print"</div>";
			print"<div class='col col-lg-auto ml-0 mb-2' id='alternative_staff4' style='display: none;'>";
				print"<textarea class='form-control form-control-ta' autofocus='autofocus' placeholder='Enter first and last name'  id='alternative_staff_4_name'/></textarea>";
			print"</div>";
		print"</div>";

						// print "<table class='table' align='center' width='75%'border='1' bgcolor='white'>";
						// 	print"<tr><th>Carer</th><th>On Staff</th><th>Present during incident</th><th>Present during intervention</th></tr>";
						// 		while($row4 = mysqli_fetch_assoc($session4)){
								
						// 				print"<tr>";
						// 					print"<td>$row4[first] $row4[last]</td>";
						// 					print"<td style='text-align: center; vertical-align: middle;'>";

						// 								print"<input type = 'checkbox'
						// 								name = 'onstaff[]'
						// 								id='$row4[personaldatakey]'
						// 								value = '$row4[personaldatakey]'/>";

						// 					print"</td>";
						// 					print"<td style='text-align: center; vertical-align: middle;'>";
						// 								print"<input type = 'checkbox'
						// 								name = 'presentincident[]'
						// 								id='$row4[personaldatakey]'
						// 								value = '$row4[personaldatakey]'/>";
						// 					print"</td>";
						// 					print"<td style='text-align: center; vertical-align: middle;'>";
						// 								print"<input type = 'checkbox'
						// 								name = 'presentintervention[]'
						// 								id='$row4[personaldatakey]'
						// 								value = '$row4[personaldatakey]'/>";
						// 					print"</td>";
						// 				print"</tr>";
						// 		}
						// print"</table>";


					?>	


			</div>
		</div>


		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>
				<div id = "PRN">
					<h3 style='color: grey'><span class='hide'>STEP 9</span> <span class='show' style='display: none'>STEP 7</span></h3>
				</div>
			</div>
		</div>

		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>
					<h4><label>Emergency Intervention Required?</label></h4>
			</div>
		</div>

		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>
					<?
					print"<select data-width='auto' id='PRN_div'class='selBox custom-select-background custom-select-lg mb-3'  name='PRN' onchange='show(this)' >";
						print "<optGroup>";
							print"<option value='0' selected>NO</option>";
							print"<option value='1'>YES</option>";
						print "</optGroup>";
					print"</select>";

					?>	
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>
				<div id='pre_PRN_observation_tag' style='display: none; color: red;'>Select specific description of behavior which required Emergency Service.</div>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class='col col-lg-8'>
				<div id='pre_PRN_observation' style='display: none'>
					<!-- <textarea class="form-control form-control-ta" name='pre_PRN_observation' id='pre_PRN_observation'; style='display: none; background-color: yellow;  value=''/></textarea> -->
					<div class="row justify-content-md-center">
						<div class='col col-lg-8'>
							<table align='center' class='table-sm table-hover table-bordered'>
								<tr>
									<th style='text-align: center'> Intervention </th>
								<?
								foreach ($contact_data as $row) {
									print"</tr>";
										print"<td>";
											print"<input type = 'checkbox'
												name = 'emergency_intervention[]'
												id = '$row[contact_type]'
												value = '$row[id]'/>";
												print"<label for='$row[contact_type]''>$row[contact_type]</label>";
										print"</td>";
									print"</tr>";
								}
								?>

							</table>
						</div>
					</div>

				</div>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>
				<h3  style='color:grey'>Date and Time Information</h3>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>
				<h3 style='color: grey'><span class='hide'>STEP 10</span> <span class='show' style='display: none'>STEP 8</span></h3>
			</div>
		</div>

		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>
				 <h4><label> When Did Episode Take Place?</label></h4>
			</div>
		</div>

		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>


<!-- 				<input onchange="checkDate()" class="form-control custom-select-lg custom-select-background" id="datetimepicker5" name="datetimepicker" type="text" placeholder='Touch to enter' data-width='auto'/> -->

                            <?
                            ####  Parsing time formats for html5 datetime-local form element
                            $now = date("Y-m-d H:i");
                            $now = str_replace(" ", "T",$now);
                            $now = str_replace(":pm", "", $now);

                            $min_time = date("Y-m-d H:i",strtotime('-7 days'));
                            $min_time = str_replace(" ", "T",$min_time);
                            $min_time = str_replace(":pm", "", $min_time);
                            ?>

                            <input type="datetime-local"
                                    onchange="checkDate()"
                                    class="form-control custom-select-lg custom-select-background col-xs-4" 
                                    width="auto" 
                                    id="datetimepicker2"
                                    name="datetimepicker" value="<? echo $now ?>"
                                    min="<? echo $min_time ?>" 
                                    max="<? echo $now ?>">
			

			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>
				<h3 style='color: grey'><span class='hide'>STEP 11</span> <span class='show' style='display: none'>STEP 9</span></h3>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>

				<h4><label> How Long Did Episode Last?</label></h4>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class='col col-lg-auto'>
				<select class='custom-select-background custom-select-lg mb-3' data-width='auto' name = "duration" id="duration" onchange='validate_form()'>
				<option value = "">Choose Minutes</option>
				<?
				// for($t = 1;$t <= 5;$t +=1){
				// 	print "<option value = $t>$t</option>";
				// }
				print "<option value = 5>less than 5</option>";
				print "<option value = 10>6-10</option>";
				print "<option value = 30>11-30</option>";
				print "<option value = 60>30-60</option>";
				print "<option value = 120>1-2 hrs</option>";
				?>
				</select>
			</div>
		</div>
</container>
	<div id = "submit">
		<input 	type = "submit"
				name = "submit"
				value = "Record Episode"/>
	</div>	
	</form>
<?build_footer_pg()?>
</body>
<script type="text/javascript">jQuery('#datetimepicker5').datetimepicker({
 datepicker:true,
 formatTime:'g:i a',
  allowTimes:['00:00 am','00:30 am','01:00 am','01:30 am','02:00 am','01:30 am','02:30 am','03:00 am','03:30 am','04:00 am','04:30 am','05:00 am','05:30 am',
 	'06:00 am','06:30 am','07:00 am','07:30 am','08:00 am','08:30 am','09:00 am','09:30 am','10:00 am','10:30 am','11:00 am','11:30 am',
 	'12:00 pm','01:00 pm','01:30 pm','02:00 pm','01:30 pm','02:30 pm','03:00 pm','03:30 pm','04:00 pm','04:30 pm','05:00 pm','05:30 pm',
 	'06:00 pm','06:30 pm','07:00 pm','07:30 pm','08:00 pm','08:30 pm','09:00 pm','09:30 pm','10:00 pm','10:30 pm','11:00 pm','11:30 pm']
});
</script>
<script type="text/javascript">$('#cal_button').click(function(){
  $('#datetimepicker5').datetimepicker('show'); //support hide,show and destroy command
});
</script>
</html>
