Example:

$mail = new Mail()
$mail->to('example@example.com');
$mail->from('Somebody <somebody@example.com>');
$mail->subject('Any subject');

$mail->send();
==========
