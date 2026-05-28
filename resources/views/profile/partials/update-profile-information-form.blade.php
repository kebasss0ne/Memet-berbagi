<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Profile Information
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Update your account's profile information.
        </p>
    </header>

    <!-- SUCCESS MESSAGE -->
    @if (session('status') === 'profile-updated')
        <div class="mt-4 text-green-600 text-sm bg-green-50 p-3 rounded-lg font-bold">
            Profile berhasil diupdate.
        </div>
    @endif

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- NAME -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Name
            </label>

            <input 
                type="text" 
                name="name"
                value="{{ old('name', auth()->user()->name) }}"
                required
                autofocus
                class="w-full px-4 py-2 border border-gray-300 rounded-lg 
                       focus:outline-none focus:ring-2 focus:ring-blue-400"
            >

            @error('name')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- ❌ EMAIL DIHAPUS TOTAL -->

        <!-- BUTTON -->
        <div>
            <button 
                type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold transition"
            >
                Save Changes
            </button>
        </div>
    </form>
</section>
