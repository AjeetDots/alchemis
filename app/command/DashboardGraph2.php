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
        header('Cache-Control: no-store');
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
                ob_end_clean();
                self::sendNoDataPng();
            }

            $topMargin    = 30;
            $bottomMargin = 32;
            $leftMargin   = 30;
            $rightMargin  = 30;
            $width        = 390;
            $height       = 250;

            $graph = new Graph($width, $height, 'auto');
            $graph->SetColor('#F9F9F9');
            $graph->SetMarginColor($bgcolor);
            $graph->SetScale('textint');
            $graph->SetBox(false);
            $graph->SetFrame(true, $bgcolor, 1);

            $graph->xaxis->SetTickLabels($labels);

            $b1plot = new BarPlot($attended);
            $b1plot->SetFillColor(Illumen_Graph::getColor(2));

            $graph->legend->SetLayout(LEGEND_HOR);
            $graph->legend->Pos(0.5, 0.95, 'center', 'bottom');

            $graph->Add($b1plot);

            $file = $request->getProperty('file');
            ob_end_clean();
            if ($file) {
                $reportDir = defined('APP_DIRECTORY') ? APP_DIRECTORY . 'app/report/tmp/' : 'app/report/tmp/';
                $graph->Stroke($reportDir . $file);
            } else {
                $graph->Stroke();
            }
            exit(0);
        } catch (Throwable $e) {
            ob_end_clean();
            self::sendErrorPng();
        }
    }
}

?>
