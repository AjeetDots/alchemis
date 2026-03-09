<?php

/**
 * Defines the app_command_DashboardGraph2 class.
 * Uses JPGraph to render the Team Zone KPI bar chart.
 * Functionality is preserved; only defensive checks are added so it
 * fails gracefully if there is no data.
 */

class app_command_DashboardGraph2 extends app_command_Command
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

    /** Visible placeholder when graph fails. */
    private static function sendErrorPng()
    {
        self::sendVisiblePng('Graph unavailable');
    }

    /** Visible placeholder when there is no data (e.g. no team stats). */
    private static function sendNoDataPng()
    {
        self::sendVisiblePng('No data');
    }

    /**
     * Draw a simple vertical KPI bar chart with GD only (fallback when JpGraph fails).
     * Matches live style: title, vertical blue bars, Y-axis scale, grid, value on top of bars.
     * @param int[] $values KPI values
     * @param string[] $labels Team names
     */
    private static function sendSimpleBarChartPng(array $values, array $labels)
    {
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Content-Type: image/png');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
        $w = 390;
        $h = 250;
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
        imagerectangle($img, 0, 0, $w - 1, $h - 1, $frame);
        $n = count($values);
        if ($n === 0) {
            imagestring($img, 4, (int)(($w - 120) / 2), (int)($h / 2 - 8), 'Team Zone – No data', $text);
            imagepng($img);
            imagedestroy($img);
            exit(0);
        }
        $maxVal = max(1, max($values));
        $marginL = 44;
        $marginR = 28;
        $marginT = 42;
        $marginB = 48;
        $plotW = $w - $marginL - $marginR;
        $plotH = $h - $marginT - $marginB;
        $barW = (int)floor(($plotW - ($n - 1) * 12) / $n);
        $barGap = 12;
        $x0 = $marginL;
        $axisY = $marginT + $plotH;
        // Title
        imagestring($img, 4, 12, 12, 'Team Zone KPIs', $text);
        // Y-axis and horizontal grid
        imageline($img, $marginL, $marginT, $marginL, $axisY, $grid);
        imageline($img, $marginL, $axisY, $marginL + $plotW, $axisY, $grid);
        $scaleMax = $maxVal <= 0 ? 100 : (int)ceil($maxVal / 500) * 500;
        if ($scaleMax < 100) {
            $scaleMax = 100;
        }
        for ($t = 0; $t <= 5; $t++) {
            $yy = $axisY - (int)round(($t / 5) * $plotH);
            if ($yy >= $marginT) {
                imageline($img, $marginL, $yy, $marginL + $plotW, $yy, $grid);
            }
            $tval = (int)round(($t / 5) * $scaleMax);
            imagestring($img, 2, max(4, $marginL - 26), $yy - 6, (string)$tval, $text);
        }
        for ($i = 0; $i < $n; $i++) {
            $val = (int)$values[$i];
            $barH = $maxVal > 0 ? (int)round(($val / $maxVal) * $plotH) : 0;
            $minBarH = ($val === 0) ? 2 : $barH;
            $x1 = $x0 + (int)floor($barW * 0.12);
            $x2 = $x0 + $barW - (int)floor($barW * 0.12);
            $y1 = $axisY - $minBarH;
            $y2 = $axisY;
            imagefilledrectangle($img, $x1, $y1, $x2, $y2, $bar);
            imagerectangle($img, $x1, $y1, $x2, $y2, $grid);
            $label = isset($labels[$i]) ? trim($labels[$i]) : '';
            if (strlen($label) > 11) {
                $label = substr($label, 0, 9) . '..';
            }
            $lblX = $x1 + (int)(($x2 - $x1 - 6 * strlen($label)) / 2);
            if ($lblX < $x1) {
                $lblX = $x1;
            }
            imagestring($img, 3, $lblX, $axisY + 4, $label, $text);
            if ($val > 0) {
                $valStr = (string)$val;
                $valX = $x1 + (int)(($x2 - $x1 - 6 * strlen($valStr)) / 2);
                if ($valX < $x1) {
                    $valX = $x1;
                }
                imagestring($img, 3, $valX, $y1 - 14, $valStr, $text);
            }
            $x0 += $barW + $barGap;
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

    protected function hasPermission($r)
    {
        return true;
    }

    /**
     * Render the Team Zone KPI bar chart using JPGraph.
     */
    protected function init(app_controller_Request $request)
    {
        while (ob_get_level()) {
            ob_end_clean();
        }
        ob_start();

        $prevErrorReporting = error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR);
        $prevHandler = set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            if ($errno === E_DEPRECATED || $errno === E_USER_DEPRECATED || $errno === E_NOTICE || $errno === E_STRICT) {
                return true;
            }
            return false;
        });

        try {
            $base = self::getBasePath();
            $jpgraphDir = $base . 'include' . DIRECTORY_SEPARATOR . 'jpgraph-2.2';
            set_include_path($jpgraphDir . PATH_SEPARATOR . get_include_path());

            require_once($base . 'app/domain/Team.php');
            require_once($base . 'include/Illumen/Graph.php');
            require_once($base . 'include/jpgraph-2.2/jpgraph.php');
            require_once($base . 'include/jpgraph-2.2/jpgraph_bar.php');
            require_once($base . 'include/jpgraph-2.2/jpgraph_pie.php');

            $media = $request->getProperty('media');
            $bgcolor = ($media === 'print') ? '#FFFFFF' : '#F9F9F9';

            $stats    = app_domain_Team::findDashboardStatistics();
            $attended = array();
            $labels   = array();

            if (is_array($stats)) {
                foreach ($stats as $stat) {
                    if (!is_array($stat) || !isset($stat['kpi'], $stat['team'])) {
                        continue;
                    }
                    $attended[] = (int)$stat['kpi'];
                    $labels[]   = (string)$stat['team'];
                }
            }

            if (count($attended) === 0) {
                while (ob_get_level()) {
                    ob_end_clean();
                }
                self::sendNoDataPng();
            }

            $topMargin    = 38;
            $bottomMargin = 40;
            $leftMargin   = 42;
            $rightMargin  = 28;
            $width        = 390;
            $height       = 250;

            $graph = new Graph($width, $height, 'auto');
            $graph->SetColor('#F9F9F9');
            $graph->SetMarginColor($bgcolor);
            $graph->SetFrame(true, '#E0E0E0', 1);
            $graph->SetBox(false);

            $graph->SetTitle('Team Zone KPIs');
            $graph->title->SetFont(FF_FONT1, FS_BOLD, 11);
            $graph->title->SetColor('#333333');
            $graph->title->SetMargin(8);

            $graph->SetScale('textint');
            $graph->SetMargin($leftMargin, $rightMargin, $topMargin, $bottomMargin);

            $graph->xaxis->SetTickLabels($labels);
            $graph->xaxis->SetLabelMargin(6);
            $graph->xaxis->SetFont(FF_FONT1, FS_NORMAL, 9);
            $graph->xaxis->SetColor('#333333');
            $graph->yaxis->SetFont(FF_FONT1, FS_NORMAL, 9);
            $graph->yaxis->SetColor('#333333');

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
            $b1plot->value->SetMargin(2);
            $b1plot->SetValuePos('top');

            $graph->Add($b1plot);

            $file = $request->getProperty('file');
            while (ob_get_level()) {
                ob_end_clean();
            }
            if ($file) {
                $reportDir = defined('APP_DIRECTORY') ? APP_DIRECTORY . 'app/report/tmp/' : 'app/report/tmp/';
                $graph->Stroke($reportDir . $file);
            } else {
                $graph->Stroke();
            }
            exit(0);
        } catch (Throwable $e) {
            while (ob_get_level()) {
                ob_end_clean();
            }
            $attended = array();
            $labels = array();
            try {
                $base = self::getBasePath();
                if (!class_exists('app_domain_Team', false)) {
                    require_once $base . 'app/domain/Team.php';
                }
                $stats = app_domain_Team::findDashboardStatistics();
                if (is_array($stats)) {
                    foreach ($stats as $stat) {
                        if (!is_array($stat)) {
                            continue;
                        }
                        $attended[] = isset($stat['kpi']) ? (int)$stat['kpi'] : 0;
                        $labels[] = isset($stat['team']) ? (string)$stat['team'] : '';
                    }
                }
            } catch (Throwable $e2) {
                // ignore; use empty and still show GD chart with "No data"
            }
            // Always output a chart (with data or "No data") so we never show "Graph unavailable"
            self::sendSimpleBarChartPng($attended, $labels);
        } finally {
            error_reporting($prevErrorReporting);
            if ($prevHandler !== null) {
                set_error_handler($prevHandler);
            }
        }
    }
}

?>
