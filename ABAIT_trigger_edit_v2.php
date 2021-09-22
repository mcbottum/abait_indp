<?session_start();
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
<title>

<?
	print $_SESSION['SITE']
?>

</title>

<script>
	function reload(form){
	var val=form.scale_name.options[form.scale_name.options.selectedIndex].value;
	self.location='resident_map.php?scale_name='+val;
	}
</script>

<?
	set_css()
?>

<style>
	table td, table th{
		width:205px;
		height: 30px;
	}
	label {
			/* whatever other styling you have applied */
			width: 100%;
			display: inline-block;
	}
	p.backButton{
		float: right;
	}
	input[type="text"] {
	    width: 195px;
		background-color: yellow;
	}
	#delete_scale {
		transform: scale(2);
	}
</style>
</head>

<body class="container">

<?	
	$names = build_page_pg();
?>

<h3 class="m-3 p-2 footer_div" align='center'>
	Edit/Delete Triggers and Interventions
</h3>

<form 	action = "ABAIT_trigger_edit_log_v2.php" 
		method = "post">
<?
		$residentkey=$_REQUEST['residentkey'];
		//$trig=str_replace('_',' ',$_REQUEST['trig']);

		$mapkey=$_REQUEST['mapkey'];
		$_SESSION['mapkey']=$mapkey;

		$sql1="SELECT * FROM behavior_maps WHERE mapkey='$mapkey'";
		$conn1=make_msqli_connection();
		$session1=mysqli_query($conn1,$sql1);
		$row1=mysqli_fetch_assoc($session1);

			print "<h3 align='center'>Enter Trigger/Intervention Updates for:  $row1[behavior]: $row1[trig]</h3>\n";	
			print "<table class='center table noScroll local hover' border='1' bgcolor='white'>";
			print "<tr><th align='center'>Item</th>";
			print "<th align='center'>Current Entries</th>";
			print "<th align='center'>Updates</th></tr>";
			print "<tr><th>Trigger</th>";
			
			print"<td>$row1[trig]</td>";
			print"<td><input type = 'text' name ='trig' style='background-color: yellow';></td></tr>";


			
			for($j=1;$j<6;$j++){
				print"<tr><th>Intervention $j</th>";
				$intervention='intervention_'.$j;
					if($row1[$intervention]){
						print"<td>$row1[$intervention]</td>";
					}else{
						print"<td> No Intervention</td>";
					}
				print "<td><input type = 'text' name=$intervention></td></tr>\n";
			}
			print"<tr><th>Intervention 6</th><td>PRN</td></tr>";
		print"</table>";
		// DELETE CURRENT SCALE !!!!

		print "<div class='form-group form-check pb-4' align='center'>";
			print "<h3 >Delete $row1[behavior]: $row1[trig]</h3>\n";
			print"<input class='form-check-input' type = 'checkbox' align='center' name = 'delete_scale' id='delete_scale' value = $mapkey>\n";
		print "</div>";



?>

		<div id = 'submit'>
		<input 	type = 'submit'
				name = 'submit'
				value = 'Submit Intervention Update'>
		</div>

</form>

<? 
	build_footer_pg();
?>
</body>
</html>
