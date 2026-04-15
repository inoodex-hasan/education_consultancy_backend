@extends('admin.layouts.master')

@section('title', 'VFS Checklist: ' . $application->application_id)

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-semibold uppercase">VFS Document Checklist</h2>
        <p class="text-xs text-white-dark mt-1">
            {{ $application->application_id }} |
            {{ $application->student->first_name }} {{ $application->student->last_name }} |
            {{ $application->university?->name ?? 'No University' }}
        </p>
    </div>
    <a href="{{ route('admin.vfs-checklist.index') }}" class="btn btn-secondary">Back to List</a>
</div>

@if (session('success'))
<div class="mt-4 p-4 border border-success bg-success/5 text-success rounded">{{ session('success') }}</div>
@endif

<div class="mt-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="vfsChecklist()">
        <!-- Progress Sidebar -->
        <div class="space-y-6">
            <div class="panel">
                <h5 class="text-sm font-semibold uppercase mb-4">Completion Status</h5>

                @php
                $totalItems = $checklistItems->count();
                $checkedItems = $checklistItems->where('is_checked', true)->count();
                $progress = $totalItems > 0 ? round(($checkedItems / $totalItems) * 100, 0) : 0;
                @endphp

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold uppercase text-white-dark">Checked:</span>
                        <span class="font-mono text-lg font-black text-success">{{ $checkedItems }} /
                            {{ $totalItems }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 dark:bg-gray-700">
                        <div class="bg-{{ $progress == 100 ? 'success' : 'primary' }} h-3 rounded-full transition-all"
                            style="width: {{ $progress }}%"></div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold uppercase text-white-dark">Progress:</span>
                        <span class="font-mono text-2xl font-black {{ $progress == 100 ? 'text-success' : '' }}">{{
                            $progress }}%</span>
                    </div>
                </div>

                @if($progress == 100)
                <div class="mt-6 p-4 bg-success/10 border border-success/30 rounded-lg text-center">
                    <svg class="w-10 h-10 mx-auto text-success mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-xs font-bold text-success uppercase">All Documents Collected!</p>
                </div>
                @endif
            </div>

            <div class="panel">
                <h6 class="text-xs font-bold uppercase mb-4 text-white-dark">Bulk Actions</h6>
                <div class="space-y-3">
                    <button @click="selectUnchecked()" class="btn btn-sm btn-outline-primary w-full"
                        x-show="selectedItems.length === 0">
                        Select All Unchecked
                    </button>
                    <button @click="selectAll()" class="btn btn-sm btn-outline-primary w-full"
                        x-show="selectedItems.length > 0 && selectedItems.length < {{ $checklistItems->count() }}"
                        x-cloak>
                        Select All
                    </button>
                    <button @click="bulkCheck()" class="btn btn-sm btn-success w-full" x-show="selectedItems.length > 0"
                        x-cloak>
                        Check Selected (<span x-text="selectedItems.length"></span>)
                    </button>
                    <button @click="selectedItems = []" class="btn btn-sm btn-outline-danger w-full"
                        x-show="selectedItems.length > 0" x-cloak>
                        Clear Selection
                    </button>
                </div>
            </div>
        </div>

        <!-- Checklist Items -->
        <div class="lg:col-span-2">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="text-lg font-semibold uppercase">Document Items</h5>
                </div>

                <div class="space-y-2">
                    @foreach($checklistItems as $index => $item)
                    <div class="p-4 border rounded-lg transition-all hover:shadow-md flex items-center gap-4 group"
                        :class="{ 'bg-success/10 border-success/30': items_{{ $item->id }} }">

                        <!-- Item Number -->
                        <span class="text-xs font-bold text-white-dark w-6 shrink-0">{{ str_pad($index + 1, 2, '0',
                            STR_PAD_LEFT) }}</span>

                        <!-- Checkbox -->
                        <input type="checkbox" :value="{{ $item->id }}" v-model="selectedItems"
                            :checked="items_{{ $item->id }}" class="w-5 h-5 rounded cursor-pointer shrink-0" />

                        <!-- Item Details -->
                        <div class="flex-1">
                            <button @click="toggleItem({{ $item->id }})"
                                class="font-medium text-left w-full {{ $item->is_checked ? 'text-success line-through' : '' }}">
                                {{ $item->checklist_item }}
                            </button>
                            @if($item->checkedBy)
                            <p class="text-[10px] text-white-dark mt-0.5">
                                Checked by <span class="font-medium">{{ $item->checkedBy->name }}</span>
                                on {{ $item->checked_at->format('M d, Y') }}
                            </p>
                            @endif
                        </div>

                        <!-- Notes -->
                        <div class="w-48 shrink-0">
                            <input type="text" x-model="notes_{{ $item->id }}" @blur="updateNotes({{ $item->id }})"
                                @keyup.enter="updateNotes({{ $item->id }})" placeholder="Add note..."
                                class="form-input text-xs py-1" value="{{ $item->notes }}" />
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function vfsChecklist() {
        return {
            selectedItems: [],
                @foreach($checklistItems as $item)
                                                                                    items_{ { $item -> id } }: { { $item -> is_checked ? 'true' : 'false' } },
                    notes_{ { $item -> id } }: '{{ addslashes($item->notes ?? '') }}',
            @endforeach

        toggleItem(itemId) {
            fetch(`{{ route('admin.vfs-checklist.toggle-item', ':item') }}`.replace(':item', itemId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    this['items_' + itemId] = data.is_checked;
                    // Remove from selection if unchecked
                    if (!data.is_checked) {
                        const index = this.selectedItems.indexOf(itemId);
                        if (index > -1) this.selectedItems.splice(index, 1);
                    }
                }
            });
        },

        updateNotes(itemId) {
            const notes = this['notes_' + itemId];
            fetch(`{{ route('admin.vfs-checklist.update-notes', ':item') }}`.replace(':item', itemId), {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ notes: notes })
            });
        },

        selectAll() {
            this.selectedItems = [
                @foreach($checklistItems as $item) {{ $item-> id }
    }, @endforeach
                                                    ];
            },

    selectUnchecked() {
        this.selectedItems = [
            @foreach($checklistItems as $item)
            @if (!$item -> is_checked) { { $item -> id } }, @endif
        @endforeach
                                                    ];
    },

    bulkCheck() {
        if (this.selectedItems.length === 0) return;
        fetch(`{{ route('admin.vfs-checklist.bulk-check', $application) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ item_ids: this.selectedItems })
        }).then(res => res.json()).then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
    }
        }
                                        }
</script>
@endpush