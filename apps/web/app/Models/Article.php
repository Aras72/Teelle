<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    use HasPublicUlid;

    protected $fillable = [
        'slug', 'title', 'excerpt', 'body_markdown', 'seo_title', 'seo_description', 'status',
        'author_id', 'submitted_by', 'submitted_at', 'published_by', 'published_at',
        'cover_disk', 'cover_path', 'cover_mime', 'cover_alt',
    ];

    protected function casts(): array
    {
        return ['submitted_at' => 'datetime', 'published_at' => 'datetime'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ArticleCategory::class, 'article_category');
    }
}
