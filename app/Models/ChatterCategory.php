<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatterCategory extends MyModel
{
    protected $table = 'chatter_categories';


    protected $guarded = [];

    public function ChatterDiscussion()
    {
        return $this->hasMany('App\Models\ChatterDiscussion')->orderBy('created_at', 'ASC');
    }
}
