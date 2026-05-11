@props([
    'nameModel'        => 'name',
    'descriptionModel' => 'description',
    'submitAction'     => 'createProject',
    'cancelAction'     => null,
    'submitLabel'      => 'Create Project',
    'title'            => 'Create New Project',
])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $title }}</h2>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Project Name <span class="text-red-500">*</span>
            </label>
            <input
                wire:model.defer="{{ $nameModel }}"
                type="text"
                placeholder="Enter project name..."
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                       focus:outline-none focus:ring-2 focus:ring-indigo-400
                       @error($nameModel) border-red-400 bg-red-50 @enderror"
            />
            @error($nameModel)
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea
                wire:model.defer="{{ $descriptionModel }}"
                rows="3"
                placeholder="Optional description..."
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                       focus:outline-none focus:ring-2 focus:ring-indigo-400 resize-none"
            ></textarea>
        </div>

        <div class="flex gap-2">
            <button
                wire:click="{{ $submitAction }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors"
            >
                {{ $submitLabel }}
            </button>

            @if($cancelAction)
                <button
                    wire:click="{{ $cancelAction }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors"
                >
                    Cancel
                </button>
            @endif
        </div>
    </div>
</div>
