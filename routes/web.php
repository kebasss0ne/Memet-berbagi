<?php

use App\Http\Controllers\Auth\OtpController;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\DatabaseBackupController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;
use App\Models\AccessLog;


Route::get('/verify-otp', [OtpController::class, 'show'])->name('otp.show');
Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
Route::post('/resend-otp', [OtpController::class, 'resend'])->name('otp.resend');

Route::get('/', [PostController::class,'index'])->name('welcome');

/*
|--------------------------------------------------------------------------
| ADMIN LOGIN PORTAL
|--------------------------------------------------------------------------
*/

Route::get('/memet76apel', function (Request $request) {

    $realIp = $request->server->get('REMOTE_ADDR');

    $allowedIps = config('admin.allowed_ips', []);

    if (!in_array($realIp, $allowedIps, true)) {
        abort(404);
    }

    if (Auth::check()) {
        return redirect('/dashboard');
    }

    return view('memet76apel');
});

Route::post('/proses-admin-login', function (Request $request) {

    $realIp = $request->server->get('REMOTE_ADDR');

    $allowedIps = config('admin.allowed_ips', []);

    if (!in_array($realIp, $allowedIps, true)) {
        abort(404);
    }

    $key = 'admin-login-'.$request->ip();

    if (RateLimiter::tooManyAttempts($key, 5)) {
        abort(429,'Terlalu banyak percobaan login.');
    }

    $request->validate([
        'email' => ['required','email'],
        'password' => ['required']
    ]);

    if (!Auth::attempt($request->only('email','password'))) {

        AccessLog::create([
            'ip' => $realIp,
            'user_email' => $request->email,
            'method' => 'POST',
            'path' => '/proses-admin-login',
            'status' => 401,
            'failed_login' => true,
            'access_time' => now()
        ]);

        RateLimiter::hit($key,60);

        return back()->withErrors([
            'email' => 'Login gagal.'
        ]);
    }

    // 🔐 CEK ROLE AMAN
    if (!Auth::user() || !Auth::user()->isAdmin()) {

        Auth::logout();

        RateLimiter::hit($key,60);

        return back()->withErrors([
            'email' => 'Login gagal.'
        ]);
    }

    RateLimiter::clear($key);

    $request->session()->regenerate();

    return redirect('/admin-panel');
});


/*
|--------------------------------------------------------------------------
| USER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','otp.verified'])->group(function () {

    Route::get('/dashboard', function () {

        if(Auth::user()->isAdmin()){
            abort(404);
        }

        return app(PostController::class)->dashboard(request());

    })->name('dashboard');

    Route::post('/post-cuitan',[PostController::class,'store'])->name('posts.store');

    Route::delete('/post/{post}',[PostController::class,'destroy'])->name('posts.destroy');

    Route::get('/profile',[ProfileController::class,'edit'])->name('profile.edit');

    Route::patch('/profile',[ProfileController::class,'update'])->name('profile.update');

    Route::delete('/profile',[ProfileController::class,'destroy'])->name('profile.destroy');

    Route::get('/yapp',[PostController::class,'myPosts'])->name('posts.mine');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','admin','hide.map'])->group(function () {

    Route::view('/admin/database','admin.database')
        ->name('admin.database');

    Route::post('/admin/database/backup', [DatabaseBackupController::class, 'download'])
        ->name('admin.database.backup');

    Route::get('/admin-panel',[AdminController::class,'dashboard']);

    Route::delete('/admin/user/{user}', function(Request $request, User $user){

        // 🔐 CEGAH DELETE DIRI SENDIRI
        if(Auth::id() === $user->id){
            abort(403);
        }

        // 🔥 HAPUS SEMUA POST USER DULU
        $user->posts()->delete();

        // 🔥 HAPUS USER
        $user->delete();

        return back();

    })->name('admin.user.delete');

    Route::delete('/admin/post/{post}', function(Post $post){

        $post->delete();

        return back();

    })->name('admin.post.delete');
});

require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| BACKUP & RESTORE
|--------------------------------------------------------------------------
*/
