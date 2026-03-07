<?php
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Collection;

class app_lib_Tps
{
    public static function check($number,$refresh = FALSE)
    {
        set_time_limit(0);
        // DB::connection()->disableQueryLog();
        $number = preg_replace('/[^0-9\/]/', '', $number);
        $numbers = explode('/', $number);

        $toReturn = [];
        $timeToCompare = strtotime("-365 days midnight");
        if(sizeof($numbers) > 0){
            $numbers = array_unique($numbers,SORT_STRING);
            foreach($numbers as $number){
                if($number === ""){
                    continue;
                }
                $numberDetails = \app_model_TpsStatus::where('telephone', $number)->orderBy('updated_at','ASC')->first();
                $fetchDetails = FALSE;
                $status = "Not Checked";
                $updatedAt = 0;
                if($refresh){
                    $fetchDetails = TRUE;
                }
                if($numberDetails){
                    $fetchDetails = FALSE;
                    $status = $numberDetails->tps_status;
                    $updatedAt = strtotime($numberDetails->updated_at);
                    if($timeToCompare > $updatedAt || $refresh){
                        $fetchDetails = TRUE;
                    }
                }
                if($fetchDetails){
                    list($headerRes,$statusReturned) = SELF::checkNumber($number);
                    // Get user information from the session
                    $session = Auth_Session::singleton();
                    $user = $session->getSessionUser();

                    try{
                        if($numberDetails){
                            $numberDetails->tps_status = $statusReturned;
                            $numberDetails->updated_by = isset($user['id'])?$user['id']:0;
                            $numberDetails->updated_at = date('Y-m-d H:i:s');
                            $numberDetails->save();
                        }
                        else{
                            // create number - as not exists
                            $numberDetails = \app_model_TpsStatus::create([
                                'telephone' => (string) $number,
                                'tps_status' => $statusReturned,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                                'updated_by' => isset($user['id'])?$user['id']:0,
                            ]);
                        }
                        $status = $statusReturned;
                        $updatedAt = time();
                    }
                    catch(\Exception $e){
                        $e->getMessage();
                    }
                }
                $style = ($status == "safe")?"color:green;":"color:red;font-weight:bold;";
                $style = ($status == "Not Checked")?"":$style;
                $toReturn[] = [
                    "number" => $number,
                    "status" => $status,
                    "style" => $style,
                    "updated_at" => $updatedAt,
                    "tpsStatus" => "(TPS: ".ucwords($status)." On ".date("d/M/Y, H:i", $updatedAt)." )",
                ];
            }
            return $toReturn;
        }else{
            return [];
        }
        
    }
    
    
    private static function checkNumber($number)
    {
        
        $token = "1552ae8042916ecc04051b7d3cef708a";
        $url = "https://121prodata.co.uk/api/?token={$token}&output=text&number={$number}";
        //check curl exists
        if (!function_exists('curl_version')) {
            throw new Exception('curl not installed');
        }
        
        //connect to URL given
        $ch = curl_init($url);
        if ($ch === FALSE) {
            throw new Exception('Couldnt connect');
        }
        
        //set multiple cURL options
        curl_setopt_array(
            $ch,
            [
                //return the response given by the 121 server
                CURLOPT_RETURNTRANSFER => TRUE,
                //amount of time to allow the connection to run for
                CURLOPT_TIMEOUT_MS => 1000,
                //ensures the hostname matches the certificate
                CURLOPT_SSL_VERIFYHOST => 2,
                /**
                * This code will be more secure if the following is set to TRUE however it can
                * cause the code to fail.
                *
                * You may get an error as follows:
                *        SSL certificate problem, verify that the CA cert is OK.
                */
                CURLOPT_SSL_VERIFYPEER => FALSE,
            ]
        );
        
        //execute cURL connection
        $result = curl_exec($ch);
        
        //cURL failed for some reason
        if ($result === FALSE) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }
        
        //get the HTTP response code
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        //return the HTTP response and the result for processing
        return array($http_status, $result);
    }
}