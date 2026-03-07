<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

/**
 * Class app_model_Clients
 */
class app_model_Clients extends Model
{

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var string
     */
    protected $table = 'tbl_clients';

    /**
     * One client can have many campaigns
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campaign()
    {
        return $this->hasOne("app_model_Campaign", "client_id");
    }
    
    public function users()
    {
      return $this->hasMany('app_model_User', 'client_id');
    }

    public function posts($page)
    {
        $amount = 150;

        return app_model_Post::hydrateRaw('SELECT p.* FROM tbl_posts AS p
            JOIN tbl_post_initiatives AS pi ON pi.post_id = p.id
            JOIN vw_client_initiatives AS ci ON ci.initiative_id = pi.initiative_id
            WHERE ci.client_id = :client_id
            GROUP BY p.id
            LIMIT :limit
            OFFSET :offset
        ', [
            'client_id' => $this->id,
            'limit' => $amount,
            'offset' => ($page - 1) * $amount,
        ]);
    }
}