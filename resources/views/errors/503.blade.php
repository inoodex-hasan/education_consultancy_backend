@extends('errors.layout')
@section('title', 'Service Unavailable')

@section('content')
    <div class="mb-8 flex justify-center">
        <div class="w-24 h-24 rounded-full bg-secondary/20 dark:bg-secondary/10 flex items-center justify-center">
            <svg class="w-12 h-12 text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
    </div>

    <h1 class="text-7xl font-bold text-[#1b2e4b] dark:text-white-light mb-4">503</h1>
    <h2 class="text-2xl font-semibold text-[#1b2e4b] dark:text-white-light mb-4">Service Unavailable</h2>

    <p class="text-base leading-relaxed mb-8">
        The application is currently under maintenance or experiencing high traffic. Please try again shortly.
    </p>

    <div class="flex gap-4 justify-center">
        <a href="{{ url()->previous() !== url()->full() ? url()->previous() : route('tyro-dashboard.index') }}"
           class="px-6 py-2.5 bg-white dark:bg-[#1b2e4b] border border-[#e0e6ed] dark:border-[#17263c] text-[#1b2e4b] dark:text-white-light rounded-md hover:bg-gray-50 dark:hover:bg-[#253b5c] transition-colors">
            Try Again
        </a>
    </div>
@endsection