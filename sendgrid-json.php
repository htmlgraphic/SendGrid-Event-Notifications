<?php

	require_once("db.class.php");
	require_once("Sendgrid.class.php");


# COMMENTED CODE WAS USED TO WRITE TO A FILE, BEFORE DATABASE WAS SETUP


##$fh = fopen('events.log', 'a+') or die("Error opening output file");

#if ( $fh ) {

	// Check if the post is coming from Checkfront servers
	if (strstr($_SERVER['HTTP_USER_AGENT'], 'SendGrid')) {

		$notifications = new Sendgrid();
		$notifications->ParseNotificationData();
	
		if ($notifications->skip !== true) {
		// skip the logging of the data if the email address returned is not correctly formatted
		$db = new DB();
		$db->write('sendgrid_events', $notifications->dataArray);
		}
		
		echo "Ok";

#		fwrite($fh, serialize($notifications->dataArray));
#		fclose($fh);

	} else {
		error_log("SendGrid Event Notification Process - Invalid Host");
		die;
	}

#}

?>