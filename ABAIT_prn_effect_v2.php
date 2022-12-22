<?
include("ABAIT_function_file.php");session_start();
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
<form 	action = "ABAIT_prn_effect_log_v2.php"
		method = "post">
<?
		$date=date('Y-m-d');
		$date_start=date('Y-m-d',(strtotime('- 2 days')));	
		//print"$date_start";
		$title1='Post Medicated Intervention Follow-Up';
		$title2='Residents requiring medication to manage behaviors during the past 48 hours.';
		#$residentkey=$_REQUEST['resident_choice'];

		$conn=make_msqli_connection();
		if($_SESSION['Target_Population']!='all'){
			$Population_strip=mysqli_real_escape_string($conn,$_SESSION['Target_Population']);
			$sql1="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population_strip'";
		}else{
			$sql1="SELECT * FROM residentpersonaldata";
		}
		$session1=mysqli_query($conn,$sql1);
		$row1=mysqli_fetch_assoc($session1);
		$Population_row1_strip=mysqli_real_escape_string($conn,$row1['Target_Population']);
		if($_SESSION['privilege']=='caregiver'){
			$sql_rm4="SELECT * from resident_mapping WHERE date > '$date_start' AND personaldatakey='$_SESSION[personaldatakey]' AND PRN='1' AND post_PRN_observation IS NULL ORDER BY date";
			$sql4="SELECT * FROM behavior_map_data WHERE  date > '$date_start' AND personaldatakey='$_SESSION[personaldatakey]' AND PRN='1' AND post_PRN_observation IS NULL ORDER BY date";
			$sql5="SELECT * FROM scale_table WHERE Target_Population='$Population_row1_strip'";
		}else{
			$sql_rm4="SELECT * from resident_mapping WHERE date > '$date_start' AND PRN='1'";
			$sql4="SELECT * FROM behavior_map_data WHERE  date > '$date_start' AND PRN='1'";
			$sql5="SELECT * FROM scale_table WHERE Target_Population='$Population_row1_strip'";
		}

		//get episode contact
		if($_SESSION['population_type']=='behavioral'){
			$post_observation_header = "Post Incident Actions (Check!)";
			$sql_contact = "SELECT * from episode_contact WHERE Target_Population='$Population_strip'";
			$session_contact = mysqli_query($conn,$sql_contact);
			$contact_data=$session_contact->fetch_all(MYSQLI_ASSOC);
		}else{
			$contact_data = false;
			$post_observation_header  = "Post Medication Observation";
		}

		$session4=mysqli_query($conn,$sql4);
		$session_rm4=mysqli_query($conn,$sql_rm4);
		$session5=mysqli_query($conn,$sql5);
		$row5=mysqli_fetch_assoc($session5);


		print"<div class='row justify-content-md-center'>";
			print"<div class='col col-lg-auto pr-0'>";
					print"<h2 align='center'> $title1 </h2>\n";
			print"</div>";
		print"</div>";
			
		print"<div class='row justify-content-md-center'>";
			print"<div class='col col-lg-auto pr-0'>";
					print"<h3 align='center'> $title2 </h3>\n";
			print"</div>";
		print"</div>";
?>
			<div class='row justify-content-md-center'>
				<div class='col col-lg-auto pr-0'>
					<input type='button' value='Tap for more Info' onClick="alert('The effectivness and or side effects of any police intervention must be observed and recorded thirty minutes after it is administered.  Record observations here.');return false">
					<input type='button' value='Print Page' onClick='window.print()'>
				</div>
			</div>
<?

					print "<table class='table-bordered' align='center'>";
					print"<thead class='thead-light'>";
						print"<tr>";
							print"<th>Episode Date</th>\n";
							print"<th>Client</th>\n";
							print"<th>Incident Description</th>\n";
							print"<th>$post_observation_header</th>";
					print"</thead>";
					print"</tr>\n";


							$session8=mysqli_query($conn,$sql_rm4);

							$table = NULL;

							$mapkey = NULL;

								$first = true;
								if(mysqli_num_rows($session8)!=0){
									while($row8=mysqli_fetch_assoc($session8)){//non-mapped  PRNs
										if($first){
											if(!$row8['post_PRN_observation']){
												$sql2="SELECT * FROM residentpersonaldata WHERE residentkey='$row8[residentkey]'";
												$do_submit=true;
												$table = "resident_mapping";
												$mapkey= $row8['mapkey'];
												print"<tr>";
													print"<td class='align-middle p-1'><table class='table-borderless'><tr><td>$row8[date]</td></tr><tr><td>$row8[time]</td></tr></table></td>";
													$session2=mysqli_query($conn,$sql2);
													while($row2=mysqli_fetch_assoc($session2)){

														if($row2['residentkey']==$row8['residentkey']){
															print"<td class='align-middle p-1'>$row2[first] $row2[last]</td>";
															// added hidden field for the single resident per page 
															
														}
													}
													print"<td class='align-middle p-1'>";
														if($contact_data){
															print"<table class='table-sm table-hover table-bordered' align='center'>";
																print"<tr>";
																	print"<td class='align-middle p-1'>$row8[behavior_description]</td>";
																print"</tr>";
																	foreach (explode(',',$row8['pre_PRN_observation']) as $key) {
																		foreach ($contact_data as $contact) {
																			if($contact[id]==$key){
																				print"<tr><td class='align-middle m-2'>$contact[contact_type]</td></tr>";
																			}
																		}
																	}
															print"</table>";
														}else{
															print $row8['pre_PRN_observation'];
														}
													print"</td>";
													print"<td class='align-middle p-1'>";
														if($contact_data){
															print"<table align='center' class='table-sm table-hover table-bordered'>";
																foreach ($contact_data as $row) {
																	if($row['contact_category']=='post'){
																		print"<tr>";
																			print"<td class='align-middle p-1'>";
																				print"<input type = 'checkbox'
																					class='m-2'
																					name = 'emergency_intervention[]'
																					id = '$row[contact_type]'
																					value = '$row[id]'/>";
																					print"<label for='$row[contact_type]''>$row[contact_type]</label>";
																			print"</td>";
																		print"</tr>";
																	}
																}
															print"</table>";
														}else{
															print "<textarea class='form-control form-control-ta' placeholder='Required...' name = 'emergency_intervention'></textarea>";	
														}
													print"</td>";

												print"</tr>";
											}
										$first = false;
										}
									}

								}
								else if(mysqli_num_rows($session8)==0 && mysqli_num_rows($session4)!=0){
									while($row4=mysqli_fetch_assoc($session4)){//non-mapped  PRNs
										if($first){
											if(!$row4['post_PRN_observation']){
												$do_submit=true;
												$table = "behavior_map_data";
												$mapkey= $row4['behaviormapdatakey'];
												print"<tr>";
													print"<td class='align-middle p-1'><table class='table-borderless'><tr><td>$row4[date]</td></tr><tr><td>$row4[time]</td></tr></table></td>";
													$session2=mysqli_query($conn,$sql1);
													while($row2=mysqli_fetch_assoc($session2)){
														if($row2['residentkey']==$row4['residentkey']){

															print"<td class='align-middle p-1'>$row2[first] $row2[last]</td>";
															// added hidden field for the single resident per page 
	
														}
													}
													print"<td class='align-middle p-1'>";
														if($contact_data){
															print"<table class='table-sm table-hover table-bordered' align='center'>";
																print"<tr>";
																	print"<td class='align-middle p-1'>$row8[behavior_description]</td>";
																print"</tr>";
																	foreach (explode(',',$row4['pre_PRN_observation']) as $key) {
																		foreach ($contact_data as $contact) {
																			if($contact[id]==$key){
																				print"<tr><td class='align-middle m-2'>$contact[contact_type]</td></tr>";
																			}
																		}
																	}
															print"</table>";
														}else{
															print $row4['behavior_description'];
														}
													print"</td>";
													print"<td class='align-middle p-1'>";
														if($contact_data){
															print"<table align='center' class='table-sm table-hover table-bordered'>";
																foreach ($contact_data as $row) {
																	if($row['contact_category']=='post'){
																		print"<tr>";
																			print"<td class='align-middle p-1'>";
																				print"<input type = 'checkbox'
																					class='m-2'
																					name = 'emergency_intervention[]'
																					id = '$row[contact_type]'
																					value = '$row[id]'/>";
																					print"<label for='$row[contact_type]''>$row[contact_type]</label>";
																			print"</td>";
																		print"</tr>";
																	}
																}
															print"</table>";
														}else{
															print "<textarea class='form-control form-control-ta' placeholder='Required...' name = 'emergency_intervention'></textarea>";
																	
														}
													print"</td>";

												print"</tr>";
											}
										$first = false;
										}
									}


								
								}else{
											$do_submit=false;
											print"<div class='row justify-content-md-center'>";
												print"<div class='col col-lg-auto pr-0'>";
														print"<h3 style='color:green' align='center'>Currently, No Medication Follow-Ups are Required</h3>\n";
												print"</div>";
											print"</div>";
								}
							

					print"</table>";


		

	if($do_submit){
		print "<input type='hidden' id='mapkey', name='mapkey' value='$mapkey'>";
		print "<input type='hidden' id='table', name='table' value='$table'>";
				print"<div id = 'submit'>";	
					print"<input 	type = 'submit'
							name = 'submit'
							value = 'Submit'>";
				print"</div>";
	}
?>
	</form>
<?build_footer_pg()?>
</body>
</html>
