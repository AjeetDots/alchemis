<?php

require_once __DIR__ . '/../ajax/domain/ajaxResponse.class.php';

class app_controller_Response
{

  private $content;
  private $headers = array();

  public function setContent($content)
  {
    $this->content = $content;
  }

  public function getContent()
  {
    return $this->content;
  }

  public function setHeader($key, $value)
  {
    $this->headers[$key] = $value;
  }

  public function fire()
  {
    // For JSON responses, discard any buffered output (e.g. PHP warnings from
    // display_errors=1) so warnings never corrupt the JSON body.
    if (isset($this->headers['Content-Type']) &&
        strpos($this->headers['Content-Type'], 'application/json') !== false) {
      while (ob_get_level() > 0) {
        ob_end_clean();
      }
    }

    foreach($this->headers as $key => $value){
      header($key . ': ' . $value);
    }
    echo $this->content;
  }

  public static function view($template, $params)
  {
    $response = new app_controller_Response;
    $view = new app_view_BaseView($template, $params);
    $response->setContent($view->render());
    return $response;
  }

  public static function json($value)
  {
    $response = new app_controller_Response;
    $response->setHeader('Content-Type', 'application/json');
    $response->setContent(json_encode($value));
    return $response;
  }

  public static function csv($value, $filename = "output.csv")
  {
    $csv = $response->array2csv($value);
    return self::download($csv, $filename);
  }
  
  public static function download($value, $filename)
  {
    // Signal the browser-side startFileDownload() poller that the file is ready.
    if (!empty($_GET['download_token'])) {
      $token = preg_replace('/[^a-z0-9]/i', '', $_GET['download_token']);
      setcookie('fileDownloadToken', $token, time() + 60, '/', '', false, false);
    }

    $response = new app_controller_Response;

    $response->setHeader('Expires', '03 Jul 2001 06:00:00 GMT');
    $response->setHeader('Cache-Control', 'max-age=0, no-cache, must-revalidate, proxy-revalidate');
    // $response->setHeader('Last-Modified', $now.'GMT');
    // force download  
    $response->setHeader('Content-Type', 'application/force-download');
    $response->setHeader('Content-Type', 'application/octet-stream');
    $response->setHeader('Content-Type', 'application/download');

    // disposition / encoding on response body
    $response->setHeader('Content-Disposition', 'attachment;filename='.$filename);
    $response->setHeader('Content-Transfer-Encoding', 'binary');
    $response->setContent($value);
    return $response;
  }

  public static function table($values)
  {
    $keys = array_keys(reset($values));
    return self::view('table', [
      'keys' => $keys,
      'rows' => $values
    ]);
  }

  public static function redirect($mixed)
  {
    $response = new app_controller_Response;
    if(is_array($mixed)){
      // full url
      if(isset($mixed['url'])){
        $response->setHeader('Location', $mixed['url']);
      }
    }else{
      // mixed is a cmd
      $response->setHeader('Location', 'index.php?cmd='.$mixed);
    }
    return $response;
  }

  public static function dump($values)
  {
    $response = new app_controller_Response;
    ob_start();
    var_dump($values);
    $dump = ob_get_clean();
    $response->setContent($dump);
    return $response;
  }
  
  public static function ajaxResponse()
  {
    return new ajaxResponse();
  }

  function array2csv(array &$array)
  {
    if (count($array) == 0) {
      return null;
    }
    ob_start();
    $df = fopen("php://output", 'w');
    fputcsv($df, array_keys(reset($array)));
    foreach ($array as $row) {
      fputcsv($df, $row);
    }
    fclose($df);
    return ob_get_clean();
  }

}