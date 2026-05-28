<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Yapp</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])
<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-white text-gray-900 antialiased">

<nav class="sticky top-0 z-50 bg-white border-b border-gray-100 px-6 py-4">
<div class="max-w-6xl mx-auto flex items-center justify-between">

<div class="flex items-center gap-3">

<a href="{{ route('dashboard') }}"
class="text-sm font-bold border-b-2 border-blue-500 pb-1 px-2">
Dashboard
</a>

@auth
<a href="{{ route('posts.mine') }}"
class="text-sm font-bold text-gray-400 hover:text-gray-900 px-2 transition">
Your Yapp
</a>
@endauth

</div>

<form action="{{ route('dashboard') }}" method="GET"
class="flex-1 max-w-md mx-8">

<input type="text"
name="search"
value="{{ request('search') }}"
placeholder="Search..."
class="w-full bg-gray-100 rounded-full py-2 px-6 text-sm outline-none">

</form>

<div class="flex items-center gap-6">

@auth

<a href="{{ route('profile.edit') }}"
class="text-sm font-bold hover:text-blue-500">
Profile
</a>

<form method="POST" action="{{ route('logout') }}">
@csrf

<button class="text-sm font-bold hover:text-blue-500">
Log Out
</button>

</form>

@else

<a href="{{ route('login') }}"
class="text-sm font-bold text-gray-600 hover:text-blue-500">
Login
</a>

@endauth

</div>
</div>
</nav>

<main class="max-w-6xl mx-auto py-12 px-6 flex gap-12">

<div class="flex-1">

@foreach($posts as $post)

<div class="flex gap-6 border-b border-gray-50 pb-8 mb-8">

<div class="w-14 h-14 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-400 text-xl">
{{ strtoupper(substr($post->user->name,0,1)) }}
</div>

<div class="flex-1">

<h3 class="font-bold text-lg">
@ {{ $post->user->name }}
</h3>

<p class="text-gray-600 mt-2 break-words">
{{ $post->content }}
</p>

</div>
</div>

@endforeach

</div>

<div class="w-[350px]">

<div class="sticky top-28">

@auth

<h2 class="font-bold text-xl mb-4">
Tell us what's happening!
</h2>

<form action="{{ route('posts.store') }}" method="POST">

@csrf

<textarea
name="content"
required
maxlength="280"
class="w-full bg-gray-50 border border-gray-100 rounded-3xl p-5 h-40 resize-none outline-none"
placeholder="Yapp right now..."></textarea>

<button
class="w-full mt-4 bg-blue-500 text-white font-bold py-3 rounded-full hover:bg-blue-600">
Post
</button>

</form>

@else

<div class="bg-blue-50 p-6 rounded-3xl border border-blue-100">

<h2 class="font-bold text-lg text-blue-900 mb-2">
Join Yapp!
</h2>

<p class="text-sm text-blue-700 mb-4">
Login buat posting cuitan sendiri.
</p>

<a href="{{ route('login') }}"
class="block text-center w-full bg-blue-500 text-white font-bold py-3 rounded-full">
Sign In
</a>

</div>

@endauth

</div>
</div>

</main>

</body>
</html>
