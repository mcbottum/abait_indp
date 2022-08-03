<?
session_start();
?>
<!doctype html>
<html lang="en">
<head>
<link rel="icon" href="favicon3.ico" type="image/x-icon">
<meta http-equiv="Content-Type" content="text/html;
	charset=utf-8" />
<title>ABAIT Home</title>
<script type='text/javascript'>

function formValidator(){
	// Make quick references to our fields
	var firstname = document.getElementById('firstname');
	var addr = document.getElementById('addr');
	var zip = document.getElementById('zip');
	var state = document.getElementById('state');
	var username = document.getElementById('username');
	var mail = document.getElementById('mail');
	var password = document.getElementById('password');
	
	// Check each input in the order that it appears in the form!
	
		//if(notEmpty(mail, "Please enter highlighted information")){
			//if(emailValidator(mail, "Please check to make sure your e-mail address is valid")){
				if(notEmpty(password, "Please enter highlighted information")){
				
				return true;		
				}
		//}
	//}
	return false;
}

function notEmpty(elem, helperMsg){
	if(elem.value.length == 0){
		elem.style.background = 'Yellow';
		alert(helperMsg);
		elem.focus(); // set the focus to this input
		return false;
	}
	elem.style.background = 'white';
	return true;
}

function emailValidator(elem, helperMsg){
	var emailExp = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	if(elem.value.match(emailExp)){
		elem.style.background = 'white';
		return true;
	}else{
		elem.style.background = 'Yellow';
		alert(helperMsg);
		elem.focus();
		return false;
	}
}

window.onload = function() {
  document.getElementById("password").focus();
};

</script>

<style>

#body{
	/*background:url(adminbg.png) repeat-x top;*/
	background:url(greybg.png) repeat-x top;
	/*background:#f3f3f3;*/
	border-bottom:3px solid #C3C3C3;
	border-right:3px solid #C3C3C3;
	border-top:3px solid grey;
	border-left:3px solid grey;
	font:13px/1.33 Verdana, sans-serif;
	color:white;
  	max-width: 90%;
	margin-left:auto;
  	margin-right: auto;
	height:44em;
	-moz-border-radius:2ex;
  	-webkit-border-radius:2ex;
  		box-shadow: inset 1px 1px 15px #000000;
	}

table.center {
	margin-left:auto; margin-right:auto;
	}

#welcome {
	/*{background:url(greenbgnd.png) repeat-x top;*/
	background-image: linear-gradient(lime, forestgreen);
	max-width: 80%;

	margin-left: auto ;
 	margin-right: auto ;
	margin-top:60px;
	-moz-border-radius:2ex;
  	-webkit-border-radius:1.5ex;
	/*height:5.5em;*/
	border:2px solid black;
	/*height: auto;*/

	}


#login {
	background:url(Images/login4.png) repeat-x top;
	/*background:#f8c6a5;*/
	margin-top:100px;
	-moz-border-radius:2ex;
  	-webkit-border-radius:1.5ex;
  	border:2px solid grey;
	
	}

#submit {
	margin-top:5px;
}

.shadow {
  -moz-box-shadow: 5px 5px 5px grey;
  -webkit-box-shadow: 5px 5px 5px grey;
  box-shadow: 5px 5px 5px grey;
  /*box-shadow: inset 0 0 5px #000000;*/
}

#head{

	/*margin-top: -60px;*/
	text-align: center;
	vertical-align: bottom;
	color:white;
	font-style:italic;
	font-variant:small-caps;
	}

#logo3{	margin-top:20px;
	}
	
#homename{
	margin-left: 10px;

	font:20px/1.33 Verdana, sans-serif;
	color:black;
	}
#homename_no_graphic{
	margin-left: 10px;
	margin-top:-60px;
	font:20px/1.33 Verdana, sans-serif;
	color:black;
	}

#homename input{
  	height:2em;
  	margin-left: 10px;
	font:18px/1.33 Verdana, sans-serif;
	font-weight:bold;
	color:#A65100;
	}

#homename input#graphical_interface{
	height:1em;
}
#aboutabait{
	margin-top:10px;
	margin-left:auto;
	margin-right:;
	cursor:pointer;
}
	
#footer { 
	clear:both;
	height: 80px; 
	text-align: center; 
	font-size:10px; 
	color:#999999; 
	font-family:Verdana; 
	padding-top: 0px; 
	width: 100%; 
	left:0px; bottom:0px; 
	font-weight:normal;
	font-style:normal;
	margin-bottom:-20px;
	}
	#footer a{
		padding-top: 10px;
	}
	#footer a:link{
		color:#999999;
		margin-top: -5px;
	}
		#footer a:hover{
		color:#4F5A64;
	}
		#footer a:visited{
		color:#999999;
	}

</style>
</head>
<body>
<div id="body" class="shadow">
<fieldset id='welcome' class="shadow">
	<div id = "head">
		<h1>Adaptive Behavior Assessment and Intervention Tool</h1> 
	</div>
</fieldset>
		<form 	onsubmit='return formValidator()'
				action = "ABAIT_passcheck_v2.php" 
				method = "get">
	<table class="center">
		<tr>
			<td>
				<div id = "homename">
				<fieldset id='login' class="shadow">


					<table>

<?
// $_SESSION['remote_login'] = False;
                                            if($_SESSION['remote_login']){
                                                print"<tr><td align='center'>";
						     print "You have not yet been logged in to the ABAIT system.<br>";
						     print "Please speak with your administrator.";
                                                print"</tr></td>";
						print"<tr><td align='center' margin-bottum='5px'>";
                                             		print "<input type=button onClick=\"location.href='$_SESSION[returnurl]'\" value='Leave ABAIT'>";
						print"</tr></td>";
                                        }else{
 //echo $_SERVER['REQUEST_URI'];

?>


							<tr><th span=2>Login ID</th></tr>
							<tr><td align="center" margin-bottum="5px">
								<input	type = "password" id = 'password' name = "password" autocomplete="off"/>

							</td></tr>
							<tr><br></tr>
							<tr><td align="center">
								<input 	id = 'submit' type = "submit"
									name = "submit"
									value = "Submit Login ID">
							</td></tr>

<?
					}
?>

					</table>
				</fieldset>
				</div>
				</td></tr>



<tr><td align='center'>



</td></tr>


				<tr><td align='center'>
				
					<FORM>
					<div id="aboutabait">
						<INPUT type="button" value="About ABAIT" onClick="window.open('ABAIT_info.html','mywindow','width=978,height=1000,scrollbars=yes')">
						</div>
					</FORM>
					
				</td></tr>
			</td></tr>
		</table>

<div id="footer"><p><a href='https://centerfordementiabehaviors.com/'>Center for Dementia Behaviors</a><br><a href='ABAIT_Privacy_Policy.html' target="_blank">Privacy Policy</a></br></p></div>
</div>
</body>
</html>
