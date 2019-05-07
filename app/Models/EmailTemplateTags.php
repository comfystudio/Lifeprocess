<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class EmailTemplateTags extends MyModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'email_template_tags';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['email_template_id','tag_name','tag_value'];
    
}
