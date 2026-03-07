<?php

require_once('batch/BatchProcess.class.php');

class batch_emailTrapper_EmailTrapper extends batch_BatchProcess
{
	private $dbEmailAddress;
	private $htmlmsg;
	private $plainmsg;
	private $charset;
	private $attachments;
	
	/**
	 * 
	 * Initialisation function - fired from parent __construct()
	 * @throws Exception
	 */
	protected function init()
	{
		try {
			$this->dbEmailAddress = 'db@illumen.co.uk';
			$mbox = imap_open("{secure.emailsrvr.com}", "db@illumen.co.uk", "VwbB92mr");
			
			echo "<h1>Mailboxes</h1>\n";
			$folders = imap_listmailbox($mbox, "{secure.emailsrvr.com}", "*");
			
			if ($folders == false) {
				echo "Call failed<br />\n";
			} else {
				foreach ($folders as $val) {
					echo $val . "<br />\n";
				}
			}
			
			echo "<h1>Headers in INBOX</h1>\n";
			$headers = imap_headers($mbox);
			
			if ($headers == false) {
				echo "Call failed<br />\n";
			} else {
				foreach ($headers as $val) {
					echo $val . "<br />\n";
				}
			}
			
			
			$message_count = imap_num_msg($mbox);
			echo $message_count;
			
			for ($i = 1; $i <= $message_count; ++$i) {
				
				$this->getmsg($mbox,$i);
				echo '<br />===============<br /><br />';
				
				$header = imap_header($mbox, $i);
	
				// Check if need to use the info in the current email header or the information in the first 'header block' in the body if email has been sent to db@
				$dbEmailAddressFound = false;
				if (array_key_exists('to', $header) && ($header->to != '' || !is_null($header->to))) {
					// check to see if the to addresses contains the db@ email address.
					// If so then we will look for the first 'header block' inside the bod text and try and use that
					foreach ($header->to as $t) {
						$toAddress = $t->mailbox .'@' . $t->host;
						if ($toAddress == $this->dbEmailAddress) {
							$dbEmailAddressFound = true;
							continue;
						}
					}
	
					// Get the headers for the email
					$standardHeader = $this->getStandardHeaders($header);
					
					// if the email was sent to the database (ie to = dbemail address) then also get the forwarding header - ie the first
					// block of from/to/cc/date/subject found in the body of the email
					$forwardingHeader = array();
					if ($dbEmailAddressFound) {
						// use  first 'header block' in body text
						echo 'Found Forwarded Email';
						$forwardingHeader = $this->getForwardingHeaders();
					}					

					if (array_key_exists('errors',$forwardingHeader)) {
						echo '<p>==== ERROR FOUND: ====<br />' . $forwardingHeader['errors'] . '<br />===========</p>';
						$this->sendErrorEmail($standardHeader, $forwardingHeader['errors']);
						$this->moveEmail($mbox, $i, 'INBOX.errors');
						continue;
					}
					$this->processHeaders($standardHeader, $forwardingHeader);
					
					
					
				} else {
					throw new Exception('Error: \'To\' address not found in email header or is not populated');
				}
				
				$this->moveEmail($mbox, $i, 'INBOX.processed');
	 			
			}
			
			imap_close($mbox, CL_EXPUNGE);
		} catch (Exception $e){
			echo 'Exception: ' . $e;
		}
	}
	
	private function moveEmail($mbox, $i, $destination) {
		imap_mail_move($mbox, $i, $destination);
	}
	
	private function processHeaders($standardHeader, $forwardingHeader) {
		
		$post_initiative_ids = $this->getPostInitiativeIds($standardHeader, $forwardingHeader);
		
		$addresses_missing_post_initiative_ids = $post_initiative_ids[1];
		$post_initiative_ids = $post_initiative_ids[0];
		
		if (count($post_initiative_ids) > 0) {
			
			$campaignNbm = $this->findByCampaignNbmEmail($standardHeader['from'][0]);
			// 		$campaignNbm = app_domain_CampaignNbm::findByCampaignNbmEmail($standardHeader['from'][0]);
			
			// 		if (is_null($campaignNbm)) {
			if (!$campaignNbm) {
				throw new Exception('Error: Could not find Campaign NBM email address which matches \'from\' address in header');
			} else {
				$user_id = $campaignNbm['user_id'];
				$campaign_id = $campaignNbm['campaign_id'];
			}
			
			// $standardHeader is empty then use $standardHeader as header information to show show in db post_initiative note record
			// Otherwise use $forwardingHeader
			if (count($forwardingHeader) > 0) {
				$header = $forwardingHeader;
			} else {
				$header = $standardHeader;
			}
			
			echo '<pre>';
			print_r($header);
			echo '</pre>';
			
			$headerText = $this->makeEmailTextHeader($header);
			
			
			// Will use plain text only as HMTL carries formatting styles that may look odd whe displayed in the database - may even
			// make the text unreadable (eg very big size)
			// 		if ($this->htmlmsg != '') {
			// 			$note = $this->htmlmsg;
			// 		} elseif ($this->plainmsg != '') {
			$note = $this->plainmsg;
			// 		} else {
			// 			throw new Exception('Error: no message body found');
			// 		}
			
			// Add attachments
			$attachment_document_ids = array();
			foreach ($this->attachments as $key => $attachment) {
				$attachment_document_ids[] = $this->createAttachmentFile($campaign_id, $user_id, date('Y-m-d H:i:s', $header['date']), $key, $attachment);
			}
				
			foreach ($post_initiative_ids as $post_initiative_id) {
				$this->addMessageToDb($post_initiative_id, date('Y-m-d H:i:s', $header['date']), $user_id, $headerText . $note, $header['subject'], $attachment_document_ids);
			}
			
			// send email notification about email recipients not found in database
			if (count($addresses_missing_post_initiative_ids) > 0) {
				$missing_recipients = implode("\n", $addresses_missing_post_initiative_ids);
				$this->sendMissingRecipientsNotificationEmail($standardHeader, $missing_recipients);
			}
			
// 			throw new Exception('Error: No post_initiative_id values found in database');
// 			return false;
		} else {
			// send email notification about email recipients not found in database
			if (count($addresses_missing_post_initiative_ids) > 0) {
				$missing_recipients = implode("\n", $addresses_missing_post_initiative_ids);
				$this->sendMissingRecipientsNotificationEmail($standardHeader, $missing_recipients);
			}	
		}
	}
	
	private function getForwardingHeaders() {
		
		try {
			//NOTE: we are currently basing this on the plain text version
// 		if ($this->htmlmsg != '') {
// 			$note = $this->htmlmsg;
// 		} elseif ($this->plainmsg != '') {
			$note = $this->plainmsg;
// 		} else {
// 			throw new Exception('Error: no message body found');
// 		}
			
			
			$subject = $note;
			// Get the forwarding headers from the body of the email
			
			$pattern = '/[FfRrOoMm]{4,4}:.*@.*/';
			preg_match_all($pattern, $subject, $fromMatches, PREG_PATTERN_ORDER);
			
			$pattern = '/[TtOo]{2,2}:.*@.*/';
			preg_match_all($pattern, $subject, $toMatches, PREG_PATTERN_ORDER);
			
			$pattern = '/[C-Cc-c]{2,2}:.*@.*/';
			preg_match_all($pattern, $subject, $ccMatches, PREG_PATTERN_ORDER);
			
			$pattern = '/[DdAaTtEe]{4,4}:.*/';
			preg_match_all($pattern, $subject, $dateMatches, PREG_PATTERN_ORDER);
			
			$pattern = '/[SsEeNnTt]{4,4}:.*/';
			preg_match_all($pattern, $subject, $sentMatches, PREG_PATTERN_ORDER);
			
			$pattern = '/[SsUuBbJjEeCcTt]{7,7}:.*/';
			preg_match_all($pattern, $subject, $subjectMatches, PREG_PATTERN_ORDER);
					
			// take the first elements of each array so that we are only using the first to and from values in the body - 
			// for example, the email might have been forwarded twice but we only want the headers of the LAST time it was forwarded
			$matches['from'] = $fromMatches[0][0];
			$matches['to'] = $toMatches[0][0];
			$matches['cc'] = $ccMatches[0][0];
			
			// now process each of the arrays which may contain email addresses
			$forwardingHeaders = array();
			foreach($matches as $key => $value){
				$pattern = '/[A-Za-z0-9\._%]*\@[A-Za-z\._-]*\.[A-Za-z.]*/';
				preg_match_all($pattern, $value, $addresses, PREG_PATTERN_ORDER);
				$forwardingHeaders[$key] = $addresses[0];
			}
	
			// Add in the date header
			if (trim(substr($dateMatches[0][0],5,strlen($dateMatches[0][0])-5)) != '') {
				$forwardingHeaders['date'] = trim(substr($dateMatches[0][0],5,strlen($dateMatches[0][0])-5));
			} elseif (trim(substr($sentMatches[0][0],5,strlen($sentMatches[0][0])-5)) != '') {
				$forwardingHeaders['date'] = trim(substr($sentMatches[0][0],5,strlen($sentMatches[0][0])-5));
			} else {
				$forwardingHeaders['date'] = ''; // not good but picked up by error handling later
			}

			// and subject header
			$forwardingHeaders['subject'] = trim(substr($subjectMatches[0][0],8,strlen($subjectMatches[0][0])-8));
			
			// Perform data validity checks
			if (count($forwardingHeaders['from']) == 0) {
				throw new Exception('Error: Forwarding From address not detected.');
			} else {
				foreach ($forwardingHeaders['from'] as $from) {
					if ($from == '' || is_null($from)) {
						throw new Exception('Error: From field found in forwarding header is not populated');
					}
				}
			}
			
			if (count($forwardingHeaders['to']) == 0) {
				throw new Exception('Error: Forwarding To address(es) not detected.');
			} else {
				foreach ($forwardingHeaders['to'] as $to) {
					if ($to == '' || is_null($to)) {
						throw new Exception('Error: To field found in forwarding header is not populated');
					}
				}
			}
			
			// Check CC if populated 
			if (count($forwardingHeaders['cc']) > 0) {
				foreach ($forwardingHeaders['cc'] as $cc) {
					if ($cc == '' || is_null($cc)) {
						throw new Exception('Error: CC field found in forwarding header is not populated');
					}
				}
			}
			
			if ($forwardingHeaders['date'] == '' || is_null($forwardingHeaders['date'])) {
				// throw an error as no date found
				throw new Exception('Error: No valid Date field found in forwarding header');
			} else {
				$unixDate = strtotime($forwardingHeaders['date']);
				if ($unixDate !== false) {
					// check we have a valid date
					$dateArray = getdate($unixDate);
					if (checkdate($dateArray['mon'], $dateArray['mday'], $dateArray['year'])) {
						$forwardingHeaders['date'] = $unixDate;
					} else {
						throw new Exception('Error: Invalid Date found in forwarding header');
					}
				} else {
					throw new Exception('Error: Invalid Date found in forwarding header');
				}
			}
			
			if ($forwardingHeaders['subject'] == '' || is_null($forwardingHeaders['subject'])) {
				// throw an error as no date found
				throw new Exception('Error: No valid Subject field found in forwarding header');
			}
			
// 			echo '<pre>';
// 			print_r($forwardingHeaders);
// 			echo '</pre>';
			
			return $forwardingHeaders;
			
		} catch(Exception $e) {
			
			$forwardingHeaders['errors'] = $e->getMessage();
			return $forwardingHeaders;		
		}
	}
	
	private function getStandardHeaders($header) 
	{
// 		echo '<pre>Header';
// 		print_r($header);
// 		echo '</pre>';
		
		$output = array();
		if (array_key_exists('to', $header)) {
			$to = $header->to;
			$toOutput = array();
			foreach($to as $item) {
				$mailbox = '';
				$host = '';
				if (array_key_exists('mailbox', $item)) {
					if ($item->mailbox != '' || !is_null($item->mailbox)) {
						$mailbox = trim($item->mailbox);
					} else {
						throw new Exception('Error: To mailbox field found in email header is not populated');
					}
				} else {
					throw new Exception('Error: No valid To mailbox field found in email header.');
				}
					
				if (array_key_exists('host', $item)) {
					if ($item->host != '' || !is_null($item->host)) {
						$host = trim($item->host);
					} else {
						throw new Exception('Error: To Host field in email header is not populated');
					}
				} else {
					throw new Exception('Error: No valid To host field found in email header.');
				}
				
				$toOutput[] = $mailbox .'@' . $host;
			}
		} else {
			throw new Exception('Error: \'To\' address not found in email header.');
		}
		
		$output['to'] = $toOutput;
		
		if (array_key_exists('from', $header)) {
			$mailbox = '';
			$host = '';
			if (array_key_exists('mailbox', $header->from[0])) {
				if ($header->from[0]->mailbox != '' || !is_null($header->from[0]->mailbox)) {	
					$mailbox = trim($header->from[0]->mailbox);
				} else {
					throw new Exception('Error: Mailbox field found in email header is not populated');
				}
			} else {
				throw new Exception('Error: No valid mailbox field found in email header.');
			}
			
			if (array_key_exists('host', $header->from[0])) {
				if ($header->from[0]->host != '' || !is_null($header->from[0]->host)) {
					$host = trim($header->from[0]->host);
				} else {
					throw new Exception('Error: Host field in email header is not populated');
				}
			} else {
				throw new Exception('Error: No valid host field found in email header.');
			}
		} else {
			throw new Exception('Error: \'From\' address not found in email header.');
		}
		
		$output['from'] = array($mailbox .'@' . $host);
		
		if (array_key_exists('cc', $header)) {
			$cc = $header->cc;
			$ccOutput = array();
			foreach($cc as $item) {
				$mailbox = '';
				$host = '';
				if (array_key_exists('mailbox', $item)) {
					if ($item->mailbox != '' || !is_null($item->mailbox)) {
						$mailbox = trim($item->mailbox);
					} else {
						throw new Exception('Error: CC mailbox field found in email header is not populated');
					}
				} else {
					throw new Exception('Error: No valid CC mailbox field found in email header.');
				}
					
				if (array_key_exists('host', $item)) {
					if ($item->host != '' || !is_null($item->host)) {
						$host = trim($item->host);
					} else {
						throw new Exception('Error: CC Host field in email header is not populated');
					}
				} else {
					throw new Exception('Error: No valid CC host field found in email header.');
				}
		
				$ccOutput[] = $mailbox .'@' . $host;
			}
		}
		
		$output['cc'] = $ccOutput;
		
		$dateFound = false;
		// check date exists
		if (array_key_exists('date',$header)) {
			if ($header->date != '' || !is_null($header->date)) {
				$date = $header->date;
				$dateFound = true;
			}
		} 
		
		if (!$dateFound) { // if 'date' key not populated or doesn't exist then check Date exists
			if (array_key_exists('Date',$header)) {
				if ($header->Date != '' || !is_null($header->Date)) {
					$date = $header->Date;
					$dateFound = true;
				}
			}
		}
		
		if (!$dateFound) {
			// if 'Sent' key not populated or doesn't exist then check Sent exists
			if (array_key_exists('Sent',$header)) {
				if ($header->Sent != '' || !is_null($header->Sent)) {
					$date = $header->Sent;
					$dateFound = true;
				}
			}
		}
		
		if (!$dateFound) {
			// if 'sent' key not populated or doesn't exist then check Sent exists
			if (array_key_exists('sent',$header)) {
				if ($header->sent != '' || !is_null($header->sent)) {
					$date = $header->sent;
					$dateFound = true;
				}
			}
		}
		
		if (!$dateFound) { // throw an error as no date found
			throw new Exception('Error: No valid date fields found in email header');
		} else {
			$unixDate = strtotime($date);
			if ($unixDate !== false) {
				// check we have a valid date
				$dateArray = getdate($unixDate);
				if (!checkdate($dateArray['mon'], $dateArray['mday'], $dateArray['year'])) {
					throw new Exception('Error: Invalid date found in email header');
				}
			} else {
				throw new Exception('Error: Invalid date found in email header');
			}
		}
		
		$output['date'] = $unixDate;
				
		$subjectFound = false;
		// check date exists
		if (array_key_exists('subject',$header)) {
			if ($header->subject != '' || !is_null($header->subject)) {
				$subject = $header->subject;
			} else {
				$subject = '<--Subject blank in email-->';
			}
			$subjectFound = true;
		}
		
		if (!$subjectFound) {
			// if 'date' key not populated or doesn't exist then check Date exists
			if (array_key_exists('Subject',$header)) {
				if ($header->Subject != '' || !is_null($header->Subject)) {
					$subject = $header->Subject;
				} else {
					$subject = '<--Subject blank in email-->';
				}
				$subjectFound = true;
			}
		}
		
		if ($subjectFound) {
			$output['subject'] = $subject;
		} else {
			// throw an error as no subject found
			throw new Exception('Error: No valid subject field found in email header');
		} 
		
// 		echo '<pre>';
// 		print_r($output);
// 		echo '</pre>';
		
		return $output;
		
	}
	
// 	// used to identify which email addresses in the headers (to/from/cc) match to a system user
// 	// returns false if no match found or array in format:
// 	//  'userEmail' 	=> $email
// 	//  'userId'		=> $tmp
// 	private function identifyUserEmailAddress($standardHeader)  
// 	{
// 		$arrayToTest = array_merge($standardHeader['to'], $standardHeader['from']);
		
// 		if (count($standardHeader['cc']) > 1) {
// 			$arrayToTest = array_merge($arrayToTest, $standardHeader['cc']);
// 		}
// // 		echo '<pre>';
// // 		print_r($arrayToTest);
// // 		echo '</pre>';
		
// 		foreach($arrayToTest as $email) {
// 			$tmp = app_domain_CampaignNbm::findUserIdByCampaignNbmEmail($email);
// 			if (!is_null($tmp)) {
// 				return array('userEmail' 	=> $email,
// 							 'userId'		=> $tmp);
// 			}
// 		}
		
// 		return false;
// 	}
	
	private function getPostInitiativeIds($standardHeader, $forwardingHeader)
	{
		$post_initiative_ids = array();
		$addresses_not_found = array();
		
		if (count($forwardingHeader) == 0) {
			foreach($standardHeader['to'] as $to) {
				$tmp = $this->findIdByPostEmailAndCampaignNbmEmail($to, $standardHeader['from'][0]);
// 				$tmp = app_domain_PostInitiative::findIdByPostEmailAndCampaignNbmEmail($to, $standardHeader['from'][0]);
				if (count($tmp) > 0) {
					foreach ($tmp as $t) {
						$post_initiative_ids[] = $t['id'];
					}
				} else {
					// record the 'to' address for which we could not find any post_initiative_id
					$addresses_not_found[] = $to;
				}
			}
			
			foreach($standardHeader['cc'] as $cc) {
				$tmp = $this->findIdByPostEmailAndCampaignNbmEmail($cc, $standardHeader['from'][0]);
// 				$tmp = app_domain_PostInitiative::findIdByPostEmailAndCampaignNbmEmail($cc, $standardHeader['from'][0]);
				if (count($tmp) > 0) {
					foreach ($tmp as $t) {
						$post_initiative_ids[] = $t['id'];
					}
				} else {
					// record the 'to' address for which we could not find any post_initiative_id
					$addresses_not_found[] = $cc;
				}
			}
			
		} else {
			// if email is forwarded then assume email needs to be added to the post_initiative record of the 
			// ORIGINAL sender (ie identified by post_id using the FROM field in the BODY and client_id using the FROM field in the 
			// standard (current) email header)
			foreach($forwardingHeader['from'] as $from) {
				$tmp = $this->findIdByPostEmailAndCampaignNbmEmail($from, $standardHeader['from'][0]);
// 				$tmp = app_domain_PostInitiative::findIdByPostEmailAndCampaignNbmEmail($from, $standardHeader['from'][0]);
				foreach ($tmp as $t) {
					$post_initiative_ids[] = $t['id'];
				}
			}
// 			$tmp = app_domain_PostInitiative::findIdByPostEmailAndCampaignNbmEmail($forwardingHeader['from'][0], $standardHeader['from'][0]);
// 			foreach ($tmp as $t) {
// 				$post_initiative_ids[] = $t['id'];
// 			}
			
// 			// then add to the record of any other recipients who have post_initiative records which appear in the forwarding header to field
// 			foreach($forwardingHeader['to'] as $to) {
// 				$tmp = app_domain_PostInitiative::findIdByPostEmailAndCampaignNbmEmail($to, $standardHeader['from'][0]);
// 				foreach ($tmp as $t) {
// 					$post_initiative_ids[] = $t['id'];
// 				}
// 			}
			
			// then add to the record of any other recipients who have post_initiative records which appear in the forwarding header cc field
			foreach($forwardingHeader['cc'] as $cc) {
				$tmp = $this->findIdByPostEmailAndCampaignNbmEmail($cc, $standardHeader['from'][0]);
// 				$tmp = app_domain_PostInitiative::findIdByPostEmailAndCampaignNbmEmail($cc, $standardHeader['from'][0]);
				foreach ($tmp as $t) {
					$post_initiative_ids[] = $t['id'];
				}
			}
		}
		
		// dedupe the $post_initiative_ids array incase we have any duplicated records - we would never want to have the same email recorded mroe than
		// once for a contact
		$post_initiative_ids = array_unique($post_initiative_ids);
		
		return array($post_initiative_ids, $addresses_not_found);
		
	}
	
	
	private function addMessageToDb($post_initiative_id, $created_date, $created_by, $note, $summary, $attachment_document_ids)
	{
		$post_initiative_note_id = $this->addPostInitiativeNote($post_initiative_id, $created_date, $created_by, $note, substr($summary,0,255), 1);
		
		foreach ($attachment_document_ids as $attachment_document_id) {
			$this->addPostInitiativeNoteDocument($post_initiative_note_id, $attachment_document_id);
		}
	}
	
	
	private function createAttachmentFile($campaign_id, $user_id, $created_date, $filename, $data)
	{
		$filename_orig = $filename;
		$documentId = $this->addDocument($campaign_id, $filename);
			
		// Set the new name of the uploaded file and where it is to be put
		$filename = $documentId;
		
		$filePath = APP_DIRECTORY . 'var/';
		$filename = $filePath . $filename;
		$handle = fopen($filename, 'w') or die('Cannot open file:  ' . $filename); //implicitly creates file
		fwrite($handle, $data);
		fclose($handle);
		
		// Update the rest of the information for this document
		$this->updateDocument($documentId, filesize($filename), mime_content_type($filename), $user_id, $created_date);
		
		return $documentId;
	}
	
	private function formatBytes($a_bytes)
	{
		if ($a_bytes < 1024) {
			return $a_bytes .' B';
		} elseif ($a_bytes < 1048576) {
			return round($a_bytes / 1024, 2) .' KB';
		} elseif ($a_bytes < 1073741824) {
			return round($a_bytes / 1048576, 2) . ' MB';
		} elseif ($a_bytes < 1099511627776) {
			return round($a_bytes / 1073741824, 2) . ' GB';
		} elseif ($a_bytes < 1125899906842624) {
			return round($a_bytes / 1099511627776, 2) .' TB';
		} elseif ($a_bytes < 1152921504606846976) {
			return round($a_bytes / 1125899906842624, 2) .' PB';
		} elseif ($a_bytes < 1180591620717411303424) {
			return round($a_bytes / 1152921504606846976, 2) .' EB';
		} elseif ($a_bytes < 1208925819614629174706176) {
			return round($a_bytes / 1180591620717411303424, 2) .' ZB';
		} else {
			return round($a_bytes / 1208925819614629174706176, 2) .' YB';
		}
	}
	
	private function getmsg($mbox,$mid) 
	{
		// input $mbox = IMAP stream, $mid = message id
		// sets all the following:
		// the message may in $htmlmsg, $plainmsg, or both
		$this->htmlmsg = $this->plainmsg = $this->charset = '';
		$this->attachments = array();
	
		// HEADER
		$h = imap_header($mbox,$mid);
		// add code here to get date, from, to, cc, subject...
	
		// BODY
		$s = imap_fetchstructure($mbox,$mid);
		if (!$s->parts)  // not multipart
			$this->getpart($mbox,$mid,$s,0);  // no part-number, so pass 0
		else {  // multipart: iterate through each part
			foreach ($s->parts as $partno0=>$p)
			$this->getpart($mbox,$mid,$p,$partno0+1);
		}
		
	}
	
	private function getpart($mbox,$mid,$p,$partno) 
	{
		// $partno = '1', '2', '2.1', '2.1.3', etc if multipart, 0 if not multipart
		
		// DECODE DATA
		$data = ($partno)?
		imap_fetchbody($mbox,$mid,$partno):  // multipart
		imap_body($mbox,$mid);  // not multipart
		
// 		$myFile = "/Users/david/Documents/testFile_$mid_$partno.txt";
// 		$fh = fopen($myFile, 'w') or die("can't open file");
// 		fwrite($fh, $data);
// 		fclose($fh);
		
		
// 		echo 'Here is $data <br />';
// // 		echo '<pre>';
// 		echo $data;
// // 		echo '</pre>';
			
		// Any part may be encoded, even plain text messages, so check everything.
		if ($p->encoding==4)
			$data = quoted_printable_decode($data);
		elseif ($p->encoding==3)
			$data = base64_decode($data);
		// no need to decode 7-bit, 8-bit, or binary
	
		// PARAMETERS
		// get all parameters, like charset, filenames of attachments, etc.
		$params = array();
		if ($p->parameters)
			foreach ($p->parameters as $x)
				$params[ strtolower( $x->attribute ) ] = $x->value;
		if ($p->dparameters)
			foreach ($p->dparameters as $x)
				$params[ strtolower( $x->attribute ) ] = $x->value;
	
		// ATTACHMENT
		// Any part with a filename is an attachment,
		// so an attached text file (type 0) is not mistaken as the message.
		if ($params['filename'] || $params['name']) {
			// filename may be given as 'Filename' or 'Name' or both
			$filename = ($params['filename'])? $params['filename'] : $params['name'];
// 			echo 'Here is the filename ' . $filename . '--End of filename--<br /><br />';
// 			echo 'Here is the data ' . $data . '--End of $data--<br /><br />';
			// filename may be encoded, so see imap_mime_header_decode()
			$this->attachments[$filename] = $data;  // this is a problem if two files have same name
		}
	
		// TEXT
		elseif ($p->type==0 && $data) {
			// Messages may be split in different parts because of inline attachments,
			// so append parts together with blank row.
			if (strtolower($p->subtype)=='plain')
				$this->plainmsg .= str_replace('\n','<br />',trim($data)) ."\n\n";
// 				$this->plainmsg = $data;
			else
				$this->htmlmsg .= $data ."<br><br>";
			$this->charset = $params['charset'];  // assume all parts are same charset
		}
	
		// EMBEDDED MESSAGE
		// Many bounce notifications embed the original message as type 2,
		// but AOL uses type 1 (multipart), which is not handled here.
		// There are no PHP functions to parse embedded messages,
		// so this just appends the raw source to the main message.
		elseif ($p->type==2 && $data) {
			$this->plainmsg .= trim($data) ."\n\n";
		}
	
		// SUBPART RECURSION
		if ($p->parts) {
			foreach ($p->parts as $partno0=>$p2)
			$this->getpart($mbox,$mid,$p2,$partno.'.'.($partno0+1));  // 1.2, 1.2.1, etc.
		}
	}
	
	private function sendErrorEmail($standardHeader, $errorMessage) 
	{
		
		$note = $this->plainmsg;
		
		//
		// Send the email
		//
		// Get email parameters
		$to_email   = $standardHeader['from'][0];
		$from_email = 'db@illumen.co.uk';
		$subject    = 'Error processing e-mail submitted to database';
		$body      	 = 'There was an error processing an e-mail you submitted to the database. The error and original e-mail are shown below.';
		$body 		.= "\n----------------------------------------\n" . $errorMessage;
		$body 		.= $this->makeEmailTextHeader($standardHeader) . "\n" . $note;
		
		require_once('Zend/Mail.php');
		$mail = new Zend_Mail();
		$mail->setFrom($from_email);
		$mail->addTo($to_email);
		$mail->setSubject($subject);
		$mail->setBodyText($body . "\n----------------------------------------\n\n" . $note);
		
		// Send email
		try {
			$mail->send();
		} catch (Exception $e) {
			die('Error sending email');
		}

	}
	
	private function sendMissingRecipientsNotificationEmail($standardHeader, $missingRecipients)
	{
	
		$note = $this->plainmsg;
	
		//
		// Send the email
		//
		// Get email parameters
		$to_email   = $standardHeader['from'][0];
		$from_email = 'db@illumen.co.uk';
		$subject    = 'DB notification: missing database contact';
		$body      	 = 'Client records could not be found for the following e-mail addresses contained in an e-mail you recently submitted to the database:';
		$body 		.= "\n" . $missingRecipients;
		$body 		.= "\n----------------------------------------\n";
		$body		.= 'The original email is listed below.';
		$body 		.= "\n----------------------------------------\n";
		$body 		.= $this->makeEmailTextHeader($standardHeader) . "\n" . $note;
	
		require_once('Zend/Mail.php');
			$mail = new Zend_Mail();
		$mail->setFrom($from_email);
		$mail->addTo($to_email);
		$mail->setSubject($subject);
		$mail->setBodyText($body);
	
		// Send email
		try {
		$mail->send();
		} catch (Exception $e) {
		die('Error sending email');
			}
	
		}
	
	private function makeEmailTextHeader($header) 
	{

		$headerText = 'Subject: ' . $header['subject'] . "\n";
		
		$headerText .= 'To: ';
		foreach($header['to'] as $t) {
		$headerText .= $t . ', ';
				}
		// strip last two chars
		$headerText = substr($headerText, 0, strlen($headerText)-2) . "\n";
		
		// Add CC text if it exists
		if (array_key_exists('cc', $header)) {
			if (count($header['cc']) > 0) {
			$headerText .= 'CC: ';
				foreach($header['cc'] as $t) {
					$headerText .= $t . ', ';
				}
				// strip last two chars
				$headerText = substr($headerText, 0, strlen($headerText)-2) . "\n";
			}
		}
		
		$headerText .= 'From: ' . $header['from'][0] . "\n";
		$headerText .= 'Date: ' . date('D, d/m/Y H:i:s', $header['date']) . "\n\n";
		
		return $headerText;
	}
	
	/**
	* Lookup a row based on an email address - assumes only one unique email in the table
	* @return raw data - single row
	*/
	function findByCampaignNbmEmail($email)
	{
		$select = $this->db->select();
		$select->from('tbl_campaign_nbms');
		$select->where('user_email = ?', $email);
					
		return $this->db->fetchRow($select);
	}
	
	/**
	* Find post_initiative_id by email address
	* @param string $email
	* @return array
	*/
	function findIdByPostEmailAndCampaignNbmEmail($post_email, $campaign_nbm_email)
	{
		$select = $this->db->select();
		$select->from(array('pi' => 'tbl_post_initiatives'), array('id'));
		$select->join(array('con' => 'tbl_contacts'), 'pi.post_id = con.post_id', array());
		$select->join(array('i' => 'tbl_initiatives'), 'i.id = pi.initiative_id', array());
		$select->join(array('cn' => 'tbl_campaign_nbms'), 'cn.campaign_id = i.campaign_id', array());
		$select->where('con.email = ?', $post_email);
		$select->where('cn.user_email = ?', $campaign_nbm_email);
		
		return $this->db->fetchAll($select);
	}
	
	function addPostInitiativeNote($post_initiative_id, $created_date, $created_by, $note, $summary, $note_type_id) {
		$newId = $this->getNextId('tbl_post_initiative_notes');
		$data = array(
			'id'					=> $newId,
			'post_initiative_id'	=> $post_initiative_id,
			'created_at'			=> $created_date,
			'created_by'			=> $created_by,
			'note'					=> $note,
			'summary'				=> $summary,
			'note_type_id'			=> $note_type_id,
		);
		
		$this->db->insert('tbl_post_initiative_notes', $data);
		return $newId;
	}
	
	function addPostInitiativeNoteDocument($post_initiative_note_id, $document_id) {
		$newId = $this->getNextId('tbl_post_initiative_note_documents');
		$data = array(
				'id'						=> $newId,
				'post_initiative_note_id'	=> $post_initiative_note_id,
				'document_id'				=> $document_id,
		);
	
		$this->db->insert('tbl_post_initiative_note_documents', $data);
	
		return $newId;
	}

	function addDocument($campaign_id, $filename) {
		$data = array(
					'campaign_id'	=> $campaign_id,
					'filename'		=> $filename,
		);
	
		$this->db->insert('tbl_documents', $data);
		return $this->db->lastInsertId();
		
	}
	
	function updateDocument($id, $filesize, $mime_id ,$user_id, $created_date) {
		$data = array(
			'size'			=> $filesize,
			'mime_id'		=> $mime_id,
			'created_by'	=> $user_id,
			'created'		=> $created_date,
		);
		
		return $this->db->update('tbl_documents', $data, 'id = ' . $id);
		
	}
	
}

?>