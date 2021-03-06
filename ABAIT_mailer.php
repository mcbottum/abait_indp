<?

# to run cron job:
# env EDITOR=vi crontab -e
# */30 * * * * /usr/bin/php /Library/WebServer/Documents/localhost/ABAIT_mailer.php >/tmp/stdout.log 2>/tmp/stderr.log
# https://ole.michelsen.dk/blog/schedule-jobs-with-crontab-on-mac-osx/


$path = 'PHPMailer';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

$db = 'agitation_indp';
$db_pwd = 'abait123!';
$host = 'localhost';
$db_user = 'abait';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $path.'/src/Exception.php';
require $path.'/src/PHPMailer.php';
require $path.'/src/SMTP.php';

function sendMail($sender='admin@abehave.com', $recipient='test', $body='test body'){



	$mail = new PHPMailer();
	$mail->isSMTP();
	$mail->SMTPAuth = true;

	$mail->SMTPSecure = 'tls';
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587;
	$mail->Username = "michael@abehave.com";
	$mail->Password = "annakai3";
	$mail->setFrom('michael@abehave.com', 'ABAIT');

	//indp-abait@my-project-288320.iam.gserviceaccount.com

	// for testing

	// $mail->Host = 'smtp.mailtrap.io';
	// $mail->Username = '29b5f846f8c831'; //paste one generated by Mailtrap
	// $mail->Password = '3c8564078f7257'; //paste one generated by Mailtrap
	// $mail->SMTPSecure = 'tls';
	// $mail->Port = 2525;

	// $mail->setFrom('info@mailtrap.io', 'Mailtrap');
	$mail->addReplyTo('michael@abehave.com', 'ABAIT');
	$mail->addAddress('michael@abehave.com', 'mike1'); 
	$mail->addCC('suzanne@abehave.com', 'Suzanne');
	// $mail->addBCC('bcc1@example.com', 'mike3');


	$mail->Subject = 'Emergency Intervention Required';

	$mail->isHTML(true);

	$mailContent = $body;
	$mail->Body = $mailContent;

	if($mail->send()){
	    // echo '<p>Message has been sent</p>';
	    $message_sent=True;
	}else{
	    // echo 'Message could not be sent.';
	    // echo 'Mailer Error: ' . $mail->ErrorInfo;
	    $message_sent=False;
	}
	return $message_sent;
}

$conn=mysqli_connect($host,$db_user,$db_pwd, $db) or die(mysqli_error());
$sql_check = "SELECT * from notification_queue WHERE date_sent IS null";
$session_check = mysqli_query($conn, $sql_check);
$notify_data=$session_check->fetch_all(MYSQLI_ASSOC);

// Set default time zone
date_default_timezone_set('London');

// Then call the date functions
$date = date('Y-m-d H:i:s');

if($notify_data){

	foreach ($notify_data as $value) {

		$message_sent = False;
		$sql_recipients = "SELECT mail FROM personaldata WHERE personaldatakey IN($value[recipients])";
		$session_recipients = mysqli_query($conn, $sql_recipients);
		$recipient_data=$session_recipients->fetch_all(MYSQLI_ASSOC);
		foreach ($recipient_data as $value2['mail']) {
			$message_sent = sendMail($sender,$value2['mail'],$value['message_body']);
		}
		if($message_sent){
			mysqli_query($conn,"UPDATE notification_queue SET date_sent='$date' WHERE id='$value[id]'");
		}
		
	}
}

?>