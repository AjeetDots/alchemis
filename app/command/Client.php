<?php

use app_controller_Response as Response;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Collection;

ini_set('display_errors', 'On');
error_reporting(E_ERROR);

class app_command_Client extends app_command_BaseCommand
{
  
  public function migrateCommunications()
  {
    // ./command run Client/migrateCommunications from:547 to:1001 date:
    set_time_limit(0);
    DB::connection()->disableQueryLog();
    $from = $this->request->input->get('from');
    $to = $this->request->input->get('to');
    $date = $this->request->input->get('date');
    
    $fromClient = app_model_Clients::find($from);
    $fromInitiative = $fromClient->campaign->initiative;
    $toClient = app_model_Clients::find($to);
    $toInitiative = $toClient->campaign->initiative;
    
    foreach ($fromInitiative->postInitiatives as $postInitiative) {
      if (!$postInitiative->communications()->count()) continue;
      
      // check if post exists in toClient
      $p = $toInitiative->postInitiatives()->where('post_id', $postInitiative->post_id)->first();
      
      // if not create
      if (!$p) {
        $fields = $postInitiative->toArray();
        $fields['initiative_id'] = $toInitiative->id;
        $p = app_model_PostInitiative::create($fields);
      }
      
      // move communications to new post initiative
      $postInitiative->communications()
        ->where('communication_date', '>=', $date)
        ->update([
          'post_initiative_id' => $p->id
        ]);
      
      // update post initiaves last communication etc
      $lastCommunication = $postInitiative->communications()->orderBy('communication_date', 'desc')->first();
      $postInitiative->last_communication_id = $lastCommunication->id;
      $postInitiative->save();
      
      $lastEffective = $postInitiative->communications()->where('is_effective', true)->orderBy('communication_date', 'desc')->first();
      $postInitiative->last_effective_communication_id = $lastEffective->id;
      $postInitiative->save();
      
    }
    return Response::dump('done');
  }
  
  public function migrateNotesAndTags()
  {
    set_time_limit(0);
    DB::connection()->disableQueryLog();
    
    $postInitiatives = new Collection(DB::select("
      select pi.id, pi2.id as new_id from tbl_post_initiatives pi
      join tbl_post_initiatives pi2 on pi2.post_id = pi.post_id and pi2.initiative_id = 980
      where 
      pi.initiative_id = 547
      and (
      select count(*) from tbl_communications c
      where c.post_initiative_id = pi.id
      and c.communication_date >= '2016-05-01'
      ) = 0
    "));
    $postInitiatives = $postInitiatives->keyBy('id');
    
    $notes = DB::select("
    select * from tbl_post_initiative_notes
    where post_initiative_id IN (
    select id from tbl_post_initiatives pi
    where 
    pi.initiative_id = 547
    and created_at >= '2016-05-01'
    and (
    select count(*) from tbl_communications c
    where c.post_initiative_id = pi.id
    and c.communication_date >= '2016-05-01'
    ) = 0
    )
    ");
    
    foreach ($notes as $note) {
      
      if (isset($postInitiatives[$note['post_initiative_id']])) {
        $p = $postInitiatives[$note['post_initiative_id']];
        DB::table('tbl_post_initiative_notes')
          ->where('id', $note['id'])
          ->update(['post_initiative_id' => $p['new_id']]);
      }
      
    }
    
    $tags = DB::select("
    select * from tbl_post_initiative_tags
    where post_initiative_id IN (
    select id from tbl_post_initiatives pi
    where 
    pi.initiative_id = 547
    and (
    select count(*) from tbl_communications c
    where c.post_initiative_id = pi.id
    and c.communication_date >= '2016-05-01'
    ) = 0
    )
    ");
    
    foreach ($tags as $tag) {
      if (isset($postInitiatives[$tag['post_initiative_id']])) {
        $p = $postInitiatives[$tag['post_initiative_id']];
        app_model_PostInitiativeTag::create([
          'post_initiative_id' => $p['new_id'],
          'tag_id' => $tag['tag_id']
        ]);
      }
    }
    
  }
  
  public function fixMigration()
  {
    set_time_limit(0);
    DB::connection()->disableQueryLog();
    
    $postInitiatives = new Collection(DB::select("
      select pi.id, pi2.id as new_id from tbl_post_initiatives pi
      join tbl_post_initiatives pi2 on pi2.post_id = pi.post_id and pi2.initiative_id = 980
      where 
      pi.initiative_id = 547
      and (
      select count(*) from tbl_communications c
      where c.post_initiative_id = pi.id
      and c.communication_date >= '2016-05-01'
      ) = 0
    "));
    $postInitiatives = $postInitiatives->keyBy('id');
    
    $comms = [
      [2636250,	845306],
      [2636657,	845417],
      [2636670,	845420],
      [2637735,	845663],
      [2637968,	845742],
      [2637971,	407152],
      [2637997,	407152],
      [2638548,	845894],
      [2638556,	845896],
      [2638560,	845897],
      [2638571,	845898],
      [2638586,	845903],
      [2638627,	845914],
      [2638630,	845917],
      [2638636,	845920],
      [2638641,	845923],
      [2638662,	845931],
      [2638683,	845938],
      [2638688,	845941],
      [2638693,	845943],
      [2638695,	845944],
      [2638697,	845947],
      [2638701,	845949],
      [2638708,	398277],
      [2638734,	407152],
      [2638758,	845969],
      [2638760,	418375],
      [2638763,	845972],
      [2638764,	845973],
      [2638782,	845980],
      [2638791,	845983],
      [2638815,	845999],
      [2638823,	845941],
      [2638829,	846003],
      [2638843,	845944],
      [2638866,	846011],
      [2638880,	846017],
      [2638885,	846019],
      [2638896,	846021],
      [2638937,	846027],
      [2638981,	845947],
      [2639001,	845972],
      [2639015,	846049],
      [2639020,	845980],
      [2639037,	846060],
      [2639061,	845352],
      [2639067,	846076],
      [2639178,	845969],
      [2639226,	845969],
      [2639240,	846174],
      [2639295,	846187],
      [2639297,	846188],
      [2639353,	846174],
      [2639450,	846247],
      [2639457,	846250],
      [2639538,	846277],
      [2639594,	846299],
      [2639650,	846049],
      [2639674,	846338],
      [2639761,	846373],
      [2639773,	846378],
      [2639839,	846402],
      [2639908,	846049],
      [2639924,	845352],
      [2639929,	846321],
      [2639930,	846433],
      [2639933,	846316],
      [2640006,	846253],
      [2640007,	846250],
      [2640028,	845668],
      [2640030,	846174],
      [2640039,	846156],
      [2640056,	846027],
      [2640074,	846373],
      [2640081,	846483],
      [2640085,	846484],
      [2640096,	846491],
      [2640161,	846156],
      [2640225,	846501],
      [2640231,	846529],
      [2640238,	846531],
      [2640256,	845941],
      [2640261,	846027],
      [2640271,	845903],
      [2640357,	846559],
      [2640457,	846586],
      [2640520,	846600],
      [2640587,	846247],
      [2640593,	846460],
      [2640607,	846618],
      [2640614,	846483],
      [2640618,	845352],
      [2640624,	846622],
      [2640660,	846630],
      [2640673,	846637],
      [2640689,	846641],
      [2640692,	846642],
      [2640696,	846643],
      [2640706,	846645],
      [2640713,	846646],
      [2640766,	410362],
      [2640898,	846699],
      [2640916,	846630],
      [2641159,	846773],
      [2644890,	846531],
      [2644897,	845941],
      [2644974,	845668],
      [2645000,	846250],
      [2645052,	845972],
      [2645131,	846483],
      [2645136,	846637],
      [2645179,	846635],
      [2645231,	846373],
      [2645243,	846378],
      [2645260,	846049],
      [2645278,	846483],
      [2645304,	846720],
      [2645310,	845972],
      [2645411,	845304],
      [2645459,	846531],
      [2645475,	845941],
      [2645571,	846378],
      [2645574,	846049],
      [2645591,	846027],
      [2645717,	845972],
      [2646515,	846645],
      [2646562,	846373],
      [2646611,	846584],
      [2646833,	846600],
      [2646882,	846618],
      [2646887,	846622],
      [2646893,	846630],
      [2646907,	846643]
    ];
    
    foreach ($comms as $comm) {
      $c = app_model_Communication::find($comm[0]);
      var_dump($comm[0]);
      if ($c) {
        if ($postInitiatives[$comm[1]]) {
          $c->post_initiative_id = $postInitiatives[$comm[1]]['new_id'];
        } else {
          $c->post_initiative_id = $comm[1];
        }
        $c->save();
      }
    }
  }
  
}