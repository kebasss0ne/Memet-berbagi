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
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-sm font-bold text-gray-400 hover:text-gray-900 px-2">Dashboard</a>
                <a href="{{ route('posts.mine') }}" class="text-sm font-bold border-b-2 border-blue-500 pb-1 px-2">Your Yapp</a>
            </div>

            <form action="{{ route('posts.mine') }}" method="GET" class="flex-1 max-w-md mx-8">
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="w-full bg-gray-100 border-none rounded-full py-2 px-6 text-sm focus:ring-1 focus:ring-blue-400 outline-none" 
                    placeholder="Search...">
            </form>

            <div class="flex items-center gap-8">
                <a href="{{ route('profile.edit') }}" class="text-sm font-bold hover:text-blue-500 transition">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-bold hover:text-blue-500 transition">Log Out</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto py-12 px-6 flex items-start gap-12">
        
        <div class="flex-1 min-w-0">
            @forelse($posts as $post)
            <div class="flex gap-6 border-b border-gray-50 pb-8 mb-8">
                <div class="w-14 h-14 bg-gray-200 rounded-full flex-shrink-0"></div>
                
                <div class="flex-1 min-w-0"> 
                    <div class="flex justify-between items-start">
                        <h3 class="font-bold text-lg text-gray-900">@ {{ $post->user->name }}</h3>
                        
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" 
                              onsubmit="return confirm('Yakin mau hapus yapping-an ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-bold text-red-400 hover:text-red-600 transition">
                                Delete
                            </button>
                        </form>
                    </div>
                    
                    <div class="max-w-xl"> 
                        <p class="text-gray-600 mt-1 leading-relaxed break-words">
                            {{ $post->content }}
                        </p>
                    </div>
                    <span class="text-[11px] text-gray-400 mt-2 block">{{ $post->created_at->diffForHumans() }}</span>
                </div>
            </div>
            @empty
            <div class="py-20 text-center bg-gray-50 rounded-[32px] border border-dashed border-gray-200">
                <p class="text-gray-400 font-medium">Tell us what's happening! Yapp right now...</p>
            </div>
            @endforelse
        </div>

        <div class="w-[350px] flex-shrink-0">
            <div class="sticky top-28">
                <h2 class="font-bold text-xl mb-4 tracking-tight">Tell us what's happening!</h2>
                <form action="{{ route('posts.store') }}" method="POST">
                    @csrf
                    <textarea name="content" class="w-full bg-gray-50 border border-gray-100 rounded-[32px] p-6 h-44 outline-none focus:ring-1 focus:ring-blue-400 resize-none transition-all" placeholder="Yapp right now..."></textarea>
                    <button type="submit" class="w-full mt-4 bg-[#4dbfff] text-white font-bold py-3 rounded-full shadow-lg hover:bg-blue-400 transition-all">
                        Post
                    </button>
                </form>
            </div>
        </div>

    </main>
</body>
</html>