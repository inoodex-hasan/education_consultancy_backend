@extends('errors.layout')
@section('title', 'Page Not Found')

@section('content')
    <div class="mb-8 flex justify-center">
        <div class="w-24 h-24 rounded-full bg-warning/20 dark:bg-warning/10 flex items-center justify-center">
            <svg class="w-12 h-12 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>

    <h1 class="text-7xl font-bold text-[#1b2e4b] dark:text-white-light mb-4">404</h1>
    <h2 class="text-2xl font-semibold text-[#1b2e4b] dark:text-white-light mb-4">Page Not Found</h2>

    <p class="text-base leading-relaxed mb-8">
        @if($exception->getMessage())
            {{ $exception->getMessage() }}
        @else
            The page you are looking for does not exist or has been moved.
        @endif
    </p>

    <div class="flex gap-4 justify-center">
        <a href="{{ route('tyro-dashboard.index') }}"
           class="px-6 py-2.5 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
            Go to Dashboard
        </a>
    </div>
@endsection