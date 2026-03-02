@section('title','| Dashboard')

<x-app-layout>

    <x-bladewind.notification />

    @if(Auth::user()->must_change_password)
        <x-bladewind.modal
            size="medium"
            title="Update Password Required"
            name="force-password-change-modal"
            ok_button_label="Update Password"
            ok_button_action="submitForcePasswordChange()"
            cancel_button_label=""
            close_after_action="false"
            show_close_icon="false"
            backdrop_can_close="false"
            blur_backdrop="true">

            <form id="force-password-change-form" class="p-2">
                <p class="text-sm text-gray-600 mb-4">
                    Your account is using a temporary password from the administrator. Update it now before you continue.
                </p>

                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Current Password
                        </label>
                        <x-bladewind.input
                            name="current_password"
                            type="password"
                            required="true"
                            viewable="true"
                            autocomplete="current-password"
                            placeholder="Enter temporary password"
                            class="force-password-field text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                            error_message="Please enter your current password."
                        />
                        <p id="force-error-current_password" class="mt-1 text-xs text-red-600 hidden"></p>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            New Password
                        </label>
                        <x-bladewind.input
                            name="password"
                            type="password"
                            required="true"
                            viewable="true"
                            autocomplete="new-password"
                            placeholder="At least 8 characters"
                            class="force-password-field text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                            error_message="Please enter a new password."
                        />
                        <p id="force-error-password" class="mt-1 text-xs text-red-600 hidden"></p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm New Password
                        </label>
                        <x-bladewind.input
                            name="password_confirmation"
                            type="password"
                            required="true"
                            viewable="true"
                            autocomplete="new-password"
                            placeholder="Re-enter new password"
                            class="force-password-field text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                            error_message="Please confirm your new password."
                        />
                        <p id="force-error-password_confirmation" class="mt-1 text-xs text-red-600 hidden"></p>
                    </div>
                </div>

            </form>
        </x-bladewind.modal>
    @endif

    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Dashboard actions -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Dashboard</h1>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">

                <!-- Filter button -->
                <x-dropdown-filter align="right" />

                <!-- Datepicker built with flatpickr -->
                <x-datepicker />

                <!-- Add view button -->
                <button class="btn bg-primary-500/90 text-white hover:bg-primary-500 dark:bg-gray1 dark:text-dafault dark:hover:bg-gray1/90">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="max-xs:sr-only">Add View</span>
                </button>
                
            </div>

        </div>
        
        <!-- Cards -->
        <div class="grid grid-cols-12 gap-6">

            <x-dashboard.dashboard-card-welcome  />

            <x-dashboard.dashboard-card-01
                title="Total Student Records" 
                :count="$totalStudents" 
                :percent="$studentPercentage" 
            />

            <x-dashboard.dashboard-card-02
                title="Total Users"
                :count="$totalUsers"
                :percent="$userPercentage"
            />

            <x-dashboard.dashboard-card-03
                title="Total Activities Logged"
                :count="$totalActivities"
                :percent="$activityPercentage"
            />

            <x-dashboard.dashboard-card-06
                title="Role Breakdown"
            />

            <x-dashboard.dashboard-card-07
                title="Most Active Users (Per Activity Type)"
                :mostActiveUsers="$mostActiveUsers"
            />

            <x-dashboard.dashboard-card-12
                title="Recent Activity"
                :recentActivities="$recentActivities"
            />

            <x-dashboard.dashboard-card-10
                title="Recently Added Students"
                :recentlyAddedStudents="$recentlyAddedStudents"
            />

        </div>
    </div>
</x-app-layout>

@if(session('error_notification') && session('redirected_from_restriction'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            showNotification('Error', '{{ session('error_notification') }}', 'error');
        });
    </script>
@endif

@if(Auth::user()->must_change_password)
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            showModal('force-password-change-modal');
            showNotification('Warning', '{{ session('force_password_change_notice', 'Please update your password before continuing.') }}', 'warning');

            const form = document.getElementById('force-password-change-form');
            const modalSubmitBtn = document.querySelector('.bw-force-password-change-modal .modal-footer button.okay');
            const submitBtn = modalSubmitBtn || { disabled: false, textContent: 'Update Password' };

            window.submitForcePasswordChange = function() {
                if (form) {
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                    } else {
                        form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                    }
                }
            };

            const fieldIds = ['current_password', 'password', 'password_confirmation'];

            const clearFieldErrors = () => {
                fieldIds.forEach((field) => {
                    const errorEl = document.getElementById(`force-error-${field}`);
                    const inputEl = form.querySelector(`[name="${field}"]`);

                    if (errorEl) {
                        errorEl.textContent = '';
                        errorEl.classList.add('hidden');
                    }

                    if (inputEl) {
                        inputEl.classList.remove('border-red-500');
                        inputEl.classList.add('border-gray-300');
                    }
                });
            };

            const applyErrors = (errors = {}) => {
                clearFieldErrors();

                Object.entries(errors).forEach(([field, messages]) => {
                    const errorEl = document.getElementById(`force-error-${field}`);
                    const inputEl = form.querySelector(`[name="${field}"]`);
                    const message = Array.isArray(messages) ? messages[0] : messages;

                    if (errorEl && message) {
                        errorEl.textContent = message;
                        errorEl.classList.remove('hidden');
                    }

                    if (inputEl) {
                        inputEl.classList.remove('border-gray-300');
                        inputEl.classList.add('border-red-500');
                    }
                });
            };

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                clearFieldErrors();

                submitBtn.disabled = true;
                const originalLabel = submitBtn.textContent;
                submitBtn.textContent = 'Updating...';

                try {
                    const response = await fetch('{{ route('force-password-change.update') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: new FormData(form),
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        hideModal('force-password-change-modal');
                        showNotification('Success', data.message || 'Password updated successfully.', 'success');
                        form.reset();
                        return;
                    }

                    if (response.status === 422) {
                        applyErrors(data.errors || {});
                        showNotification('Error', data.message || 'Please correct the highlighted fields.', 'error');
                    } else {
                        showNotification('Error', data.message || 'Failed to update password.', 'error');
                    }
                } catch (error) {
                    showNotification('Error', 'Failed to update password.', 'error');
                } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalLabel;
                    const firstInvalid = form.querySelector('.border-red-500');
                    if (firstInvalid) firstInvalid.focus();
                }
            });
        });
    </script>
@endif
