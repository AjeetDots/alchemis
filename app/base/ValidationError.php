<?php

/**
 * Defines the app_base_RuleValidator class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

/**
 * @package Framework
 */
class app_base_ValidationError
{
	/**
	 * The short error description (used for tooltips).
	 * @var string
	 */
	protected $tip = null;

	/**
	 * The full error description.
	 * @var string
	 */
	protected $error_msg = null;

	/**
	 * Error constructor.<br />
	 * Instatiates an existing Error object.
	 * @param string $tip short error description
	 * @param string $error_msg the full error description.  If not supplied 
	 *        then set to equal the short description.	
	 */
	function __construct($tip, $error_msg = null)
	{
		$this->tip = $tip;
		if (!empty($error_msg) && trim($error_msg) != '')
		{
			$this->error_msg = $error_msg;
		}
		else
		{
			$this->error_msg = $this->tip;
		}
	}

	/**
	 * Returns the short error description (tip).
	 * @return string
	 */
	public function getTip()
	{
		return $this->tip;
	}

	/**
	 * Returns the full error description.
	 * @return string
	 */
	public function getMessage()
	{
		return $this->error_msg;
	}

}

?>