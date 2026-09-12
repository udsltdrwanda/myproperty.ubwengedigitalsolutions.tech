<x-form-section submit="updatePassword">
    <x-slot name="title">
        {{ __('Update Password') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <label for="current_password" class="block text-xs sm:text-sm font-semibold text-slate-600 mb-1.5">{{ __('Current Password') }}</label>
            <input id="current_password" type="password" wire:model="state.current_password" autocomplete="current-password"
                class="focus-uds block w-full bg-slate-50 border border-slate-300 hover:border-slate-400 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition-all duration-200" />
            <x-input-error for="current_password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <label for="password" class="block text-xs sm:text-sm font-semibold text-slate-600 mb-1.5">{{ __('New Password') }}</label>
            <input id="password" type="password" wire:model="state.password" autocomplete="new-password"
                class="focus-uds block w-full bg-slate-50 border border-slate-300 hover:border-slate-400 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition-all duration-200" />
            <x-input-error for="password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <label for="password_confirmation" class="block text-xs sm:text-sm font-semibold text-slate-600 mb-1.5">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" type="password" wire:model="state.password_confirmation" autocomplete="new-password"
                class="focus-uds block w-full bg-slate-50 border border-slate-300 hover:border-slate-400 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition-all duration-200" />
            <x-input-error for="password_confirmation" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3 text-xs sm:text-sm font-semibold text-uds-green" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <button type="submit"
            class="btn-uds inline-flex items-center justify-center text-white text-sm sm:text-base font-semibold px-6 py-3 rounded-xl shadow-lg hover:-translate-y-0.5 transition-all duration-200">
            {{ __('Save') }}
        </button>
    </x-slot>
</x-form-section>
