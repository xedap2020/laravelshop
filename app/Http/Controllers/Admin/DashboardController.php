<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $count_users = User::count();
        // $count_posts = Post::count();

        return view('admin.dashboard', [
            'count_users' => $count_users
        ]);
    }
}
