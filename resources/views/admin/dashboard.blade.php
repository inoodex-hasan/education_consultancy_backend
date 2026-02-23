@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')
    <!-- Breadcrumb -->
    @if (auth()->check() && auth()->user()->hasRole('admin'))
        <!-- <ul class="flex space-x-2 rtl:space-x-reverse">
                                                                                                                                                                                                                                                                                                                                                                                                                            <li>
                                                                                                                                                                                                                                                                                                                                                                                                                                <a href="{{ route('tyro-dashboard.index') }}" class="text-primary hover:underline">Dashboard</a>
                                                                                                                                                                                                                                                                                                                                                                                                                            </li>
                                                                                                                                                                                                                                                                                                                                                                                                                            <li class="before:content-['/'] ltr:before:mr-2 rtl:before:ml-2">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <span>Sales</span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </li>
                                                                                                                                                                                                                                                                                                                                                                                                                        </ul> -->
        <h5 class="text-xs font-semibold text-[#506690]">Administration</h5>
        <div class="pt-5">
            <!-- Stats Grid -->
            <div class="mb-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                <div class="panel h-full sm:col-span-2 xl:col-span-1">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div class="text-success">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                        stroke="currentColor" stroke-width="1.5" />
                                </svg>
                            </div>
                        </div>
                        <div class="w-full ltr:ml-3 rtl:mr-3">
                            <p class="text-xl dark:text-white-light">{{ number_format($stats['total_roles'] ?? 0) }}</p>
                            <h5 class="text-xs font-semibold text-[#506690]">Total Roles</h5>
                        </div>
                    </div>
                </div>

                <div class="panel h-full sm:col-span-2 xl:col-span-1">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div class="text-info">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"
                                        stroke="currentColor" stroke-width="1.5" />
                                </svg>
                            </div>
                        </div>
                        <div class="w-full ltr:ml-3 rtl:mr-3">
                            <p class="text-xl dark:text-white-light">{{ number_format($stats['total_privileges'] ?? 0) }}
                            </p>
                            <h5 class="text-xs font-semibold text-[#506690]">Total Privileges</h5>
                        </div>
                    </div>
                </div>

                <div class="panel h-full sm:col-span-2 xl:col-span-1">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div class="text-warning">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                                    <ellipse opacity="0.5" cx="12" cy="17" rx="7" ry="4" stroke="currentColor"
                                        stroke-width="1.5" />
                                </svg>
                            </div>
                        </div>
                        <div class="w-full ltr:ml-3 rtl:mr-3">
                            <p class="text-xl dark:text-white-light">{{ number_format($stats['total_users'] ?? 0) }}</p>
                            <h5 class="text-xs font-semibold text-[#506690]">Total Users</h5>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="text-xs font-semibold text-[#506690]">Stats</h5>
            <div class="pt-5">
                <div class="mb-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-4">

                    <!-- Students -->
                    <div class="panel h-full sm:col-span-2 xl:col-span-1">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="text-primary">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            <div class="w-full ltr:ml-3 rtl:mr-3">
                                <p class="text-xl dark:text-white-light">{{ number_format($stats['total_students'] ?? 0) }}</p>
                                <h5 class="text-xs font-semibold text-[#506690]">Total Students</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Applications -->
                    <div class="panel h-full sm:col-span-2 xl:col-span-1">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="text-info">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            <div class="w-full ltr:ml-3 rtl:mr-3">
                                <p class="text-xl dark:text-white-light">{{ number_format($stats['total_applications'] ?? 0) }}
                                </p>
                                <h5 class="text-xs font-semibold text-[#506690]">Total Applications</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Revenue -->
                    <div class="panel h-full sm:col-span-2 xl:col-span-1">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="text-success">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            <div class="w-full ltr:ml-3 rtl:mr-3">
                                <p class="text-xl dark:text-white-light">BDT
                                    {{ number_format($stats['total_revenue'] ?? 0, 2) }}
                                </p>
                                <h5 class="text-xs font-semibold text-[#506690]">Total Revenue</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Leads -->
                    <div class="panel h-full sm:col-span-2 xl:col-span-1">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="text-secondary">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            <div class="w-full ltr:ml-3 rtl:mr-3">
                                <p class="text-xl dark:text-white-light">{{ number_format($stats['total_leads'] ?? 0) }}</p>
                                <h5 class="text-xs font-semibold text-[#506690]">Total Leads</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    @endif

        @if(auth()->check() && auth()->user()->hasRole('editor'))
            <h5 class="text-xs font-semibold text-[#506690] mt-6">Education Data</h5>
            <div class="pt-5 pb-6">
                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                    <!-- Country -->
                    <div class="panel h-full sm:col-span-2 xl:col-span-1">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="text-info">
                                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 3C7.03 3 3 7.03 3 12C3 16.97 7.03 21 12 21C16.97 21 21 16.97 21 12C21 7.03 16.97 3 12 3Z"
                                            stroke="currentColor" stroke-width="1.5" />
                                        <path d="M3 12H21" stroke="currentColor" stroke-width="1.5" />
                                        <path d="M12 3C14.5 6 14.5 18 12 21C9.5 18 9.5 6 12 3Z" stroke="currentColor"
                                            stroke-width="1.5" />
                                    </svg>
                                </div>
                            </div>
                            <div class="w-full ltr:ml-3 rtl:mr-3">
                                <p class="text-xl dark:text-white-light">{{ number_format($stats['total_countries'] ?? 0) }}
                                </p>
                                <h5 class="text-xs font-semibold text-[#506690]">Countries</h5>
                            </div>
                        </div>
                    </div>

                    <!-- University -->
                    <div class="panel h-full sm:col-span-2 xl:col-span-1">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="text-info">
                                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">

                                        <!-- University -->
                                        <path d="M3 10L12 4L21 10" stroke="#16A34A" stroke-width="1.8" stroke-linecap="round"
                                            stroke-linejoin="round" />

                                        <path d="M6 10V18M10 10V18M14 10V18M18 10V18" stroke="#16A34A" stroke-width="1.8"
                                            stroke-linecap="round" />

                                        <path d="M4 18H20" stroke="#16A34A" stroke-width="1.8" stroke-linecap="round" />
                                    </svg>

                                </div>
                            </div>
                            <div class="w-full ltr:ml-3 rtl:mr-3">
                                <p class="text-xl dark:text-white-light">{{ number_format($stats['total_universities'] ?? 0) }}
                                </p>
                                <h5 class="text-xs font-semibold text-[#506690]">Universities</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Course -->
                    <div class="panel h-full sm:col-span-2 xl:col-span-1">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <div class="text-info">
                                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">

                                        <!-- Book -->
                                        <path
                                            d="M5 6C5 5.45 5.45 5 6 5H11C12.1 5 13 5.9 13 7V18
                                                                                                                                                                                                                                                                                     C13 16.9 12.1 16 11 16H6C5.45 16 5 16.45 5 17V6Z"
                                            stroke="#9cd21fff" stroke-width="1.8" stroke-linecap="round"
                                            stroke-linejoin="round" />

                                        <path
                                            d="M19 6C19 5.45 18.55 5 18 5H13C11.9 5 11 5.9 11 7V18
                                                                                                                                                                                                                                                                                     C11 16.9 11.9 16 13 16H18C18.55 16 19 16.45 19 17V6Z"
                                            stroke="#9cd21fff" stroke-width="1.8" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>


                                </div>
                            </div>
                            <div class="w-full ltr:ml-3 rtl:mr-3">
                                <p class="text-xl dark:text-white-light">{{ number_format($stats['total_courses'] ?? 0) }}
                                </p>
                                <h5 class="text-xs font-semibold text-[#506690]">Courses</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(auth()->check() && auth()->user()->hasRole('marketing'))
            @include('admin.marketing.leads.index')
        @endif

        @if(auth()->check() && auth()->user()->hasRole('consultant'))
            @include('admin.students.index')
        @endif

        @if(auth()->check() && auth()->user()->hasRole('application'))
            @include('admin.applications.index')
        @endif

        @if(auth()->check() && auth()->user()->hasRole('accountant'))
            @include('admin.payments.index')
        @endif
    </div>
@endsection