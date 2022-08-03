<?
session_start();
include("ABAIT_function_file.php");
if($_SESSION['passwordcheck']!='pass'){
	header("Location:logout.php");
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
<!-- <script src="static/js/jquery-3-5-1.min.js"></script> -->
<script src="static/js/canvasjs.min.js"></script>
<title>
<?
print $_SESSION['SITE']
?>
</title>

<script>
function backButton1(target_population) {
	var url = 'ABAIT_episode_historical_review_v2.php?tp='+target_population;
	window.location.href='ABAIT_episode_historical_review_v2.php?tp='+target_population;
}

function hide() {

	obj = document.getElementById("chartContainer");
	obj1 = document.getElementById("hide_graph");

	obj.style.display = "none";
	obj1.style.display = "none";

}
</script>
<?
set_css()
?>
<style>

    table.local thead th{
        width:145px;
        background-color: white;
        background-color: #F5F5F5;
        padding-left: 0px !important;
    }
/*    table.local tbody{
        max-height: 400px;
    }*/
    table.local tbody td{
        width:145px;
        background-color: white;
        padding-left: 0px !important;
        text-align:center;
    }

    table.hover tbody tr:hover{
        background-color: #D3D3D3;
    }
    label {
        /* whatever other styling you have applied */
        width: 100%;
        display: inline-block;
    }
    p.backButton {
      float:right;
    }
    table.eoi thead th.first{
        width:180px;
    }

    table.eoi tbody td.first{
        width:180px;
    }
    table.eoi thead th{
        width:115px;
		text-align:center;
	}

    table.eoi tbody td{
        width:115px;
		text-align:center;
    }

    table.local thead th{
        width:250px;
        text-align:center;
    }

    table.local tbody td{
        width:250px;
        text-align:center;
    }
    span.tab{
        width:75px !important;
    }

    label {
        /* whatever other styling you have applied */
        width: 100%;
        display: inline-block;
    }
    .noScroll {
    	border-collapse: collapse;
    	border:1px solid black ;
    }
    .table th {
    	background-color: lightgrey;
    }



</style>
</head>
<body class="container">

<?
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

$names = build_page_pg();

$filename=$_REQUEST['submit'];
$Population=$_REQUEST['Target_Population'];
$Population=str_replace('_',' ',$_SESSION['pop']);
//print $Population;
//print $_SESSION[Target_Population];
$residentkey=$_REQUEST['residentkey'];
$date=date('Y-m-d');
//print $residentkey;
if($filename=="Submit Resident for Global Analysis"){
		if(isset($_REQUEST['all_residents'])){
			$all_residents=$_REQUEST['all_residents'];
		}else{
			$all_residents=Null;
		}
		$review_time=$_REQUEST['review_time'];
		//$scale_array=$_REQUEST[scale_array];
		if(isset($_REQUEST['scale_totals'])){
			$scale_totals=$_REQUEST['scale_totals'];
		}else{
			$scale_totals=Null;
		}
		if(isset($_REQUEST['behavior_units'])){
			$behavior_units=$_REQUEST['behavior_units'];
		}else{
			$behavior_units=Null;
		}
		if(isset($_REQUEST['behavior_units_per_time'])){
			$behavior_units_per_time=$_REQUEST['behavior_units_per_time'];
		}else{
			$behavior_units_per_time=Null;
		}
		if(isset($_REQUEST['episode_time_of_day'])){
			$episode_time_of_day=$_REQUEST['episode_time_of_day'];
		}else{
			$episode_time_of_day=Null;
		}
		if(isset($_REQUEST['trigger_breakdown'])){
			$trigger_breakdown=$_REQUEST['trigger_breakdown'];
		}else{
			$trigger_breakdown=Null;
		}
		if(isset($_REQUEST['carer_breakdown'])){
			$carer_breakdown=$_REQUEST['carer_breakdown'];
		}else{
			$carer_breakdown=Null;
		}
		if(isset($_REQUEST['intervention_effect'])){
			$intervention_effect=$_REQUEST['intervention_effect'];
		}else{
			$intervention_effect=Null;
		}
		if(isset($_REQUEST['all_episode'])){
			$all_episode=$_REQUEST['all_episode'];
		}else{
			$all_episode=Null;
		}
		if(isset($_REQUEST['review_time'])){
			$reviewtime=$_REQUEST['review_time'];
		}else{
			$reviewtime=Null;
		}
		if(isset($_REQUEST['include_unmapped'])){
			$include_unmapped=$_REQUEST['include_unmapped'];
		}else{
			$include_unmapped=Null;
		}

		// Default date end is now
		$date_end=date("Y-m-d");
		$date_end=date('Y-m-d',(strtotime('+ 1 days')));

		if($reviewtime===0){
			$date_start=date('Y-m-d', strtotime('first day of last month'));
			$date_end=date('Y-m-d', strtotime('last day of last month'));
		}


		if($reviewtime==1){
		$date_start=date('Y-m-d',(strtotime('- 30 days')));
		}
		if($reviewtime==3){
		$date_start=date('Y-m-d',(strtotime('- 90 days')));
		}
		if($reviewtime==6){
		$date_start=date('Y-m-d',(strtotime('- 180 days')));
		}
		if($reviewtime=='all'){
		$date_start=date('Y-m-d',(strtotime('- 1000 days')));
		}


		//if($reviewtime!=3 && $reviewtime!=6 && $reviewtime!=10 && $reviewtime!='all'){
		//	$reviewtime=$_REQUEST['customtime'];
		//}
		//if(empty($reviewtime)){
		//$date_start=date('Y-m-d',(strtotime('- 30 days')));
		//}

		$title='Global Analysis';
}
		$conn=make_msqli_connection();

		$scale_array=$_SESSION['scale_array'];
		foreach($scale_array as $value){
			$sum_behaviorarray[$value]=0;
		}//end foreach
		print "<table width='100%'><tr><td>";
		$residentkey_assoc_array = array();
		if($residentkey=='all_residents'){
			print"<h2 class='m-3 p-2 footer_div' align='center'> $title for <em>All Residents</em></h2>";
			$Population_strip=mysqli_real_escape_string($conn,$Population);
        	$houses = explode(",",$_SESSION['house']);
        	$houses = join("', '", $houses);
			$sql="SELECT * FROM residentpersonaldata WHERE house IN ('$houses') order by first";
			//$sql="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population_strip'";
			$session=mysqli_query($conn,$sql);
			while($row=mysqli_fetch_assoc($session)){
				$residentkey_array[]=$row['residentkey'];
				$residentkey_assoc_array[$row['residentkey']] = $row['first'].' '.$row['last'];
			}
		}elseif($residentkey&&$residentkey!='all_residents'){
			$sql="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey'";
			$session=mysqli_query($conn,$sql);
			$row=mysqli_fetch_assoc($session);
			$res_first=$row['first'];
			$res_last=$row['last'];
			$residentkey_array[]=$row['residentkey'];
			$residentkey_assoc_array[$row['residentkey']] = $row['first'].' '.$row['last'];
			print"<h2 class='m-3 p-2 footer_div' align='center'>$title for $res_first $res_last</h2>";
		}else{
			print"A resident selection was not made, please return to the previous page";
			die;
		}
		print "<p class='backButton'>";
			print "<input	type = 'button'
						name = ''
						id = 'backButton1'
						value = 'Return to Analysis Design'
						onClick=\"backButton1('$Population')\"/>\n";
		print "</p>";
		print "</td></tr><tr><td align='right'>";
				?>
					<FORM>
						<INPUT TYPE="button" value="Print Page" onClick="window.print()">
					</FORM></td></tr>
				<?
		print "</td></tr></table>";


if($scale_totals){///////////////////////scale totals////////////////////////////
		$i=0;
		unset($sql_array);

	if($_REQUEST['all']=='all'){


		if($residentkey=='all_residents'){
			${'sql_all'}="SELECT * FROM behavior_map_data WHERE date > '$date_start' AND date < '$date_end' AND residentkey IN ('".implode("', '", $residentkey_array)."')";
		}else{
			${'sql_all'}="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start' AND date < '$date_end' order by date";
		}
		$sql_array[]=$sql_all;
	}else{


		foreach($_SESSION['scale_array'] as $behavior){//$i counts the sql variables!!
			$behavior=str_replace(' ','_',$behavior);
			${'behave_'.$i}=$_REQUEST[$behavior];
			if(in_array(${'behave_'.$i},$_SESSION['scale_array'])){
				if($residentkey=='all_residents'){
					${'sql'.$i}="SELECT * FROM behavior_map_data WHERE date > '$date_start' AND date < '$date_end' AND behavior='${'behave_'.$i}' order by date";
				}else{
					${'sql'.$i}="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start' AND date < '$date_end' AND behavior='${'behave_'.$i}' order by date";
				}
				$sql_array[]=${'sql'.$i};
				$i=$i+1;
			}
		}
	}//end all if

		for($j=0;$j<count($sql_array);$j++){

			$sum_duration=0;
			$sum_PRN=0;
			$sum_episodes=0;
			//$row=null;
			$session=${'session'.$j};
			$session=mysqli_query($conn,$sql_array[$j]);
			while(${'row'.$j}=mysqli_fetch_assoc($session)){
				$sum_duration=${'row'.$j}['duration']+$sum_duration;
				$sum_PRN=${'row'.$j}['PRN']+$sum_PRN;
				$sum_episodes=$sum_episodes+1;
				foreach($scale_array as $behavior){
					if(${'row'.$j}['behavior']==$behavior){
						$sum_behaviorarray[$behavior]=$sum_behaviorarray[$behavior]+${'row'.$j}['duration'];
					}
				}//end behaviorarray foreach
			}
			//call graph function
			$values_bar=$sum_behaviorarray;
			$graphTitle_bar='Duration of '.$behavior_spelling .'Episodes vs. '.$behavior_spelling;
			$yLabel_bar='Total Duration (minutes)';
			$xLabel_bar=$behavior_spelling.'s';
			ABAIT_bar_graph($values_bar, $graphTitle_bar, $yLabel_bar,$xLabel_bar,'bar');


			print"<table width='100%'>";
				print"<tr>";
					print"<td>";
						if($j==count($sql_array)-1&&in_array($sql_all,$sql_array)){
							print"<h4 class='center_header'>Scale Totals for <em>All</em> Triggers - Start Date:  <em>$date_start</em> End Date:   <em>$date_end</em></h4>\n";
						}else{
							print"<h3 class='center_header'>Scale Totals for <em>${'behave_'.$j}</em> Triggers - Start Date:  <em>$date_start</em> End Date:   <em>$date_end</em></h3>\n";
						}
					print"</td>";
					print"<td align='right'>";
						print"<input type='submit' value='Tap for more Info' onClick=\"alert('This is the thirty day global analysis of your resident selected.  The analysis provides information about total minutes of epsisodes and total minutes of episodes per trigger.  Additionally, the anlysis provides information about most effective interventions of each of the triggers.');return false\">";
					print"</td>";
				print"</tr>";
			print "</table>";

			print "<table width='100%'>";//
				print "<tr><td>";//table in table data for more info

					print "<table class='table table-bordered'>";
							print"<tr align='center'>\n";

								print"<th>Start Date</th>";
								print"<th>End Date</th>";
								print"<th>Episode Count</th>";
								print"<th>Total Duration</th>";
								print"<th>PRN Count</th>";
								print"<th>Graph</th>";

							print"</tr>\n";

							print"<tr align='center'>\n";

									print"<td>$date_start</td>";
									print"<td>$date_end</td>";
									print"<td>$sum_episodes</td>";
									print"<td>$sum_duration</td>";
									print"<td>$sum_PRN</td>";
									print"<td><INPUT class='icon' height='35' type=\"image\" src=\"Images/chart_icon.png\" onClick=\"window.open('behaviorgraphbar.png','','width=700,height=400')\"></td>";

							print "</tr>\n";
					print "</table>";
				print "</td>";
		print "</tr>";
	print "</table>";


}
	}

if($episode_time_of_day){///////////////////////////////////////time of day//////////////////////////////////////////
		$i=0;
		unset($sql_array);
	if($_REQUEST['all']=='all'){
		if($residentkey=='all_residents'){
			${'sql_all'}="SELECT * FROM behavior_map_data WHERE date > '$date_start' AND date < '$date_end' AND residentkey IN ('".implode("', '", $residentkey_array)."')";
		}else{
			${'sql_all'}="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start' AND date < '$date_end'";
		}
		$sql_array[]=$sql_all;
	}else{
		foreach($_SESSION[scale_array] as $behavior){//$i counts the sql variables!!
			$behavior=str_replace(' ','_',$behavior);
			${'behave_'.$i}=$_REQUEST[$behavior];
			if(in_array(${'behave_'.$i},$_SESSION[scale_array])){
				if($residentkey=='all_residents'){
					${'sql'.$i}="SELECT * FROM behavior_map_data WHERE date > '$date_start' AND date < '$date_end' AND behavior='${'behave_'.$i}'";
				}else{
					${'sql'.$i}="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start' AND date < '$date_end' AND behavior='${'behave_'.$i}'";
				}
				$sql_array[]=${'sql'.$i};
				$i=$i+1;
			}
		}
	}//end all if
				$episode_start_array=array(7,10,13,16,19,22,1,4);//hours for shifts
				//$episode_end_array=array(10,13,19,22,1,4,7);
	for($j=0;$j<count($sql_array);$j++){
				foreach($episode_start_array as $i){
					${'episode_count'.$i}=0;
					${'sum_duration'.$i}=0;
				}
				$session=${'session'.$j};
				$session=mysqli_query($conn,$sql_array[$j]);
				$sum_duration = 0;
				while(${'row'.$j}=mysqli_fetch_assoc($session)){
					$sum_duration=${'row'.$j}['duration']+$sum_duration;
						foreach($episode_start_array as $i){
							if($i*10001<=str_replace(':','',${'row'.$j}['time'])&&str_replace(':','',${'row'.$j}['time'])<=($i+3)*10000){
								${'episode_count'.$i}=${'episode_count'.$i}+1;
								${'sum_duration'.$i}=${'row'.$j}['duration']+${'sum_duration'.$i};
							}
							${'episode_count_array'.$j}[$i]=${'episode_count'.$i};
							${'sum_duration_array'.$j}[$i]=${'sum_duration'.$i};
						}
				}
		// section for printing episode time of day table follows

			//call graph function
				$values_bar_e=${'episode_count_array'.$j};
				$graphTitle_bar='Count of Episodes per Three Hour Interval';
				$yLabel_bar=' Episode Count';
				$xLabel_bar='|-------Day Shift-------||------PM Shift------||-----Night Shift-----|';
			if(!empty($values_bar_e)){
			ABAIT_bar_graph($values_bar_e, $graphTitle_bar, $yLabel_bar,$xLabel_bar,$j+5);
			}
			//call graph function
				$values_bar_d=${'sum_duration_array'.$j};
				$graphTitle_bar='Duration of '.$behavior_spelling.' Episodes per Three Hour Interval';
				$yLabel_bar='Total Episode Duration (minutes)';
				$xLabel_bar='|-------Day Shift-------||------PM Shift------||-----Night Shift-----|';
			if(!empty($values_bar_d)){

			ABAIT_bar_graph($values_bar_d, $graphTitle_bar, $yLabel_bar,$xLabel_bar,$j+10);
			}

			if($j==count($sql_array)-1&&in_array($sql_all,$sql_array)){
				print"<h3 class='center_header'>Episode per Time of Day for <em>All</em> Triggers - Start Date:  <em>$date_start</em> End Date:   <em>$date_end</em></h3>\n";
			}else{
				print"<h3 class='center_header'>Episode per Time of Day for <em>${'behave_'.$j}</em> Triggers - Start Date:  <em> $date_start</em> End Date:   <em> $date_end</em></h3>\n";
			}




			print "<table width='100%'>";//table for more info copy this line
				print "<tr align='right'>";
					print "<td>";
						print"<input type='submit' value='Tap for more Info' onClick=\"alert('This table groups $behavior_spelling episodes by the time of day during which they occurred.');return false\">";
					print "</td>";
				print "</tr>";

					print "<tr><td>";//table in table data for more info
						// print "<table width='100%' class='table hover local' border='1' bgcolor='white'>";
						print "<table class='table table-bordered'>";
							// print "<thead>";
								print"<tr align='center'>\n";

										print"<th>Time Interval (Hours)</th>";
										foreach($episode_start_array as $i){
											$k=$i+3;
											if($k==25){
												$k=1;
											}
											print"<th>$i-$k</th>";
										}
										print"<th>Graph</th>";

								print"</tr>\n";
							// print "</thead>";
							// print "<tbody>";
								print"<tr>\n";

										print "<td>Total Episodes</td>";
										foreach($episode_start_array as $i){
											print "<td>${'episode_count'.$i}</td>";
										}
										print"<td><INPUT class='icon' height='35' type=\"image\" src=\"Images/chart_icon.png\" onClick=\"window.open('behaviorgraph'+($j+5)+'.png','','width=700px,height=400')\"></td>";

								print"</tr>\n";
								print"<tr>\n";

										print"<td>Total Episode Duration (min)</td>";
										foreach($episode_start_array as $i){
												print "<td>${'sum_duration'.$i}</td>";
										}
										print"<td><INPUT class='icon' height='35' type=\"image\" src=\"Images/chart_icon.png\" onClick=\"window.open('behaviorgraph'+($j+10)+'.png','','width=700px,height=400')\"></td>";

								print"</tr>\n";
							// print "</tbody>";
						print"</table>";
				print"</td></tr></table>";

	}//end for
}//end if

if($behavior_units){///////////////////////////////////////////// behavior units////////////////////////////////////////
	print"<table width='100%'>";
		print"<tr><td colspan='2'>";
			print"<h3 class='center_header'>Effect of Interventions on Agitated $behavior_spelling Episodes</h3>";
		print"</td></tr>";
		print"<tr><td>";
			print"<h5 class='center_header'>Intervention values are the sum of improvement levels on $behavior_spelling intensity rating scale.</h5>";
		print"</td>";
		print"<td align='right' valign-'bottom'>";

			print"<input type='submit' value='Tap for more Info' onClick=\"alert('This table breaks down improved $behavior_spelling by the interventions of each trigger. Numerical values represent the sum of $behavior_spelling improvement as measured by the $behavior_spelling rating scale.  The $behavior_spelling rating scales contain five levels, ranging from extremely agitated to normal.  Each level has a value of one point.');return false\">";

		print"</td></tr>";
	print"</table>";

	$r=0;
print "<table width='100%'>";//table for more info copy this line
	print "<tr><td>";//table in table data for more info
	foreach($scale_array as $behavior){

		unset($trig_array_keys);
		$trigger_count=0;
		$trigger_duration=NULL;
		unset($trigger_array);
		if($residentkey=='all_residents'){
			$sql2="SELECT * FROM behavior_maps WHERE behavior='$behavior' AND residentkey IN ('".implode("', '", $residentkey_array)."')";
		}else{
			$sql2="SELECT * FROM behavior_maps WHERE behavior='$behavior' AND residentkey='$residentkey'";
		}
		$session2=mysqli_query($conn,$sql2);
		$trig_array_keys=[];
			while($row2=mysqli_fetch_assoc($session2)){
				$intervention_array=null;
				$trigger_array[$behavior][]=$row2['trig'];
				$episodes=0;
				$duration=0;
				$intv=0;
				$intv1=0;
				$intv2=0;
				$intv3=0;
				$intv4=0;
				$intv5=0;
				$intv6=0;
				//print"<tr>\n";
				if($residentkey=='all_residents'){
					$sql3="SELECT * FROM behavior_map_data WHERE date > '$date_start' AND date < '$date_end' AND behavior='$behavior' AND residentkey IN ('".implode("', '", $residentkey_array)."')";
				}else{
					$sql3="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND behavior='$behavior' AND date > '$date_start' AND date < '$date_end'";
				}

					$session3=mysqli_query($conn,$sql3);
						while($row3=mysqli_fetch_assoc($session3)){
								if($row2['mapkey']==$row3['mapkey']){
									$episodes=$episodes+1;
									$duration=$duration+$row3['duration'];
									$intv1=$intv1+$row3['intervention_score_1'];
									$intv2=$intv2+$row3['intervention_score_2'];
									$intv3=$intv3+$row3['intervention_score_3'];
									$intv4=$intv4+$row3['intervention_score_4'];
									$intv5=$intv5+$row3['intervention_score_5'];
									$intv6=$intv6+$row3['intervention_score_6'];
								}
							}//end invtervention while

							$trigger_duration[$row2['trig']]=$duration;
							//$trigger_array[$trigger_duration[$row2[trig]]]=$duration;
							$intv=0;
							$best='';
							for ($s=1;$s<7;$s++){
								if($intv<${'intv'.$s}){
									$intv=${'intv'.$s};
									$best=$s;
								}
								if($row2['intervention_'.$s]){
									$intervention_array[$row2['intervention_'.$s]]=${'intv'.$s};
								}
							}
							if($intervention_array){
								arsort($intervention_array);
								$trig_array[$row2['trig']]=$intervention_array;
								$trig_array[$row2['trig']]['episodes']=$episodes;
								$trig_array[$row2['trig']]['duration']=$duration;
								$trig_array_keys[$row2['trig']]=array_keys($intervention_array);
								$values[]=$intervention_array;
								$best='intervention_'.$best;
							}
				}

		if($trig_array_keys){

            // print "<table class='center scroll local eoi hover'  bgcolor='white'>";
            print "<table class='table table-bordered'>";
                // print "<thead>";
					print "<tr>";
						print "<th colspan='8'>$behavior $behavior_spelling Episodes.  Start Date: <em>$date_start</em>   End Date:   <em>$date_end</em></th>";
					print "</tr>";
					print"<tr>";
						print"<th class='first'>Trigger (episodes/duration)</th>";
						$j=0;
						foreach($trig_array_keys as $trig){
							if($j==0){
								$trigger_array_keys=(array_keys($trig_array_keys));
								if($trig!='duration'||$trig!='episodes'){
									//for($i=1;$i<=count($trig);$i++){// print intervention number
									for($i=1;$i<=6;$i++){// print intervention number
										print"<th>Interv. $i</th>";
									}
								}
										// print"<th>Graph</th>";
							}
					print"</tr>";

					print"<tr>";
							print"<td class='first'>$trigger_array_keys[$j]</td>";
							$tr=array_keys($trig_array[$trigger_array_keys[$j]]);

							for($i=0;$i<6;$i++){// print intervention key
								if(isset($tr[$i])&&$tr[$i]!='episodes'&&$tr[$i]!='duration'){
									print"<td>$tr[$i]</td>";
								}else{
									print"<td>None Set</td>";
								}
							}
							// print"<td>None</td>";//no support for this yet
					print"</tr>";
					print"<tr>";
						$a=$trigger_array_keys[$j];
							print"<em>";
								print"<td class='first'>". $trig_array[$a]['episodes'].' episodes / '. $trig_array[$a]['duration'].' minutes'."</td>";
							print"</em>";
							$trig=array_values($trig_array[$trigger_array_keys[$j]]);
								for($i=0;$i<6;$i++){// print intervention key
									if(isset($trig[$i])&&$trig[$i]){
										print"<td>$trig[$i]</td>";
									}else{
										print"<td>None</td>";
									}
								}

								// for($i=0;$i<count($trig)-2;$i++){
								// 	print"<span class='tab_90'>$trig[$i]</span>";
								// }
								// print"<td>None</td>";//no support for this yet
									unset($trig);

					print"</tr>";




				// print "</tbody>";

				unset($trig);
				$j++;
				}
			print"</table>";
		}//end foreach
	}//end if tri_array_keys exists
	print"</td></tr>";
	print"</table>";
}


if($trigger_breakdown){ ////////////////////////////////////////trigger breakdown//////////////////////////////////////
    print"<h3 align=center>Trigger and Intervention Analysis</h3>\n";

						print"<input type='submit' style='float:right' value='Tap for more Info' onClick=\"alert('This is the Trigger and Intervention Breakdown. Information listed is the total number of each type of episode, total duration of those episodes, and the most effective intervention based on cumulative reduction in episode intensity.  Click on the pie icon to display a pie chart of all episode type durations.');return false\">";

    $r=0;
    $trigger_array_index=0;
    foreach($scale_array as $behavior){

        $trigger_count=0;
        $trigger_duration=NULL;

        $behavior_maps_sql="SELECT * FROM behavior_maps WHERE behavior='$behavior' AND residentkey IN ('".implode("', '", $residentkey_array)."')";
        //$sql2="SELECT * FROM behavior_maps WHERE behavior='$behavior' AND residentkey='$residentkey'";
        $behavior_maps_session=mysqli_query($conn,$behavior_maps_sql);



            print "<table align='center' width='100%'>";
            	// print "<tr align='right'>";
            	// 	print "<td>";

						// print"<input type='submit' value='Tap for more Info' onClick=\"alert('This is the Trigger and Intervention Breakdown. Information listed is the total number of each type of episode, total duration of those episodes, and the most effective intervention based on cumulative reduction in episode intensity.  Click on the pie icon to display a pie chart of all episode type durations.');return false\">";

				// 	print "</td>";
				// print "</tr>";

            print "<tr><td>";
            // print "<table  class='table scroll local' border='1' bgcolor='white'>";
            print "<table class='table table-bordered'>";
            
                // print "<thead>";
                    print"<tr><th colspan='5'>$behavior $behavior_spelling Episodes From <em>$date_start</em> - <em>$date_end</em></th></tr>";
                    print"<tr>";
                    		print"<th>----Trigger----</th>";
                    		print"<th>Number of Episodes</th>";
                    		print"<th>Duration of Episodes</th>";
                    		print"<th>Most Effective Intervention</th>";
                    		print"<th>Graph</th>";
                    print"</tr>";
            // print "</thead>";
            // print "<tbody>";
                while($behavior_maps_row=mysqli_fetch_assoc($behavior_maps_session)){

                                $intervention_array=null;
                                $trigger_array[]=$behavior_maps_row['trig'];
                                $episodes=0;
                                $duration=0;
                                $intv=0;
                                $intv1=0;
                                $intv2=0;
                                $intv3=0;
                                $intv4=0;
                                $intv5=0;
                                $intv6=0;

																print"<tr align='center'>";
                                    print "<td> $behavior_maps_row[trig] </td>";

																			if($residentkey=='all_residents'){
																				$behavior_map_data_sql="SELECT * FROM behavior_map_data WHERE date > '$date_start' AND date < '$date_end' AND behavior='$behavior' AND residentkey IN ('".implode("', '", $residentkey_array)."')";
																			}else{
																				$behavior_map_data_sql="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND behavior='$behavior' AND date > '$date_start' AND date < '$date_end'";
																			}
								                                $behavior_map_data_session=mysqli_query($conn,$behavior_map_data_sql);


                                            $trig_episodes=False;
                                            while($behavior_map_data_row=mysqli_fetch_assoc($behavior_map_data_session)){
                                                if($behavior_maps_row['mapkey']==$behavior_map_data_row['mapkey']){
                                                    $episodes=$episodes+1;
                                                    $duration=$duration+$behavior_map_data_row['duration'];
                                                    $intv1=$intv1+$behavior_map_data_row['intervention_score_1'];
                                                    $intv2=$intv2+$behavior_map_data_row['intervention_score_2'];
                                                    $intv3=$intv3+$behavior_map_data_row['intervention_score_3'];
                                                    $intv4=$intv4+$behavior_map_data_row['intervention_score_4'];
                                                    $intv5=$intv5+$behavior_map_data_row['intervention_score_5'];
                                                    $intv6=$intv6+$behavior_map_data_row['intervention_score_6'];
                                                    $trig_episodes=True;

                                                }
                                            }//end invtervention while
                                            if($trig_episodes){
                                                $trigger_duration[$behavior_maps_row['trig']]=$duration;
                                                $intv=0;
                                                for ($s=1;$s<7;$s++){
                                                    if(${'intv'.$s}<0){
                                                        ${'intv'.$s}=0;
                                                    }
                                                    if($intv<${'intv'.$s}){
                                                        $intv=${'intv'.$s};
                                                        $best=$s;
                                                    }
                                                    if($behavior_maps_row['intervention_'.$s]){
                                                        $intervention_array[$behavior_maps_row['intervention_'.$s]]=${'intv'.$s};
                                                    }

                                                }
                                                $values[]=$intervention_array;

                                                print"<td>$episodes</td>";
                                                print"<td>$duration</td>";

                                                $best_intervention='intervention_'.$best;
                                                print"<td>$behavior_maps_row[$best_intervention]</td>";
																								print"<td><INPUT class='icon' height='35' type=\"image\" src=\"Images/pie_icon.png\" onClick=\"window.open('behaviorgraph'+($r+20)+'.png','','width=700px,height=400')\"></td>";

                                                $graphTitle='Relative Effectiveness of '.$trigger_array[$trigger_array_index].' Interventions';
                                                $yLabel='Relative Effectiveness';

                                                ABAIT_pie_graph($values[$r], $graphTitle, $yLabel,$r+20);


                                                //print"<td align=center>";

                                                $r+=1;

                                            }else{
                                                print "<td>0</td><td>0</td><td>None</td><td>No Graph</td>";
                                            }
                                print"</tr>";

                    	$trigger_array_index+=1;
                	}//end row2 while for each trigger
								// print "</tbody>";
            print "</table>";

     	print"</td></tr></table>";

    }//end foreach
}// end trigger_breakdown if



if($carer_breakdown){ ////////////////////////////////////////carer breakdown//////////////////////////////////////

	///////// testing //////////
	$dataPoints1 = array(
    array("label"=> "2010", "y"=> 36.12),
    array("label"=> "2011", "y"=> 34.87),
    array("label"=> "2012", "y"=> 40.30),
    array("label"=> "2013", "y"=> 35.30),
    array("label"=> "2014", "y"=> 39.50),
    array("label"=> "2015", "y"=> 50.82),
    array("label"=> "2016", "y"=> 74.70)
);
$dataPoints2 = array(
    array("label"=> "2010", "y"=> 64.61),
    array("label"=> "2011", "y"=> 70.55),
    array("label"=> "2012", "y"=> 72.50),
    array("label"=> "2013", "y"=> 81.30),
    array("label"=> "2014", "y"=> 63.60),
    array("label"=> "2015", "y"=> 69.38),
    array("label"=> "2016", "y"=> 98.70)
);


	/////// end testing ///////////


	if($residentkey=='all_residents'){
		$res_name="all residents";
	}else{
		$res_name=$res_first." ".$res_last;
	}
    print"<h3 align=center>Carer Presence during Episodes</h3>\n";

    $carers_sql = "SELECT * from personaldata WHERE target_population='$Population'";
    $carers_session=mysqli_query($conn,$carers_sql);
    $carer_presence_array=array();
    while($row=mysqli_fetch_assoc($carers_session)){
    	$carer_presence_array[$row['personaldatakey']]=array("name"=> $row['first']." ".$row['last'],"On Staff"=>0,"Present During Incident"=>0,"Present During Intervention"=>0);
    }

    $behavior_map_data_sql="SELECT * FROM behavior_map_data WHERE date > '$date_start' AND date < '$date_end' AND residentkey IN ('".implode("', '", $residentkey_array)."')";
    $behavior_map_data_session=mysqli_query($conn,$behavior_map_data_sql);

    while($row=mysqli_fetch_assoc($behavior_map_data_session)){
    	foreach($carer_presence_array as $x => $x_value) {
    		if(in_array($x,explode(",", $row['on_staff']))){
    			$carer_presence_array[$x]['On Staff']+=1;
    		}
    		if(in_array($x,explode(",", $row['staff_present_incident']))){
    			$carer_presence_array[$x]['Present During Incident']+=1;
    		}
    		if(in_array($x,explode(",", $row['staff_present_intervention']))){
    			$carer_presence_array[$x]['Present During Intervention']+=1;
    		}
    	}
    }

    $resident_mapping_data_sql="SELECT * FROM resident_mapping WHERE date > '$date_start'  AND date < '$date_end' AND residentkey IN ('".implode("', '", $residentkey_array)."')";
    $resident_mapping_data_session=mysqli_query($conn,$resident_mapping_data_sql);
    while($row=mysqli_fetch_assoc($resident_mapping_data_session)){

    	foreach($carer_presence_array as $x => $x_value) {
    		if(in_array($x,explode(",", $row['on_staff']))){
    			$carer_presence_array[$x]['On Staff']+=1;

    		}
    		if(in_array($x,explode(",", $row['staff_present_incident']))){
    			$carer_presence_array[$x]['Present During Incident']+=1;
    		}
    		if(in_array($x,explode(",", $row['staff_present_intervention']))){
    			$carer_presence_array[$x]['Present During Intervention']+=1;
    		}
    	}
    }

    // foreach($scale_array as $behavior){

        $trigger_count=0;
        $trigger_duration=NULL;

        $behavior_maps_sql="SELECT * FROM behavior_maps WHERE behavior='$behavior' AND residentkey IN ('".implode("', '", $residentkey_array)."')";
        $behavior_maps_session=mysqli_query($conn,$behavior_maps_sql);

            print "<table class='table table-bordered'>";

                    print"<tr align='center' ><th colspan='5' >Carer Interactions with $res_name From <em>$date_start</em> - <em>$date_end</em></th></tr>";
                    print"<tr >";
                    		print"<th colspan='1'>Carer</th>";
                    		print"<th colspan='1'>On Staff</th>";
                    		print"<th colspan='1'>Present During Episode</th>";
                    		print"<th colspan='1'>Present During Interaction</th>";
                    		print"<th colspan='1'>Graph</th>";
                    print"</tr>";


            		$on_staff = array();
            		$present_incident = array();
            		$staff_present_intervention = array();
            		foreach ($carer_presence_array as $key => $value) {
            			$on_staff[] = array("label" => $carer_presence_array[$key]["name"], "y"=> $carer_presence_array[$key]["On Staff"]);
            			$present_incident[] = array("label" => $carer_presence_array[$key]["name"], "y"=> $carer_presence_array[$key]["Present During Incident"]);
            			$staff_present_intervention[] = array("label" => $carer_presence_array[$key]["name"], "y"=> $carer_presence_array[$key]["Present During Intervention"]);


            			print"<tr >";
            			    foreach ($carer_presence_array[$key] as $key1 => $value1) {
            			    	print"<td colspan='1'>$value1</td>";
            			    }
            				print"<td><INPUT class='icon' height='35' type=\"image\" src=\"Images/chart_icon.png\" onClick='chart_call()'></td>";
            			print"</tr>";
            		}



            print "</table>";

     	// print"</td></tr></table>";

    // }//end foreach
}// end carer_breakdown if
// foreach ($on_staff as $key => $value) {

// 	echo $value["name"],$value['y'];

// }
?>
<script>
function chart_call() {
	obj = document.getElementById("chartContainer");
	obj1 = document.getElementById("hide_graph");
	obj.style.display = "block";
	obj1.style.display = "block";
 
var chart = new CanvasJS.Chart("chartContainer", {

    animationEnabled: true,
    theme: "light2",
    title:{
        text: "Carer Resident Interactions"
    },
    axisY:{
        includeZero: true
    },
    legend:{
        cursor: "pointer",
        verticalAlign: "center",
        horizontalAlign: "right",
        itemclick: toggleDataSeries
    },
    data: [{
        type: "column",
        name: "On Staff",
        indexLabel: "{y}",
        yValueFormatString: "#0",
        showInLegend: true,
        dataPoints: <?php echo json_encode($on_staff, JSON_NUMERIC_CHECK); ?>
    },{
        type: "column",
        name: "Present During Incident",
        indexLabel: "{y}",
        yValueFormatString: "#0",
        showInLegend: true,
        dataPoints: <?php echo json_encode($present_incident, JSON_NUMERIC_CHECK); ?>
    },{
        type: "column",
        name: "Present During Intervention",
        indexLabel: "{y}",
        yValueFormatString: "#0",
        showInLegend: true,
        dataPoints: <?php echo json_encode($staff_present_intervention, JSON_NUMERIC_CHECK); ?>
    }]
});
chart.render();
 
function toggleDataSeries(e){
    if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
        e.dataSeries.visible = false;
    }
    else{
        e.dataSeries.visible = true;
    }
    chart.render();
}
 
}
</script>
<?

print"<div id='chartContainer' style='display: none; height: 370px; width: 100%;'></div>";
print"<script src='static/js/canvasjs.min.js'></script>";

print"<div id='hide_graph' style='display: none'>";
	print "<p class='backButton'>";
		print "<input	type = 'button'
					name = ''
					id = 'hideButton2'
					value = 'Hide Chart'
					onClick=\"hide()\"/>\n";
	print "</p>";
print"</div>";


if($all_episode){//////////////////////////////////////////all_episode/////////////////////////////////////////
		if($residentkey=='all_residents'){
			$sql="SELECT * FROM behavior_map_data WHERE date > '$date_start' AND date < '$date_end' ORDER BY date, residentkey";
		}else{
			$sql="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start' AND date <'$date_end' ORDER BY date";
		}
		$session=mysqli_query($conn,$sql);

			print "<table width='100%'>";//
				print "<tr><td>";//table in table data for more info
				print"<h3 class='center_header'>All Mapped $behavior_spelling Episode Report</h3>";
				print"</td></tr>";

        // print "<table class='center noScroll local hover'>";
				print "<table class='table table-bordered'>";
          
						print "<tr>";

							print "<th></th>";
							print "<th colspan='2'>Start Date   <em>$date_start</em></th>";
							// print "<th>$date_start</th>";
							print "<th colspan='2' >End Date    <em>$date_end</em></th>";
							// print "<th>$date_end</th>";
							print "<th></th>";

							print "</tr>";
						print "<tr>";

							print "<th>Resident</th>";
							print "<th>Date</th>";
							print "<th>Time</th>";
							print "<th>$behavior_spelling Classification</th>";
							print "<th>Trigger</th>";
							print "<th>PRN Given</th>";

					print "</tr>";

				while($row=mysqli_fetch_assoc($session)){
					$sql1="SELECT trig, residentkey FROM behavior_maps WHERE mapkey='$row[mapkey]'";
					$session1=mysqli_query($conn,$sql1);
					$row1=mysqli_fetch_assoc($session1);
					$rk = $row1['residentkey'];
					//$residentkey_assoc_array[$rk]
					print "<tr>";

							print"<td>$residentkey_assoc_array[$rk]</td>";
							print"<td>$row[date]</td>";
							print"<td>$row[time]</td>";
							print"<td>$row[behavior]</td>";
							print"<td>$row1[trig]</td>";
							if($row['PRN']==1){
								print"<td>Yes</td>";
							}else{
								print"<td>None</td>";
							}

					print "</tr>";
				}

			print "</table>";
		print "</td></tr>";
	print "</table>";


}//end all_epsisode if

if($include_unmapped){//////////////////////////////////////////include_unmapped/////////////////////////////////////////
		if($residentkey=='all_residents'){
			$sql="SELECT * FROM resident_mapping WHERE date > '$date_start' AND date < '$date_end' ORDER BY date, residentkey";
		}else{
			$sql="SELECT * FROM resident_mapping WHERE residentkey='$residentkey' AND date > '$date_start' AND date <'$date_end' ORDER BY date";
		}
		$session=mysqli_query($conn,$sql);

			print "<table width='100%'>";//
				print "<tr><td>";//table in table data for more info
				print"<h3 class='center_header'>Unmapped $behavior_spelling Episode Report</h3>";
				print"</td></tr>";

        print "<table class='table table-hover' border='1'>";

						print "<tr>";

							print "<th></th>";
							print "<th colspan='2'>Start Date:    <em>$date_start</em></th>";
							// print "<th>$date_start</th>";
							print "<th colspan='2'>End Date:     <em>$date_end</em></th>";
							// print "<th>$date_end</th>";
							print "<th></th>";

						print "</tr>";
						print "<tr>";

							print "<th>Resident</th>";
							print "<th>Date</th>";
							print "<th>Time</th>";
							print "<th>$behavior_spelling Classification</th>";
							print "<th>Trigger</th>";
							print "<th>PRN Given</th>";

					print "</tr>";

				$total_duration = 0;
				while($row=mysqli_fetch_assoc($session)){
					$total_duration += $row['duration'];
					$rk = $row['residentkey'];
					//$residentkey_assoc_array[$rk]
					print "<tr>";

							print"<td>$residentkey_assoc_array[$rk]</td>";
							print"<td>$row[date]</td>";
							print"<td>$row[time]</td>";
							print"<td>$row[behavior]</td>";
							print"<td>$row[trigger]</td>";
							if($row['PRN']==1){
								print"<td>Yes</td>";
							}else{
								print"<td>None</td>";
							}

					print "</tr>";
				}
				if($total_duration==0){
					print "<tr align='center'><td colspan='6'> Unmapped $behavior_spelling episodes have not been recorded.</td></tr>";
				}else{
					print "<tr>";
						print "<td colspan=3>Total Duration of Episodes (min) </td>";
						print "<td colspan=3>$total_duration</tr>";
					print "</tr>";
				}

			print "</table>";
		print "</td></tr>";
	print "</table>";


}//end include_unmapped if

print "<div class='mb-4'>";
print "<p class='backButton'>";
	print "<input	type = 'button'
				name = ''
				class='mb-3'
				id = 'backButton3'
				value = 'Return to Analysis Design'
				onClick=\"backButton1('$Population')\"/>\n";
print "</p>";
print "</div>";

print "</br>";

?>
<!--
			<div id = "submit">
				<input 	type = "submit"
						name = "submit"
						value = "Back to Admin Home Page">
			</div>

	</form> -->

<?build_footer_pg()?>
</body>
</html>
