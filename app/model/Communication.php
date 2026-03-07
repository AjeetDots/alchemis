<?php

class app_model_Communication extends Illuminate\Database\Eloquent\Model {
  
  protected $guarded = [];
  public $table = 'tbl_communications';
  public $timestamps = false;
  
  public function note()
  {
    return $this->hasOne('app_model_PostInitiativeNote', 'id', 'note_id');
  }

}