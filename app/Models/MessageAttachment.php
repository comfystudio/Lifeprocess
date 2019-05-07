<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MessageAttachment extends MyModel
{
    protected $table = 'message_attachments';

    protected $fillable = ['message_id', 'attachment'];

    protected static function boot() {
        parent::boot();
        static::addGlobalScope('message_attachments.deleted', function (Builder $builder) {
            $builder->where('message_attachments.deleted', '=', '0');
        });
    }
}
