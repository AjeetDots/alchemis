<?php

/**
 * Defines the app_view_ManipulationView class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * Extends the base View class by adding function(s) to handle validation 
 * and help with making field values sticky.
 * @package Framework
 */
abstract class app_view_ManipulationView extends app_view_View
{
	/**
	 * Handles any validation errors which need to be assigned to smarty.
	 */
	protected function handleValidationErrors()
	{
		// Ensure the array of errors are assigned to smarty
		if ($errors = $this->request->getObject('errors'))
		{
			$this->smarty->assign('errors', $errors);
		}
	}

	/**
	 * Handles sticky fields by ensuring they're assigned to smarty.
	 */
	protected function handleStickyFields()
	{
//		echo "<p><b>app_view_ManipulationView::handleStickyFields()</b></p>";
		$fields = $this->request->getObject('fields');

		// Ensure fields are sticky
		if ($fields = $this->request->getObject('fields'))
		{
			foreach ($fields as $key => $value)
			{
//				echo "<p>$key => $value</p>";
				$this->smarty->assign($key, $value);
			}
		}
	}

}

?>