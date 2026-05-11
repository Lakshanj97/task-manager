<div class="p-5 space-y-4">

    {{-- Task Panel Header --}}
    <div class="flex items-center justify-between">
        <h4 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                         M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2
                         m-6 9l2 2 4-4"/>
            </svg>
            Tasks for: <span class="text-indigo-600">{{ $project->name }}</span>
        </h4>
        <span class="text-xs text-gray-400">{{ $tasks->count() }} task(s)</span>
    </div>

    {{-- Task Messages --}}
    @if($errorMessage)
        <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-xs">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            {{ $errorMessage }}
            <button wire:click="clearMessages" class="ml-auto text-red-400 hover:text-red-600">✕</button>
        </div>
    @endif

    @if($successMessage)
        <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 px-3 py-2 rounded-lg text-xs">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ $successMessage }}
            <button wire:click="clearMessages" class="ml-auto text-green-400 hover:text-green-600">✕</button>
        </div>
    @endif

    {{-- ===== ADD TASK FORM ===== --}}
    <div class="flex gap-2">
        <div class="flex-1">
            <input
                wire:model.defer="newTaskTitle"
                wire:keydown.enter="createTask"
                type="text"
                placeholder="Add a new task... (Press Enter or click Add)"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                       focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent
                       @error('newTaskTitle') border-red-400 bg-red-50 @enderror"
            />
            @error('newTaskTitle')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <button
            wire:click="createTask"
            wire:loading.attr="disabled"
            wire:target="createTask"
            class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60
                   text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors whitespace-nowrap"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span wire:loading.remove wire:target="createTask">Add Task</span>
            <span wire:loading wire:target="createTask">Adding...</span>
        </button>
    </div>

    {{-- ===== TASKS LIST ===== --}}
    @if($tasks->isEmpty())
        <div class="text-center py-8 text-gray-400">
            <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                         M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
            </svg>
            <p class="text-sm">No tasks yet. Add your first task above!</p>
        </div>
    @else
        <ul class="space-y-2">
            @foreach($tasks as $task)
                <li
                    wire:key="task-{{ $task->id }}"
                    class="flex items-center gap-3 bg-white border rounded-lg px-4 py-3 shadow-sm
                           {{ $task->is_completed ? 'border-green-200 bg-green-50/50' : 'border-gray-200' }}
                           transition-colors duration-150"
                >
                    {{-- Checkbox --}}
                    <input
                        type="checkbox"
                        wire:click="toggleTask({{ $task->id }})"
                        {{ $task->is_completed ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-gray-300 text-indigo-600
                               focus:ring-indigo-400 cursor-pointer shrink-0"
                    />

                    {{-- Task Title or Edit Form --}}
                    @if($editingTaskId === $task->id)
                        {{-- Edit Mode --}}
                        <div class="flex-1 flex items-center gap-2">
                            <input
                                wire:model.defer="editTaskTitle"
                                wire:keydown.enter="updateTask"
                                wire:keydown.escape="cancelEditingTask"
                                type="text"
                                class="flex-1 border border-indigo-300 rounded-md px-2 py-1 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-indigo-400
                                       @error('editTaskTitle') border-red-400 bg-red-50 @enderror"
                            />
                            @error('editTaskTitle')
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <button
                                wire:click="updateTask"
                                class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-3 py-1 rounded-md transition-colors"
                            >Save</button>
                            <button
                                wire:click="cancelEditingTask"
                                class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium px-3 py-1 rounded-md transition-colors"
                            >Cancel</button>
                        </div>

                    @else
                        {{-- View Mode --}}
                        <span class="flex-1 text-sm text-gray-800 {{ $task->is_completed ? 'line-through text-gray-400' : '' }}">
                            {{ $task->title }}
                        </span>

                        {{-- Status badge --}}
                        @if($task->is_completed)
                            <span class="text-xs font-medium text-green-600 bg-green-100 px-2 py-0.5 rounded-full">Done</span>
                        @endif

                        {{-- Edit button --}}
                        <button
                            wire:click="startEditingTask({{ $task->id }})"
                            title="Edit task"
                            class="p-1.5 rounded-md text-gray-300 hover:text-blue-500 hover:bg-blue-50 transition-colors"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                         m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>

                        {{-- Delete button --}}
                        <button
                            wire:click="deleteTask({{ $task->id }})"
                            wire:confirm="Delete this task?"
                            title="Delete task"
                            class="p-1.5 rounded-md text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858
                                         L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    {{-- Task Summary Footer --}}
    @if($tasks->count() > 0)
        <div class="pt-2 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
            <span>{{ $tasks->where('is_completed', true)->count() }} of {{ $tasks->count() }} completed</span>
            @if($tasks->where('is_completed', false)->count() > 0)
                <span class="text-amber-500 font-medium">
                    {{ $tasks->where('is_completed', false)->count() }} remaining
                </span>
            @else
                <span class="text-green-500 font-medium">All tasks complete! 🎉</span>
            @endif
        </div>
    @endif

</div>
