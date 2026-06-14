<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;

    /**
     * Halaman utama
     */
    public function index(Request $request)
    {
        $search = substr($request->input('search',''),0,50);

        $posts = Post::with('user')
            ->when($search, function($query) use ($search) {
                return $query->where(
                    'content',
                    'like',
                    '%' . $search . '%'
                );
            })
            ->latest()
            ->get();

        // 🔥 ADMIN JANGAN MASUK DASHBOARD USER
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect('/admin-panel');
        }

        if (Auth::check()) {
            return view('dashboard', compact('posts'));
        }

        return view('welcome', compact('posts'));
    }

    /**
     * Dashboard user
     */
    public function dashboard(Request $request)
    {
        if (
            Auth::check()
            && Auth::user()->isAdmin()
        ) {
            abort(404);
        }

        return $this->index($request);
    }

    /**
     * Simpan posting
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:280'
        ]);

        if (!Auth::check()) {
            abort(401);
        }

        $content = strip_tags(
            trim($request->content)
        );

        Post::create([
            'user_id' => Auth::id(),
            'content' => $content,
        ]);

        return redirect()->back();
    }

    /**
     * Post milik sendiri
     */
    public function myPosts(Request $request)
    {
        if (
            Auth::check()
            && Auth::user()->isAdmin()
        ) {
            abort(404);
        }

        $search = substr(
            $request->input('search',''),
            0,
            50
        );

        $posts = Post::where(
            'user_id',
            Auth::id()
        )
        ->when($search,function($query)
        use($search){
            return $query->where(
                'content',
                'like',
                '%' . $search . '%'
            );
        })
        ->latest()
        ->get();

        return view(
            'yapp',
            compact('posts')
        );
    }

    /**
     * Hapus post
     */
    public function destroy(Post $post)
    {
        if (
            $post->user_id !== Auth::id()
            && !Auth::user()->isAdmin()
        ) {
            abort(
                403,
                'Unauthorized action.'
            );
        }

	Log::info('USER_DELETE_POST', [
    	    'user_id' => Auth::id(),
    	    'user_email' => Auth::user()->email,
    	    'post_id' => $post->id,
    	    'content' => $post->content,
    	    'ip' => request()->ip(),
    	    'time' => now()->toDateTimeString(),
	]);

        $post->delete();

        return redirect()->back();
    }
}
