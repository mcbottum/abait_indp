<?ob_start();
 include("autoLoader.php");
 session_start();


/// *** CONFIGURATION SETTINGS *** ///

///// FOR POST REMOTE LOGIN
if(isset($_REQUEST['abait'])){
 	$remote_login_guid=$_REQUEST['abait'];
 	$_SESSION['remote_login']=1;
 }else{
 	$remote_login_guid = Null;
 	$_SESSION['remote_login']=0;
 }
 if(isset($_REQUEST['returnurl'])){
 	$_SESSION['returnurl']=$_REQUEST['returnurl'];
 }else{
 	$_SESSION['returnurl']='ABAIT_logout_v2.php';
 }
if(isset($_REQUEST['client'])){
	$k=$_REQUEST['client'];
}else{
	$k=Null;
}

// ****** for spellings
$_SESSION['country_location'] = 'UK';
//$_SESSION['country_location'] = 'USA';

// ***** for Instance owner - Education Module uses this
$_SESSION['client'] = 'PCS';

// $_SESSION['HOME']='index.php';
$_SESSION['HOME']='ABAIT_Home.php';
$_SESSION['favicon'] = 'favicon3.ico';

$_SESSION['provider_resident'] = 'Care Recipient';

// ****** Sending care notes:
$_SESSION['send_care_note'] = True;
$_SESSION['care_note_url'] = 'https://care.personcentredsoftware.com/integration/api/GenericAPI/insertcarenote?DevApikey=8de7a68c-f962-4fb1-a98a-1d08e3263dd9&Apikey=a09a69a2-dbe0-4a47-bf9c-9d5cc92e8434';

// ******* Hosting Service ******* //
$_SESSION['hosting_service'] = 'azure_UK';
//$_SESSION['hosting_service'] = 'azure_US';
//$_SESSION['hosting_service'] = 'dreamhost_US';
//$_SESSION['hosting_service'] = 'local';
//$_SESSION['hosting_service'] = 'cog.abaitscale';

// ****** READ IN DB CONNECTIONS SETTINGS USING config.json ******* //
$string = file_get_contents("config.json");
if ($string === false) {
    $nextfile="ABAIT_adminhome_v2.php";
}
$db_configs = json_decode($string, true);
$db = $db_configs['db_connections'][$_SESSION['hosting_service']]['db'];
$_SESSION['db'] = $db;
$host = $db_configs['db_connections'][$_SESSION['hosting_service']]['host'];
$db_user = $db_configs['db_connections'][$_SESSION['hosting_service']]['db_user'];
$db_pwd = $db_configs['db_connections'][$_SESSION['hosting_service']]['db_pwd'];
$_SESSION['reset_password'] = $db_configs['db_connections'][$_SESSION['hosting_service']]['reset_password'];
$_SESSION['use_ssl'] = $db_configs['db_connections'][$_SESSION['hosting_service']]['use_ssl'];
$_SESSION['cert'] = $db_configs['db_connections'][$_SESSION['hosting_service']]['certificate'];
// NOTE: could be cognitive or behavioral
$_SESSION['population_type'] = $db_configs['db_connections'][$_SESSION['hosting_service']]['population_type'];
$_SESSION['default_target_Population'] = $db_configs['db_connections'][$_SESSION['hosting_service']]['target_population'];

// ******* HARD CODED DB CONNECTION SETTINGS
// if ($_SESSION['population_type']==='cognitive'){ // ***  for remote dementia (indp, cs, ):  ***//

// 	if ($_SESSION['hosting_service']==='local'){
// 		$db = 'abait_cog';
// 		$_SESSION['db'] = $db;
// 		$host = '';
// 		$db_user = 'root';
// 		$db_pwd = 'abaitroot!';
// 		$_SESSION['reset_password'] = True;
// 		$_SESSION['use_ssl'] = False;
// 	}elseif($_SESSION['hosting_service']==='azure_UK'){
// 		$db = 'abait-c';
// 		$_SESSION['db'] = $db;
// 		$host = 'abait-cog.mysql.database.azure.com';
// 		$db_user = 'abaitadmin@abait-cog';
// 		$db_pwd = 'Abehave8*';
// 		$_SESSION['reset_password'] = False;
// 		$_SESSION['use_ssl'] = True;
// 	}elseif($_SESSION['hosting_service']==='dreamhost_US'){
//         $db = 'agitation_cog';
//         $_SESSION['db'] = $db;
//      	$host = 'mysqlcog.abaitscale.com';
//      	$db_user = 'abaitcog';
//      	$db_pwd = 'abaitcog13!';
//      	$_SESSION['reset_password'] = False;
// 	}

// }elseif ($_SESSION['population_type']==='behavioral') { // *** for remote behavioral (cog, hs, ):   ***//

// 	if ($_SESSION['hosting_service']==='local'){
// 		$db = 'abait_cog';
// 		$_SESSION['db'] = $db;
// 		$host = '';
// 		$db_user = 'root';
// 		$db_pwd = 'abaitroot!';
// 		$_SESSION['reset_password'] = True;
// 		$_SESSION['use_ssl'] = False;
// 	}elseif($_SESSION['hosting_service']==='dreamhost_US'){
// 		$db = 'agitation_cs';
// 		$_SESSION['db'] = $db;
// 		$host = 'mysqlcs.abaitscale.com';
// 		$db_user = 'abaitcs';
// 		$db_pwd = 'abaitcs13!';
// 		$_SESSION['reset_password'] = False;
// 		$_SESSION['use_ssl'] = False;
// 	}
	
// }else{
// 	//for local
// 	$db = 'abait_cog';
// 	$_SESSION['db'] = $db;
// 	$host = 'mysql';
// 	$db_user = 'root';
// 	$db_pwd = 'abaitroot';
// 	$_SESSION['use_ssl'] = False;
// }

$_SESSION['passwordcheck']='fail';

$filename =$_REQUEST["submit"];

	if ($filename=="Submit Login ID"){
		$_SESSION['graphical_interface']=$_REQUEST['graphical_interface'];
		$password=$_REQUEST["password"];
	}elseif($remote_login_guid){
		$password=$remote_login_guid;
		$payload = json_decode(file_get_contents('php://input'), true);
	}
	else{
		$nextfile=$_SESSION['HOME'];
	}
        if(strlen($password)<5){
		$password="";
	}
	if($password!=""){
		#$con:=mysqli_connect($host,$db_user,$db_pwd);

		// if($host === 'abait-cog.mysql.database.azure.com'){
		// 	$conn = mysqli_init();
		// 	mysqli_ssl_set($conn,NULL,NULL, "/var/www/html/BaltimoreCyberTrustRoot.crt.pem", NULL, NULL);
		// 	mysqli_real_connect($conn, $host, $db_user, $db_pwd, $db, 3306, MYSQLI_CLIENT_SSL);
		// 	if (mysqli_connect_errno($conn)) {
		// 		die('Failed to connect to MySQL: '.mysqli_connect_error());
		// 	}
		// }else{

		// 	$conn=mysqli_connect($host,$db_user,$db_pwd,$db);
		// 	if (mysqli_connect_errno()) {
		// 		printf("Connect failed: %s\n", mysqli_connect_error());
		// 		exit();
		// 	}
		// }

	if($_SESSION['use_ssl']){
		$conn = mysqli_init();
		mysqli_ssl_set($conn,NULL,NULL, "/var/www/html/".$_SESSION['cert'], NULL, NULL);
		mysqli_real_connect($conn, $host, $db_user, $db_pwd, $_SESSION['db'], 3306, MYSQLI_CLIENT_SSL);
		if (mysqli_connect_errno($conn)) {
			die('Failed to connect to MySQL: '.mysqli_connect_error());
		}

	}else{
		$conn=mysqli_connect($host,$db_user,$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
	}


		$password=mysqli_real_escape_string($conn, $password);
		//$sql1="SELECT * FROM personaldata WHERE password='$password'";	
		$sql1="SELECT * FROM personaldata WHERE password LIKE '$password%' OR password2='$password'";

		#mysqli_select_db($db);
		$session1=mysqli_query($conn,$sql1);
		if($row1=mysqli_fetch_assoc($session1)){
			$_SESSION['SITE']='ABAIT Home';

				// if($_SERVER['REQUEST_METHOD'] === 'POST' && $row1['accesslevel']=='auto_update'&&$row1['password']==$password){
			if($row1['accesslevel']=='auto_update'&& strpos($row1['password'], $password)){
					echo $payload;
					autoLoad($payload);

				}

				else if($row1['accesslevel']=='globaladmin'&& (strpos($row1['password'], $password)!== false) || (strpos($row1['password2'], $password)!== false)){
					$_SESSION['adminfirst']=$row1['first'];
					$_SESSION['adminlast']=$row1['last'];
					$_SESSION['cgfirst'] = '';
					$_SESSION['cglast'] = '';
					$_SESSION['personaldatakey']=$row1['personaldatakey'];
					$nextfile="ABAIT_adminhome_v2.php";
					$_SESSION['passwordcheck']='pass';
					$_SESSION['privilege']='globaladmin';
					$_SESSION['Target_Population']='all';
					$_SESSION['house']='all';
					$_SESSION['communities']=$row1['community'];
					$_SESSION['home_page']='ABAIT_adminhome_v2.php';
				}
				elseif($row1['accesslevel']=='admin'&& (strpos($row1['password'], $password)!== false) || (strpos($row1['password2'], $password)!== false)){


                                        if($k){
                                                $sql="SELECT * FROM residentpersonaldata WHERE residentkey='$k'";
                                                $session=mysqli_query($conn,$sql);
                                                if($row=mysqli_fetch_assoc($session)){
                                                        $nextfile="ABAIT_scale_select_pcs_v2.php?k=".$k;
                                                }else{
                                                        $nextfile="ABAIT_adminhome_v2.php";
                                                }
                                        }else{
                                                $nextfile="ABAIT_adminhome_v2.php";
                                        }

					$_SESSION['adminfirst']=$row1['first'];
					$_SESSION['adminlast']=$row1['last'];
                    			$_SESSION['cgfirst'] = '';
					$_SESSION['cglast'] = '';
					$_SESSION['personaldatakey']=$row1['personaldatakey'];
					$nextfile="ABAIT_adminhome_v2.php";
					$_SESSION['home_page']='ABAIT_adminhome_v2.php';
					$_SESSION['passwordcheck']='pass';
					$_SESSION['privilege']='admin';
					$_SESSION['privilegekey']=$row1['privilegekey'];
					$_SESSION['Target_Population']=$row1['Target_Population'];
					$_SESSION['house']=$row1['house'];
					$_SESSION['communities']=$row1['community'];
					//following makes an array of scale names
					$sql3="SELECT * FROM scale_table WHERE Target_Population='$_SESSION[Target_Population]'";
					$session3=mysqli_query($sql3,$conn);
					$scale_holder='';
						while($row3=mysqli_fetch_assoc($session3)){
							if($row3[scale_name]!=$scale_holder){
								$scale_array[]=$row3['scale_name'];
							}
							$scale_holder=$row3[scale_name];
						}
					$_SESSION[scale_array]=$scale_array;						
				}					
				elseif($row1['accesslevel']=='caregiver'&& strpos($row1['password'], $password)!== false){
					if($k){
						$sql="SELECT * FROM residentpersonaldata WHERE residentkey='$k'";	
						$session=mysqli_query($conn,$sql);
						if($row=mysqli_fetch_assoc($session)){
							$nextfile="ABAIT_scale_select_pcs_v2.php?k=".$k;
						}else{
							$nextfile="ABAIT_caregiverhome_v2.php";
						}
					}else{
						$nextfile="ABAIT_caregiverhome_v2.php";
					}

					$_SESSION['home_page']='ABAIT_caregiverhome_v2.php';
					$_SESSION['passwordcheck']='pass';
					$_SESSION['personaldatakey']=$row1['personaldatakey'];
					$_SESSION['password']=$password;
					$_SESSION['cgfirst']=$row1['first'];
					$_SESSION['cglast']=$row1['last'];
					$_SESSION['privilege']='caregiver';
					$_SESSION['privilegekey']=$row1['privilegekey'];
					$_SESSION['Target_Population']=$row1['Target_Population'];
					$_SESSION['house']=$row1['house'];
					$_SESSION['communities']=$row1['community'];
					//following makes an array of scale names
					$sql3="SELECT * FROM scale_table WHERE Target_Population='$_SESSION[Target_Population]'";
					$session3=mysqli_query($sql3,$conn);
					$scale_holder='';
						while($row3=mysqli_fetch_assoc($session3)){
							if($row3['scale_name']!=$scale_holder){
								$scale_array[]=$row3['scale_name'];
							}
							$scale_holder=$row3['scale_name'];
						}
					$_SESSION['scale_array']=$scale_array;
				}else{$nextfile=$_SESSION['HOME'];
			}
		}else{
			$nextfile=$_SESSION['HOME'];
		}
	} else{   
                $nextfile=$_SESSION['HOME'];
        }
	if($_SESSION['passwordcheck']=='pass'){
			$_SESSION['hostname']=$host;
			$_SESSION['user']=$db_user;
			$_SESSION['mysqlpassword']=$db_pwd;
			$_SESSION['database']=$db;
	}
	header("Location:$nextfile");
	?>

