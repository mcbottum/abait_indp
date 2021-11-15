<?

$string = file_get_contents("config.json");
if ($string === false) {
    $nextfile="ABAIT_adminhome_v2.php";
}
$db_configs = json_decode($string, true);
$db = $db_configs['db_connections'][$_SESSION['hosting_service']]['db'];

$host = $db_configs['db_connections'][$_SESSION['hosting_service']]['host'];
$db_user = $db_configs['db_connections'][$_SESSION['hosting_service']]['db_user'];
$db_pwd = $db_configs['db_connections'][$_SESSION['hosting_service']]['db_pwd'];


print_r($db_configs['db_connections']['local']);


echo $db;
echo $host;
echo $db_user;
echo $db_pwd;
echo "hi";
?>