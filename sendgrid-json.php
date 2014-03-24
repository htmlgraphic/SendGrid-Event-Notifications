<?php

	require_once("db.class.php");
	require_once("Sendgrid.class.php");


# COMMENTED CODE WAS USED TO WRITE TO A FILE, BEFORE DATABASE WAS SETUP


##$fh = fopen('events.log', 'a+') or die("Error opening output file");

#if ( $fh ) {

	// Check if the post is coming from Checkfront servers
	if (strstr($_SERVER['HTTP_USER_AGENT'], 'SendGrid')) {

		$db = new MysqliDb('localhost', 'username', 'P@$sw0rD', 'domain_com');

		$notifications = new Sendgrid();
		$notifications->ParseNotificationData();

		foreach ( $notifications->dataArray as $data ) {
			$db->insert('sendgrid_events', $data);
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