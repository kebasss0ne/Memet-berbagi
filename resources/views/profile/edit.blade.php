<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings - Yapp</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-900 antialiased">
    <nav class="sticky top-0 z-50 bg-white border-b border-gray-100 px-6 py-4">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="text-sm font-bold pb-1 px-2">Dashboard</a>
                @auth
                    @if(Auth::user()->role !== 'admin')
                        <a href="{{ route('posts.mine') }}" class="text-sm font-bold pb-1 px-2">Your Yapp</a>
                    @endif
                @endauth
            </div>

            <div class="flex items-center gap-8">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <span class="text-[10px] bg-red-600 text-white px-2 py-1 rounded-full font-black">ADMIN</span>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="text-sm font-bold border-b-2 border-blue-500 pb-1 px-2">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-bold pb-1 px-2">Log Out</button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto py-12 px-6">

        <div class="space-y-12">
            <div class="w-full px-4 py-6 border border-gray-300 rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="w-full px-4 py-6 border border-gray-300 rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="bg-red-50 p-8 border border-red-100">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </main>
</body>
</html>
