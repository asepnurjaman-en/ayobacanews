<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
	use LogsActivity, HasFactory;

	protected $guarded = ['id'];

	public function article_category(): BelongsTo
	{
		return $this->belongsTo(ArticleCategory::class, 'article_category_id');
	}

	public function comment(): HasMany
	{
		return $this->hasMany(ArticleComment::class);
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()->logOnly(['title', 'file', 'file_type', 'file_source', 'author', 'description', 'datetime', 'publish', 'tags', 'related', 'article_category_id', 'ad_setup']);
	}

	public function scopePublish($query)
	{
		$today = date('Y-m-d');
		$now = date('H:i:s');
		return $query->where('publish', 'publish')->orWhere(function ($query) use ($now, $today) {
			$query->where('publish', 'schedule')
				->whereDate('schedule_time', '<=', $today)
				->whereTime('schedule_time', '<=', $now);
		});
	}
}
