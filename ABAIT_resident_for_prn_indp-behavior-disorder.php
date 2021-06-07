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

	table.hover tbody tr:hover{
			background-color: #D3D3D3;
	}
	label {
			/* whatever other styling you have applied */
			width: 100%;
			display: inline-block;
	}
    .table th {

	   	background-color: lightgrey;
    }
	input[type=radio]{
	  	transform:scale(1.5);
	}
</style>
</head>
<body class="container">

<?  
$names = build_page_pg();

print"<h2 class='m-3 p-2 footer_div' align='center'>Residents for Police Intervention Review</h2>";

if(isset($_REQUEST['Population'])){
	$Population=str_replace('_',' ',$_REQUEST['Population']);
}else{
	$Population=Null;
}
if($_SESSION['Target_Population']=='all'&&!$Population){
	$sql1="SELECT * FROM behavior_maps";
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());

	$session1=mysqli_query($conn,$sql1);
	?>
	<form action="resident_for_prn.php" method="post">
		<?
		print"<h3><label>Select ABAIT Scale Target Population</label></h3>";
			?>
							<select name = 'Population'>
								<?
							print"<option value =''>Choose</option>";
								$Target_Pop[]="";
								while($row1=mysqli_fetch_assoc($session1)){
									if(!array_search($row1['Target_Population'],$Target_Pop)){
										$pop=str_replace(' ','_',$row1['Target_Population']);
										$pop=mysqli_real_escape_string($conn,$pop);
										print"<option value=$pop>$row1[Target_Population]</option>";
										$Target_Pop[]=$row1['Target_Population'];
									}
								}
							print"</select>";
							?>
				<div id="submit">
					<input 	type = "submit"
							name = "submit"
							value = "Submit Target Population">
				</div>
				<?
		}//end global admin if
	else{
	?>
	<form	name = 'form1'
	 			action = "ABAIT_PRN_review_v2.php"
				method = "post">

	<?
$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
if($_SESSION['Target_Population']!='all'){
	$Population=mysqli_real_escape_string($conn,$_SESSION['Target_Population']);
	$sql="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population' order by first";
}else{
	$sql="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population' order by first";
}
	$session=mysqli_query($conn,$sql);

	print "<input type='hidden' name='population' value='$Population'>\n";

	// print "<table class='scroll local hover center' border='1' bgcolor='white'>";
	print "<table class='table table-bordered'>";
			// print "<thead>";
					print"<tr align='center'>";
							print"<th><p><span class='tab'>Click Choice</span><span class='tab'>Name</span></p></th>";
					print"</tr>";
				// print "</thead>";
				// print "<tbody>";
				print"<tr align='center'>";
					print"<td><p><label><span class='tab'>";
						print "<input type = 'radio'
													name = 'residentkey'
													value = 'all'></span>";

						print "<span class='tab'><strong>All Residents</strong></span>";
					print "</label></p></td>";
				print "</tr>";
					while($row=mysqli_fetch_assoc($session)){
						print"<tr align='center'>";
							print"<td><p><label><span class='tab'>";
								print "<input type = 'radio'
															name = 'residentkey'
															value = $row[residentkey]></span>";

								print "<span class='tab'>".$row['first']." ".$row['last']."</span>";
							print "</label></p></td>";
						print "</tr>";
					}
				// print "</tbody>";
			print "</table>";

			print "<div id = 'submit'>";
				print 	"<input 	type = 'submit'
								name = 'submit'
								value = 'Submit Resident for Review'>";
			print "</div>";
		}

	print "</fieldset>";
	print "</form>";
build_footer_pg()?>
</body>
</html>
