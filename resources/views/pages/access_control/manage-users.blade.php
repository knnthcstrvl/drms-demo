@section('title','| Access Control')

<x-app-layout>

    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Manage Users</h1>
            </div>
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <x-datepicker />
            </div>
        </div>

        <div class="w-full bg-white dark:bg-gray-800 p-4 rounded-lg">
            <livewire:AccessControlManageUser-table />
        </div>
    </div>

    <x-bladewind.notification />

    @if($username)
        <x-bladewind.modal
            size="large"
            title="Edit User"
            name="edit-user-modal"
            ok_button_label="Save Changes"
            ok_button_action="submitUpdateUserDetails()"
            close_after_action="false" >

            <form action="{{ route('manage-users-edit-submit', $username->username) }}" method="POST" class="edit-user-form" data-username="{{ $username->username }}">
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <div class="w-full p-4">
                    <div class="flex gap-4 w-full border-b-2 border-gray1 pb-4 mb-4 items-start dark:border-gray1/10">
                        <div class="flex-1 min-w-0 flex flex-col">
                            <label for="full_name" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Full Name
                            </label>
                            <x-bladewind.input 
                                name="full_name" 
                                required="true" 
                                placeholder="e.g., Juan Dela Cruz"
                                selected_value="{{$username->full_name}}"
                                class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                                error_message="Please enter Full Name."
                            />
                        </div>
                    </div>

                    <div class="flex gap-4 w-full border-b-2 border-gray1 pb-4 mb-4 items-start dark:border-gray1/10">
                        <div class="flex-1 min-w-0 flex flex-col">
                            <label for="username" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Username
                            </label>
                            <x-bladewind.input 
                                name="username" 
                                required="true"
                                disabled="true"
                                placeholder="Choose a unique username"
                                selected_value="{{$username->username}}"
                                class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                                error_message="Please enter Username."
                            />
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col">
                            <label for="email" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email
                            </label>
                            <x-bladewind.input 
                                name="email"
                                type="email"
                                disabled="true"
                                placeholder="e.g., user@example.com"
                                selected_value="{{$username->email}}"
                                class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary" 
                            />
                        </div>
                    </div>

                    <div class="flex gap-4 w-full border-b border-gray1 pb-4 mb-4 items-start dark:border-gray1/10">
                        <div class="flex-1 min-w-0 flex flex-col">
                            <label for="access_level" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Access Level
                            </label>

                            <?php
                                $access_level = [
                                    [ 'label' => 'Allow View', 'value' => 'View' ],
                                    [ 'label' => 'Allow Download', 'value' => 'Download' ],
                                    [ 'label' => 'Allow Print', 'value' => 'Print' ],
                                    [ 'label' => 'Allow Import', 'value' => 'Import' ],
                                    [ 'label' => 'Allow Add Student', 'value' => 'Add' ],
                                ];
                            ?>

                            <x-bladewind.select
                                name="access_level"
                                required="true"
                                label_key="label"
                                value_key="value"
                                multiple="true"
                                selected_value="{{$username->access_level}}"
                                data="{{ json_encode($access_level) }}"
                                class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                            />
                        </div>
                    </div>

                    <div class="flex gap-4 w-full items-start">
                        <div class="flex-1 min-w-0 flex flex-col">
                            <label for="status" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Role
                            </label>
                            <?php
                                $role = [
                                    [ 'label' => 'Admin', 'value' => 'Admin' ],
                                    [ 'label' => 'User', 'value' => 'User' ],
                                ];
                            ?>
                            <x-bladewind.select
                                name="role"
                                required="true"
                                label_key="label"
                                value_key="value"
                                selected_value="{{$username->role}}"
                                data="{{ json_encode($role) }}"
                                class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                            />
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col">
                            <label for="status" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status
                            </label>
                            <?php
                                $status = [
                                    [ 'label' => 'Active', 'value' => 'Active' ],
                                    [ 'label' => 'Inactive', 'value' => 'Inactive' ],
                                ];
                            ?>
                            <x-bladewind.select
                                name="status"
                                required="true"
                                label_key="label"
                                value_key="value"
                                selected_value="{{$username->status}}"
                                data="{{ json_encode($status) }}"
                                class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                            />
                        </div>
                    </div>                    
                </div>
            </form>

            <x-bladewind.processing
                name="editing-user"
                message="Updating user, please wait..." />
        </x-bladewind.modal>
    @endif

    @if($email)
        <x-bladewind.modal
            size="large"
            title="Password Reset"
            name="reset-user-modal"
            ok_button_label="Save Changes"
            ok_button_action="submitResetPassword()"
            close_after_action="false" >

            <form action="{{ route('manage-users-reset-submit', $email->email) }}" method="POST" class="reset-user-form" data-email="{{ $email->email }}">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="w-full p-4">
                    <div class="flex gap-4 w-full border-b-2 border-gray1 pb-4 mb-4 items-start dark:border-gray1/10">
                        <div class="flex-1 min-w-0 flex flex-col">
                            <label for="full_name" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Full Name
                            </label>
                            <x-bladewind.input 
                                name="full_name" 
                                required="true"
                                disabled="true"
                                placeholder="e.g., Juan Dela Cruz"
                                selected_value="{{$email->full_name}}"
                                class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                                error_message="Please enter Full Name."
                            />
                        </div>
                    </div>
                    <div class="flex gap-4 w-full items-start">
                        <div class="flex-1 min-w-0 flex flex-col">
                            <label for="password" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Password
                            </label>
                            <x-bladewind.input 
                                name="password" 
                                required="true"
                                type="password" 
                                placeholder="At least 8 characters" 
                                viewable="true" 
                                class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                                error_message="Please enter your Password."
                            />
                        </div>

                        <div class="flex-1 min-w-0 flex flex-col">
                            <label for="confirm_password" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Confirm Password
                            </label>
                            <x-bladewind.input 
                                name="confirm_password" 
                                required="true" 
                                type="password" 
                                placeholder="Re-enter your password" 
                                viewable="true" 
                                class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                                error_message="Please confirm your Password."
                            />
                        </div>
                    </div>                    
                </div>
            </form>

            <x-bladewind.processing
                name="resetting-user"
                message="Updating user, please wait..."
            />
        </x-bladewind.modal>
    @endif

    <x-bladewind.modal
        size="small"
        title="Delete User"
        name="delete-user-modal"
        ok_button_label="Confirm"
        ok_button_action="confirmDeleteUser()">
        
        <span id="delete-message">Are you sure you want to delete this user(s)?</span>
        
    </x-bladewind.modal>
</x-app-layout>

@if(session('success_notification'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            showNotification('Success', '{{ session('success_notification') }}', 'success');
        });
    </script>
@endif

@if(session('error_notification'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            showNotification('Error', '{{ session('error_notification') }}', 'error');
        });
    </script>
@endif

<script>
    const submitUpdateUserDetails = () => {
        const form = document.querySelector('.edit-user-form');
        const username = form.dataset.username;
        const emailInput = form.querySelector('input[name="email"]');
        const roleSelect = form.querySelector('input[name="role"]');
        const accessLevelSelect = form.querySelector('input[name="access_level"]');
        const statusSelect = form.querySelector('input[name="status"]');

        let isValid = validateForm('.edit-user-form');

        if (!isValid) {
            document.querySelectorAll('.edit-user-form input[required]').forEach(input => {
                input.classList.toggle('border-red-500', !input.value.trim());
                input.classList.toggle('border-gray-300', input.value.trim());
            });

            if (!roleSelect.value.trim()) {
            showNotification('Error', 'Please select at least one role.', 'error');
            }

            if (!accessLevelSelect.value.trim()) {
            showNotification('Error', 'Please select at least one access level.', 'error');
            isValid = false;
            }

            if (!statusSelect.value.trim()) {
                showNotification('Error', 'Please select a status.', 'error');
                isValid = false;
            }
        }

        if (emailInput.value.trim() !== "" && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
            showNotification('Error', 'Please enter a valid email address.', 'error');
            emailInput.classList.add('border-red-500');
            isValid = false;
        }

        if (!isValid) return;

        unhide('.editing-user');
        hide('.edit-user-form');
        hideModalActionButtons('edit-user-modal');

        makeAjaxCall(`/access-control/manage-users/edit/submit/${username}`, new FormData(form));
    };

    const submitResetPassword = () => {
        const form = document.querySelector('.reset-user-form');
        const email = form.dataset.email;
        const passwordInput = form.querySelector('input[name="password"]');
        const confirmPasswordInput = form.querySelector('input[name="confirm_password"]');

        let isValid = validateForm('.reset-user-form');

        if (passwordInput.value.trim() !== confirmPasswordInput.value.trim()) {
            showNotification('Error', 'Passwords do not match.', 'error');
            passwordInput.classList.add('border-red-500');
            confirmPasswordInput.classList.add('border-red-500');
            isValid = false;
        }

        if (!isValid) return;

        unhide('.resetting-user');
        hide('.reset-user-form');
        hideModalActionButtons('reset-user-modal');

        makeAjaxCall(`/access-control/manage-users/reset/submit/${email}`, new FormData(form));
    };

    const makeAjaxCall = (url, formData) => {
        fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                setTimeout(() => {
                    window.location.href = "{{ route('manage-users') }}";
                }, 1500);
            } else {
                showNotification('Error', data.message || 'Something went wrong.', 'error');

                setTimeout(() => {
                    window.location.href = "{{ route('manage-users') }}";
                }, 1500);
            }
        })
        .catch(() => {
            showNotification('Error', 'Request failed.', 'error');

            setTimeout(() => {
                    window.location.href = "{{ route('manage-users') }}";
                }, 1500);
        });
    };

    window.addEventListener('DOMContentLoaded', () => {
        showModal('edit-user-modal');
    });

    window.addEventListener('DOMContentLoaded', () => {
        showModal('reset-user-modal');
    });
    
    window.addEventListener('showDeleteUserModal', () => {
        showModal('delete-user-modal');
    });

    function confirmDeleteUser() {
        // Use plain JavaScript to dispatch Livewire event
        window.dispatchEvent(new CustomEvent('deleteConfirmed'));
        hideModal('delete-user-modal');
    }

    function showDeleteModal(username) {
        // Use Livewire.find() with the component name
        const componentEl = document.querySelector('[wire\\:id]');
        if (componentEl) {
            const wireId = componentEl.getAttribute('wire:id');
            Livewire.find(wireId).call('confirmSingleDelete', username);
        }
    }

    document.addEventListener('livewire:initialized', () => {
        Livewire.on('showDeleteUserModal', () => {
            showModal('delete-user-modal');
        });
    });
</script>