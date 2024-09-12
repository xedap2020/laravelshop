<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $fillable = ['id', 'name', 'email', 'password', 'status', 'age', 'phoneNumber', 'address', 'role',
        'image_path', 'deleted_at', 'created_at', 'updated_at', 'provider_id', 'provider'];
    protected $hidden = ['password'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
