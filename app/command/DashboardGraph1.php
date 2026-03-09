<?php

/**
 * Defines the app_command_DashboardGraph2 class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

/**
 * @package Alchemis
 */
class app_command_DashboardGraph1 extends app_command_Command
{
	private static function getBasePath()
	{
		return defined('APP_DIRECTORY')
			? rtrim(APP_DIRECTORY, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
			: dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR;
	}

	/** 1x1 transparent PNG so img never breaks layout. */
	private static function sendPlaceholderPng()
	{
		while (ob_get_level()) {
			ob_end_clean();
		}
		header('Content-Type: image/png');
		header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
		header('Pragma: no-cache');
		header('Expires: 0');
		echo base64_decode(
			'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg=='
		);
		exit(0);
	}

	/** Visible placeholder when graph fails (so user sees something). */
	private static function sendErrorPng()
	{
		self::sendVisiblePng('Graph unavailable');
	}

	/** Visible placeholder when there is no data to show. */
	private static function sendNoDataPng()
	{
		self::sendVisiblePng('No data');
	}

	/**
	 * Draw a simple horizontal bar chart with GD only (fallback when JpGraph fails).
	 * Matches live style: title, two horizontal bars (Set, Attended), blue bars, grid, value labels.
	 * @param int[] $values two values [Set, Attended]
	 */
	private static function sendSimpleBarChartPng(array $values)
	{
		while (ob_get_level()) {
			ob_end_clean();
		}
		header('Content-Type: image/png');
		header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
		header('Pragma: no-cache');
		header('Expires: 0');
		$w = 530;
		$h = 220;
		$img = @imagecreatetruecolor($w, $h);
		if ($img === false) {
			self::sendVisiblePng('Graph unavailable');
		}
		$bg = imagecolorallocate($img, 249, 249, 249);
		$bar = imagecolorallocate($img, 164, 197, 232); // #A4C5E8
		$text = imagecolorallocate($img, 51, 51, 51);
		$grid = imagecolorallocate($img, 232, 232, 232);
		$frame = imagecolorallocate($img, 224, 224, 224);
		imagefill($img, 0, 0, $bg);
		// Frame
		imagerectangle($img, 0, 0, $w - 1, $h - 1, $frame);
		$max = max(1, max($values));
		$marginL = 90;
		$marginR = 52;
		$plotW = $w - $marginL - $marginR;
		$barH = 38;
		$gap = 22;
		$y1 = 56;
		$y2 = $y1 + $barH + $gap;
		$labels = array('Set', 'Attended');
		// No title inside image – heading is in the page above
		// Horizontal grid line at top of plot area
		imageline($img, $marginL, $y1, $marginL + $plotW, $y1, $grid);
		imageline($img, $marginL, $y2 + $barH, $marginL + $plotW, $y2 + $barH, $grid);
		foreach (array(0, 1) as $i) {
			$y = $i === 0 ? $y1 : $y2;
			$val = isset($values[$i]) ? (int)$values[$i] : 0;
			$barW = $max > 0 ? (int)round(($val / $max) * $plotW) : 0;
			if ($barW > 0) {
				imagefilledrectangle($img, $marginL, $y, $marginL + $barW, $y + $barH, $bar);
				imagerectangle($img, $marginL, $y, $marginL + $barW, $y + $barH, $grid);
			}
			imagerectangle($img, $marginL, $y, $marginL + $plotW, $y + $barH, $grid);
			imagestring($img, 4, 14, $y + 12, $labels[$i], $text);
			imagestring($img, 4, $marginL + $plotW + 8, $y + 12, (string)$val, $text);
		}
		imagepng($img);
		imagedestroy($img);
		exit(0);
	}

	private static function sendVisiblePng($text)
	{
		while (ob_get_level()) {
			ob_end_clean();
		}
		header('Content-Type: image/png');
		header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
		header('Pragma: no-cache');
		header('Expires: 0');
		if (extension_loaded('gd')) {
			$w = 320;
			$h = 48;
			$img = @imagecreatetruecolor($w, $h);
			if ($img !== false) {
				$gray = imagecolorallocate($img, 248, 248, 248);
				$dark = imagecolorallocate($img, 120, 120, 120);
				imagefill($img, 0, 0, $gray);
				imagestring($img, 3, 10, 16, $text, $dark);
				imagepng($img);
				imagedestroy($img);
				exit(0);
			}
		}
		self::sendPlaceholderPng();
	}

	/**
	 * Get progress data for "Summary of progress this month to date" graph.
	 * Uses client_id from request (same as Dashboard dropdown) and current month so live/local match.
	 * Returns [meeting_set_count, meeting_attended_count] for current user/client and month.
	 * Falls back to [0, 0] if no data.
	 *
	 * @param app_controller_Request $request
	 * @return array two-element array of integers
	 */
	private static function getProgressData(app_controller_Request $request)
	{
		try {
			if (!class_exists('Auth_Session', false)) {
				$base = self::getBasePath();
				require_once $base . 'include' . DIRECTORY_SEPARATOR . 'Auth' . DIRECTORY_SEPARATOR . 'Session.php';
			}
			$session = Auth_Session::singleton();
			$user = $session->getSessionUser();
			if (empty($user['id'])) {
				return array(0, 0);
			}
			$nbm_id = (int) $user['id'];
			$client_id = $request->getProperty('client_id');
			if ($client_id === null || $client_id === '' || $client_id === '0') {
				$clients = app_domain_Client::findByUserId($nbm_id);
				if (empty($clients) || !isset($clients[0]['id'])) {
					return array(0, 0);
				}
				$client_id = (int) $clients[0]['id'];
			} else {
				$client_id = (int) $client_id;
			}
			$year_month = date('Ym');
			$actuals = app_domain_Client::findActualsByClientIdAndYearmonth($client_id, $year_month);
			if (empty($actuals) || !is_array($actuals)) {
				return array(0, 0);
			}
			$set = isset($actuals['meeting_set_count']) ? (int) $actuals['meeting_set_count'] : 0;
			$attended = isset($actuals['meeting_attended_count']) ? (int) $actuals['meeting_attended_count'] : 0;
			return array($set, $attended);
		} catch (Throwable $e) {
			return array(0, 0);
		}
	}

	/** Check if GD extension is loaded */
	private static function checkGd()
	{
		if (!extension_loaded('gd')) {
			while (ob_get_level()) { ob_end_clean(); }
			header('Content-Type: image/png');
			header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
			header('Pragma: no-cache');
			header('Expires: 0');

			$w = 400;
			$h = 50;
			$img = imagecreatetruecolor($w, $h);
			$bg = imagecolorallocate($img, 248, 248, 248);
			$textColor = imagecolorallocate($img, 255, 0, 0);
			imagefill($img, 0, 0, $bg);
			imagestring($img, 3, 10, 16, 'GD library not installed!', $textColor);
			imagepng($img);
			imagedestroy($img);
			exit(0);
		}
	}

	/**
	 * Bypass parent execute so we never run initSessionUser(); graph must always output PNG only.
	 */
	public function execute(app_controller_Request $request)
	{
		$this->init($request);
		exit(0);
	}

	public function doExecute(app_controller_Request $request)
	{
		$this->init($request);
		return self::statuses('CMD_OK');
	}

	protected function init(app_controller_Request $request)
	{
		while (ob_get_level()) {
			ob_end_clean();
		}
		ob_start();

		// Check GD before doing anything
		self::checkGd();

		// Suppress deprecation/notice so JpGraph renders; restore after
		$prevErrorReporting = error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR);
		$prevHandler = set_error_handler(function ($errno, $errstr, $errfile, $errline) {
			// Suppress deprecations and notices so graph still outputs
			if ($errno === E_DEPRECATED || $errno === E_USER_DEPRECATED || $errno === E_NOTICE || $errno === E_STRICT) {
				return true;
			}
			return false;
		});

		try {
			$base = self::getBasePath();
			$jpgraphDir = $base . 'include' . DIRECTORY_SEPARATOR . 'jpgraph-2.2';
			set_include_path($jpgraphDir . PATH_SEPARATOR . get_include_path());

			require_once($base . 'include/jpgraph-2.2/jpgraph.php');
			require_once($base . 'include/jpgraph-2.2/jpgraph_bar.php');
			require_once($base . 'include/jpgraph-2.2/jpgraph_pie.php');
			require_once($base . 'include/Illumen/Graph.php');

			$attended = self::getProgressData($request);
			if (!is_array($attended) || count($attended) === 0) {
				$attended = array(0, 0);
			}

			$topMargin    = 24;
			$bottomMargin = 36;
			$leftMargin   = 36;
			$rightMargin  = 36;
			$width        = 530;
			$height       = 220;

			$graph = new Graph($width, $height, 'auto');
			$graph->SetColor('#F9F9F9');
			$graph->SetMarginColor('#F9F9F9');
			$graph->SetFrame(true, '#E0E0E0', 1);
			$graph->SetBox(false);

			$graph->SetScale('textint');
			$graph->Set90AndMargin($leftMargin, $rightMargin, $topMargin, $bottomMargin);

			$graph->yaxis->SetTickLabels(array('Set', 'Attended'));
			$graph->yaxis->SetLabelMargin(6);
			$graph->yaxis->SetFont(FF_FONT1, FS_NORMAL, 9);
			$graph->yaxis->SetColor('#333333');
			$graph->xaxis->SetFont(FF_FONT1, FS_NORMAL, 9);
			$graph->xaxis->SetColor('#333333');

			$graph->ygrid->Show(true);
			$graph->ygrid->SetColor('#E8E8E8');
			$graph->ygrid->SetWeight(1);
			$graph->xgrid->Show(false);

			$b1plot = new BarPlot($attended);
			$b1plot->SetFillColor('#A4C5E8');
			$b1plot->SetWidth(0.6);
			$b1plot->value->Show(true);
			$b1plot->value->SetFormat('%d');
			$b1plot->value->SetColor('#333333');
			$b1plot->value->SetFont(FF_FONT1, FS_NORMAL, 9);
			$b1plot->value->SetMargin(4);

			$graph->Add($b1plot);

			while (ob_get_level()) {
				ob_end_clean();
			}
			$graph->Stroke();
			exit(0);
		} catch (Throwable $e) {
			while (ob_get_level()) {
				ob_end_clean();
			}
			// Fallback: draw simple bar chart with GD so user always sees a chart
			$fallbackData = self::getProgressData($request);
			if (is_array($fallbackData) && count($fallbackData) >= 2) {
				self::sendSimpleBarChartPng($fallbackData);
			}
			self::sendErrorPng();
		} finally {
			error_reporting($prevErrorReporting);
			if ($prevHandler !== null) {
				set_error_handler($prevHandler);
			}
		}
	}
}

?>