@extends('admin.layouts.master')

@section('title', 'Manage Currencies')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Manage Currencies</h2>
        <a href="{{ route('admin.currencies.refresh') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.3" />
            </svg>
            Refresh Rates
        </a>
    </div>

    @canany(['*accountant'])
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mt-6">
            <!-- Add Currency Form -->
            <div class="panel h-fit">
                <h5 class="mb-5 text-lg font-semibold">Add New Currency</h5>
                <form action="{{ route('admin.currencies.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="code">Currency Code (e.g. USD) <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="code" class="form-input" placeholder="USD" required
                            max="10">
                        @error('code')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="symbol">Symbol (e.g. $) </label>
                        <input type="text" name="symbol" id="symbol" class="form-input" placeholder="$" max="10">
                        @error('symbol')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="is_active">Status</label>
                        <select name="is_active" id="is_active" class="form-select" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Add Currency</button>
                </form>
            </div>
        @endcanany

        <!-- Currencies List -->
        <div class="panel lg:col-span-2">
            <h5 class="mb-5 text-lg font-semibold">Existing Currencies</h5>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Symbol</th>
                            <th>Exchange Rate (to BDT)</th>
                            <th>Last Updated</th>
                            <th>Status</th>
                            @canany(['*accountant'])
                                <th class="text-center">Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($currencies as $currency)
                            <tr>
                                <td class="font-bold text-primary">{{ $currency->code }}</td>
                                <td>{{ $currency->symbol ?? '-' }}</td>
                                <!-- <td>
                                                                                                    @if ($currency->exchange_rate)
    <span class="font-semibold text-success">1 BDT =
                                                                                                            {{ number_format($currency->exchange_rate, 6) }} {{ $currency->code }}</span>
                                                                                                        <div class="text-[10px] text-white-dark">Converting to BDT: amount / rate</div>
@else
    -
    @endif
                                                                                                </td> -->
                                <td>
                                    @if ($currency->exchange_rate && $currency->exchange_rate > 0)
                                        <span class="font-semibold text-success">
                                            1 {{ $currency->code }} =
                                            {{ number_format(1 / $currency->exchange_rate, 6) }} BDT
                                        </span>
                                        <!-- <div class="text-[10px] text-white-dark">
                                                                                            Converting to BDT: amount × rate
                                                                                        </div> -->
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-sm">
                                    {{ $currency->last_updated_at ? $currency->last_updated_at->diffForHumans() : 'Never' }}
                                </td>
                                <td>
                                    <span
                                        class="badge {{ $currency->is_active ? 'badge-outline-success' : 'badge-outline-warning' }}">
                                        {{ $currency->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                @canany(['*accountant'])
                                    <td>
                                        <div class="flex items-center justify-center gap-2">
                                            <form action="{{ route('admin.currencies.destroy', $currency) }}" method="POST"
                                                onsubmit="return confirm('Are you sure? This will fail if the currency is used in courses.')">
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
                                @endcanany
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
