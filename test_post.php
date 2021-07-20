<?

$data_array = array(
    'RecordUUID' => 'c35faaaa-740f-41c6-90e0-084f7c253f9c',
    'PersonID' => '5f52a314-02b8-4c38-8661-b8601e180129',
    'FirstNames' => 'Louisa',
    'LastName' => 'Paphipis',
    'ExternalPersonID' => '',
    'DateOfBirth' => '1918-08-13',
    'NHSNumber' => '',
    'UTCDateTime' => '2021-07-1 11:30:00',
    'TimeZone' => 'Europe/London',
    'ActionIconID' => '2007',
    'ShortText' => '',
    'ActionText' => 'Test-ABAIT',
    'IsHandover' => 'false',
    'ExternalNoteID' => '',
    'Measure1' => '',
    'Measure2' => '',
    'Sliders' => array('' => ''),
);

//$data=json_encode($data_array,JSON_FORCE_OBJECT);
$data=json_encode($data_array);
echo $data;

$url = 'https://care.personcentredsoftware.com/integration/api/GenericAPI/insertcarenote?DevApikey=8de7a68c-f962-4fb1-a98a-1d08e3263dd9&Apikey=a09a69a2-dbe0-4a47-bf9c-9d5cc92e8434';
$ch = curl_init($url);
//$postString = http_build_query($data, '', '&');

curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));


# Form data string
//$postString = http_build_query($data, '', '&');

//curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);

curl_setopt($ch, CURLOPT_FAILONERROR, true); // Required for HTTP error codes to be reported via our call to curl_error($ch)
//...
curl_exec($ch);
if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
}


if (isset($error_msg)) {
    echo "there were errors";
    print_r($error_msg);
    // TODO - Handle cURL error accordingly
}




# Get the response
//$response = curl_exec($ch);
//print_r($response);
curl_close($ch);

?>
