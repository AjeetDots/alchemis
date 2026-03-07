<?php

class ajaxWarning 
{

	protected $warning = null;
	
    function __construct($warning) 
    {
       $this->setWarning($warning);
   	}
    
    /*
	 * ----- Start of Accessors -----
	 */
    
    /**
	 * Gets the warning.
	 * @return string
	 * @access public
	 */
	public function getWarning()
	{
		return $this->warning;
	}
	
	/*
	 * ----- Start of Mutators -----
	 */
    
    /**
	 * Sets the warning.
	 * @param string
	 * @access public
	 */
	public function setWarning($warning)
	{
		$this->warning = $warning;
	}

}
?>