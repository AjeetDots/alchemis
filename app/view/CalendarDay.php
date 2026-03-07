<?php

/**
 * Defines the app_view_CalendarDay class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_CalendarDay extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('date', $this->request->getObject('date'));
		
		// Set display options
		$this->smarty->assign('display', $this->request->getObject('display'));
		
		$this->smarty->assign('client_id', $this->request->getObject('client_id'));
		$this->smarty->assign('nbm_id', $this->request->getObject('nbm_id'));
		
		
		// Get activities
		$entries = $this->request->getObject('entries');
		$this->smarty->assign('day_data', $entries);
		
		
		// Get activities
		$month_data = $this->request->getObject('month_data');
		$this->smarty->assign('month_data', $month_data);
		$this->smarty->assign('year', $this->request->getObject('year'));
		$this->smarty->assign('month', $this->request->getObject('month'));
		$this->smarty->assign('day', $this->request->getObject('day'));
		
		
		$year_data = $this->request->getObject('year_data');
		$this->smarty->assign('year_data', $year_data);
		
//		$data = array(	'9' => array	(0 => array(	'from'  => '09:00',
//													'to' => '13:00',
//													'title'  => 'Info Req to Dave',
//													'notes'  => 'Dave Newman requested some info on such and such. Integer nec massa. Cras massa. Duis orci dolor, pellentesque et, vehicula et, egestas non, neque. Duis iaculis. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nunc ultrices fermentum mi. Curabitur orci tellus, blandit a, pretium vitae, varius id, nisi. Suspendisse potenti. Phasellus eget mi. Duis faucibus augue sed dolor. Sed venenatis, lorem quis porta sodales, lectus orci laoreet dolor, a sodales metus lacus sit amet urna. Aliquam nonummy, quam ut vulputate nonummy, felis arcu vestibulum eros, in feugiat erat lectus a augue. Quisque elementum lectus et ipsum. Nullam blandit. Nulla venenatis. Proin nibh. Curabitur turpis pede, dignissim nec, eleifend non, fringilla a, dui.',
//													'type'   => 's21'),
//									1 => array	(	'from'  => '14:30',
//														'to' => '16:30',
//														'title'  => 'Call with Rob',
//														'notes'  => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec eu risus cursus nulla suscipit tristique. Morbi faucibus augue ac sem. Sed ut nisi. Morbi in massa. Aliquam laoreet elit sed felis. Vivamus ac eros id libero imperdiet congue. Sed faucibus. Nulla ac nunc. Curabitur ante enim, ultricies a, consequat et, bibendum nec, lectus. Vivamus facilisis. Aliquam diam lacus, accumsan id, eleifend ut, sagittis et, mauris. Morbi placerat cursus sem. Aliquam elementum volutpat nisi. Fusce at quam laoreet diam rhoncus imperdiet. Sed adipiscing velit. Aenean pede. Sed dignissim. Sed id purus. Donec congue. Nam lacinia volutpat mi. Duis porta quam ac mauris. Phasellus laoreet justo id enim. Nulla facilisi. Proin faucibus tempor lacus. Aliquam erat volutpat. Quisque elementum, urna id pulvinar pharetra, orci sapien commodo sapien, non vulputate justo diam quis nunc.Aliquam id turpis vitae magna vulputate eleifend. Sed fermentum. Integer ornare. Duis vestibulum sapien sed erat. Sed ac odio. Vivamus pretium, massa vel ultricies congue, urna arcu eleifend arcu, vel viverra sem augue in dui. Sed leo. Aliquam ac sapien. Cras suscipit feugiat neque. Vestibulum vehicula. Integer id mi sit amet nisl facilisis porta. Integer vitae diam vel mi luctus fringilla. Maecenas fermentum ultricies tellus. Nam orci elit, malesuada in, vehicula vel, mattis nec, mi. Ut luctus. Suspendisse potenti. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Vestibulum vitae enim id tortor nonummy fringilla. Etiam dapibus lectus malesuada urna. Donec euismod semper libero. Curabitur justo purus, aliquet id, dapibus viverra, laoreet ac, diam. Fusce nunc nulla, faucibus ac, posuere sit amet, venenatis vitae, orci. Maecenas egestas sem sit amet nisl. Etiam arcu. Duis quis risus ut erat ultrices venenatis. Donec hendrerit, dolor vel molestie sagittis, ligula ligula vulputate est, at placerat orci magna sed mi. Sed cursus nunc sed est. Praesent sem. Aenean ullamcorper vulputate enim. Integer nec massa. Cras massa. Duis orci dolor, pellentesque et, vehicula et, egestas non, neque. Duis iaculis. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nunc ultrices fermentum mi. Curabitur orci tellus, blandit a, pretium vitae, varius id, nisi. Suspendisse potenti. Phasellus eget mi. Duis faucibus augue sed dolor. Sed venenatis, lorem quis porta sodales, lectus orci laoreet dolor, a sodales metus lacus sit amet urna. Aliquam nonummy, quam ut vulputate nonummy, felis arcu vestibulum eros, in feugiat erat lectus a augue. Quisque elementum lectus et ipsum. Nullam blandit. Nulla venenatis. Proin nibh. Curabitur turpis pede, dignissim nec, eleifend non, fringilla a, dui.',
//									'type'   => 's23'
//													)
//									),
//					'15' => array	(0 => array(	'from'  => '09:00',
//												'to' => '13:00',
//												'title'  => 'Info Req to Dave',
//												'type'   => 's21'),
//									1 => array	(	'from'  => '14:30',
//														'to' => '16:30',
//														'title'  => 'Call with Rob',
//														'type'   => 's23'
//													)
//									),
//					'24' => array	(0 => array	( 	'from'  => '09:30',
//														'to' => '12:00',
//														'title'  => 'Morning with Jim',
//														'type'   => 's22'
//													),
//									1 => array	(	'from'  => '14:30',
//														'to' => '16:30',
//														'title'  => 'Call with Rob',
//														'type'   => 's23'
//													)
//									)
//				);
//		$this->smarty->assign('data', $data);
//
//
//
//		$data = array('2007-03-22' => array(	0 => array(	'from'  => '09:00',
//															'to' => '13:00',
//															'title'  => 'Info Req to Dave',
//															'notes'  => 'Dave Newman requested some info on such and such. Integer nec massa. Cras massa. Duis orci dolor, pellentesque et, vehicula et, egestas non, neque. Duis iaculis. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nunc ultrices fermentum mi. Curabitur orci tellus, blandit a, pretium vitae, varius id, nisi. Suspendisse potenti. Phasellus eget mi. Duis faucibus augue sed dolor. Sed venenatis, lorem quis porta sodales, lectus orci laoreet dolor, a sodales metus lacus sit amet urna. Aliquam nonummy, quam ut vulputate nonummy, felis arcu vestibulum eros, in feugiat erat lectus a augue. Quisque elementum lectus et ipsum. Nullam blandit. Nulla venenatis. Proin nibh. Curabitur turpis pede, dignissim nec, eleifend non, fringilla a, dui.',
//															'type'   => 's21'),
//												1 => array(	'from'  => '14:30',
//															'to' => '16:30',
//															'title'  => 'Call with Rob',
//															'notes'  => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec eu risus cursus nulla suscipit tristique. Morbi faucibus augue ac sem. Sed ut nisi. Morbi in massa. Aliquam laoreet elit sed felis. Vivamus ac eros id libero imperdiet congue. Sed faucibus. Nulla ac nunc. Curabitur ante enim, ultricies a, consequat et, bibendum nec, lectus. Vivamus facilisis. Aliquam diam lacus, accumsan id, eleifend ut, sagittis et, mauris. Morbi placerat cursus sem. Aliquam elementum volutpat nisi. Fusce at quam laoreet diam rhoncus imperdiet. Sed adipiscing velit. Aenean pede. Sed dignissim. Sed id purus. Donec congue. Nam lacinia volutpat mi. Duis porta quam ac mauris. Phasellus laoreet justo id enim. Nulla facilisi. Proin faucibus tempor lacus. Aliquam erat volutpat. Quisque elementum, urna id pulvinar pharetra, orci sapien commodo sapien, non vulputate justo diam quis nunc.Aliquam id turpis vitae magna vulputate eleifend. Sed fermentum. Integer ornare. Duis vestibulum sapien sed erat. Sed ac odio. Vivamus pretium, massa vel ultricies congue, urna arcu eleifend arcu, vel viverra sem augue in dui. Sed leo. Aliquam ac sapien. Cras suscipit feugiat neque. Vestibulum vehicula. Integer id mi sit amet nisl facilisis porta. Integer vitae diam vel mi luctus fringilla. Maecenas fermentum ultricies tellus. Nam orci elit, malesuada in, vehicula vel, mattis nec, mi. Ut luctus. Suspendisse potenti. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Vestibulum vitae enim id tortor nonummy fringilla. Etiam dapibus lectus malesuada urna. Donec euismod semper libero. Curabitur justo purus, aliquet id, dapibus viverra, laoreet ac, diam. Fusce nunc nulla, faucibus ac, posuere sit amet, venenatis vitae, orci. Maecenas egestas sem sit amet nisl. Etiam arcu. Duis quis risus ut erat ultrices venenatis. Donec hendrerit, dolor vel molestie sagittis, ligula ligula vulputate est, at placerat orci magna sed mi. Sed cursus nunc sed est. Praesent sem. Aenean ullamcorper vulputate enim. Integer nec massa. Cras massa. Duis orci dolor, pellentesque et, vehicula et, egestas non, neque. Duis iaculis. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nunc ultrices fermentum mi. Curabitur orci tellus, blandit a, pretium vitae, varius id, nisi. Suspendisse potenti. Phasellus eget mi. Duis faucibus augue sed dolor. Sed venenatis, lorem quis porta sodales, lectus orci laoreet dolor, a sodales metus lacus sit amet urna. Aliquam nonummy, quam ut vulputate nonummy, felis arcu vestibulum eros, in feugiat erat lectus a augue. Quisque elementum lectus et ipsum. Nullam blandit. Nulla venenatis. Proin nibh. Curabitur turpis pede, dignissim nec, eleifend non, fringilla a, dui.',
//															'type'   => 's23'),
//												2 => array(	'from'  => '09:00',
//															'to' => '13:00',
//															'title'  => 'Info Req to Dave',
//															'type'   => 's21')
//											),
//					'2007-03-23' => array(		0 => array(	'from'  => '09:00',
//															'to' => '13:00',
//															'title'  => 'Info Req to Dave',
//															'notes'  => 'Dave Newman requested some info on such and such. Integer nec massa. Cras massa. Duis orci dolor, pellentesque et, vehicula et, egestas non, neque. Duis iaculis. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nunc ultrices fermentum mi. Curabitur orci tellus, blandit a, pretium vitae, varius id, nisi. Suspendisse potenti. Phasellus eget mi. Duis faucibus augue sed dolor. Sed venenatis, lorem quis porta sodales, lectus orci laoreet dolor, a sodales metus lacus sit amet urna. Aliquam nonummy, quam ut vulputate nonummy, felis arcu vestibulum eros, in feugiat erat lectus a augue. Quisque elementum lectus et ipsum. Nullam blandit. Nulla venenatis. Proin nibh. Curabitur turpis pede, dignissim nec, eleifend non, fringilla a, dui.',
//															'type'   => 's22')
//											)
//					);
//		$this->smarty->assign('day_data', $data);
		
		$this->smarty->display('CalendarDay.tpl');
	}
}

?>