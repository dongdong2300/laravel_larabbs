<?php

namespace App\Models;

/**
 * @property $content
 * @property $user_id
 * @property $topic_id
 */
class Reply extends Model
{
    protected $fillable = ['content'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
