<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $fillable = ['id', 'idkey', 'shop_id','module', 'locale', 'parent_id', 'title', 'slug', 'is_slug_override', 'duplicate', 'description',
        'content', 'image', 'image_extension', 'image_banner', 'image_icon', 'url', 'url_type', 'author_id', 'target', 'price', 'params',
        'params_plus', 'total_item', 'total_view', 'total_order', 'order', 'providers_id', 'position', 'display_type', 'sticky', 'is_display',
        'seo_title', 'seo_description', 'seo_robots', 'status', 'account_fake', 'started_at', 'ended_at', 'published_at', 'created_at',
        'updated_at', 'deleted_at'];

    public function children() {
        return $this->hasMany(Post::class, 'parent_id');
    }
    public function users() {
        return $this->belongsTo(User::class, 'author_id')->select(['id', 'name']);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
