<div class="p-6">
    {{-- Viewing Tasks --}}
    @if ($viewingTasks && $selectedProject)
        <div class="space-y-6">
            {{-- Header with Back Button --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button wire:click="backToProjects" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $selectedProject->name }}</h1>
                        <p class="text-sm text-gray-500">{{ $selectedProject->description ?: 'No description' }}</p>
                    </div>
                </div>
            </div>

            {{-- Task Manager Component --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                @livewire('task-manager', ['project' => $selectedProject], key('tasks-view-'.$selectedProject->id))
            </div>
        </div>

    {{-- Viewing Projects --}}
    @else
        {{-- Page Header --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Project Manager</h1>
            <button wire:click="openAddModal"
                class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg font-semibold shadow-sm hover:bg-indigo-700 transition-colors">
                + Add Project
            </button>
        </div>

        {{-- Alerts --}}
        @if ($successMessage)
            <x-alert-message type="success" :message="$successMessage" onDismiss="clearMessages" class="mb-4" />
        @endif
        @if ($errorMessage)
            <x-alert-message type="error" :message="$errorMessage" onDismiss="clearMessages" class="mb-4" />
        @endif

        {{-- Projects Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold uppercase text-gray-500">Project</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold uppercase text-gray-500">Description</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold uppercase text-gray-500">Progress</th>
                        <th class="text-right px-6 py-3.5 text-xs font-semibold uppercase text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($projects as $project)
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td wire:click="openTaskView({{ $project->id }})" class="px-6 py-4">
                                {{-- When the user clicks on the project name, open the task view --}}
                                <button wire:click="openTaskView({{ $project->id }})"
                                    class="font-medium text-indigo-600 hover:underline">
                                    {{ $project->name }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $project->description ?: 'No description' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex justify-between text-[10px] font-bold text-gray-500 uppercase">
                                        <span>Progress</span>
                                    </div>
                                    <span class="text-[10px] text-gray-400">
                                        {{ $project->completed_tasks_count }}/{{ $project->tasks_count }} Tasks
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button wire:click="openEditModal({{ $project->id }})"
                                    class="text-green-500 hover:text-green-700 font-medium">Edit</button>

                                <button wire:click="deleteProject({{ $project->id }})"
                                    wire:confirm="Are you sure you want to delete this project?"
                                    class="text-red-400 hover:text-red-600 font-medium">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    <span>No projects found. Create one to get started!</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Add & Edit Modals --}}
        <x-modals.add-project :show="$showAddModal" />
        <x-modals.edit-project :show="$showEditModal" />
    @endif
</div>
