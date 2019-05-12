<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatterPost extends MyModel
{
    protected $table = 'chatter_post';


    protected $guarded = [];

    public function ChatterDiscussion()
    {
        return $this->belongsTo('App\Models\ChatterDiscussion');
    }

    public function User()
    {
        return $this->belongsTo('App\Models\User');
    }
}
