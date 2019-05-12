<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatterDiscussion extends MyModel
{
    protected $table = 'chatter_discussion';


    protected $guarded = [];

    public function ChatterCategory()
    {
        return $this->belongsTo('App\Models\ChatterCategory');
    }

    public function User()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function ChatterPost()
    {
        return $this->hasMany('App\Models\ChatterPost')->orderBy('created_at', 'ASC');
    }
}
