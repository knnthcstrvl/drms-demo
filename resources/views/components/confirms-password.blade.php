@props(['title' => __('Confirm Password'), 'content' => __('For your security, please confirm your password to continue.'), 'button' => __('Confirm')])

@php
    $confirmableId = md5($attributes->wire('then'));
@endphp

<span
    {{ $attributes->wire('then') }}
    x-data
    x-ref="span"
    x-on:click="
        if (typeof showModal === 'function') showModal('confirm-password-modal');
        $wire.startConfirmingPassword('{{ $confirmableId }}');
    "
    x-on:password-confirmed.window="setTimeout(() => $event.detail.id === '{{ $confirmableId }}' && $refs.span.dispatchEvent(new CustomEvent('then', { bubbles: false })), 250);"
>
    {{ $slot }}
</span>

@once
    <div
        x-data="{}"
        x-on:password-confirmed.window="
            if (typeof hideModal === 'function') hideModal('confirm-password-modal');
        "
    >
        <x-bladewind.modal
            wire:ignore.self
            name="confirm-password-modal"
            size="large"
            title="{{ $title }}"
            ok_button_label="{{ $button }}"
            ok_button_action="document.getElementById('confirm-password-modal-submit').click()"
            cancel_button_action="document.getElementById('confirm-password-modal-cancel').click()"
            backdrop_can_close="false">

            <div class="space-y-4">
                <div>{{ $content }}</div>

                <x-input
                    type="password"
                    class="mt-1 block w-full"
                    placeholder="{{ __('Password') }}"
                    x-ref="confirmPasswordInput"
                    wire:model="confirmablePassword"
                    wire:keydown.enter="confirmPassword" />

                <x-input-error for="confirmable_password" class="mt-2" />

                <button type="button" id="confirm-password-modal-cancel" class="hidden" wire:click="stopConfirmingPassword"></button>
                <button type="button" id="confirm-password-modal-submit" class="hidden" dusk="confirm-password-button" wire:click="confirmPassword"></button>
            </div>
        </x-bladewind.modal>
    </div>
@endonce
