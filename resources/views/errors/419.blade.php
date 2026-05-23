@extends('errors.layout')
@section('title', 'Session Expired')

@section('content')
    <div class="mb-8 flex justify-center">
        <div class="w-24 h-24 rounded-full bg-info/20 dark:bg-info/10 flex items-center justify-center">
            <svg class="w-12 h-12 text-info" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>

    <h1 class="text-7xl font-bold text-[#1b2e4b] dark:text-white-light mb-4">419</h1>
    <h2 class="text-2xl font-semibold text-[#1b2e4b] dark:text-white-light mb-4">Session Expired</h2>

    <p class="text-base leading-relaxed mb-8">
        Your session has expired. Please log in again to continue working.
    </p>

    <div class="flex gap-4 justify-center">
        <a href="{{ route('tyro-login.login') }}"
           class="px-6 py-2.5 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
            Log In Again
        </a>
    </div>
@endsection