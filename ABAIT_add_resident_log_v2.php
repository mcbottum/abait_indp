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

</head>
<?	
	$names = build_page_pg();
?>


		
		<form 	action = "ABAIT_adminhome_v2.php"
				method = "post">
									
			<h3 class='m-3 p-2 footer_div' align='center'>Resident Data</h3>

<?
		$first=$_REQUEST['first'];
		$last=$_REQUEST['last'];
		if(isset($_REQUEST['gender'])){
			$gender=$_REQUEST['gender'];
		}
		$action=$_REQUEST['action'];
		if(isset($_REQUEST['residentkey'])){
			$residentkey=$_REQUEST['residentkey'];
		}
		$house=$_REQUEST['sel_house'];
		if($house=='other'){
			$house=str_replace(' ','_',$_REQUEST['custom_house']);
		};
	
		//$year=$_REQUEST['year'];
		//$month=$_REQUEST['month'];
		//$day=$_REQUEST['day'];
		//$birthdate=$year.$month.$day;
		//$age=floor((time() - strtotime($birthdate))/31556926);
		$date=date("Y,m,d");
		$Target_Population=str_replace('_',' ',$_REQUEST['Target_Population']);

		$privilegekey=$_SESSION['personaldatakey'];

		$conn = make_msqli_connection();

		if($first&&$last&&$gender&&$Target_Population&&$action=='Enroll'){
			$Target_Population=mysqli_real_escape_string($conn,$Target_Population);
			mysqli_query($conn, "INSERT INTO residentpersonaldata VALUES(null,'$first','$last',null,'$gender','$privilegekey','$Target_Population','$house',null,'',null)");
			print "<h4 align='center'>$first $last has been entered as a new resident.</h4>\n";
			print "<h4 align='center'><a href='ABAIT_add_resident_v2.php'>Return to Enroll New Resident Form</a></h4>\n";
		}else if($first&&$last&&$gender&&$Target_Population&&$action=='Update'){
			$Target_Population=mysqli_real_escape_string($conn,$Target_Population);
			mysqli_query($conn, "UPDATE residentpersonaldata SET first='$first', last='$last', gender='$gender', Target_Population='$Target_Population', house='$house' WHERE residentkey='$residentkey'");
			print "<h4 align='center'>Resident $first $last has been Updated.</h4>\n";
			print "<h4 align='center'><a href='ABAIT_add_resident_v2.php'>Return to Enter a new Resident Form</a></h4>\n";
			print "<h4 align='center'><a href='updateMembers.php'>Return to Update Members Form</a></h4>\n";


		}else{
			print"<h4 align='center'>Please return to resident data page, some information was missing.</h4>";
			print "<h4 align='center'><a href='ABAIT_add_resident_v2.php'>New Resident Form</a></h4>\n";
			print "<h4 align='center'><a href='updateMembers.php'>Update Resident Data</a></h4>\n";

		}
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