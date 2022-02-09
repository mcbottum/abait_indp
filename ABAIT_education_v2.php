<?session_cache_limiter('nocache');
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
	set_css();
?>

</head>
<script type="text/javascript">
function reload(form){
	var val1=form.Trigger_Class.options[form.Trigger_Class.options.selectedIndex].value;
	self.location='ABAIT_education_v2.php?Trigger_Class='+val1;
}	
</script>
<style type="text/css">
	.space { 
    margin:0; padding:0; height:25px; 
}
</style>
<body class="container"
<?
	$names = build_page_pg();

	if($_SESSION['country_location']=='UK'){
		$behavior_spelling = 'Behaviour';
		$vocalization_spelling = 'Vocalisation';
		$characterization_spelling = 'Characterization';
		$date_format = 'dd-mm-yyyy';
	}else{
		$behavior_spelling = 'Behavior';
		$vocalization_spelling = 'Vocalization';
		$characterization_spelling = 'Characterisation';
		$date_format = 'mm-dd-yyyy';
	}
?>
<form 	name = 'form'
		method = "post">


<?
if (isset($_REQUEST['Trigger_Class'])){
	$Trigger_Class=$_REQUEST['Trigger_Class'];
}else{
	$Trigger_Class = '';
}

$conn=make_msqli_connection();


if($_SESSION['Target_Population']!='all'){
	$Population=$_SESSION['Target_Population'];
}else{
	$_SESSION['Population']='Dementia/Alzheimers Disease';
	$Population='Dementia';
}
if($_SESSION['privilege']=='globaladmin' || $_SESSION['privilege']=='admin'){
	$addressing_behavior_heading = "- Admin Roles";
}else{
	$addressing_behavior_heading = "- Carer Roles";
}

$Population_strip=mysqli_real_escape_string($conn,$Population);
$sql="SELECT * FROM triggers_and_interventions WHERE Target_Population='$Population_strip' ORDER BY Trigger_Class";

print "<h2 class='m-3 p-2 footer_div' align='center'>". $names[0]."'s Interactive Education Module</h2>";

?>

<table class="m-auto">
	<tr>
	<td>
<div id="label">
<?
	print "<h4 class='m-3'>Observing and Addressing Difficult $behavior_spelling</h3>";
?>
</div>
	<div id="menu">
	<ol type="I">
		<? print "<li><h4>When a $behavior_spelling Occurs</h4></li>"; ?>
			<ul class="m-2">
				<li class="m-2"><a href='ABAIT_scale_select_pcs_v2.php'>STEP 1 - Record in 2 Week Resident Observation</a></li>
					<ol>
<?
						print "<li>What ".strtolower($behavior_spelling)." occured?</li>"; 
						print "<li>Where ".strtolower($behavior_spelling)." happened?</li>"; 
						print "<li>When ".strtolower($behavior_spelling)." took place (date and time)?</li>";
						print "<li>How long did ".strtolower($behavior_spelling)." last?</li>";
						print "<li>Who was involved?</li>";
						print "<li>Why did ".strtolower($behavior_spelling)." happen (triggers)?</li>";
						print "<li>What worked and what did not work to intervene?</li>";
?>
					</ol>
				<li class="m-2">STEP 2 - What to Look For</li>
					<ol>
						<li>Pain</li>
						<li>Fever</li>
						<li>Signs of dehydration</li>
						<li>Change in levels of consciousness</li>
						<li>Ambulation changes</li>
						<li>Medical changes</li>
						<li>Depression</li>

					</ol>
				<li class="m-2">STEP 3 - Look for Triggers and Interventions</li>
				
				<li class="m-2" style="list-style-type:none">
					<ul style="list-style-type:none">
					<?
						//SELECT STUFF HERE
						$session=mysqli_query($conn,$sql);





						print "<li class='align-left'>";
						print "<select class='form-select form-select-lg m-3' data-width='auto' name='Trigger_Class' onchange=\"reload(this.form)\"><option value=''>Select a Trigger Class</option>"."<BR>";
						$trigger_holder=[];

						while($row=mysqli_fetch_assoc($session)){
							if(!in_array($row['Trigger_Class'], $trigger_holder)){

								if($row[Trigger_Class]==$Trigger_Class){
									$clean=str_replace('_', ' ', $Trigger_Class);
									print "<option selected value=$Trigger_Class>$clean</option>";
								}else{
									$clean=str_replace('_', ' ', $row[Trigger_Class]);
									print  "<option value=$row[Trigger_Class]>$clean</option>";
								}

								$trigger_holder[]=$row[Trigger_Class];
							}
						}//end while
						print "</select>";
						print "</li>";

						
						if($Trigger_Class){
							print "<li>";
							$sql2="SELECT * FROM triggers_and_interventions WHERE Trigger_Class='$Trigger_Class'";
							$session2=mysqli_query($conn,$sql2);
								print "<table class='table table-sm-responsive' style='margin-left:-35px'; width='110%' bgcolor='white'>";
									print"<tr><th>Trigger</th><th>Intervention</th>";
									while($row2=mysqli_fetch_assoc($session2)){
										print"<tr>";
										print"<td align='center'>$row2[Trigger_Example]</td>";
										print"<td align='center'>$row2[Intervention]</td>";
										print"</tr>";
									}
								print"</table>";
							print "</li>";
					}


					
					print "</ul>";
				print "</li>";
			print "</ul>";
		 print "<li><h4>Addressing the $behavior_spelling $addressing_behavior_heading</h4></li>"; 
				
					if($_SESSION['privilege']=='globaladmin' || $_SESSION['privilege']=='admin'){
						print "<ol>";
							print "<li>";
								print "<h6>Phase 1:  Intro to Care Plan Creation Training</h6>";
								print "<video width='320' height='240' controls>";
								print "<source src='ABAITAdminPlatform-IntroCarePlanTrainingPCS-PartA.mp4' type='video/mp4'>";


							print "</li>";
							print "<li>";
								print "<div class='space'></div>";
								print "<h6>Phase 2:  Platform Analysis Training</h6>";
								print "<video width='320' height='240' controls>";
								print "<source src='ABAITAdminPlatform-AnalysisEducation-PartB.mp4' type='video/mp4'>";
							print "</li>";


							if($_SESSION['client'] === 'PCS'){
								// print"<li><a href='ABAIT-Phase3AdminProductGuidePCS20211115.pdf' target='_blank'>Administrative Set-up</a></li>";
								// print"<li><a href='ABAIT-Phase3AdminProductGuidePCS20211115.pdf' target='_blank'>Administrative Set-up</a></li>";
								print "<div class='space'></div>";
								print "<li><h6>Phase 3:  </h6><a href='ABAIT-Phase3AdminProductGuidePCS20211115.pdf' class='btn btn-info' role='button' target='_blank'>Administrative Set-up</a></li>";
							}else{
								print"<li><a href='Phase3.pdf' target='_blank'>Phase3: Administrative Set-up</a></li>";	
							}	
						print "</ol>";
		print "<div class='space'></div>";
		print "<li><h4>Addressing the $behavior_spelling - Carer Roles</h4></li>"; 
						print "<ol>";

							print "<li><h6>Phase1:   </h6><a href='https://www.loom.com/share/cce71315910e481880969a2b54ff3d82' target='_blank' class='btn btn-info' role='button' target='_blank'>Positive Interactions Bootcamp</a></li>";
			                print "<li>";
			                	print "<div class='space'></div>";
				                if($_SESSION['client']==='PCS'){
				                	print "<h6>Phase 2 Carer Platform Training</h6>";
									print "<video width='320' height='240' controls>";
									print "<source src='ABAITCarerPlatformTraining.mp4' type='video/mp4'>";
								}else{
									print"<a href='https://www.loom.com/share/78b7edc903fd4bbebec4334fa8a4749c' target='_blank'>Phase2: Using the Caregiver Platform</a>";
								}
			                
			                print"</li>";

						print "</ol>";
			print "</li>";

					}else{
						print "<ol>";
							print "<li><h6><a href='https://www.loom.com/share/cce71315910e481880969a2b54ff3d82' target='_blank'>Phase1: Positive Interactions Bootcamp</a></h6></li>";
			                print "<li>";
				                if($_SESSION['client']==='PCS'){
				                	print "<h6>Phase 2 Carer Platform Training</h6>";
									print "<video width='320' height='240' controls>";
									print "<source src='ABAITCarerPlatformTraining.mp4' type='video/mp4'>";
								}else{
									print"<a href='https://www.loom.com/share/78b7edc903fd4bbebec4334fa8a4749c' target='_blank'>Phase2: Using the Caregiver Platform</a>";
								}
			                
			                print"</li>";
		        		print "</ol>";
		            }
				

?>
	</ol>
	</div>
	</td>
	</tr>
</table>	


<?build_footer_pg()?>

</body>
</html>
