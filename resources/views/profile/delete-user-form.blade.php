<x-action-section>
    <x-slot name="title">
        {{ __('Delete Account') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Permanently delete your account.') }}
    </x-slot>

    <x-slot name="content">
        <div>
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
            </div>

            <div class="mt-5">
                <x-danger-button onclick="showModal('delete-account-modal')">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>

            <x-bladewind.modal
                wire:ignore.self
                name="delete-account-modal"
                size="large"
                type="warning"
                title="{{ __('Delete Account') }}"
                ok_button_label="{{ __('Delete Account') }}"
                ok_button_action="document.getElementById('delete-account-submit').click()"
                cancel_button_action="document.getElementById('delete-account-cancel').click()"
                backdrop_can_close="false">

                <div class="space-y-4">
                    <div>
                        {{ __('Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </div>

                    <x-input
                        type="password"
                        class="mt-1 block w-full"
                        autocomplete="current-password"
                        placeholder="{{ __('Password') }}"
                        wire:model="password"
                        wire:keydown.enter="deleteUser" />

                    <x-input-error for="password" class="mt-2" />

                    <button type="button" id="delete-account-cancel" class="hidden" wire:click="$set('confirmingUserDeletion', false)"></button>
                    <button type="button" id="delete-account-submit" class="hidden" wire:click="deleteUser"></button>
                </div>
            </x-bladewind.modal>
        </div>
    </x-slot>
</x-action-section>
