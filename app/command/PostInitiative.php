<?php

require_once('include/Utils/Utils.class.php');

use app_controller_Response as Response;
use Illuminate\Database\Capsule\Manager as DB;

class app_command_PostInitiative extends app_command_BaseCommand
{
    public function addDataSources()
    {
        set_time_limit(0);
        DB::connection()->disableQueryLog();
        $time_start = microtime(true);
        // $linkedIn = app_model_DataSource::where('description', 'Linked-in')->first();
        
        // $hasLinkedIn = app_model_PostInitiative::
        //     whereHas('post.contact', function ($query) {
        //         $query->whereNotNull('linked_in');
        //     })
        //     ->whereNull('last_communication_id')
        //     ->get();

        $now = Utils::getTimestamp();
        
        // foreach ($hasLinkedIn as $postInitiative) {
        //     $postInitiative->data_source_id = $linkedIn->id;
        //     $postInitiative->data_source_updated = $now;
        //     $postInitiative->save();

        //     $currentTag = $postInitiative->tags()->wherePivot('data_source', true)->first();

        //     if ($currentTag) {
        //         $currentTag->value = 'Linked-in';
        //         $currentTag->save();
        //     } else {
        //         $tag = new app_model_Tag(['value' => 'Linked-in', 'category_id' => 3]);
        //         $postInitiative->tags()->save($tag, ['data_source' => true]);
        //     }
        // }

        $spokenBefore = app_model_DataSource::where('description', "We've spoken before")->first();
        $count = 0;

        $hasSpokenBefore = app_model_PostInitiative::select('id')->whereNotNull('last_communication_id')->chunk(150, function ($initiatives) use (&$count) {
            foreach ($initiatives as $postInitiative) {
                $postInitiative->data_source_id = $spokenBefore->id;
                $postInitiative->data_source_updated = $now;
                $postInitiative->save();
    
                $currentTag = $postInitiative->tags()->wherePivot('data_source', true)->first();
    
                if ($currentTag) {
                    $currentTag->value = "We've spoken before";
                    $currentTag->save();
                } else {
                    $tag = new app_model_Tag(['value' => "We've spoken before", 'category_id' => 3]);
                    $postInitiative->tags()->save($tag, ['data_source' => true]);
                }
            }

            $count += 150;
            var_dump($count);
        });

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        return Response::dump('Done in ' . $time . ' seconds.');
    }
}