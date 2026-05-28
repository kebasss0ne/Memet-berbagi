<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

        <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">
            Welcome Back
        </h2>
        <p class="text-center text-gray-500 mb-8">
            Please login to your Yapp account
        </p>

        <!-- 🔥 ERROR MESSAGE -->
        @if ($errors->any())
            <div class="mb-4 text-red-500 text-sm bg-red-50 p-3 rounded-lg text-center font-bold">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- SUCCESS MESSAGE -->
        @if (session('success'))
            <div class="mb-4 text-green-600 text-sm bg-green-50 p-3 rounded-lg text-center font-bold">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                >
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                >
            </div>

            <!-- CAPTCHA -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Berapa hasil dari {{ $num1 }} + {{ $num2 }} ?
                </label>

                <input
                    type="text"
                    name="captcha"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                >
            </div>

            <!-- Remember Me -->
            <div class="flex items-center mb-6">
                <input type="checkbox" name="remember" class="mr-2 rounded">

                <label class="text-sm text-gray-600">
                    Remember me
                </label>
            </div>

            <!-- Button -->
            <button
                type="submit"
                class="w-full bg-blue-500 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-600 transition duration-200 shadow-md"
            >
                Login
            </button>

            <!-- Register -->
            <p class="text-sm text-center text-gray-500 mt-6">

                <a href="{{ route('password.request') }}"
                   class="text-blue-500 font-semibold">
                   Forgot Password?
                </a>

                Don't have an account?

                <a href="{{ route('register') }}"
                   class="text-blue-500 font-medium hover:underline">
                    Register
                </a>

            </p>
        </form>

    </div>

</body>
</html>
