<div class="space-y-6">

    {{-- Alerts --}}
    @if($errorMessage)
        <x-alert-message type="error" :message="$errorMessage" onDismiss="clearMessages"/>
    @endif
    @if($successMessage)
        <x-alert-message type="success" :message="$successMessage" onDismiss="clearMessages"/>
    @endif

    {{-- Create Form --}}
    <x-project-form
        title="Create New Project"
        submitAction="createProject"
        submitLabel="Create Project"
    />

    {{-- Projects List --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            All Projects
            <span class="text-sm font-normal text-gray-500 ml-2">{{ $projects->count() }} project(s)</span>
        </h2>

        @if($projects->isEmpty())
            <div class="bg-white rounded-xl border border-dashed border-gray-300 p-12 text-center">
                <p class="text-gray-500 text-sm">No projects yet. Create your first project above!</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($projects as $project)
                    <x-project-card
                        :project="$project"
                        :isSelected="$selectedProjectId === $project->id"
                        :isEditing="$editingProjectId === $project->id"
                    >
                        {{-- Task Panel Slot --}}
                        @if($selectedProjectId === $project->id && $selectedProject)
                            <div class="border-t border-gray-100 bg-gray-50 rounded-b-xl">
                                @livewire('task-manager', ['project' => $selectedProject], key('tasks-'.$project->id))
                            </div>
                        @endif
                    </x-project-card>
                @endforeach
            </div>
        @endif
    </div>

</div>
