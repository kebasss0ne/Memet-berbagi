<x-app-layout>

<style>
    nav {
        display: none !important;
    }
</style>

<div class="min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl">

        <h2 class="text-3xl font-bold text-center mb-2">
            Forgot Password
        </h2>

        <p class="text-gray-500 text-center mb-6">
            Masukkan email akun kamu
        </p>

        @if (session('status'))
            <div class="mb-4 text-green-600 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-6">
                <label class="block mb-2 font-medium">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    class="w-full border rounded-lg px-4 py-3"
                    required
                >

                @error('email')
                    <p class="text-red-500 text-sm mt-2">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full bg-blue-500 text-white py-3 rounded-lg font-semibold"
            >
                Kirim Link Reset
            </button>
        </form>

    </div>

</div>

</x-app-layout>
