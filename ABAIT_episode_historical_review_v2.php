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
<style>
    table.local thead th{
        width:500px;
    }
    table.local tbody{
        max-height: 400px;
    }
    table.local tbody td{
        width:500px;
    }
    label {
        /* whatever other styling you have applied */
        width: 100%;
        display: inline-block;
    }
	input[type=checkbox]{
	  	transform:scale(1.5);
	}
	input[type=radio]{
	  	transform:scale(1.5);

	}
	input[type=radio]:hover {

	}

	.space { 
		margin:10px;   
	}
.scroll tbody,
.scroll thead { 
  	margin-left: auto;
  	margin-right: auto;
}

.table {
  	margin-left: auto;
  	margin-right: auto;	
}


</style>

</head>

<body class="container">

<?  
$names = build_page_pg();


print"<h2 class='m-3 p-2 footer_div' align='center'>Resident Espisode Historical Review</h2>";


$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
if(isset($_GET['tp'])){
	$Population=str_replace('_',' ',$_GET['tp']);
}
elseif(isset($_REQUEST['Population'])){
	$Population=str_replace('_',' ',$_REQUEST['Population']);
}else{
	$Population=null;
}

if($_SESSION['Target_Population']=='all'&&!$Population){
			$sql1="SELECT * FROM behavior_maps";


			$session1=mysqli_query($conn,$sql1);

			?>
		<form action="episode_historical_review.php" method="post">
			<?
			print"<h3><label>Select ABAIT Target Population</label></h3>";
			?>
								<select name = 'Population'>
			<?
								print"<option value =''>Choose</option>";
									$Target_Pop[]="";
									while($row1=mysqli_fetch_assoc($session1)){
										if(!array_search($row1['Target_Population'],$Target_Pop)){
											$pop=str_replace(' ','_',$row1[Target_Population]);
											$pop_strip=mysqli_real_escape_string($conn,$pop);
											print"<option value=$pop>$row1[Target_Population]</option>";
											$Target_Pop[]=$row1[Target_Population];
										}
									}
								print"</select>";
		?>
					<div id="submit">
						<input 	type = "submit"
								name = "submit"
								value = "Submit Target Population">
					</div>
				</form>
		<?
}//end global admin if
else{

?>
	<form 	name = 'form1'
		action = "ABAIT_episode_historical_review_analysis_v2.php"
		method = "post">
<? //print $Population;
	$scale_array[]=null;
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
if($_SESSION['Target_Population']!='all'){
	$Population_strip=mysqli_real_escape_string($conn,$_SESSION['Target_Population']);
	$sql1="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population_strip' order by first";
	$sql2="SELECT * FROM behavior_maps WHERE Target_Population='$Population_strip'";
	$sql3="SELECT * FROM scale_table WHERE Target_Population='$Population_strip'";
	$Population=$_SESSION['Target_Population'];
}//end target population if
else{
	$Population_strip=mysqli_real_escape_string($conn,$Population);
	$sql1="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population_strip' order by first";
	$sql2="SELECT * FROM behavior_maps WHERE Target_Population='$Population_strip'";
	$sql3="SELECT * FROM scale_table WHERE Target_Population='$Population_strip'";

}//end else
$pop=str_replace(' ','_',$Population);
//print $pop;
$_SESSION['pop']=$pop;
print"<input type='hidden' value='$pop' name='Target_Population'>";
//print $Population;

$scale_array=array();
	$session1=mysqli_query($conn,$sql1);
	$session3=mysqli_query($conn,$sql3);
	//following makes an array of scale names
	$scale_holder='';
		while($row3=mysqli_fetch_assoc($session3)){
			if(!in_array($row3['scale_name'],$scale_array)){
				$scale_array[]=$row3['scale_name'];
			}
			$scale_holder=$row3['scale_name'];
		}
	$_SESSION['scale_array']=$scale_array;

	$counterarray=$_SESSION['scale_array'];


	print "<h4 align='center'>Select Resident <input type='submit' align='block' value='Info' onClick=\"alert('Choose either a single resident or all residents for review.  Note, All Residents provides a comparison of intervention effectiveness between residents.');return false\"></h4>";

// print "<table class='wrapper-table table-responsive'>";
// 	print "<tr >";
// 		print "<td>";
			print "<table class='table table-hover scroll mt-5 mb-5 local' align='center' width='100%'>";
				print "<thead align='center'>";
					print"<tr align='center'>";
						print"<th>";
							print"<p><label>";
								print"<span class='tab'>Click Choice</span>";
								print"<span class='tab'>First Name</span>";
								print"<span class='tab'>Last Name</span>";
							print"</label></p>";
						print"</th>";
					print"</tr>";
				print "</thead>";
				print "<tbody align='center'>";
					print "<tr align='center'>";
						print "<td>";
							print "<label>";
								print "<span class='tab'>";
									print "<input 	type = 'radio'
													class='space'
													name = 'residentkey'
													value ='all_residents'>";
								print"</span>";
								print "<strong><span class='tab'>All Resident</span><span class='tab'> Summary</span></strong>";
							print"</label>";
						print"</td>";
					print"</tr>";
				while($row1=mysqli_fetch_assoc($session1)){
					print"<tr align='center'>";
						print"<td>";
							print "<label>";
								print "<span class='tab'>";
									print "<input type = 'radio'
														class='space'
														name = 'residentkey'
														value = $row1[residentkey]>";
								print"</span>";
								print "<span class='tab'> $row1[first]</span>";
								print "<span class='tab'> $row1[last]</span>";
							print"</label>";
						print"</td>";
					print "</tr>";
				}
				print "</tbody>";
				
			print "</table>";
// 		print "</td>";
// 	print "</tr>";
// print "</table>";







		?>


			<h4 align='center'>Design Review <input type='submit' value='Info' onClick="alert('Selecting checkboxes allows a customizable analysis. Review dates are from selected interval to present.');return false"></h4>
			<h5 align='center'><label><input type='checkbox'
								class='space'
								name='include_unmapped'
								value='1'>Include Unmapped Behavior Report</label></h5>
		<?

		print "<table class='center'><tr><td>";
			print "<table class='table local' border='0' bgcolor='white'>";
				print "<thead>";
					print "<tr>\n";
						print "<th align='center'>Review Duration</th>\n";
						print "<th align='center'>Behavior</th>\n";
						print "<th align='center'>Analysis</th>\n";
					print "</tr>\n";
				print "</thead>";
				print "<tbody>";
					print "<tr><td>\n";
						print "<table class='table table-hover local' width='100%' >\n";
							print "<tr><td>";
							print "<label>";
							print "<input type='radio'
								class='space'
								name= 'review_time'
								value= '0'>Previous Month</label></td></tr>\n";
								
							print "<tr><td>";
							print "<label>";
							print "<input type='radio'
								class='space'
								name= 'review_time'
								value= '1'>30 Days</label></td></tr>\n";

							print "<tr><td>";
							print "<label>";
							print "<input type='radio'
								class='space'
								name= 'review_time'
								value= '3'>90 Days</label></td></tr>\n";

							print "<tr><td>";
							print "<label>";
							print "<input type='radio'
									class='space'
									name='review_time'
									value='6'>180 Days</label></td></tr>\n";

							print "<tr><td>";
							print "<label>";
							print "<input type='radio'
									class='space'
									name='review_time'
									value='all'>All Time</label></td></tr>\n";

							//print "<tr><td><input type='textbox' size='5'
								//name='custom_time'>Select Date</td></tr>\n";
						print "</table>\n";
					print"</td>\n";
					print"<td>\n";
							//print"<td rowspan=$count>\n";
							//print"<ul id='counterarray'>\n";
						print"<table class='table-hover'>";
								foreach($counterarray as $count){
									$count_=str_replace(' ','_',$count);
									print"<tr>";
									print "<td>\n";
									print "<label>";
									print"<input type='checkbox' class='space' name='$count_' value='$count'>$count";
									print "</label>";
									print"</td></tr>\n";
								}
								print"<tr><td><label><input type='checkbox' class='space' name='all' value='all'>All Behavior</label></td></tr>";
						print"</table>\n";
					print"</td>\n";
					print"<td>\n";
						print"<table class='table-hover'>\n";
							print"<tr><td>";
		  						print "<label>";
									print "<input type='checkbox'
											class='space'
											name='scale_totals'
											value='1'>Behavior Episode Totals</label>";
							print "</td></tr>\n";
						
								

							print"<tr><td>";
							print "<label>";
							print "<input type='checkbox'
									class='space'
									name='behavior_units'
									value='1'>Behavior Units Improved vs. Intervention</label>";
							print "</td></tr>\n";

							//print"<tr><td><input type='checkbox'
							//		name='behavior_units_per_time'
							//		value='1'>Behavior Units Improved to Caregiver Time Ratio</td></tr>\n";

							print"<tr><td>";
							print "<label>";
							print "<input type='checkbox'
									class='space'
									name='episode_time_of_day'
									value='1'>Episode Time of Day</label>";
							print "</td></tr>\n";

							print"<tr><td>";
							print "<label>";
							print "<input type='checkbox'		
									class='space'
									name='trigger_breakdown'
									value='1'>Trigger Breakdown / Most Effective Intervention</label>";
							print "</td></tr>\n";

							print"<tr><td>";
							print "<label>";
							print "<input type='checkbox'
									class='space'
									name='carer_breakdown'
									value='1'>Carer Involvement</label>";
							print "</td></tr>";

							print"<tr><td>";
							print "<label>";
							print "<input type='checkbox'
									class='space'
									name='all_episode'
									value='1'>All Episode Printout</label>";
							print "</td></tr>";
						print"</table>\n";
					print"</td></tr>\n";
				print "</tbody>";
			print "</table>";
		print "</td>";

		print "</tr>";
	 print "</table>";
	?>
		<div id = "submit">
			<input 	type = "submit"
					name = "submit"
					value = "Submit Resident for Global Analysis">
		</div>
	<?
}//end else for if not global admin
?>

	</form>
	<?build_footer_pg()?>
</body>
</html>
