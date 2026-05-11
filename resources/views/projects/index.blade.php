<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project & Task Manager</title>
    @livewireStyles
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex">

    {{-- ── Sidebar ── --}}
    <aside class="w-56 min-h-screen bg-white border-r border-gray-200 flex flex-col shrink-0">


        {{-- Navigation Bar --}}
        <nav class="flex-1 px-3 py-4 space-y-1">

            <p class="px-3 pt-4 pb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">
                Project Management
            </p>

            <a href="#"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium bg-indigo-50 text-indigo-700 transition-colors">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Project Manager
            </a>
        </nav>
    </aside>

    {{-- ── Main area ── --}}
    <div class="flex-1 flex flex-col min-h-screen">

        {{-- Top bar --}}
        <header class="h-14 bg-white border-b border-gray-200 flex items-center justify-between px-6 shrink-0">
            <h1 class="text-lg font-semibold text-gray-800">Project & Task Manager</h1>
        </header>

        {{-- Content --}}
        <main class="flex-1 px-8 py-8">
            @livewire('project-manager')
        </main>
    </div>

    @livewireScripts
</body>
</html>
