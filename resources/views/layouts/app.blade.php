<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Project & Task Manager' }}</title>
    @livewireStyles
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <nav class="bg-indigo-700 shadow-lg">
        <div class="max-w-6xl mx-auto px-4 py-4">
            <h1 class="text-white text-xl font-bold">Project & Task Manager</h1>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-8">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
