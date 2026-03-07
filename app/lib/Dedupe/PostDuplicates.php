<?php namespace Dedupe;

use Illuminate\Support\Collection;
use Illuminate\Database\Capsule\Manager as DB;
use Carbon\Carbon;

class PostDuplicates implements Deduper {

  public function run()
  {
    $dupes = $this->getDupeContacts();
    $data = [];
    // loop through dupe posts per company
    foreach($dupes as $dupe){
      $contacts = \app_model_Contact::whereIn('id', explode(',', $dupe['ids']))
        ->with('post')
        ->get();
        
      foreach($contacts as $contact){
        $contact = $this->getContactData($contact);
      }
      
      // sort by master
      $contacts = $this->sortContacts($contacts)->values();
      
      $master = null;
      foreach($contacts as $key => $contact){
        // test data
        $data[] = [
          'master' => $key == 0 ? 'master' : 'dupe',
          'site' => $dupe['name'],
          'name' => $contact->first_name . ' ' . $contact->surname,
          'post_title' => $contact->post->job_title,
          'meeting_set' => $contact->data['meeting'],
          'effective_call' => $contact->data['effective_call'],
          'initiatives' => $contact->post->initiatives()->count(),
          'post_id' => $contact->post_id,
          'contact_id' => $contact->id,
        ];
        unset($master->data);
        unset($contact->data);
        
        if($key == 0){
          $master = $contact;
          continue;
        }
        
        // move any missing data into master, phone number etc
        // contact
        $master->email = !empty($master->email) ? $master->email : $contact->email;
        $master->linked_in = !empty($master->linked_in) ? $master->linked_in : $contact->linked_in;
        $master->telephone_mobile = !empty($master->telephone_mobile) ? $master->telephone_mobile : $contact->telephone_mobile;
        $master->save();
        
        // post
        $master->post->notes = !empty($master->post->notes) ? $master->post->notes : $contact->post->notes;
        $master->post->telephone_1 = !empty($master->post->telephone_1) ? $master->post->telephone_1 : $contact->post->telephone_1;
        $master->post->telephone_2 = !empty($master->post->telephone_2) ? $master->post->telephone_2 : $contact->post->telephone_2;
        $master->post->telephone_switchboard = !empty($master->post->telephone_switchboard) ? $master->post->telephone_switchboard : $contact->post->telephone_switchboard;
        $master->post->telephone_fax = !empty($master->post->telephone_fax) ? $master->post->telephone_fax : $contact->post->telephone_fax;
        $master->post->spend = !empty($master->post->spend) ? $master->post->spend : $contact->post->spend;
        $master->post->save();
        
        // move relations
        // tbl_post_initiatives - change id
        try {
          DB::update("UPDATE tbl_post_initiatives
            SET post_id = :master_id
            WHERE post_id = :dupe_id", [
            'master_id' => $master->post_id,
            'dupe_id' => $contact->post_id
          ]);
        } catch (QueryException $e) {
          // echo $e;
        }
        
        // tbl_post_tags - delete
        try {
          DB::delete("DELETE FROM tbl_post_tags
            WHERE post_id = :dupe_id", [
            'dupe_id' => $contact->post_id
          ]);
        } catch (QueryException $e) {
          // echo $e;
        }
        
        // tbl_post_site - delete old
        try {
          DB::delete("DELETE FROM tbl_post_site
            WHERE post_id = :dupe_id", [
            'dupe_id' => $contact->post_id
          ]);
        } catch (QueryException $e) {
          // echo $e;
        }
        
        // delete contact
        $contact->deleted = 1;
        $contact->save();
        
        // delete empty post
        $contact->post->deleted = 1;
        $contact->post->save();
        
      }
    }

    // log to db
    return $data;
  }
  
  public function getDupeContacts()
  {
    $query = "select co.id as company_id,
      co.name,
      c.first_name,
      c.surname,
      count(*) as count,
      GROUP_CONCAT(c.id) as ids
      from tbl_contacts c
      join tbl_posts p
        on p.id = c.post_id
      join tbl_companies co
        on co.id = p.company_id
      where c.deleted = 0
        and p.deleted = 0
        and co.deleted = 0
        and c.first_name != ''
        and c.surname != ''
      group by co.id, c.first_name, c.surname
      having count(*) > 1";
      
    return new Collection(DB::select($query));
  }
  
  public function getContactData($contact)
  {
    $data = [];
    
    // Meeting set 1/1/2015 or later
    $meeting = DB::selectOne(
      "select MAX(co.communication_date) as date
      from tbl_communications co
      join tbl_post_initiatives pi
      on co.post_initiative_id = pi.id
      where pi.post_id = :post_id
      and co.status_id IN (12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27)
      and co.communication_date >= '2015-01-01'
      group by pi.post_id", [
        'post_id' => $contact->post_id
      ]
    )['date'];
    $data['meeting'] = $meeting ? new Carbon($meeting) : null;
    
    // Effective call 1/1/2014 or later
    $effective_call = DB::selectOne(
      "select MAX(co.communication_date) as date
      from tbl_communications co
      join tbl_post_initiatives pi
      on co.post_initiative_id = pi.id
      where pi.post_id = :post_id
      and co.communication_date >= '2014-01-01'
      and co.is_effective = 1
      group by pi.post_id", [
        'post_id' => $contact->post_id
      ]
    )['date'];
    $data['effective_call'] = $effective_call ? new Carbon($effective_call) : null;
    
    $contact->data = $data;
    
    return $contact;
  }
  
  public function sortContacts($contacts)
  {
    
    return $contacts->sort(function ($a, $b) {
      
      // 1. If Unknown post title sort lower
      if($a->post->job_title == 'Unknown'){
        return 1;
      }else if($b->post->job_title == 'Unknown'){
        return -1;
      }
      
      // 2. Meeting set 1/1/2015 or later sort higher
      if($a->data['meeting'] && $b->data['meeting']){
        return ($a->data['meeting']->gt($b->data['meeting'])) ? -1 : 1;
      }else if($a->data['meeting']){
        return -1;
      }else if($b->data['meeting']){
        return 1;
      }
      
      // 3. Effective call 1/1/2014 or later sort higher
      if($a->data['effective_call'] && $b->data['effective_call']){
        return ($a->data['effective_call']->gt($b->data['effective_call'])) ? -1 : 1;
      }else if($a->data['effective_call']){
        return -1;
      }else if($b->data['effective_call']){
        return 1;
      }
      
      // 4. Latest contact id - sort lower
      if($a->id > $b->id){
        return -1;
      }else {
        return 1;
      }

    });
    
  }

  
}