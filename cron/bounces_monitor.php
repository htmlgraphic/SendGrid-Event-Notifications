#!/usr/bin/php
<?php
// NOTIFICATION PROCESS
// This script is designed to check for bad emails and alert the admin
// Which server will this process one on? The live OR development server
$path = dirname(dirname(__FILE__));
require_once $path . '/includes/functions.inc.php';

// Needed for SendGrid
require_once $path . '/includes/swift_required.php';
require_once $path . '/includes/sendgrid/SmtpApiHeader.php';



	$api_user = 'sendgrid_account';
	$api_key = 'password';



# -------------------------------------	


	// grab XML feeds from SendGrid
	$blocks = sendgrid_data('https://sendgrid.com/api/blocks.get.xml?api_user='. $api_user .'&api_key='. $api_key);
	$invalids = sendgrid_data('https://sendgrid.com/api/invalidemails.get.xml?api_user='. $api_user .'&api_key='. $api_key);
	$bounces = sendgrid_data('https://sendgrid.com/api/bounces.get.xml?api_user='. $api_user .'&api_key='. $api_key);
	$spam = sendgrid_data('https://sendgrid.com/api/spamreports.get.xml?api_user='. $api_user .'&api_key='. $api_key);
	$unsubscribe = sendgrid_data('https://sendgrid.com/api/unsubscribes.get.xml?api_user='. $api_user .'&api_key='. $api_key);


// Check if there are any flagged email addresses. if so, send 'er out.
if (!empty($blocks->block) || !empty($invalids->invalidemail) || !empty($bounces->bounce) || !empty($spam->spamreport) || !empty($unsubscribe->unsubscribe)) {

	$hdr = new SmtpApiHeader();

	$subject 		= 'Bounced Email Alert!';
	$to      		= array('email@domain.com' => 'Jason Doe');
	$from 			= array('alert@domain.com' => 'Hosting');
	
	$html = "<p>The following email addresses have been flagged as problematic:</p>";

# -------------------------------------	

	$html .= '<h2>Blocked Delivery</h2>';
	if (!empty($blocks->block)) {
		$html .= "<ul>";
		$loop_count = count($blocks);
		for ($i = 0; $i < $loop_count; $i++) {
			$html .= '<li><a href="mailto:'.$blocks->block[$i]->email.'">' .$blocks->block[$i]->email. '</a> - ' .$blocks->block[$i]->reason. '</li>';
		}
		$html .= "</ul>";

	} else {
		$html .= 'No blocked emails to report.<br />';
	}

# -------------------------------------	

	$html .= '<br /><h2>Invalid Email</h2>';
	if (!empty($invalids->invalidemail)) {
		$html .= "<ul>";
		$loop_count = count($invalids);
		for ($i = 0; $i < $loop_count; $i++) {
			$html .= '<li><a href="mailto:'.$invalids->invalidemail[$i]->email.'">' .$invalids->invalidemail[$i]->email. '</a> - ' .$invalids->invalidemail[$i]->reason. '</li>';
		}
		$html .= "</ul>";
	} else {
		$html .= 'No invalid emails to report.<br />';
	}

# -------------------------------------	

	$html .= '<br /><h2>Bounces</h2>';
	if (!empty($bounces->bounce)) {
		$html .= "<ul>";
		$loop_count = count($bounces);
		for ($i = 0; $i < $loop_count; $i++) {
			$html .= '<li><a href="mailto:'.$bounces->bounce[$i]->email.'">' .$bounces->bounce[$i]->email. '</a> - ' .$bounces->bounce[$i]->reason. '</li>';
		}
		$html .= "</ul>";
	} else {
		$html .= 'No bounced emails to report.<br />';
	}

# -------------------------------------	

	$html .= '<br /><h2>Spam Reports</h2>';
	if (!empty($spam->spamreport)) {
		$html .= "<ul>";
		$loop_count = count($spam);
		for ($i = 0; $i < $loop_count; $i++) {
			$html .= '<li><a href="mailto:'.$spam->spamreport[$i]->email.'">' .$spam->spamreport[$i]->email. '</a> - ' .$spam->spamreport[$i]->ip. '</li>';
		}
		$html .= "</ul>";
	} else {
		$html .= 'No spam emails to report.<br />';
	}

# -------------------------------------	

	$html .= '<br /><h2>Unsubscribes</h2>';
	if (!empty($unsubscribe->unsubscribe)) {
		$html .= "<ul>";
		$loop_count = count($unsubscribe);
		for ($i = 0; $i < $loop_count; $i++) {
			$html .= '<li><a href="mailto:'.$unsubscribe->unsubscribe[$i]->email.'">' .$unsubscribe->unsubscribe[$i]->email. '</a></li>';
		}
		$html .= "</ul>";
	} else {
		$html .= 'No unsubscribed email to report.<br />';
	}


	$html .= "<p>Please refer to the <a href='http://www.domain.com/sendgrid/clear_bounces.php'>bounced email log</a> for more details.</p>";

# -------------------------------------	
	
	$text .= "\r\n" . "Please refer to the bounced, invalid and blocked email log. (http://www.domain.com/sendgrid/clear_bounces.php)";
	

	$hdr->setCategory("Alert");
	mailTo_SendGrid($subject, $from, $to, $html, $text, $hdr);

} else {
	echo('All good. Nothing to report.');
}
?>