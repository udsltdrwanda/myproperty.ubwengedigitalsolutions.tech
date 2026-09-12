<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{ photoName: null, photoPreview: null }" class="col-span-6 sm:col-span-4">
                <input type="file" id="photo" class="hidden" wire:model.live="photo" x-ref="photo"
                    x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <label for="photo" class="block text-xs sm:text-sm font-semibold text-slate-600 mb-1.5">{{ __('Photo') }}</label>

                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}"
                        class="object-cover w-20 h-20 rounded-full ring-2 ring-slate-100">
                </div>

                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block bg-center bg-no-repeat bg-cover w-20 h-20 rounded-full ring-2 ring-slate-100"
                        x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <button type="button"
                    class="mt-2 me-2 inline-flex items-center justify-center bg-white border border-slate-300 text-slate-600 hover:text-uds-navy hover:border-slate-400 text-xs sm:text-sm font-semibold px-4 py-2 rounded-xl transition-all duration-200"
                    x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </button>

                @if ($this->user->profile_photo_path)
                    <button type="button"
                        class="mt-2 inline-flex items-center justify-center bg-white border border-slate-300 text-slate-600 hover:text-uds-navy hover:border-slate-400 text-xs sm:text-sm font-semibold px-4 py-2 rounded-xl transition-all duration-200"
                        wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <label for="name" class="block text-xs sm:text-sm font-semibold text-slate-600 mb-1.5">{{ __('Name') }}</label>
            <input id="name" type="text" wire:model="state.name" required autocomplete="name"
                class="focus-uds block w-full bg-slate-50 border border-slate-300 hover:border-slate-400 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition-all duration-200" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <label for="email" class="block text-xs sm:text-sm font-semibold text-slate-600 mb-1.5">{{ __('Email') }}</label>
            <input id="email" type="email" wire:model="state.email" required autocomplete="username"
                class="focus-uds block w-full bg-slate-50 border border-slate-300 hover:border-slate-400 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition-all duration-200" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) &&
                    !$this->user->hasVerifiedEmail())
                <p class="mt-2 text-xs sm:text-sm text-slate-500 font-medium">
                    {{ __('Your email address is unverified.') }}

                    <button type="button"
                        class="text-xs sm:text-sm text-uds-orange hover:text-uds-orange/80 font-semibold hover:underline"
                        wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 text-xs sm:text-sm font-medium text-uds-green">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        <!-- Company Email -->
        <div class="col-span-6 sm:col-span-4">
            <label for="company_email" class="block text-xs sm:text-sm font-semibold text-slate-600 mb-1.5">{{ __('Company Email') }}</label>
            <input id="company_email" type="email" wire:model.defer="state.company_email" autocomplete="company_email"
                class="focus-uds block w-full bg-slate-50 border border-slate-300 hover:border-slate-400 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition-all duration-200" />
            <x-input-error for="company_email" class="mt-2" />
        </div>

    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3 text-xs sm:text-sm font-semibold text-uds-green" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <button type="submit" wire:loading.attr="disabled" wire:target="photo"
            class="btn-uds inline-flex items-center justify-center text-white text-sm sm:text-base font-semibold px-6 py-3 rounded-xl shadow-lg hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50">
            {{ __('Save') }}
        </button>
    </x-slot>
</x-form-section>
