<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
	use LogsActivity, HasFactory;

    protected $fillable = [
        'title', 'content', 'pinned', 'actived',
		'user_id', 'ip_addr' // harus selalu ada
	];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['title', 'content', 'actived']);
    }
}
