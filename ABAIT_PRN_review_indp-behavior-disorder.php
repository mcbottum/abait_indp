<?
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
<style>
	table.hover tbody tr:hover{
			background-color: #D3D3D3;
	}
	tr.floatRight{
		float:right;
	}
hr {
  margin-top: 1rem;
  margin-bottom: 3rem;
  border: 0;
  border-top: 1px solid rgba(0, 0, 0, 0.1);
}

</style>
</head>
<body class="container">

<?  
$names = build_page_pg();
print"<h2 class='m-3 p-2 footer_div' align='center'>Emergency Intervention Review</h2>";
?>
<form 	action = "ABAIT_PRN_review_log_v2.php"
		method = "post">

<?
		$conn=make_msqli_connection();

		$date=date('Y-m-d');
		$date_start=date('Y-m-d',(strtotime('- 30 days')));
		$title='Emergency Intervention Review';
		$request_residentkey=$_REQUEST['residentkey'];
		$Population=$_REQUEST['population'];
		print "<input type='hidden' name='residentkey' value='$request_residentkey'>";
		print "<input type='hidden' name='population' value='$Population'>";

		if($request_residentkey=='all'){
			$sql1="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population'";
		}else{
			$sql1="SELECT * FROM residentpersonaldata WHERE residentkey='$request_residentkey'";
		}

		$session1=mysqli_query($conn,$sql1);
		//yikes, loop through all residents in population !!
		?>
<table width='100%'>
	<tr class='floatRight' />
		<td>
			<input type='submit' value='Tap for more Info' onClick="alert('This data compares behavior episodes requiring Emergency intervention, to those that did not.  Additionally, each Emergency intervention requiring behavior episode is listed in the second table along with a specific description of the aggitated behavior.');return false">
		</td>
	</tr>
	<tr class='floatRight' />
		<td>
			<FORM>
				<INPUT TYPE="button" value="Print Page" onClick="window.print()">
			</FORM>
		</td>
	</tr>
</table>

<?
		while($row1=mysqli_fetch_assoc($session1)){
					$residentkey = $row1['residentkey'];
					$res_first=$row1['first'];
					$res_last=$row1['last'];

					$sql_rm4="SELECT * from resident_mapping WHERE residentkey='$residentkey' AND date > '$date_start'";
					//$sql4="SELECT date, PRN, behavior, behavior_description, duration, intensity  FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start' UNION SELECT date, PRN, behavior, behavior_description, duration, intensity FROM resident_mapping WHERE residentkey='$residentkey' AND date > '$date_start' ORDER by date";
					$sql4="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start'";
					$session4=mysqli_query($conn,$sql4);
					$session_rm4=mysqli_query($conn,$sql_rm4);

					$sql5="SELECT * FROM scale_table WHERE Target_Population='$Population_strip'";
					$session5=mysqli_query($conn,$sql5);
					$row5=mysqli_fetch_assoc($session5);

					//get episode contact
					$sql_contact = "SELECT * from episode_contact";
					$session_contact = mysqli_query($conn,$sql_contact);
					$contact_data=$session_contact->fetch_all(MYSQLI_ASSOC);

					$sum_PRN=0;
					$sum_PRN_rm=0;
					$sum_episodes=0;
					$sum_episodes_rm=0;
					$PRN_duration=0;
					$total_duration=0;
					while($row4=mysqli_fetch_assoc($session4)){
						$sum_PRN=$sum_PRN+$row4['PRN'];
						$sum_episodes=$sum_episodes+1;
						if($row4['PRN']==1){
							$PRN_duration=$PRN_duration+$row4['duration'];
						}
						$total_duration=$total_duration+$row4['duration'];
					}
					while($row_rm4=mysqli_fetch_assoc($session_rm4)){
						$sum_PRN_rm=$sum_PRN_rm+$row_rm4['PRN'];
						$sum_episodes_rm=$sum_episodes_rm+1;
						if($row_rm4['PRN']==1){
							$PRN_duration_rm=$PRN_duration_rm+$row_rm4['duration'];
						}
						$total_duration_rm=$total_duration_rm+$row_rm4['duration'];
					}
					if($sum_episodes!=0){
						$PRN_ratio=round($sum_PRN/$sum_episodes,2);
					}else{
						$PRN_ratio=0;
					}
					if($sum_PRN!=0){
						$ave_PRN_duration=round($PRN_duration/$sum_PRN,2);
					}else{
						$ave_PRN_duration=0;
					}
					if(($sum_episodes-$sum_PRN)!=0){
						$ave_nonPRN_duration=round(($total_duration-$PRN_duration)/($sum_episodes-$sum_PRN),2);
					}else{
						$ave_nonPRN_duration=0;
					}
					if($sum_episodes_rm!=0){
						$PRN_ratio_rm=round($sum_PRN_rm/$sum_episodes_rm,2);
					}else{
						$PRN_ratio_rm=0;
					}
					if($sum_PRN_rm!=0){
						$ave_PRN_duration_rm=round($PRN_duration_rm/$sum_PRN_rm,2);
					}else{
						$ave_PRN_duration_rm=0;
					}
					if(($sum_episodes_rm-$sum_PRN_rm)!=0){
						$ave_nonPRN_duration_rm=round(($total_duration_rm-$PRN_duration_rm)/($sum_episodes_rm-$sum_PRN_rm),2);
					}else{
						$ave_nonPRN_duration_rm=0;
					}

					print"<table width='100%'><tr ><td  align='left'>";
					print"<h3> $title for $res_first $res_last</h3>\n";
					print"</td>";
					?>

						</tr>
					</table>
						<?
								print "<table class='table table-bordered'>";
									print"<tr>\n";
										print"<th>Start Date</th>\n";
										print"<th>End Date</th>\n";
										print"<th>Total Emergency Int</th>\n";
										print"<th>Total Episodes</th>\n";
										print"<th>Emergency Int/Total Episode Ratio</th>\n";
										print"<th>Ave Duration of Emergency Int Episode (min)</th>\n";
										print"<th>Ave Duration of non-Emergency Int Episode (min)</th>\n";
									print"</tr>\n";
									print"<tr><td colspan='7' align='center'><i>During Two Week Observation Period</i></td></tr>";
									print"<tr>\n";
										print"<td>$date_start</td>\n";
										print"<td>$date</td>\n";
										print"<td>$sum_PRN_rm</td>\n";
										print"<td>$sum_episodes_rm</td>\n";
										print"<td>$PRN_ratio_rm</td>\n";
										print"<td>$ave_PRN_duration_rm</td>\n";
										print"<td>$ave_nonPRN_duration_rm</td>\n";
									print"</tr>\n";
									print"<tr><td colspan='7' align='center'><i>After Behavior Plan Creation</i></td></tr>";
									print"<tr>\n";
										print"<td>$date_start</td>\n";
										print"<td>$date</td>\n";
										print"<td>$sum_PRN</td>\n";
										print"<td>$sum_episodes</td>\n";
										print"<td>$PRN_ratio</td>\n";
										print"<td>$ave_PRN_duration</td>\n";
										print"<td>$ave_nonPRN_duration</td>\n";
									print"</tr>\n";
								print "</table>";

					print"<h3> 30 Day Listing of Emergency Int Episodes for $res_first $res_last</h3>\n";

								print "<table class='table table-bordered'>";

									print"<tr><th>Episode Date</th>\n";
									print"<th>Episode Time</th>\n";
									print"<th>Specific Behavior Description</th>\n";
									print"<th>48 Hour Emergency Int Response</th></tr>\n";

									print"<tr><td colspan='7' align='center'><i>During Two Week Observation Period</i></td></tr>";
									$session8=mysqli_query($conn,$sql_rm4);
									$PRN_given = False;
									while($row8=mysqli_fetch_assoc($session8)){
										if($row8['PRN']=='1'){
											$PRN_given = True;
											print"<tr>";
												print"<td>$row8[date]</td>";
												print"<td>$row8[time]</td>";

												print"<td>$row8[behavior_description]</td>";
												// print"<td>$row8[post_PRN_observation]</td>";
												print"<td>";
												foreach (explode(',',$row8['post_PRN_observation']) as $key) {
													foreach ($contact_data as $contact) {
														if($contact['id']==$key){
															print"--$contact[contact_type]";
														}
													}
												}
												print"</td>";
											print"</tr>";
										}
									}
									if(!$PRN_given){
										print"<tr>";
											print"<td colspan='7' align='center'>No Emergency Int Required</td>";
										print"</tr>";
									}

									print"<tr><td colspan='7' align='center'><i>After Behavior Plan Creation</i></td></tr>";
									$session6=mysqli_query($conn,$sql4);
									$PRN_given = False;
									while($row6=mysqli_fetch_assoc($session6)){
										if($row6['PRN']=='1'){
											$PRN_given = True;
											print"<tr>";
												print"<td>$row6[date]</td>";
												print"<td>$row6[time]</td>";
												print"<td>$row6[behavior_description]</td>";
												print"<td>";
												foreach (explode(',',$row6[post_PRN_observation]) as $key) {
													foreach ($contact_data as $contact) {
														if($contact[id]==$key){
															print"--$contact[contact_type]";
														}
													}
												}
												print"</td>";
											print"</tr>";
										}

									}
									if(!$PRN_given){
										print"<tr>";
											print"<td colspan='7' align='center'>No Emergency Int Required</td>";
										print"</tr>";
									}
								print"</table>";

				print"<h3> Enter Administrative Emergency Int Review Comments for  <em>$res_first $res_last</em> </h3>";
					$report_name = 'PRNreport_'.$row1['residentkey'];
					print"<input type='text' name=$report_name style='background-color: yellow; font-size: 14px; width:99%;' size='130'>";
				print "<hr class='greyline'>";
		}//end row1 while for residents in Target_Population


			print"<div id = 'submit'>";
				print"<input 	type = 'submit'
											name = 'submit'
											value = 'Submit Emergency Intervention Report'>";
			print"</div>";
	print"</fieldset>";
	print"</form>";
build_footer_pg()?>
</body>
</html>
