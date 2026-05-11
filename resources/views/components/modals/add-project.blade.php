@props(['show' => false])

@if($show)
    <div class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm" wire:click="closeAddModal"></div>

    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md" wire:click.stop>
            {{-- Header --}}
            <div class="px-6 pt-6 pb-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-800">Add New Project</h2>
                <button wire:click="closeAddModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Project Name <span class="text-red-500">*</span>
                    </label>
                    <input wire:model.defer="name" type="text" placeholder="Enter project name..."
                           class="w-full border rounded-lg bg-gray-50 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white transition {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}"/>
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                    <textarea wire:model.defer="description" rows="3" placeholder="Optional description..."
                              class="w-full border border-gray-200 rounded-lg bg-gray-50 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white transition resize-none"></textarea>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 pb-6 flex justify-end gap-2">
                <button wire:click="closeAddModal" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Cancel</button>
                <button wire:click="createProject" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-colors">Create Project</button>
            </div>
        </div>
    </div>
@endif
