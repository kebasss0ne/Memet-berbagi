<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Yapp</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-900 antialiased">
    <nav class="sticky top-0 z-50 bg-white border-b border-gray-100 px-6 py-4">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center">
                <a href="/" class="text-sm font-bold border-b-2 border-blue-500 pb-1 px-2">Dashboard</a>
            </div>

            <form action="/" method="GET" class="flex-1 max-w-md mx-8">
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="w-full bg-gray-100 border-none rounded-full py-2 px-6 text-sm focus:ring-1 focus:ring-blue-400 outline-none" 
                    placeholder="Search...">
            </form>
            <div class="flex items-center gap-6">
                <a href="{{ route('login') }}" class="text-sm font-bold hover:text-blue-500 transition">Log In</a>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto py-12 px-6 flex items-start gap-12">
        
        <div class="flex-1 min-w-0">
            @foreach($posts as $post)
            <div class="flex gap-6 border-b border-gray-50 pb-8 mb-8">
                <div class="w-14 h-14 bg-gray-200 rounded-full flex-shrink-0"></div>
                
                <div class="flex-1 min-w-0"> 
                    <h3 class="font-bold text-lg text-gray-900">@ {{ $post->user->name }}</h3>
                    
                    <div class="max-w-xl"> 
                        <p class="text-gray-600 mt-1 leading-relaxed break-words">
                            {{ $post->content }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="w-[350px] flex-shrink-0 hidden md:block">
            <div class="sticky top-28 p-8 bg-gray-50 rounded-[32px] border border-gray-100 text-center">
                <h2 class="font-bold text-xl mb-2 tracking-tight">New to Yapp?</h2>
                <p class="text-gray-500 text-sm mb-6 leading-relaxed">Sign up now to get your own personalized timeline!</p>
                <a href="{{ route('register') }}" class="block w-full bg-[#4dbfff] text-white text-center font-bold py-3 rounded-full shadow-lg hover:bg-blue-400 transition-all">
                    Create Account
                </a>
                <p class="mt-4 text-xs text-gray-400 px-4">By signing up, you agree to the Terms of Service and Privacy Policy.</p>
            </div>
        </div>

    </main>
</body>
</html>