<?php

require_once('batch/BatchProcess.class.php');
require_once('include/Illumen/PostmarkInbound/postmark_inbound.php');

class batch_emailTrapper_PostmarkEmailTrapper2 extends batch_BatchProcess
{
	private $dbEmailAddress;
	private $htmlmsg;
	private $plainmsg;
	private $charset;
	private $attachments;
	private $inbound;
	private $transactionId;
	
	function __construct($inboundData) {
		$connection = self::getDbConnection();
		$config = new Zend_Config(
			array (
				'database' => array(
					'adapter' => 'Mysqli',
					'params'  => array(
						'host'     => $connection['hostname'],
						'dbname'   => $connection['database'],
						'username' => $connection['username'],
						'password' => $connection['password'],
					)
				)
			)
		);
	
		$this->db = Zend_Db::factory($config->database);
		$this->db->setFetchMode(Zend_Db::FETCH_ASSOC);
	
		$this->init($inboundData);
	}
	
	protected function init($inboundData) {
		
		$this->addRawPostmarkData($inboundData);
		exit();
		$this->transactionId = mt_rand();
		
		$this->inbound = New PostmarkInbound($inboundData);
		$this->addToLog('Initialising process (see log_entry for raw data)', $this->transactionId, $inboundData);
		
		$this->dbEmailAddress = 'alchemis@illumen.co.uk';
		
		$dbEmailAddressFound = false;
		if ($this->inbound->to() == $this->dbEmailAddress) {
			$dbEmailAddressFound = true;
		}
		
		$standardHeader = $this->getStandardHeaders();
		
		$forwardingHeader = array();
		if ($dbEmailAddressFound) {
			// use  first 'header block' in body text
			$forwardingHeader = $this->getForwardingHeaders($this->inbound->text_body());
		}
		
		if (array_key_exists('errors',$forwardingHeader)) {
			echo '<p>==== ERROR FOUND: ====<br />' . $forwardingHeader['errors'] . '<br />===========</p>';
		}
		
		$this->processHeaders($standardHeader, $forwardingHeader);
		
		$this->addToLog('Completed process', $this->transactionId);
	}
	
	private function moveEmail($mbox, $i, $destination) {
		imap_mail_move($mbox, $i, $destination);
	}
	
	private function processHeaders($standardHeader, $forwardingHeader) {
		
// 		Zend_Debug::dump($standardHeader);
// 		Zend_Debug::dump($forwardingHeader);
		$post_initiative_ids = $this->getPostInitiativeIds($standardHeader, $forwardingHeader);
// 		Zend_Debug::dump($post_initiative_ids);
		$addresses_missing_post_initiative_ids = $post_initiative_ids[1];
		$post_initiative_ids = $post_initiative_ids[0];
		
		if (count($post_initiative_ids) > 0) {
			
			$campaignNbm = $this->findByCampaignNbmEmail($standardHeader['from'][0]);
			if (!$campaignNbm) {
// 				throw new Exception();
				$this->addToLog('Error: Could not find Campaign NBM email address which matches \'from\' address in header', $this->transactionId);
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
			
			$headerText = $this->makeEmailTextHeader($header);
			
			// Will use plain text only as HMTL carries formatting styles that may look odd whe displayed in the database - may even
			// make the text unreadable (eg very big size)
			$note = $this->inbound->text_body();

			$attachment_document_ids = array();
			if ($this->inbound->has_attachments()) {
				$attachments = $this->inbound->attachments();
				foreach ($attachments as $attachment) {
					 $attachedmentId = $this->createAttachmentFile($campaign_id, $user_id, date('Y-m-d H:i:s', $header['date']), $attachment->name(), $attachment->read());
					 $attachment_document_ids[] = $attachedmentId;
					$this->addToLog('Created attachment doc id: ' . $attachedmentId,  $this->transactionId);
				}
			}
			
//			Zend_Debug::dump($attachment_document_ids, '$attachment_document_ids');
//			exit();
// 			Zend_Debug::dump($post_initiative_ids, '$post_initiative_ids');
			
			foreach ($post_initiative_ids as $post_initiative_id) {
				$noteId = $this->addMessageToDb($post_initiative_id, date('Y-m-d H:i:s', $header['date']), $user_id, $headerText . $note, $header['subject'], $attachment_document_ids);
				$this->addToLog('post_initiative_note_id: ' . $noteId . ' added to database for post_initiative_id: ' . $post_initiative_id, $this->transactionId, $note);
			}
			
			// send email notification about email recipients not found in database
			if (count($addresses_missing_post_initiative_ids) > 0) {
				$missing_recipients = implode("\n", $addresses_missing_post_initiative_ids);
				$this->sendMissingRecipientsNotificationEmail($standardHeader, $missing_recipients);
				$this->addToLog('Missing database recipients found', $this->transactionId, $missing_recipients);
			}
			
		} else {
			// send email notification about email recipients not found in database
			if (count($addresses_missing_post_initiative_ids) > 0) {
				$missing_recipients = implode("\n", $addresses_missing_post_initiative_ids);
				$this->sendMissingRecipientsNotificationEmail($standardHeader, $missing_recipients);
				$this->addToLog('No valid database recipients in email', $this->transactionId, $missing_recipients);
			}	
		}
	}
	
	private function getForwardingHeaders($note) {
		
		try {
			
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
// 			Zend_Debug::dump($ccMatches, '$ccMatches');
			if (count($ccMatches[0]) > 0) {
				$matches['cc'] = $ccMatches[0][0];
			} 
			
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
			if (array_key_exists('cc', $forwardingHeaders)) { 
				if (count($forwardingHeaders['cc']) > 0) {
					foreach ($forwardingHeaders['cc'] as $cc) {
						if ($cc == '' || is_null($cc)) {
							throw new Exception('Error: CC field found in forwarding header is not populated');
						}
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
			
			return $forwardingHeaders;
			
		} catch(Exception $e) {
			
			$forwardingHeaders['errors'] = $e->getMessage();
			return $forwardingHeaders;		
		}
	}
	
	private function getStandardHeaders() 
	{
		$output = array();
		
		$to = explode(',', $this->inbound->to());
		$toOutput = array();
		foreach ($to as $item) {
			$toOutput[] = trim($item);
		}
		$output['to'] = $toOutput;
		
		$from = explode(',', $this->inbound->from_email());
		$fromOutput = array();
		foreach ($from as $item) {
			$fromOutput[] = trim($item);
		}
		$output['from'] = $fromOutput;
		
		$cc = explode(',', $this->inbound->cc());
		$ccOutput = array();
		foreach ($cc as $item) {
			if (trim($item) != '') {
				$ccOutput[] = trim($item);
			}
		}
		$output['cc'] = $ccOutput;
		
		$output['date'] = time();
		
		$subject='';
		if (trim($this->inbound->subject()) == '') {
			$subject = '<--Subject blank in email-->';
		} else {
			$subject = trim($this->inbound->subject());
		}
		$output['subject'] = $subject;

		return $output;
	}
	
	private function getPostInitiativeIds($standardHeader, $forwardingHeader)
	{
		$post_initiative_ids = array();
		$addresses_not_found = array();
		
		if (count($forwardingHeader) == 0) {
			foreach($standardHeader['to'] as $to) {
				$tmp = $this->findIdByPostEmailAndCampaignNbmEmail($to, $standardHeader['from'][0]);
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
				foreach ($tmp as $t) {
					$post_initiative_ids[] = $t['id'];
				}
			}
			
			// then add to the record of any other recipients who have post_initiative records which appear in the forwarding header cc field
			if (array_key_exists('cc', $forwardingHeader)){
				if (count($forwardingHeader['cc']) > 0) {
					foreach($forwardingHeader['cc'] as $cc) {
						$tmp = $this->findIdByPostEmailAndCampaignNbmEmail($cc, $standardHeader['from'][0]);
						foreach ($tmp as $t) {
							$post_initiative_ids[] = $t['id'];
						}
					}
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
		
		return $post_initiative_note_id;
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
		$select->join(array('p' => 'tbl_posts'), 'pi.post_id = p.id', array());
		$select->join(array('con' => 'tbl_contacts'), 'pi.post_id = con.post_id', array());
		$select->join(array('i' => 'tbl_initiatives'), 'i.id = pi.initiative_id', array());
		$select->join(array('cn' => 'tbl_campaign_nbms'), 'cn.campaign_id = i.campaign_id', array());
		$select->where('con.email = ?', $post_email);
		$select->where('cn.user_email = ?', $campaign_nbm_email);
		$select->where('p.deleted = 0');
		
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
	
	function addToLog($summary, $transaction = null, $blob = null) {
		$data = array(
			'summary'		=> $summary,
			'transaction'	=> $transaction,
			'log_entry'		=> $blob,
			
		);
		$this->db->insert('tbl_log', $data);
	}
	
	function addRawPostmarkData($data) {
		$data = array(
						'data'			=> $data
		);
	
		$this->db->insert('tbl_postmark_data', $data);
		return $this->db->lastInsertId();
	
	}
}

?>