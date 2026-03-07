<?php

/**
 * Defines the app_domain_HelperFactory class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/mapper/Collections.php');

/**
 * @package Framework
 */
class app_domain_HelperFactory
{
	/**
	 * @param string $type class name
	 * @return app_mapper_Mapper the mapper object (data layer) which handles the transition from 
	 * database to object.
	 * @throws app_base_AppException
	 */
	static function getFinder($type)
	{
		$type = preg_replace('/^.*_/', '', $type);
		$mapper = "app_mapper_{$type}Mapper";
		if (class_exists($mapper))
		{
			return new $mapper();
		}
		else
		{
			$file = 'app' . DIRECTORY_SEPARATOR . 'mapper' . DIRECTORY_SEPARATOR . "{$type}Mapper.php";
			if (file_exists($file))
			{
				require_once($file);
				if (class_exists($mapper))
				{
					return new $mapper();
				}
			}
		}
		throw new app_base_AppException("Unknown: $mapper");
	}
	
	/**
	 * @param string $type class name
	 * @return app_mapper_Collection the collection object (data layer).
	 * @throws app_base_AppException
	 */
	static function getCollection($type)
	{
		$type = preg_replace('/^.*_/', '', $type);
		$collection = "app_mapper_{$type}Collection";
		if (class_exists($collection))
		{
			return new $collection();
		}
		throw new app_base_AppException("Unknown: $collection");
	}

	/**
	 * @param string $type class name
	 * @return app_mapper_ReaderMapper the mapper object (data layer) which handles the transition from 
	 * database to object.
	 * @throws app_base_AppException
	 */
	static function getReader($type)
	{
		$type = preg_replace('/^.*_/', '', $type);
		$reader = "app_mapper_{$type}Mapper";
		if (class_exists($reader))
		{
			return new $reader();
		}
		throw new app_base_AppException("Unknown: $reader");
	}

}

?>