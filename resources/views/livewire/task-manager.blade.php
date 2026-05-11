<div class="p-5 space-y-6">
    {{-- Alerts --}}
    @if($successMessage)
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded relative mb-4">
            <span class="block sm:inline text-xs">{{ $successMessage }}</span>
            <button wire:click="clearMessages" class="absolute top-0 bottom-0 right-0 px-4 py-2">✕</button>
        </div>
    @endif

    {{-- Add Task Form --}}
    <div class="flex gap-2">
        <input wire:model.defer="newTaskTitle" wire:keydown.enter="createTask" type="text"
               placeholder="Add a new task..."
               class="flex-1 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
        <button wire:click="createTask" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold">Add</button>
    </div>

    {{-- Tasks Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="w-12 px-4 py-3"></th>
                    <th class="text-left py-3 font-semibold text-gray-600">Task Title</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">Status</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tasks as $task)
                    {{-- CRITICAL: wire:key here ensures Livewire tracks the correct row --}}
                    <tr wire:key="task-row-{{ $task->id }}" class="hover:bg-gray-50 {{ $task->is_completed ? 'bg-gray-50' : '' }}">
                        <td class="px-4 py-3 text-center">
                            {{-- Use wire:change for immediate backend update --}}
                            <input type="checkbox"
                                   wire:change="toggleTask({{ $task->id }})"
                                   {{ $task->is_completed ? 'checked' : '' }}
                                   class="w-4 h-4 text-indigo-600 border-gray-300 rounded cursor-pointer">
                        </td>
                        <td class="py-3">
                            @if($editingTaskId === $task->id)
                                <div class="flex items-center gap-2">
                                    <input wire:model.defer="editTaskTitle" type="text" class="border rounded px-2 py-1 text-sm flex-1">
                                    <button wire:click="updateTask" class="text-indigo-600 text-xs font-bold">Save</button>
                                </div>
                            @else
                                <span class="{{ $task->is_completed ? 'line-through text-gray-400' : 'text-gray-700' }}">
                                    {{ $task->title }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($task->is_completed)
                                <span class="text-[10px] bg-green-100 text-green-700 px-2 py-1 rounded-full font-bold uppercase">Done</span>
                            @else
                                <span class="text-[10px] bg-amber-100 text-amber-700 px-2 py-1 rounded-full font-bold uppercase">Pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button wire:click="startEditingTask({{ $task->id }})" class="text-gray-400 hover:text-blue-600 mr-2">Edit</button>
                            <button wire:click="deleteTask({{ $task->id }})" wire:confirm="Delete task?" class="text-gray-400 hover:text-red-600">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-400">No tasks found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $tasks->links() }}
    </div>
</div>
