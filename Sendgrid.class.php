<?php

class Sendgrid 
{

	public $dataArray;
	public $skip = false;

	function __construct(){  

		// Parse data sent via post, useful example: http://hookdebug.sendgrid.com
		$this->raw_data = (file_get_contents('php://input'));
		$this->data = json_decode($this->raw_data);

        // Setup timezone timestamp, if needed
        $this->site = new StdClass;
        $this->site->date = new DateTime();
        $this->site->date->setTimezone(new DateTimeZone('EST'));
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

		foreach ( $this->data as $d )
		{

				$this->email 		= strtolower($d->email); //required to save data
				$this->timestamp  	= $this->UnixTimestamp( $d->timestamp );
				$this->category  	= json_encode($d->category);
				// Future addon: Custom Postfix X-Envelope-From email variable 
				//if(isset($d['sender)) { $this->sender  	= strtolower($d['sender); }
				$this->attempt  	= intval($d->attempt); // number of attempts tried by SendGrid
				$this->event  		= strtolower($d->event);
				$this->type  		= $d->type;
				$this->response 	= $d->response;
				$this->reason  		= $d->reason;
				$this->url  		= $d->url;
				$this->smtp_id 		= $d->smtp-id;
				$this->ip  			= sprintf('%u', ip2long($d->ip));
				$this->user_agent 	= $d->useragent;

				// These emails don't need to be logged.
				$ignore_email = array('john.doe@gmail.com','user2@example.com','user3@example.com');
				if (in_array($this->email, $ignore_email)) {
				    $this->skip = true;
				} else {
					$this->skip = false;
				}
				
				if (!$this->skip) { 

					// Build array to loop
					$this->dataArray[] = array('email' 		=> $this->email,
											'timestamp' 	=> $this->timestamp,
											'category' 		=> $this->category, 
											'attempt' 		=> $this->attempt,
											'event' 		=> $this->event, 
											'type' 			=> $this->type, 
											'response' 		=> $this->response,
											'reason' 		=> $this->reason, 
											'url' 			=> $this->url,
											'smtp_id' 		=> $this->smtp_id,
											'ip' 			=> $this->ip,
											'user_agent' 	=> $this->user_agent,
											'raw_data'		=> serialize(json_encode($d)),
											);
			    } // End skip
		}
	}
}
