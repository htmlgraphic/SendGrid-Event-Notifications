<?php

define('EMAIL_FORMAT', '/^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i');

class Sendgrid 
{

	public $email;
	public $timestamp;
	public $category;
	public $sender;
	public $attempt;
	public $event;
	public $type;
	public $response;
	public $reason;
	public $url;

	public $dataArray;
	public $skip = false;

	function __construct(){  
		if(isset($_POST['email'])) { $this->email  			= strtolower($_POST['email']); } //required to save data
		if(isset($_POST['timestamp'])) { $this->timestamp  	= $this->UnixTimestamp($_POST['timestamp']); }
		if(isset($_POST['category'])) { $this->category  	= $_POST['category']; }
		//if(isset($_POST['sender'])) { $this->sender  		= strtolower($_POST['sender']); } // from X-Envelope-From email variable 
		if(isset($_POST['attempt'])) { $this->attempt  		= intval($_POST['attempt']); }// number of attempts tried by SendGrid
		if(isset($_POST['event'])) { $this->event  			= strtolower($_POST['event']); }
		if(isset($_POST['type'])) { $this->type  			= $_POST['type']; }
		if(isset($_POST['response'])) { $this->response  	= $_POST['response']; }
		if(isset($_POST['reason'])) { $this->reason  		= $_POST['reason']; }
		if(isset($_POST['url'])) { $this->url  				= $_POST['url']; }
    }


	/**
	 *
	 * @convert UNIX TIMESTAMP to MySQL TIMESTAMP
	 *
	 * @param int $timestamp
	 *
	 * @return string
	 *
	 */
	public function UnixTimestamp($timestamp) {
	    return date('Y-m-d H:i:s', $timestamp);
	}


	public function ParseNotificationData() {
/*

Logging the notifications sent from SendGrid, improving automatted responses.

*/

	// These emails don't need to be logged.
	switch ($this->email) {
	    case 'user@example.com':
	        $this->skip = true;
	        break;
	}


	
	if (!$this->skip) { 

		if (isset($this->email)) {
			if (preg_match(EMAIL_FORMAT, $this->email)) {
			$this->skip = false;
			}	
		}

		$this->dataArray = array('email' 		=> $this->email, 
								'attempt' 		=> $this->attempt, 
								'timestamp' 	=> $this->timestamp,
								'category' 		=> $this->category, 
								'event' 		=> $this->event, 
								'type' 			=> $this->type, 
								'response' 		=> $this->response,
								'reason' 		=> $this->reason, 
								'url' 			=> $this->url
								);
    } // End skip

	}

}

?>
