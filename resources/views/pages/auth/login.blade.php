<x-layouts::auth :title="__('Log in')">
    <div class="flex flex-col gap-6">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-zinc-900">Welcome back</h2>
            <p class="text-zinc-500 mt-1 text-sm">Enter your credentials to access your account</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required="true"
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <div class="flex flex-col gap-1">
                @if (Route::has('password.request'))
                    <div class="flex items-center justify-end">
                        <flux:link class="text-sm" :href="route('password.request')" wire:navigate="true">
                            {{ __('Forgot password?') }}
                        </flux:link>
                    </div>
                @endif
                <flux:input
                    name="password"
                    type="password"
                    required="true"
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    viewable
                />
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" value="1" :label="__('Remember me')" :checked="old('remember')" />

            <div class="pt-2">
                <flux:button variant="primary" type="submit" class="w-full py-3" data-test="login-button">
                    {{ __('Sign in') }}
                </flux:button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="text-center text-sm text-zinc-500 pt-2 border-t border-zinc-200">
                <span>Don't have an account?</span>
                <flux:link :href="route('register')" wire:navigate="true" class="font-semibold">{{ __('Create one') }}</flux:link>
            </div>
        @endif
    </div>
</x-layouts::auth>
