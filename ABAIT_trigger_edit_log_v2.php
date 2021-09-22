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

<script type="text/javascript">
	function backButton(target_population) {
		self.location='ABAIT_trigger_v2.php';
	}
</script>

<?
	set_css()
?>

<style type="text/css">
	p.backButton{
		float: right;
	}
</style>

</head>

<body class="container">

<?	
	$names = build_page_pg();
?>

<?

	$conn=make_msqli_connection();

	$sql="SELECT * FROM behavior_maps WHERE mapkey='$_SESSION[mapkey]'";
	$session=mysqli_query($conn,$sql);
	$row=mysqli_fetch_assoc($session);
	$trig=$_REQUEST['trig'];

print "<div class='row justify-content-md-center'> ";
	print "<div class='col col-lg-5 mt-4'>";

		if(isset($_REQUEST['delete_scale'])){
			$del_scale=$_REQUEST['delete_scale'];
			mysqli_query($conn,"DELETE FROM behavior_maps WHERE mapkey = '$_SESSION[mapkey]'");
			print"<h5 class='text-center'><em><b>Behavior Scale for: $row[behavior] $row[trig] DELETED</em></h5></b>\n";

		}else{
			for($i=1;$i<6;$i++){
				${'intervention'.$i}=$_REQUEST['intervention_'.$i];
			}
			print"<h3 class='text-center'>Scale Update</h3>";
			if($trig){
				$sql6="UPDATE behavior_maps SET trig='$trig' WHERE mapkey='$_SESSION[mapkey]'";
				$retval = mysqli_query($conn,$sql6);
				print"<h5 class='text-center'><em>$row[trig]</em> replaced with <em>$trig</em> as updated trigger.</h5><br>";
			}
			for($i=1;$i<6;$i++){
				if(${'intervention'.$i}){
					$intervention='intervention_'.$i;
					$sqlv='sql'.$i;
					$sqlv="UPDATE behavior_maps SET $intervention='${'intervention'.$i}' WHERE mapkey='$_SESSION[mapkey]'";
					$retval = mysqli_query($conn,$sqlv);
					print"<h5 class='text-center'><em>$row[$intervention]</em> replaced with <em>${'intervention'.$i}</em> as updated $intervention</h5><br>";
				}
			}
		}//END ELSE
?>
	</div>
</div>
<!-- <div class='row justify-content-md-center'> 
	<div class='col col-lg-5 mt-4'>

		<button type='button' 
			class='btn btn-info' 
			id = 'backButton' 
			value = 'Back to Scale Edit Page' 
			onClick="backButton('')">
			Back to Scale Edit Page
		</button>


	</div>
</div> -->


</form>

<? 
	build_footer_pg();
?>
</body>
</html>
