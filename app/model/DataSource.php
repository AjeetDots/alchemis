<?php

class app_model_DataSource extends Illuminate\Database\Eloquent\Model {

  protected $guarded = [];
  public $timestamps = false;
  public $table = 'tbl_lkp_data_sources';

}