<?php

/**
 * Description
 * @author    $Author$
 * @copyright 2006 Illumen Ltd
 * @package   <package>
 * @version   SVN: $Id$
 */

/**
 * @package illumen
 */
class app_domain_PaginateHelper
{
	/**
	 * The current item being paginated to. The default is 0.
	 * @var integer
	 */
	protected $offset = 0;
	
	/**
	 * The current number of items displayed per page.  The default is 10.
	 * @var integer
	 */
	protected $limit = 50;

	/**
	 * The total number if items being paginated.
	 * @var integer
	 */
	protected $total = null;

	/**
	 * 
	 * @param app_controller_Request $request
	 */
	function __construct(app_controller_Request $request)
	{
		$this->setOffset($request->getProperty('next'));
		$this->setLimit($request->getProperty('limit'));
	}

	/**
	 * Set the current item being paginated to.
	 * @param integer $offset
	 */
	protected function setOffset($offset = 0)
	{
		if (is_numeric($offset))
		{
			if ($offset > 0)
			{
				$this->offset = --$offset;
			}
		}
		else
		{
			$this->offset = 0;
		}
	}

	/**
	 * Return the current item being paginated to.
	 * @return integer
	 */
	public function getOffset()
	{
		$remainder = $this->offset % $this->limit;
		$myOffset = $this->offset - $remainder;
		$count = $myOffset / $this->limit;
		$this->offset = $count * $this->limit;
		return $this->offset;
	}

	/**
	 * Set the number of items displayed per page.
	 * @param integer $limit
	 */
	protected function setLimit($limit)
	{
		if (is_numeric($limit))
		{
			$this->limit = $limit;
		}
	}

	/**
	 * Gets the current number of items displayed per page.
	 * @return integer
	 */
	public function getLimit()
	{
//		$this->offset = $this->findPage($this->offset, $this->limit);
		return $this->limit;
	}
	
//	protected function findPage($offset, $limit)
//	{
//		$mod = $offset % $limit;
//		$newOffset = $offset - $mod;
//		$count = $newOffset / $limit;
//		return $count * $limit;
//	}

	/**
	 * Set the total number if items being paginated. This MUST be set for the paginator to work.
	 * @param integer $total
	 */
	public function setTotal($total)
	{
		$this->total = $total;
	}

	/**
	 * Return the total number if items being paginated.
	 * @return integer
	 */
	public function getTotal()
	{
		return $this->total;
	}

}

?>