<?php

/**
 * Defines the app_command_ManipulationCommand class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

require_once('app/command/Command.php');

/**
 * Extends the base Command class by adding function(s) to handle validation 
 * and help with making field values sticky.
 * @package Framework
 */
abstract class app_command_ManipulationCommand extends app_command_Command
{
	/**
	 * Handle any validation errors by assigning them to an error object to be 
	 * passed to the view and adding them as feedback.
	 * @param app_controller_Request $request the object from which the form 
	 *        values can be accessed
	 * @param array $errors array of errors
	 */
	protected function handleValidationErrors(app_controller_Request $request, $errors)
	{
		// Ensure each error is assigned as feedback back to the view
		foreach ($errors as $key => $error)
		{
			if (empty($error))
			{
				unset($errors[$key]);
			}
			else
			{
				$request->setValidationError(true);
				$request->addFeedback($error->getMessage());
			}
		}
		$request->setObject('errors', $errors);
	}

	/**
	 * Takes a list of the fields being used and re-assigns any values entered 
	 * to make sticky.
	 * @param app_controller_Request $request 
	 * @param array $sticky_fields the names of fields to make sticky
	 */
	protected function doHandleStickyFields(app_controller_Request $request, $sticky_fields)
	{
		$fields = array();
		foreach ($sticky_fields as $item)
		{
			$fields[$item] = $request->getProperty($item);
		}
		$request->setObject('fields', $fields);
	}
}

?>