@extends('errors.layout')
@section('title', 'Access Denied')

@section('content')
    <div class="mb-8 flex justify-center">
        <div class="w-24 h-24 rounded-full bg-danger/20 dark:bg-danger/10 flex items-center justify-center">
            <svg class="w-12 h-12 text-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
        </div>
    </div>

    <h1 class="text-7xl font-bold text-[#1b2e4b] dark:text-white-light mb-4">403</h1>
    <h2 class="text-2xl font-semibold text-[#1b2e4b] dark:text-white-light mb-4">Access Denied</h2>

    <p class="text-base leading-relaxed mb-8">
        @if($exception->getMessage() && $exception->getMessage() !== 'This action is unauthorized.')
            {{ $exception->getMessage() }}
        @else
            You don't have permission to access this resource. This could be because:
        @endif
    </p>

    @if(!$exception->getMessage() || $exception->getMessage() === 'This action is unauthorized.')
        <ul class="text-left text-sm space-y-2 mb-8 bg-gray-50 dark:bg-[#1b2e4b] rounded-lg p-4">
            <li class="flex items-start gap-2">
                <span class="text-warning mt-0.5 shrink-0">•</span>
                <span>Your account doesn't have the required role or privilege.</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="text-warning mt-0.5 shrink-0">•</span>
                <span>You are trying to access a record you didn't create.</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="text-warning mt-0.5 shrink-0">•</span>
                <span>Your session may have expired — try logging out and back in.</span>
            </li>
        </ul>
    @endif

    <div class="flex gap-4 justify-center">
        <a href="{{ url()->previous() !== url()->full() ? url()->previous() : route('tyro-dashboard.index') }}"
           class="px-6 py-2.5 bg-white dark:bg-[#1b2e4b] border border-[#e0e6ed] dark:border-[#17263c] text-[#1b2e4b] dark:text-white-light rounded-md hover:bg-gray-50 dark:hover:bg-[#253b5c] transition-colors">
            Go Back
        </a>
        <a href="{{ route('tyro-dashboard.index') }}"
           class="px-6 py-2.5 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
            Go to Dashboard
        </a>
    </div>
@endsection