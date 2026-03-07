<?php

class ajaxNotice
{

	protected $notice = null;
	
    function __construct($notice) 
    {
       $this->setNotice($notice);
   	}
    
    /*
	 * ----- Start of Accessors -----
	 */
    
    /**
	 * Gets the notice.
	 * @return string
	 * @access public
	 */
	public function getNotice()
	{
		return $this->notice;
	}
	
	/*
	 * ----- Start of Mutators -----
	 */
    
    /**
	 * Sets the notice.
	 * @param string
	 * @access public
	 */
	public function setNotice($notice)
	{
		$this->notice = $notice;
	}

}
?>