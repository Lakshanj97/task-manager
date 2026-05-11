@props(['project', 'isSelected' => false, 'isEditing' => false])

<div class="bg-white rounded-xl shadow-sm border transition-all duration-150
            {{ $isSelected ? 'border-indigo-400 ring-2 ring-indigo-100' : 'border-gray-200' }}">

    @if($isEditing)
        {{-- Edit Mode --}}
        <div class="p-5 space-y-3">
            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Editing Project</h3>

            <div>
                <input wire:model.defer="editName" type="text"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-400
                           @error('editName') border-red-400 bg-red-50 @enderror"/>
                @error('editName')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <textarea wire:model.defer="editDescription" rows="2"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                       focus:outline-none focus:ring-2 focus:ring-indigo-400 resize-none">
            </textarea>

            <div class="flex gap-2">
                <button wire:click="updateProject"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-4 py-2 rounded-lg">
                    Save Changes
                </button>
                <button wire:click="cancelEditing"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold px-4 py-2 rounded-lg">
                    Cancel
                </button>
            </div>
        </div>

    @else
        {{-- View Mode --}}
        <div class="p-5">
            <div class="flex items-start justify-between gap-4">

                {{-- Project Info --}}
                <button wire:click="selectProject({{ $project->id }})" class="flex-1 text-left group">
                    <h3 class="font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors">
                        {{ $project->name }}
                    </h3>

                    @if($project->description)
                        <p class="text-sm text-gray-500 mt-0.5">{{ $project->description }}</p>
                    @endif

                    {{-- Progress --}}
                    <div class="mt-3 flex items-center gap-3">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full
                            {{ $project->tasks_count > 0 && $project->completed_tasks_count === $project->tasks_count
                                ? 'text-green-700 bg-green-100'
                                : 'text-indigo-700 bg-indigo-100' }}">
                            {{ $project->completed_tasks_count }}/{{ $project->tasks_count }} Tasks Completed
                        </span>

                        @if($project->tasks_count > 0)
                            <div class="flex-1 max-w-32 bg-gray-200 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full transition-all
                                    {{ $project->completed_tasks_count === $project->tasks_count ? 'bg-green-500' : 'bg-indigo-500' }}"
                                    style="width: {{ round(($project->completed_tasks_count / $project->tasks_count) * 100) }}%">
                                </div>
                            </div>
                        @endif
                    </div>
                </button>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-1 shrink-0">
                    <button wire:click="selectProject({{ $project->id }})" title="View tasks"
                        class="p-2 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
                        {{ $isSelected ? '▲' : '▼' }}
                    </button>
                    <button wire:click="startEditing({{ $project->id }})" title="Edit"
                        class="p-2 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">✏️</button>
                    <button wire:click="deleteProject({{ $project->id }})"
                        wire:confirm="Delete '{{ $project->name }}'?"
                        title="Delete"
                        class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">🗑️</button>
                </div>
            </div>
        </div>

        {{-- Task Panel --}}
        {{ $slot }}
    @endif
</div>
