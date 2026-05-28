<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Control</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body{
            background: linear-gradient(to right,#0f172a,#020617);
        }
    </style>
</head>
<body class="text-white min-h-screen flex">

    <!-- SIDEBAR -->
    <div class="w-72 bg-blue-950/40 border-r border-white/10 p-8">

        <div class="flex items-center gap-4 mb-10">

            <a href="/admin-panel">
                <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center text-3xl font-bold">
                    Y
                </div>
            </a>

            <div>
                <h1 class="text-4xl font-bold">app</h1>
                <p class="text-gray-400">Database Center</p>
            </div>

        </div>

        <a href="/admin-panel"
           class="block bg-blue-500/20 hover:bg-blue-500/40 transition p-4 rounded-2xl font-semibold">
            ← Back to Admin Panel
        </a>

    </div>

    <!-- CONTENT -->
    <div class="flex-1 p-10">

        <h1 class="text-6xl font-black mb-3">
            Database Control
        </h1>

        <p class="text-gray-400 mb-10">
            Backup & Restore MariaDB
        </p>

        <!-- BACKUP -->
        <div class="bg-white/5 border border-white/10 rounded-3xl p-8 mb-8">

            <h2 class="text-2xl font-bold mb-6">
                Backup Database
            </h2>

            <form action="{{ route('admin.database.backup') }}" method="POST">
                @csrf

                <button
                    class="w-full bg-blue-500 hover:bg-blue-600 transition py-5 rounded-2xl text-xl font-bold">

                    Download Backup

                </button>

            </form>

        </div>

        <!-- RESTORE -->
        <div class="bg-white/5 border border-white/10 rounded-3xl p-8">

            <h2 class="text-2xl font-bold mb-6">
                Restore Database
            </h2>

            <form action="/admin/database/restore"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <input type="file"
                       name="backup"
                       class="mb-6 block w-full text-sm text-gray-300">

                <button
                    class="bg-green-500 hover:bg-green-600 transition px-8 py-4 rounded-2xl font-bold">

                    Restore Backup

                </button>

            </form>

        </div>

    </div>

</body>
</html>
