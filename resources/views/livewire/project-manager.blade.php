<div class="p-6">
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
                        <td class="px-6 py-4">
                            <button wire:click="openTaskModal({{ $project->id }})"
                                class="font-medium text-indigo-600 hover:underline">
                                {{ $project->name }}
                            </button>
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $project->description ?: 'No description' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-indigo-100 text-indigo-700">
                                {{ $project->completed_tasks_count }}/{{ $project->tasks_count }} Tasks
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button wire:click="openTaskModal({{ $project->id }})"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors text-xs font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                Tasks
                            </button>
                            <button wire:click="openEditModal({{ $project->id }})"
                                class="text-green-500 hover:text-green-700">Edit</button>
                            <button wire:click="deleteProject({{ $project->id }})"
                                class="text-red-400 hover:text-red-600">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center text-gray-400">No projects found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modals --}}
    <x-modals.add-project :show="$showAddModal" />
    <x-modals.edit-project :show="$showEditModal" />
    <x-modals.task :show="$showTaskModal" :project="$selectedProject" />
</div>
