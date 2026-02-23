@extends('admin.layouts.master')

@section('title', 'Manage Categories')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Finance Categories</h2>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mt-6">
        <!-- Add Category Form -->
        <div class="panel">
            <h5 class="mb-5 text-lg font-semibold">Add New Category</h5>
            <form action="{{ route('admin.finance-categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name">Category Name</label>
                    <input type="text" name="name" id="name" class="form-input" placeholder="e.g. Marketing"
                        required>
                </div>
                <div class="mb-4">
                    <label for="type">Type</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="expense">Expense Only</option>
                        <option value="income">Income Only</option>
                        {{-- <option value="both">Both</option> --}}
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-full">Add Category</button>
            </form>
        </div>

        <!-- Categories List -->
        <div class="panel lg:col-span-2">
            <h5 class="mb-5 text-lg font-semibold">Existing Categories</h5>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td class="font-semibold">{{ $category->name }}</td>
                                <td class="uppercase text-xs">
                                    <span
                                        class="badge {{ $category->type == 'expense' ? 'badge-outline-danger' : ($category->type == 'income' ? 'badge-outline-success' : 'badge-outline-primary') }}">
                                        {{ $category->type }}
                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="badge {{ $category->is_active ? 'badge-outline-success' : 'badge-outline-warning' }}">
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('admin.finance-categories.destroy', $category) }}"
                                            method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-danger hover:text-red-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path
                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                    </path>
                                                    <line x1="10" y1="11" x2="10" y2="17">
                                                    </line>
                                                    <line x1="14" y1="11" x2="14" y2="17">
                                                    </line>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
