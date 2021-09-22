
<?
echo 'My username is ' .$_ENV["USER"] . '!';
echo $_ENV["DATABASE_SERVER"];
echo $_SERVER["SITE_HTMLROOT"];
// echo phpinfo();

//The original plaintext password.
$password = 'test123';

//Hash it with BCRYPT.
$passwordHashed = password_hash($password, PASSWORD_BCRYPT);

//Print it out.
echo $passwordHashed;
?>
