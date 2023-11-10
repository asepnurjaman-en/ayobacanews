<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleCategory extends Model
{
	use LogsActivity, HasFactory;

    protected $guarded = ['id'];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

	public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['title', 'color', 'index_sort', 'ad_setup']);
    }

    public function scopePublish($query)
	{
		return $query->where('publish', 'publish');
	}
}
