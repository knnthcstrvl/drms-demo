<div
    x-data="{ holdProcessing: false, holdTimer: null }"
    x-init="@this.on('saved', () => {
        clearTimeout(holdTimer);
        holdProcessing = true;
        holdTimer = setTimeout(() => {
            holdProcessing = false;
            showNotification('Success', '{{ __('Password updated successfully.') }}', 'success');
        }, 2000);
    })">
<x-form-section submit="updatePassword">
    <x-slot name="title">
        {{ __('Update Password') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 contents" wire:loading.remove wire:target="updatePassword" x-bind:class="{ 'hidden': holdProcessing }">
            <div class="col-span-6 sm:col-span-4">
                <x-label for="current_password" value="{{ __('Current Password') }}" />
                <x-input id="current_password" type="password" class="mt-1 block w-full" wire:model.live="state.current_password" autocomplete="current-password" />
                <x-input-error for="current_password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-label for="password" value="{{ __('New Password') }}" />
                <x-input id="password" type="password" class="mt-1 block w-full" wire:model.live="state.password" autocomplete="new-password" />
                <x-input-error for="password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" type="password" class="mt-1 block w-full" wire:model.live="state.password_confirmation" autocomplete="new-password" />
                <x-input-error for="password_confirmation" class="mt-2" />
            </div>
        </div>

        <div class="col-span-6" wire:loading wire:target="updatePassword">
            <x-bladewind.processing
                name="settings-password-updating"
                hide="false"
                message="Updating password, please wait..."
                class="mt-2" />
        </div>
        <div class="col-span-6" x-show="holdProcessing" x-cloak>
            <x-bladewind.processing
                name="settings-password-updated-wait"
                hide="false"
                message="Updating password, please wait..."
                class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-button>
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
</div>
