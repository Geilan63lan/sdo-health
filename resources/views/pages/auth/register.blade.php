<x-layouts::auth :title="__('Register')">
    <div class="flex flex-col gap-6">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-zinc-900">Create account</h2>
            <p class="text-zinc-500 mt-1 text-sm">Enter your details to get started</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Full name')"
                :value="old('name')"
                type="text"
                required="true"
                autofocus
                autocomplete="name"
                :placeholder="__('John Doe')"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required="true"
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required="true"
                autocomplete="new-password"
                :placeholder="__('Min. 8 characters')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required="true"
                autocomplete="new-password"
                :placeholder="__('Re-enter password')"
                viewable
            />

            <div class="pt-2">
                <flux:button type="submit" variant="primary" class="w-full py-3" data-test="register-user-button">
                    {{ __('Create account') }}
                </flux:button>
            </div>
        </form>

        <div class="text-center text-sm text-zinc-500 pt-2 border-t border-zinc-200">
            <span>Already have an account?</span>
            <flux:link :href="route('login')" wire:navigate="true" class="font-semibold">{{ __('Sign in') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
