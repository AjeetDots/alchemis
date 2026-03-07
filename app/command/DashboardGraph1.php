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
		header('Cache-Control: no-store');
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

	private static function sendVisiblePng($text)
	{
		while (ob_get_level()) {
			ob_end_clean();
		}
		header('Content-Type: image/png');
		header('Cache-Control: no-store');
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

	/** Check if GD extension is loaded */
	private static function checkGd()
	{
		if (!extension_loaded('gd')) {
			while (ob_get_level()) { ob_end_clean(); }
			header('Content-Type: image/png');
			header('Cache-Control: no-store');

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

		try {
			$base = self::getBasePath();
			$jpgraphDir = $base . 'include' . DIRECTORY_SEPARATOR . 'jpgraph-2.2';
			set_include_path($jpgraphDir . PATH_SEPARATOR . get_include_path());

			require_once($base . 'include/jpgraph-2.2/jpgraph.php');
			require_once($base . 'include/jpgraph-2.2/jpgraph_bar.php');
			require_once($base . 'include/jpgraph-2.2/jpgraph_pie.php');
			require_once($base . 'include/Illumen/Graph.php');

			$attended = array(20, 30);

			$topMargin    = 30;
			$bottomMargin = 32;
			$leftMargin   = 30;
			$rightMargin  = 30;
			$width        = 530;
			$height       = $topMargin + $bottomMargin + (max(count($attended), 1) * 10);

			if (!is_array($attended) || count($attended) === 0) {
				ob_end_clean();
				self::sendNoDataPng();
			}

			$graph = new Graph($width, $height, 'auto');
			$graph->SetColor(Illumen_Graph::getColorPlotBackground());
			$graph->SetColor('#F9F9F9');
			$graph->SetMarginColor('#F9F9F9');

			$graph->SetScale('textint');
			$graph->SetFrame(true, '#F9F9F9', 1);

			$graph->Set90AndMargin($leftMargin, $rightMargin, $topMargin, $bottomMargin);

			$b1plot = new BarPlot($attended);
			$b1plot->SetFillColor(Illumen_Graph::getColor(2));

			$graph->legend->SetLayout(LEGEND_HOR);
			$graph->legend->Pos(0.5, 0.95, 'center', 'bottom');

			$graph->Add($b1plot);

			ob_end_clean();
			$graph->Stroke();
			exit(0);
		} catch (Throwable $e) {
			ob_end_clean();
			self::sendErrorPng();
		}
	}
}

?>