<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class EmailTemplate extends MyModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'email_templates';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['template-name','slug','trigger','tags','to','subject', 'content'];


}
