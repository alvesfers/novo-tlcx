@extends('layouts.fullscreen-layout')

@section('content')
    <div class="relative bg-white dark:bg-gray-900">
        <div class="relative flex min-h-screen w-full flex-col lg:flex-row">
            <!-- Left side - Form section -->
            <div class="flex w-full flex-col lg:w-1/2">
                <!-- Header -->
                <div class="px-6 py-6 sm:px-10 sm:py-8">
                    <a href="/"
                        class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        <svg class="stroke-current" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 20 20" fill="none">
                            <path d="M12.7083 5L7.5 10.2083L12.7083 15.4167" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span>Back</span>
                    </a>
                </div>

                <!-- Main content -->
                <div class="flex flex-1 flex-col justify-center px-6 sm:px-10">
                    <div class="w-full max-w-sm">
                        <!-- Title section -->
                        <div class="mb-8">
                            <h1 class="mb-3 text-3xl font-bold text-gray-900 dark:text-white">Welcome back</h1>
                            <p class="text-base text-gray-600 dark:text-gray-400">Sign in to your account to continue</p>
                        </div>

                        <!-- Social login buttons -->
                        <div class="mb-6 grid gap-3 sm:grid-cols-2">
                            <a href="{{ route('auth.google') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.7511 10.1944C18.7511 9.47495 18.6915 8.94995 18.5626 8.40552H10.1797V11.6527H15.1003C15.0011 12.4597 14.4654 13.675 13.2749 14.4916L13.2582 14.6003L15.9087 16.6126L16.0924 16.6305C17.7788 15.1041 18.7511 12.8583 18.7511 10.1944Z" fill="#4285F4" />
                                    <path d="M10.1788 18.75C12.5895 18.75 14.6133 17.9722 16.0915 16.6305L13.274 14.4916C12.5201 15.0068 11.5081 15.3666 10.1788 15.3666C7.81773 15.3666 5.81379 13.8402 5.09944 11.7305L4.99473 11.7392L2.23868 13.8295L2.20264 13.9277C3.67087 16.786 6.68674 18.75 10.1788 18.75Z" fill="#34A853" />
                                    <path d="M5.10014 11.7305C4.91165 11.186 4.80257 10.6027 4.80257 9.99992C4.80257 9.3971 4.91165 8.81379 5.09022 8.26935L5.08523 8.1534L2.29464 6.02954L2.20333 6.0721C1.5982 7.25823 1.25098 8.5902 1.25098 9.99992C1.25098 11.4096 1.5982 12.7415 2.20333 13.9277L5.10014 11.7305Z" fill="#FBBC05" />
                                    <path d="M10.1789 4.63331C11.8554 4.63331 12.9864 5.34303 13.6312 5.93612L16.1511 3.525C14.6035 2.11528 12.5895 1.25 10.1789 1.25C6.68676 1.25 3.67088 3.21387 2.4155 9.4593C2.3615 9.61687 2.3615 9.78794 2.41549 9.94552C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C13.5184 15.3619 16.5055 13.0957 17.5849 9.94555C17.6389 9.78797 17.6389 9.6169 17.5849 9.45932C16.5055 6.30919 13.5184 4.04297 10.0002 4.04297ZM9.99151 7.84413C8.96527 7.84413 8.13333 8.67606 8.13333 9.70231C8.13333 10.7286 8.96527 11.5605 9.99151 11.5605H10.0064C11.0326 11.5605 11.8646 10.7286 11.8646 9.70231C11.8646 8.67606 11.0326 7.84413 10.0064 7.84413H9.99151Z" fill="#EB4335" />
                                </svg>
                                <span class="hidden sm:inline">Google</span>
                            </a>

                            <a href="{{ route('auth.twitter') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                                <svg width="18" height="18" class="fill-current" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.6705 1.875H18.4272L12.4047 8.75833L19.4897 18.125H13.9422L9.59717 12.4442L4.62554 18.125H1.86721L8.30887 10.7625L1.51221 1.875H7.20054L11.128 7.0675L15.6705 1.875ZM14.703 16.475H16.2305L6.37054 3.43833H4.73137L14.703 16.475Z" />
                                </svg>
                                <span class="hidden sm:inline">X</span>
                            </a>
                        </div>

                        <!-- Divider -->
                        <div class="relative mb-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="bg-white px-2 text-gray-500 dark:bg-gray-900 dark:text-gray-400">Or continue with email</span>
                            </div>
                        </div>

                        <!-- Email/Password form -->
                        <form action="{{ route('login') }}" method="POST" class="space-y-4">
                            @csrf

                            <!-- Email -->
                            <div>
                                <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                    Email address
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    placeholder="you@example.com"
                                    autocomplete="email"
                                    value="{{ old('email') }}"
                                    required
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder:text-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-brand-500 dark:focus:ring-brand-500/30" />
                                @error('email')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <div class="mb-2 flex items-center justify-between">
                                    <label for="password" class="text-sm font-medium text-gray-900 dark:text-white">
                                        Password
                                    </label>
                                    <a href="/reset-password" class="text-sm text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                                        Forgot?
                                    </a>
                                </div>
                                <div x-data="{ showPassword: false }" class="relative">
                                    <input
                                        id="password"
                                        name="password"
                                        :type="showPassword ? 'text' : 'password'"
                                        placeholder="••••••••"
                                        autocomplete="current-password"
                                        required
                                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder:text-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-brand-500 dark:focus:ring-brand-500/30" />
                                    <button
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <svg x-show="!showPassword" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 3C5.58 3 1.73 6.46 1 11c.73 4.54 4.58 8 10 8s9.27-3.46 10-8c-.73-4.54-4.58-8-10-8zm0 14c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6zm0-10c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z" />
                                        </svg>
                                        <svg x-show="showPassword" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" style="display: none">
                                            <path d="M11.83 9L15.29 5.54c.39-.39.39-1.02 0-1.41-.39-.39-1.02-.39-1.41 0L10.41 7.59 7.12 4.3c-.39-.39-1.02-.39-1.41 0-.39.39-.39 1.02 0 1.41L8.59 9 5.3 12.29c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0L10.41 10.59l3.29 3.29c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L11.83 9z" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remember me -->
                            <div x-data="{ checked: false }" class="flex items-center">
                                <input
                                    type="checkbox"
                                    id="remember"
                                    name="remember"
                                    @change="checked = !checked"
                                    class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-brand-600" />
                                <label for="remember" class="ml-2.5 text-sm text-gray-700 dark:text-gray-300">
                                    Keep me signed in
                                </label>
                            </div>

                            <!-- Submit button -->
                            <button
                                type="submit"
                                class="w-full rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 dark:bg-brand-600 dark:hover:bg-brand-700 dark:focus:ring-offset-gray-900">
                                Sign in
                            </button>
                        </form>

                        <!-- Sign up link -->
                        <div class="mt-6 border-t border-gray-200 pt-6 dark:border-gray-700">
                            <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                                Don't have an account?
                                <a href="/signup" class="font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                                    Sign up
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="border-t border-gray-200 px-6 py-6 text-center text-xs text-gray-500 dark:border-gray-700 dark:text-gray-400 sm:px-10">
                    <p>Protected by modern security standards</p>
                </div>
            </div>

            <!-- Right side - Branding section -->
            <div class="relative hidden bg-gradient-to-br from-brand-600 to-brand-800 lg:flex lg:w-1/2 lg:flex-col lg:items-center lg:justify-center">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute -right-40 -top-40 h-96 w-96 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="absolute -left-40 -bottom-40 h-96 w-96 rounded-full bg-white/5 blur-3xl"></div>
                </div>

                <div class="relative z-10 max-w-sm text-center">
                    <div class="mb-8 flex justify-center">
                        <div class="rounded-xl bg-white/10 p-4 backdrop-blur-sm">
                            <img src="./images/logo/auth-logo.svg" alt="TailAdmin" class="h-16 w-auto" />
                        </div>
                    </div>

                    <h2 class="mb-3 text-2xl font-bold text-white">TailAdmin</h2>
                    <p class="text-lg text-white/80">
                        Free and Open-Source Tailwind CSS Admin Dashboard Template
                    </p>

                    <div class="mt-12 flex justify-center gap-4">
                        <div class="text-left">
                            <div class="text-2xl font-bold text-white">100%</div>
                            <div class="text-sm text-white/70">Open Source</div>
                        </div>
                        <div class="text-left">
                            <div class="text-2xl font-bold text-white">∞</div>
                            <div class="text-sm text-white/70">Free Forever</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Theme toggle -->
            <div class="fixed bottom-6 right-6 z-50">
                <button
                    @click.prevent="$store.theme.toggle()"
                    class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-brand-600 text-white transition-colors hover:bg-brand-700 dark:bg-brand-700 dark:hover:bg-brand-600"
                    aria-label="Toggle theme">
                    <svg class="hidden h-5 w-5 fill-current dark:block" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                    </svg>
                    <svg class="h-5 w-5 fill-current dark:hidden" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM7.05 11.464l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM5.828 5.828a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endsection
