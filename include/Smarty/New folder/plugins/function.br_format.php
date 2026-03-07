<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {br_format} function plugin
 *
 * Type:     function<br>
 * Name:     br_format<br>
 * Input:<br>
 *           - values     (required if no options supplied) - array
 * Purpose:  Formats an adddress from the passed parameters
 * @link http://smarty.php.net/manual/en/language.function.html.options.php {html_image}
 *      (Smarty online manual)
 * @author David Carter
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 */
function smarty_function_br_format($params, &$smarty)
{
//    require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');
    $address = '';
    
    foreach($params as $_key => $_val) 
    {
    	if (!is_null($_val))
       	{
       		$address_line = trim($_val);
       		$address_line = rtrim($address_line, ',');
       		
       		if ($address_line != '')
       		{
       			$address .= $address_line . '<br />';
       		} 
       	}
    }

    return $address;

}

?>
