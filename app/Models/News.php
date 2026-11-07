<?php

namespace App\Models;

use App\Support\YouTube;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'subcategory_id',
        'admin_id',
        'title',
        'slug',
        'image',
        'video_type',
        'video_url',
        'video_path',
        'details',
        'tags',
        'breaking_news',
        'top_slider',
        'first_section_three',
        'first_section_nine',
        'post_date',
        'post_month',
        'status',
        'view_count',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id', 'id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function videoEmbedUrl(): ?string
    {
        return $this->video_type === 'youtube' ? YouTube::embedUrl($this->video_url) : null;
    }

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'breaking_news' => 'boolean',
            'top_slider' => 'boolean',
            'first_section_three' => 'boolean',
            'first_section_nine' => 'boolean',
            'view_count' => 'integer',
        ];
    }
}
