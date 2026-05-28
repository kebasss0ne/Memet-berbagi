<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\AccessLog;

class AdminController extends Controller
{
    public function dashboard()
    {
        // POSTS
        $posts = Post::with('user')
            ->latest()
            ->get();

        // USERS
        $users = User::latest()
            ->get();

        // ACCESS LOGS
        $logs = AccessLog::latest()
            ->take(100)
            ->get();

        return view(
            'admin.dashboard',
            compact(
                'posts',
                'users',
                'logs'
            )
        );
    }
}
