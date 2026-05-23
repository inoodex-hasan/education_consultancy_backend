@extends('errors.layout')
@section('title', 'Server Error')

@section('content')
    <div class="mb-8 flex justify-center">
        <div class="w-24 h-24 rounded-full bg-danger/20 dark:bg-danger/10 flex items-center justify-center">
            <svg class="w-12 h-12 text-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
        </div>
    </div>

    <h1 class="text-7xl font-bold text-[#1b2e4b] dark:text-white-light mb-4">500</h1>
    <h2 class="text-2xl font-semibold text-[#1b2e4b] dark:text-white-light mb-4">Server Error</h2>

    <p class="text-base leading-relaxed mb-8">
        @if($exception->getMessage())
            {{ $exception->getMessage() }}
        @else
            Something went wrong on our end. Please try again later.
        @endif
    </p>

    <div class="flex gap-4 justify-center">
        <a href="{{ route('tyro-dashboard.index') }}"
           class="px-6 py-2.5 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
            Go to Dashboard
        </a>
    </div>
@endsection