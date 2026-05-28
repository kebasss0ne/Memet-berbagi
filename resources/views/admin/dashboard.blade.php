<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Yapp Control Center</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])
<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-[#0f172a] text-white antialiased overflow-x-hidden">

<div class="fixed inset-0 overflow-hidden pointer-events-none">

<div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-blue-500/20 blur-3xl rounded-full"></div>

<div class="absolute top-1/2 -right-40 w-[500px] h-[500px] bg-red-500/10 blur-3xl rounded-full"></div>

</div>

<div class="flex min-h-screen relative z-10">

<!-- SIDEBAR -->
<aside class="w-[290px] border-r border-white/10 bg-white/5 backdrop-blur-xl p-7 flex flex-col justify-between">

<div>

<div class="flex items-center gap-4 mb-14">

<a href="{{ route('admin.database') }}"
class="w-14 h-14 rounded-3xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-2xl font-black hover:scale-110 transition">

Y

</a>

<div>
<h1 class="text-2xl font-black">Yapp</h1>
<p class="text-sm text-gray-400">Control Center</p>
</div>

</div>

<div class="bg-blue-500/20 border border-blue-400/20 rounded-2xl px-5 py-4">
<p class="text-sm font-bold text-blue-300">
Dashboard Overview
</p>
</div>

</div>

<div class="border-t border-white/10 pt-6">

<p class="font-bold">
{{ Auth::user()->name }}
</p>

<p class="text-xs text-gray-400 mb-5">
Administrator
</p>

<form method="POST" action="{{ route('logout') }}">
@csrf

<button class="w-full text-sm bg-red-500/10 border border-red-500/20 text-red-300 px-4 py-3 rounded-xl hover:bg-red-500 hover:text-white">
Logout
</button>

</form>

</div>

</aside>

<!-- MAIN -->
<main class="flex-1 p-10">

<div class="mb-10">

<h2 class="text-5xl font-black">
Moderation Panel
</h2>

<p class="text-gray-400 mt-3">
Monitor activity & access logs
</p>

</div>

<!-- STATS -->

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

<div class="bg-white/5 border border-white/10 rounded-3xl p-7">

<p class="text-gray-400">
Total Posts
</p>

<h3 class="text-5xl font-black mt-3">
{{ $posts->count() }}
</h3>

</div>

<div class="bg-white/5 border border-white/10 rounded-3xl p-7">

<p class="text-gray-400">
System
</p>

<h3 class="text-3xl font-black mt-3 text-green-300">
ONLINE
</h3>

</div>

</div>

<!-- ACCESS LOG -->

<div class="bg-white/5 border border-white/10 rounded-3xl p-8 mb-12">

<h2 class="text-2xl font-black mb-6">
Access Logs
</h2>

<div class="overflow-auto">

<table class="w-full text-sm">

<thead>

<tr class="border-b border-white/10 text-gray-400">

<th class="text-left py-4">
IP
</th>

<th class="text-left py-4">
Email
</th>

<th class="text-left py-4">
Method
</th>

<th class="text-left py-4">
Path
</th>

<th class="text-left py-4">
Status
</th>

<th class="text-left py-4">
Failed
</th>

<th class="text-left py-4">
Time
</th>

</tr>

</thead>

<tbody>

@foreach($logs as $log)

<tr class="border-b border-white/5">

<td class="py-4">
{{ $log->ip }}
</td>

<td>
{{ $log->user_email ?? '-' }}
</td>

<td>
{{ $log->method }}
</td>

<td>
{{ $log->path }}
</td>

<td>
{{ $log->status }}
</td>

<td>

@if($log->failed_login)

<span class="text-red-400">
YES
</span>

@else

<span class="text-green-400">
NO
</span>

@endif

</td>

<td>
{{ $log->access_time }}
</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

<!-- USERS -->

<div class="bg-white/5 border border-white/10 rounded-3xl p-8 mb-12">

<h2 class="text-2xl font-black mb-6">
User Management
</h2>

<div class="space-y-5">

@foreach($users as $user)

<div class="bg-black/20 border border-white/10 rounded-2xl p-5 flex justify-between items-center">

<div>

<h3 class="text-lg font-bold">
{{ $user->name }}
</h3>

<p class="text-sm text-gray-400">
{{ $user->email }}
</p>

<p class="text-xs mt-1 text-blue-300">
Role: {{ $user->role }}
</p>

</div>

<div class="flex gap-3">

<!-- DELETE USER -->

<form action="{{ route('admin.user.delete', $user) }}"
method="POST">

@csrf
@method('DELETE')

<button
class="bg-red-500/10 border border-red-500/20 text-red-300 px-5 py-2 rounded-xl hover:bg-red-500 hover:text-white transition">

Delete User

</button>

</form>

</div>

</div>

@endforeach

</div>

</div>

<!-- POSTS -->

<div class="space-y-7">

@foreach($posts as $post)

<div class="bg-white/5 border border-white/10 rounded-[30px] p-8">

<div class="flex justify-between gap-8">

<div class="flex gap-5 flex-1">

<div class="w-16 h-16 rounded-3xl bg-gradient-to-br from-gray-700 to-black flex items-center justify-center text-2xl font-black">
{{ strtoupper(substr($post->user->name,0,1)) }}
</div>

<div>

<h3 class="text-xl font-black mb-3">
@ {{ $post->user->name }}
</h3>

<p class="text-gray-300">
{{ $post->content }}
</p>

</div>

</div>

<div class="w-[220px]">

<form action="{{ route('admin.post.delete',$post) }}"
method="POST">

@csrf
@method('DELETE')

<button class="w-full bg-red-500/10 border border-red-500/20 text-red-300 py-3 rounded-2xl">
Delete Post
</button>

</form>

</div>

</div>

</div>

@endforeach

</div>

</main>

</div>

</body>
</html>
