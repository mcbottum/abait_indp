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

</head>
<body class="container">

<?  
$names = build_page_pg();
print"<h2 class='m-3 p-2 footer_div' align='center'>Medicated Intervention Report Log</h2>";
?>
		<form 	action = "ABAIT_adminhome_v2.php"
				method = "post">
<?


		$conn=make_msqli_connection();

		$Population=$_REQUEST['population'];
		$request_residentkey=$_REQUEST['residentkey'];
		if($request_residentkey=='all'){
			$sql="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population'";
		}else{
			$sql="SELECT * FROM residentpersonaldata WHERE residentkey='$request_residentkey'";
		}

		$session=mysqli_query($conn,$sql);
		//yikes, loop through all residents in population !!

		while($row=mysqli_fetch_assoc($session)){
			$residentkey=$row['residentkey'];
			$PRNreport_reskey='PRNreport_'.$residentkey;

				$PRN_report=$_REQUEST[$PRNreport_reskey];

				$date=date("Y,m,d");
				$privilegekey=$_SESSION['personaldatakey'];
				if($PRN_report){
					mysqli_query($conn,"INSERT INTO PRN_report VALUES(null,'$privilegekey','$residentkey','$Population','$date','$PRN_report')");
					echo mysqli_error($conn);
					print "<h3 align='center'>PRN Review Report for ".$row['first']." ".$row['last']." has been logged.</h3>\n";
				}else{
					print"<h3 style='color:red'>Please return to PRN Report Page, PRN report was missing for ".$row['first']." ".$row['last'].".<h3>";
				}
		}// END WHILE.
?>

				<div id = "submit">
				<input 	type = "submit"
						name = "submit"
						value = "Return to Administrator Home"/>
				</div>

	</form>
<?build_footer_pg()?>
</body>
</html>
