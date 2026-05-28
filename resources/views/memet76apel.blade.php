<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Secret Access</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-md border border-gray-100">

    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Welcome Back Admin</h1>
        <p class="text-gray-500 mt-2">Restricted administrator access</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 text-red-600 text-sm bg-red-50 p-3 rounded-lg text-center font-bold border border-red-100">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 text-red-600 text-sm bg-red-50 p-3 rounded-lg text-center font-bold border border-red-100">
            {{ session('error') }}
        </div>
    @endif

    <form action="/proses-admin-login" method="POST" autocomplete="off">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Email
            </label>

            <input
                type="email"
                name="email"
                required
                autofocus
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="admin@yapp.com">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Password
            </label>

            <input
                type="password"
                name="password"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="••••••••">
        </div>

        <button
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition shadow-md">
            Login as Admin
        </button>
    </form>

    <div class="mt-8 text-center">
        <p class="text-xs text-gray-400">
            Restricted Area • Authorized Personnel Only
        </p>
    </div>

</div>

</body>
</html>
