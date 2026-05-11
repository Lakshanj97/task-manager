@props(['show' => false, 'project' => null])

@if($show && $project)
    <div class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm" wire:click="closeTaskModal"></div>

    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[85vh] flex flex-col" wire:click.stop>
            {{-- Header --}}
            <div class="px-6 pt-6 pb-4 border-b border-gray-100 flex items-center justify-between shrink-0">
                <div>
                    <h2 class="text-base font-semibold text-gray-800">{{ $project->name }}</h2>
                    @if($project->description)
                        <p class="text-xs text-gray-400 mt-0.5">{{ $project->description }}</p>
                    @endif
                </div>
                <button wire:click="closeTaskModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Task Manager Component --}}
            <div class="flex-1 overflow-y-auto p-6">
                @livewire('task-manager', ['project' => $project], key('tasks-' . $project->id))
            </div>
        </div>
    </div>
@endif
