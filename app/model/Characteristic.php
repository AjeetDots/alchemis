<?php

/**
 * Class app_model_Characteristic
 */
class app_model_Characteristic extends Illuminate\Database\Eloquent\Model {

  /**
   * @var array
   */
  protected $guarded = [];
  /**
   * @var string
   */
  public $table = 'tbl_characteristics';
  /**
   * @var bool
   */
  public $timestamps = false;

  /**
   * One characteristic has many object characteristics
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function objectCharacteristic()
  {
    return $this->hasMany('app_model_ObjectCharacteristics', 'characteristic_id');
  }

}