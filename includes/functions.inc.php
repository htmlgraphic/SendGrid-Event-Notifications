<?php

// determine whether we're working on a local server or on the real server:
if (get_cfg_var('IS_LIVE') == 1) {
  define('IS_LIVE', true);
} else {
  define('IS_LIVE', false);
}


$Conf = new stdClass();
$Conf->site = new stdClass();
$Conf->site->date = new DateTime(null, new DateTimeZone('America/Chicago'));



// Needed for SendGrid
require(dirname(__FILE__) . '/swift_required.php');
require(dirname(__FILE__) . '/sendgrid/SmtpApiHeader.php');



function sendgrid_data($f) {
  if ($f) { // Check if any data exists in XML feed.
      return simplexml_load_file($f);
  } else {
      return false;
  }
}


function mailTo_SendGrid($subject, $from, $to, $html, $text, $hdr, $attachment=false) {
  global $hdr;

    if (!IS_LIVE) {
      // send direct to test email system
      $transport = Swift_SmtpTransport::newInstance('email-test.domain.com', 2525);
      $transport->setUsername('testing');
      $transport->setPassword('tdesigns1');
    } else {
      // send through SendGrid
      $transport = Swift_SmtpTransport::newInstance('smtp.sendgrid.net', 465, 'ssl');
      $transport->setUsername('sendgrid_account');
      $transport->setPassword('password');
    }
  $swift = Swift_Mailer::newInstance($transport);
  
  // Create a message (subject)
  $message = new Swift_Message($subject);

  if ($attachment) {
    foreach ($attachment as $file) {
        if (file_exists($file)) {
            $message->attach(Swift_Attachment::fromPath($file));
        }
    }
  }
  
  // add SMTPAPI header to the message
  $headers = $message->getHeaders();
  $headers->addTextHeader('X-SMTPAPI', $hdr->asJSON());

  if (!IS_LIVE) {
  // Add the testing email for proper email testing
  $message->setBcc(array('testing@domain.com' => 'Testing'));
  }
  
  // attach the body of the email
  $message->setFrom($from)
  ->setBody($html, 'text/html')
  ->setTo($to)
  ->addPart($text, 'text/plain');
 
  if ($recipients = $swift->send($message, $failures)) {
    // This will let us know how many users received this message
    // If we specify the names in the X-SMTPAPI header, then this will always be 1.
    //echo 'Message blasted '.$recipients.' users'; // This will break the jSON signup process if turned on.
  } else {
    // something went wrong =(
    print_r($failures);
  }
}

function mailTo_GMAIL($subject, $from='email@address.com', $to, $html, $text, $hdr) {
  global $hdr;

  // Create new swift connection and authenticate
  $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl');
  $transport->setUsername('email@address.com');
  $transport->setPassword('somethingpassword');
  $swift = Swift_Mailer::newInstance($transport);

  // Create a message (subject)
  $message = new Swift_Message($subject);

  // add SMTPAPI header to the message
  $headers = $message->getHeaders();
  $headers->addTextHeader('X-SMTPAPI', $hdr->asJSON());

  // attach the body of the email
  $message->setFrom($from);
  $message->setBody($html, 'text/html');
  $message->setTo($to);
  $message->addPart($text, 'text/plain');


  // send out the emails
  //return $swift->send($message, $failures);

  if ($recipients = $swift->send($message, $failures)) {
    // This will let us know how many users received this message
    // If we specify the names in the X-SMTPAPI header, then this will always be 1.
    //echo 'Message blasted '.$recipients.' users'; // This will break the jSON signup process if turned on.
  } else {
    // something went wrong =(
    print_r($failures);
  }
}

?>