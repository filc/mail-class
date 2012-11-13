<?php

class Mail
{
	private $to;
	private $from;
	private $body;
	private $subject;
	private $replyTo;
	private $cc = array();
	private $bcc = array();
	private $attachments = array();
	private $contentType = 'text/plain';
	private $encoding = 'utf-8';

	private $composedMail = '';

	public function __construct()
	{
			
	}

	private function composeMail()
	{
		$boundary = md5(time());

		$msg = "";

		$msg .= "To: " . $this->to . "\n";
		$msg .= "From: " . $this->from . "\n";
		$msg .= "Subject: " . $this->subject . "\n";
		
		if(!empty($this->replyTo))
			$msg .= "Reply-To: " . $replyTo . "\n";

		$msg .= "MIME-Version: 1.0\n";
		$msg .= "Content-Type: multipart/mixed;\n\tboundary=\"" . $boundary . "\"\n\n";

		$msg .= "This is a multipart message in MIME format.\n\n";

		$msg .= "--$boundary\n";
		$msg .= "Content-Type: " . $this->contentType . ";\n\tcharset=\"" . $this->encoding . "\"\n";
		$msg .= "Content-Transfer-Encoding: quoted-printable\n\n";

		$msg .= $this->body . "\n\n";
		$msg .= "--$boundary--";

		if(count($this->attachments))
		{
			foreach($this->attachments as $a)
			{
				$filename = basename($a['file']);
				$encodedFile = base64_encode(file_get_contents($a['file']));

				$msg .= "Content-Type: " . $a['type'] . ";\n\tname=\"" . $filename . "\"\n";
				$msg .= "Content-Transfer-Encoding: base64\n";
				$msg .= "Content-Disposition: attachment;\n\tfilename=\"" . $filename . "\"\n\n";

				$msg .= $encodedFile . "\n";

				$msg .= "--" . $boundary . "--";
			}
		}

		$this->composedMail = $msg;
	}

	public function send()
	{
		if(empty($this->composedMail))
			$this->composeMail();

		return mail($this->to, $this->subject, '', $this->composedMail);	
	}

	public function getMail()
	{	
		if(empty($this->composedMail))
			$this->composeMail();

		return $this->composedMail;
	}

	public function to($address)
	{
		$this->to = $address;
	}

	public function from($from)
	{
		$this->from = $from;
	}

	public function body($msg)
	{
		$this->body = $msg;
	}

	public function subject($subject)
	{
		$this->subject = $subject;
	}

	public function replyTo($addr)
	{
		$this->replyTo = $addr;
	}

	public function addCC($cc)
	{
		$this->cc[] = $cc;
	}

	public function attach($file,$type = 'application/octet-stream')
	{
		$this->attachments[] = array(
			"file" => $file,
			"type" => $type
		);	
	}

	public function contentType($contentType)
	{
		$this->contentType = $contentType;
	}

	public function encoding($enc)
	{
		$this->encoding = $enc;
	}
}

?>
