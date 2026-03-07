<?php

/**
 * illumen_Graph Class<br />
 * Provides storage of standard colours and layouts used when creating graphs.
 * @access public
 * @package myPRM.net
 */
 
class illumen_Graph
{
	
	
	/**
	* Returns the selected color from the array using modulus arithmetic to ensure a value is 
	* always returned by looping back to the start of the color array.
	* @param integer index of the color to return
	* @return string RGB color
	* @access public
	* @static
	*/
	public static function getColor($i)
	{
		$colors = array('#9DDFA9', '#A0E4D9', '#A4C5E8', '#B3A7ED', '#E6AAF1', '#F6ADD2', '#FABDB1', '#FFF2B4');
		$index = $i % count($colors);
		return $colors[$index];
	}
	
	
	/**
	* Returns an array of colors.
	* @param integer the number of colors in the array
	* @return array string RGB colors
	* @access public
	* @static
	*/
	public static function getColors($number)
	{
		$colors = array();
		for ($i = 0; $i < $number; $i++)
		{
			$colors[] = Graph::getColor($i);
		}
		return $colors;
	}
	
	/**
	* Returns a color to use for the background of graph plot areas.
	* @return string RGB color
	* @access public
	* @static
	*/
	public static function getColorPlotBackground()
	{
		return '#E3E7F2';
	}


	/**
	* Returns a color to use for the background of graph title areas.
	* @return string RGB color
	* @access public
	* @static
	*/
	public static function getColorTitleBackground()
	{
		return '#C9D0E6';
	}

}


?>