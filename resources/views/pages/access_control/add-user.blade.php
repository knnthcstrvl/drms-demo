@section('title','| Access Control')

<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Add User</h1>
            </div>
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <x-datepicker />
                <button onclick="showModal('add-user-modal')" class="btn bg-primary-500/90 text-white hover:bg-primary-500 dark:bg-gray1 dark:text-dafault dark:hover:bg-gray1/90">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="max-xs:sr-only">Add User</span>
                </button>
            </div>
        </div>

        <div class="w-full bg-white dark:bg-gray-800 p-4 rounded-lg">
            <livewire:AccessControlAddUser-table />
        </div>
    </div>

    <x-bladewind.notification />

    <x-bladewind.modal
        size="large"
        title="Add User"
        name="add-user-modal"
        ok_button_label="Submit"
        ok_button_action="submitAddUser()"
        close_after_action="false" >

        <form action="{{ route('add-user-store') }}" method="POST" class="add-user-form">
            @csrf
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
                            class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                            error_message="Please enter a full name."
                        />
                    </div>
                    <div class="flex-1 min-w-0 flex flex-col">
                        <label for="username" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Username
                        </label>
                        <x-bladewind.input 
                            name="username" 
                            required="true" 
                            placeholder="Choose a unique username" 
                            class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                            error_message="Please enter a username."
                        />
                    </div>
                </div>

                <div class="flex gap-4 w-full border-b-2 border-gray1 pb-4 mb-4 items-start dark:border-gray1/10">
                    <div class="flex-1 min-w-0 flex flex-col">
                        <label for="email" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email
                        </label>
                        <x-bladewind.input 
                            name="email"
                            required="true" 
                            type="email"
                            placeholder="e.g., user@example.com" 
                            class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                            error_message="Please enter an email."
                        />
                    </div>
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
                            data="{{ json_encode($role) }}"
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
                                    [ 'label' => 'Allow Reset Password', 'value' => 'Reset' ],
                                ];
                            ?>
                        <x-bladewind.select
                            name="access_level"
                            required="true"
                            label_key="label"
                            value_key="value"
                            multiple="true"
                            data="{{ json_encode($access_level) }}"
                            selected_value="View"
                            class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
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
                            error_message="Please enter a password."
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
                            placeholder="Re-enter the password" 
                            viewable="true" 
                            class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                            error_message="Please confirm the password."
                        />
                    </div>
                </div>
            </div>
        </form>

        <x-bladewind.processing
            name="adding-user"
            message="Adding user, please wait..." />
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
    const submitAddUser = () => {
        const form = document.querySelector('.add-user-form');
        const usernameInput = form.querySelector('input[name="username"]');
        const emailInput = form.querySelector('input[name="email"]');
        const roleSelect = form.querySelector('input[name="role"]');
        const accessLevelSelect = form.querySelector('input[name="access_level"]');
        const passwordInput = form.querySelector('input[name="password"]');
        const confirmPasswordInput = form.querySelector('input[name="confirm_password"]');

        let isValid = validateForm('.add-user-form');

        if (!isValid) {
            document.querySelectorAll('.add-user-form input[required]').forEach(input => {
                input.classList.toggle('border-red-500', !input.value.trim());
                input.classList.toggle('border-gray-300', input.value.trim());
            });

            if (!roleSelect.value.trim()) {
                showNotification('Error', 'Please select at least one role.', 'error');
            }

            if (!accessLevelSelect.value.trim()) {
                showNotification('Error', 'Please select at least one access level.', 'error');
            }
        }

        if (emailInput.value.trim() !== "" && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
            showNotification('Error', 'Please enter a valid email address.', 'error');
            emailInput.classList.add('border-red-500');
            isValid = false;
        }

        if (passwordInput.value.trim() !== confirmPasswordInput.value.trim()) {
            showNotification('Error', 'Passwords do not match.', 'error');
            passwordInput.classList.add('border-red-500');
            confirmPasswordInput.classList.add('border-red-500');
            isValid = false;
        }

        if (!isValid) return;

        checkExistingUser(usernameInput, emailInput, () => {
            unhide('.adding-user');
            hide('.add-user-form');
            hideModalActionButtons('add-user-modal');
            makeAjaxCall(serialize('.add-user-form'));
        });
    };

    const checkExistingUser = async (usernameInput, emailInput, callback) => {
        try {
            const response = await fetch("{{ route('access-control.check-username-email') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ 
                    username: usernameInput.value.trim(), 
                    email: emailInput.value.trim() 
                })
            });

            const data = await response.json();

            usernameInput.classList.remove('border-red-500');
            usernameInput.classList.add('border-gray-300');
            emailInput.classList.remove('border-red-500');
            emailInput.classList.add('border-gray-300');

            if (data.exists) {
                showNotification('Error', data.message, 'error');

                if (data.username_exists) {
                    usernameInput.classList.add('border-red-500');
                    usernameInput.classList.remove('border-gray-300');
                }

                if (data.email_exists) {
                    emailInput.classList.add('border-red-500');
                    emailInput.classList.remove('border-gray-300');
                }
            } else {
                callback();
            }
        } catch (error) {
            showNotification('Error', 'Failed to check username/email.', 'error');
        }
    };

    const makeAjaxCall = (formData) => {
        fetch("{{ route('add-user-store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify(Object.fromEntries(new FormData(document.querySelector('.add-user-form'))))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                setTimeout(() => {
                    window.location.href = "{{ route('add-user') }}";
                }, 1500);
            } else {
                showNotification('Error', data.message || 'Something went wrong.', 'error');

                setTimeout(() => {
                    window.location.href = "{{ route('add-user') }}";
                }, 1500);
            }
        })
        .catch(() => {
            showNotification('Error', 'Failed to add user.', 'error');

            setTimeout(() => {
                window.location.href = "{{ route('add-user') }}";
            }, 1500);
        });
    };
</script>
