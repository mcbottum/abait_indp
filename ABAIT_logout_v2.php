<?ob_start()?>
<?session_start();
if($_SESSION['remote_login']){
	$nextfile=$_SESSION['returnurl'];
}else{
	$nextfile=$_SESSION['HOME'];
}
header("Location:$nextfile");
session_unset();
session_destroy();
$_SESSION = array();

?>