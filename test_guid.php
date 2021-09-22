<?
function guidv4()
{
    if (function_exists('com_create_guid') === true)
        return trim(com_create_guid(), '{}');

    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function check_for_vowel($check){
	//Checks if first character of a string is a vowel
	$vowels = array(a,e,i,o,u);
	if(in_array($check[0],$vowels)){
		return True;
	}else{
		return False;
	}
}

echo date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
echo date('Y-m-d H:i:s');
echo guidv4();

$nov = "this is a test";
$v = "at the movies";
if(!check_for_vowel($nov)){
	echo " DID NOT FIND VOWEL";
}
echo "    ";
if(check_for_vowel($v)){
	echo "found vowel";
}


?>
