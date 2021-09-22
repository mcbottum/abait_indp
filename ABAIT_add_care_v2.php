<?
include("ABAIT_function_file.php");session_start();
//session_start();
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
<script type='text/javascript'>

function check_pass(){
	if (document.form.password1.value == document.form.password2.value){
		if (document.form.password1.value.length > 0 && document.form.password2.value.length > 0){
			document.getElementById('message').innerHTML = ""
		}
	} else {
		if (document.form.password1.value.length > 0 && document.form.password2.value.length > 0){
			document.getElementById('message').innerHTML = "These passwords don't yet match.";
		} else {
			document.getElementById('message').innerHTML = "";
		}
	}
}
window.onload = function() {
  document.form.first.focus();
};

function validate_form()
{
	valid=true;
	var alertstring=new String("");

	if(document.form.first.value=="")
	{
		alertstring=alertstring+"\n-First Name-";
		document.form.first.style.background = "Yellow";
		valid=false;
	}else{
		document.form.first.style.background = "white";
	}//end first name
	
	if(document.form.last.value=="")
	{
		alertstring=alertstring+"\n-Last Name-";
		document.form.last.style.background = "Yellow";
		valid=false;
	}else{
		document.form.last.style.background = "white";
	}//end first name

	var Target_Home = document.getElementById("Target_Home")
	if(Target_Home.options[Target_Home.selectedIndex].value=="Choose*")
	{
		alertstring=alertstring+"\n-Resident Home-";
		document.form.Target_Home.style.background = "Yellow";
		valid=false;
	
	}else{
		document.form.Target_Home.background = "white";
	}//end Target_Home check


	var Target_Population = document.getElementById("Target_Population")
	if(Target_Population.options[Target_Population.selectedIndex].value=="")
	{
		alertstring=alertstring+"\n-Target Population-";
		document.form.Target_Population.style.background = "Yellow";
		valid=false;
	
	}else{
		document.form.Target_Population.background = "white";
	}//end Target Population check	
	if(document.form.password1.value.length<4){
		alertstring=alertstring+"\n-Login ID must contain at least 4 characters-";
		document.form.password1.style.background = "Yellow";
		valid=false;
	} else if (document.form.password2.value.length==0)
	{
		alertstring=alertString+"\n-Please confirm your password";
		document.form.password1.style.background = "Yellow";
		valid=false;
	} else if(document.form.password1.value != document.form.password2.value)
	{
		alertstring=alertstring+"\n-Password one must match password two";
		document.form.password1.style.background = "Yellow";
		document.form.password2.style.background = "Yellow";

		valid=false;
	}else{
		document.form.password1.background = "white";
		document.form.password2.background = "white";

	}//passwordcheck
	
	if (valid==false){
		alert("Please enter the following data;" + alertstring);
	}
	return valid;
}	

function show( selTag ) {
	obj = document.getElementById("custom_house");
	customTrig = document.getElementById("custom_house");

	if ( selTag.value== 'other' ){
		customTrig.style.display = "block";
	} else {
		obj1.style.display = "none";
	}
}

</script>

<?
	set_css()
?>
<style>
.space { 
	margin:0; padding:0; height:25px; 
}
</style>

</head>

<?	
	$names = build_page_pg();



$sql="SELECT * FROM scale_table";
$conn = make_msqli_connection();

$session=mysqli_query($conn,$sql);

# select distinct values from two tables
$home_sql="SELECT DISTINCT house FROM (
    SELECT house FROM personaldata
    UNION 
    SELECT house FROM residentpersonaldata
)t";

$home_session=mysqli_query($conn,$home_sql);

if(isset($_POST["ck"])){
		$key=$_POST["ck"];
		$action="Update";
		$sql1=mysqli_query($conn,"SELECT * FROM personaldata WHERE personaldatakey=$key");
		$data=mysqli_fetch_assoc($sql1);
	}else{
		$action="Enroll";
		$data='';
	}

?>
	<form 	
			name="form"
			onsubmit="return validate_form()"		
			action = "ABAIT_add_care_log_v2.php"
			method = "post">			
	
<?		
		print"<h3 class='m-3 p-2 footer_div' align='center'>$action  Care Provider</h3>";

		print"<h4 align='center'><label id='formlabel'> Care Provider Data Form (*required)</label></h4>";

		print"<div id = 'dataform'>";

			print"<input type='hidden' name='action' value=$action>";		
			print"<table class='form' align='center'>";
			
				print"<div id ='name'>";
					print"<tr>";
						print"<td colspan=2>";
							if($data){
								print"<h4><label>Edit Care Provider Name*</label></h4>";
							}else{
								print"<h4><label>Enter Care Provider Name*</label></h4>";
							}
						print"</td>";
					print"</tr>";
					print"<tr>";
						print"<td>";

							if($data){
								print"<input type='hidden' name='key' value=$key>";
								print"<input type = 'text'
										placeholder = $data[first]
										value = $data[first]
										name = 'first'/>";
							}else{
								print"<input type = 'text'
										placeholder = 'First Name*'
										name = 'first'/>";						
							}
						print"</td>";
					print"</tr>";
					print"<tr>";
						print"<td>";
							if($data){	
								print"<input	type = 'text'
									placeholder = $data[last]
									value = $data[last]
									name = 'last'/>";
							}else{
								print"<input	type = 'text'
										placeholder = 'Last Name*'
										name = 'last'/>";	
							}
						print"</td>";
					print"</tr>";
				print"</div>";

			print"<div id = 'home' class = 'tooltip'>";
			print "<tr>";
				print "<td>";
				print "<div class='space'></div>";
				print"</td>";
			print "</tr>";
				print"<tr><td colspan=2>";
					print"<h4><label>Select or Enter Resident Home ID*</label></h4>";

				print"</td></tr>";
				print"<tr><td colspan=2>";

					print"<div class='form-group '>";
						print "<select class='form-control w-auto'  name = 'sel_house' id = 'Target_Home' onchange='show(this)'>";
						
							if($data){
								print"<option value=$data[house]>$data[house]</option>";
							}else{
								print"<option value = 'Choose*'>Choose*</option>";
							}
									while($row=mysqli_fetch_assoc($home_session)){
										if($row['house']==$data['house']&&$row['house']!=''){
											print "<option selected value=$row[house]>$row[house]</option>";
										}else{
											print  "<option value=$row[house]>$row[house]</option>";
										}
									}
								print "<option value='other'>Enter NEW House</option>";
							print"</select>";
						print "<input type = 'text' name ='custom_house' id='custom_house' class='textBox selBox' style='display: none; background-color: GreenYellow' placeholder='Enter new house here' value=''  autofocus='autofocus' onfocus=\"if(this.value==this.defaultValue) this.value='';\"/>";
					print "</div>";
				print"</td></tr>";
			print"</div>";			
			print "<tr>";
				print "<td>";
				print "<div class='space'></div>";
				print"</td>";
			print "</tr>";
			print"<div id = 'target_population'>";

				print"<tr><td colspan=2>";
					print"<h4><label>Select ABAIT Target Population*</label></h4>";

				print"</td></tr>";
				print"<tr><td colspan=2>";
			
			
					print"<div class='form-group '>";
						print "<select class='form-control w-auto'  name = 'Target_Population' id = 'Target_Population'>";
							if($data){
								print"<option value = '$data[Target_Population]'>$data[Target_Population]</option>";
							}else{
								print"<option value = ''>Choose*</option>";
							}

							if($_SESSION['privilege']=='globaladmin'){
								$Target_Population="";
								while($row=mysqli_fetch_assoc($session)){
									if($row[Target_Population]!=$Target_Population){
										$Target_Population=str_replace(' ','_',$row[Target_Population]);
										$Population_strip=mysqli_real_escape_string($conn,$Target_Population);
										print"<option value=$Population_strip>$row[Target_Population]</option>";
										$Target_Population=$row[Target_Population];
									}
								}
							}elseif($_SESSION['privilege']=='admin'){
								$Target_Population=str_replace(' ','_',$_SESSION['Target_Population']);
								//$Target_Population=$_SESSION['Target_Population'];
								$Population_strip=mysqli_real_escape_string($conn,$Target_Population);
								$Population=$_SESSION['Target_Population'];
								print"<option value=$Population_strip>$Population</option>";
							}

						print"</select>";
					print "</div>";
				print"</td></tr>";
			print"</div>";
		if($_SESSION['reset_password']){
			print "<tr>";
				print "<td>";
				print "<div class='space'></div>";
				print"</td>";
			print "</tr>";
			print"<div id ='clientpassword'>";
				print"<tr>";
					print"<td colspan=2>";
						if($data){
							print"<h4><label>Edit Care Provider Login ID*</label></h4>";
						}else{
							print"<h4><label>Create Care Provider Login ID*</label></h4>";
						}
					print"</td>";
				print"</tr>";
				print"<tr>";
					print"<td>";
						if($data){
							print"<input	type = 'password'
									placeholder=$data[password]
									name = 'password1'
									value = $data[password]
									oninput='check_pass();'/>";
						}else{
							print"<input type = 'password'
							        placeholder='Login ID*'
									name='password1'
									oninput='check_pass();'/>";				
						}
					print"</td>";
					print"<td>";
						print"<input type='submit' value='Info' onClick=\'alert('Login ID must be at least 4 characters long');return false\'>";
					print"</td>";
				print"</tr>";
				print"<tr>";
					print"<td>";
						if($data){
							print"<input	type = 'password'
									placeholder=$data[password]
									name = 'password2'
									value = $data[password]
									oninput='check_pass();'/>";
						}else{
							print"<input type = 'password'
									placeholder='Re-enter Login ID*'
									name='password2'
									oninput='check_pass();'/>";	

						}
						print"<div style='color:red' id='message' style='display: inline-block'></div>";
					print"</td>";
				print"</tr>";
			print"</div>";
		}
	print"</table>";
?>
	
	<p><div id="greyline"></div></p>
</div>													
		<div id = "submit">
				<input 	type = "submit"
						name = "submit"
						value = "Submit Caregiver Data"/
						>
		</div>

	</form>
<?build_footer_pg()?>
</body>
</html>
