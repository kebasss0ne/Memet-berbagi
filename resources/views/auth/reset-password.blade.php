<x-app-layout>

<style>
    nav {
        display: none !important;
    }
</style>

<div class="min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl">

        <h2 class="text-3xl font-bold text-center mb-6">
            Reset Password
        </h2>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input
                type="hidden"
                name="token"
                value="{{ $request->route('token') }}"
            >

            <div class="mb-4">
                <label class="block mb-2 font-medium">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $request->email) }}"
                    class="w-full border rounded-lg px-4 py-3"
                    required
                >
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">
                    Password Baru
                </label>

                <input
                    type="password"
                    name="password"
                    class="w-full border rounded-lg px-4 py-3"
                    required
                >
            </div>

            <div class="mb-6">
                <label class="block mb-2 font-medium">
                    Konfirmasi Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    class="w-full border rounded-lg px-4 py-3"
                    required
                >
            </div>

            <button
                type="submit"
                class="w-full bg-blue-500 text-white py-3 rounded-lg font-semibold"
            >
                Reset Password
            </button>
        </form>

    </div>

</div>

</x-app-layout>
