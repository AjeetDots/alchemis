<?php

require_once('app/domain/Collections.php');


/**
 * The class maintains two arrays, $objects and $raw. If a client requests a particular element, 
 * then the getObjectAt() method looks first in $objects to see if it has one already instantiated. 
 * If so, that gets returned. Otherwise the method looks in $raw for the row data. $raw data is 
 * only present if a Mapper object is also present, so the raw data can be passed to a new Mapper 
 * method, loadArray(), which is simply the array loading stage of the find process pulled out into 
 * a separate operation. This returns a DomainObject object, which is cached in the $objects array 
 * with the relevant index. The object is returned to the user.
 * 
 * Concrete implementations extending app_mapper_Collection implement the Iterator interface which 
 * requires the following functions are defined: rewind(), current(), key(), next(), valid().
 * 
 * By also implementing Countable we ensure that instances can be safely passed to PHP's count()
 * in PHP 7.2+ / 8+, avoiding TypeError when templates call count($collection).
 * 
 * @package Framework
 */ 
abstract class app_mapper_Collection implements Iterator, Countable
{
	private $mapper;
    private $result; 
    private $total = 0; 
    private $pointer = 0;
    
    /**
     * Stores cached objects.
     * @var array
     * @private
     */
    private $objects = array();

    /**
     * Stores raw object data.
     * @var array
     * @private
     */
    private $raw = array();

	/**
	 * Because it accepts null values, type checking is deferred to the init_db() method. (If you 
	 * use a type hint, then the method will not accept a null value for that argument.)
	 * 
	 * If no arguments are passed, the class starts out empty, though the doAdd() method can be 
	 * used for adding to the collection.
	 * 
	 * 
	 * 
	 * @param MDB2_Result $result 
	 * @param app_mapper_Mapper $mapper
	 */
	public function __construct($result = null, $mapper = null)
	{
		if ($result && $mapper)
		{
			$this->init_db($result, $mapper);
		}
	}
	
	/**
	 * 
	 * @param MDB2_Result $result 
	 * @param app_mapper_Mapper $mapper
	 */
	protected function init_db(MDB2_Result $result, app_mapper_Mapper $mapper)
	{
		$this->result = $result;
		$this->mapper = $mapper;
		$this->total += $result->numRows();
		
		while ($row = $this->result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$this->raw[] = $row;
			$this->result->nextResult();
		}
	}

	/**
	 * @param app_domain_DomainObject $object
	 */
	protected function doAdd(app_domain_DomainObject $object)
	{
		$this->notifyAccess();
		$this->objects[$this->total] = $object;
		$this->total++;
	}
	
	/**
	 * Deliberately left blank for child classes to implement.
	 * Becomes important when we encounter the Lazy Load pattern.
	 * Called from any method whose invocation is the result of a call from the outside world.
	 */
	protected function notifyAccess()
	{
		// Deliberately left blank for child classes to implement.
	}
	
	/**
	 * Returns the object at the given index. Implements lazy loading by only fully loading the 
	 * object upon the first access to the index.   
	 * @param integer $num
	 * @return app_domain_DomainObject the object at the index or null if index out of bounds 
	 */
	private function getObjectAt($num)
	{
//		echo "<h1>app_mapper_Collection::getObjectAt($num)</h1>";
		$this->notifyAccess();
		
		if ($num >= $this->total || $num < 0)
		{
//			echo "<p>return null</p>";
			return null;
		}
		
		/* TODO Check whether this is correct */
//		if ($this->objects[$num])
		if (isset($this->objects[$num]))
		{
//			echo "<p>from objects</p>";
			return $this->objects[$num];
		}
		
		if ($this->raw[$num])
		{
//			echo "<p>from raw - " . get_class($this->mapper) . "</p>";
			$this->objects[$num] = $this->mapper->loadArray($this->raw[$num]);
			return $this->objects[$num];
		}
	}
	
	/**
	 * Send pointer to start of list.
	 */
	public function rewind()
	{
		$this->pointer = 0;
	}

	/**
	 * Return element at current pointer position.
	 * @return app_domain_DomainObject the object at the index or null if index out of bounds
	 */
	public function current()
	{
		return $this->getObjectAt($this->pointer);
	}

	/**
	 * Return current key (i.e. pointer value).
	 * @return integer current key (pointer location)
	 */
	public function key()
	{
		return $this->pointer;
	}

	/**
	 * Return element at current pointer and advance pointer.
	 * @return app_domain_DomainObject the object at the current index.
	 */
	public function next()
	{
		$row = $this->getObjectAt($this->pointer);
		if ($row)
		{
			$this->pointer++;
		}
		return $row;
	}

	/**
	 * Confirms that there is an element at the current pointer position.
	 * @return boolean
	 */
	public function valid()
	{
		return (!is_null( $this->current()));
	}

	/**
	 * Gets the number of items in the Collection.
	 * @return integer
	 * @author Ian Munday
	 */
	public function count()
	{
		return $this->total;
	}

	/**
	 * Returns the collection as a numeric array.
	 * @return array
	 */
	public function getArray()
	{
		// Get current pointer postion
		$key = $this->key();
		
		// Rewind so can iterate through the whole collection
		$this->rewind();
		
		// Build array
		$myArray = array();
		for ($i = 0; $i < $this->count(); $i++)
		{
			$item = $this->current();
			$myArray[] = $item;
			$this->next();
		}
		
		// Set key back to original postion
		$this->pointer = $key;
		
		return $myArray;
	}

//	public function elementAt() {}
//	public function deleteAt() {}

	/**
	 * TODO - Added this as getObjectAt does not handle the case where more than one object has 
	 * the same ID, e.g. in the instance where loading campaign history - here we need to use the 
	 * UID, not the ID.
	 *  
	 * Returns the object at the given index. Implements lazy loading by only fully loading the 
	 * object upon the first access to the index.   
	 * @param integer $num
	 * @return app_domain_DomainObject the object at the index or null if index out of bounds 
	 */
	public function getRaw($num)
	{
//		echo "<p>app_mapper_Collection::getRaw($num)</p>";
		$this->notifyAccess();
		
		if ($num >= $this->total || $num < 0)
		{
//			echo "<h1>return null</h1>";
			return null;
		}
		
		if ($this->raw[$num])
		{
			return $this->raw[$num];
		}
	}

	/**
	 * Returns the collection as a numeric array of app_domain_DomainObject 
	 * objects. Does not touch the current pointer position.
	 * @return array app_domain_DomainObject objects 
	 */
	public function toArray()
	{
		$results = array();
		for ($i = 0; $i < $this->count(); $i++)
		{
			$results[$i] = $this->getObjectAt($i);
		}
		return $results;
	}

	/**
	 * Returns the collection as a numeric array of raw objects from the 
	 * database, (i.e. it does not return loaded app_domain_DomainObject 
	 * objects). Does not touch the current pointer position.
	 * @return array of raw key values pairs (no loaded app_domain_DomainObjects) 
	 */
	public function toRawArray()
	{
		$column_names = array_keys($this->result->getColumnNames());
		$results = array();
		foreach ($this->raw as $row)
		{
			$output = array();
			foreach ($column_names as $column_name)
			{
				$output[$column_name] = $row[$column_name];
				// $output[$column_name] = mb_convert_encoding($row[$column_name], 'ISO-8859-1', 'UTF-8');
			}
			$results[] = $output;
		}
		return $results;
	}

	/**
	 * Returns the collection as a numeric array of raw objects from the 
	 * database, (i.e. it does not return loaded app_domain_DomainObject 
	 * objects) changing the output encoding. Does not touch the current pointer position.
	 * @return array of raw key values pairs (no loaded app_domain_DomainObjects) 
	 */
	public function toRawArrayWithEncodingChange($from_encoding, $to_encoding)
	{
		$column_names = array_keys($this->result->getColumnNames());
		$results = array();
		foreach ($this->raw as $row)
		{
			$output = array();
			foreach ($column_names as $column_name)
			{
//				$output[$column_name] = $row[$column_name];
				$output[$column_name] = mb_convert_encoding($row[$column_name], $to_encoding, $from_encoding);
			}
			$results[] = $output;
		}
		return $results;
	}
	
	/**
	 * Loops through a MDB2_Result object and returns the results in a 
	 * multi-dimensional associative array.
	 * @param MDB2_Result $result
	 * @return array
	 */
	public static function mdb2ResultToArray(MDB2_Result $result)
	{
		$raw = array();
		while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$raw[] = $row;
			$result->nextResult();
		}
		return $raw;
	}

	public static function merge(app_mapper_Collection $collection1, app_mapper_Collection $collection2)
	{
		if (get_class($collection1) == get_class($collection2))
		{
			$class_name = get_class($collection1);
			$new_collection = new $class_name();
			foreach ($collection1 as $c)
			{
				$new_collection->add($c);
			}
			foreach ($collection2 as $c)
			{
				$new_collection->add($c);
			}
			return $new_collection;
		}
		else
		{
			throw new Exception('Collection types are not the same');
		}  
	}

}

?>