<x-layouts::auth :title="__('Log in')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Sign in')" :description="__('Enter your email and password below to log in')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
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
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('Password') }}</label>
                        <flux:link class="text-sm" :href="route('password.request')" wire:navigate="true">
                            {{ __('Forgot your password?') }}
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

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Log in') }}
                </flux:button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                <span>{{ __('Don\'t have an account?') }}</span>
                <flux:link :href="route('register')" wire:navigate="true">{{ __('Sign up') }}</flux:link>
            </div>
        @endif
    </div>
</x-layouts::auth>
