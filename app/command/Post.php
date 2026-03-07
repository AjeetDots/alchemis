<?php

require_once('include/Utils/Utils.class.php');

use app_controller_Response as Response;
use Illuminate\Database\Capsule\Manager as DB;

class app_command_Post extends app_command_BaseCommand
{
    public function addDataSources()
    {
        set_time_limit(0);
        DB::connection()->disableQueryLog();
        $time_start = microtime(true);
        $linkedIn = app_model_DataSource::where('description', 'Linked-in')->first();
        
        app_model_Post::
            whereHas('contact', function ($query) {
                $query->whereNotNull('linked_in');
            })
            ->update([
                'data_source_id' => $linkedIn->id,
                'data_source_updated' => Utils::getTimestamp(),
            ]);

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        return Response::dump('Done in ' . $time . ' seconds.');
    }
}