@section('title','| Document')

<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Document</h1>
            </div>
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <x-datepicker />
                @auth
                    @php
                        $user = Auth::user();
                        $accessLevels = explode(',', $user->access_level);
                    @endphp

                    @if($user->role === 'Admin' || ($user->role === 'User' && (in_array('Import', $accessLevels) || in_array('Add', $accessLevels))))
                        @if($user->role === 'Admin' || in_array('Import', $accessLevels))
                            <button onclick="showModal('import-student-modal')" class="btn bg-primary-500/90 text-white hover:bg-primary-500 dark:bg-gray1 dark:text-dafault dark:hover:bg-gray1/90">
                                <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                                </svg>
                                <span class="max-xs:sr-only">Import</span>
                            </button>
                        @endif

                        <!-- @if($user->role === 'Admin' || in_array('Add', $accessLevels))
                            <button onclick="showModal('add-student-modal')" class="btn bg-primary-500/90 text-white hover:bg-primary-500 dark:bg-gray1 dark:text-dafault dark:hover:bg-gray1/90">
                                <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 1-1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                                </svg>
                                <span class="max-xs:sr-only">Add Student</span>
                            </button>
                        @endif -->
                    @endif
                @endauth
            </div>
        </div>

        <div class="w-full bg-white dark:bg-gray-800 p-4 rounded-lg">
            <livewire:Student-table />
        </div>
        
        <x-bladewind.notification />

        <x-bladewind.modal
            size="large"
            title="Import Student Record"
            name="import-student-modal"
            ok_button_label="Import File"
            ok_button_action="importStudent()"
            close_after_action="false"
            backdrop_can_close="false" >

            <form action="{{ route('import-users') }}" method="POST" class="import-student-form" enctype="multipart/form-data">
                @csrf
                <div class="w-full p-4">
                    <div class="flex gap-4 w-full items-start">
                        <div class="flex-1 min-w-0 flex flex-col">
                            <label for="import_student"></label>
                            <x-bladewind.filepicker
                                name="import_student"
                                required="true"
                                placeholder="Supported files: Excel and CSV"
                                accepted_file_types=".xls,.xlsx,.csv" />
                        </div>
                    </div>                  
                </div>
            </form>

            <x-bladewind.processing
                name="student-importing"
                message="Importing students, please wait..." />
        </x-bladewind.modal>

        <x-bladewind.modal
            size="large"
            title="Add Student"
            name="add-student-modal"
            ok_button_label="Submit"
            ok_button_action="submitAddStudent()"
            close_after_action="false" >

            <form action="{{ route('add-student') }}" method="POST" class="add-student-form">
                @csrf
                <div class="w-full p-4">
                    <div class="flex gap-4 w-full border-b-2 border-gray1 pb-4 mb-4 items-start">
                        <div class="flex-1 min-w-0 flex flex-col">
                            <label for="last_name" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Last Name
                            </label>
                            <x-bladewind.input 
                                name="last_name" 
                                required="true"
                                placeholder="e.g., Juan" 
                                class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                                error_message="Please enter a last name."
                            />
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col">
                            <label for="first_name" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                First Name
                            </label>
                            <x-bladewind.input 
                                name="first_name" 
                                required="true"
                                placeholder="e.g., Dela Cruz" 
                                class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                                error_message="Please enter a first name."
                            />
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col">
                            <label for="middle_name" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Middle Name
                            </label>
                            <x-bladewind.input 
                                name="middle_name"
                                placeholder="e.g., Santos" 
                                class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                            />
                        </div>
                    </div>

                    <div class="flex gap-4 w-full border-b-2 border-gray1 pb-4 mb-4 items-start">
                        <div class="flex-1 min-w-0 flex flex-col">
                            <label for="birth_date" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Birth Date
                            </label>
                            <x-bladewind.datepicker
                                name="birth_date"
                                required="true"
                                format="yyyy-mm-dd"
                                class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                                error_message="Please enter a birth date."
                            />
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col">
                            <label for="middle_name" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Student Number
                            </label>
                            <x-bladewind.input 
                                name="middle_name"
                                required="true"
                                placeholder="e.g. 2022xxxxx"
                                class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                                error_message="Please enter an student number."
                            />
                        </div>
                    </div>

                    <div class="flex gap-4 w-full border-b-2 border-gray1 pb-4 mb-4 items-start">
                        <div class="flex-1 min-w-0 flex flex-col">
                            <label for="program" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Program
                            </label>
                                <?php
                                    $programs = [
                                        [ 'label' => 'Pharmacy', 'value' => 'cop' ],
                                        [ 'label' => 'Pre-dentistry', 'value' => 'pdent' ],
                                        [ 'label' => 'Pre-dentistry', 'value' => 'pdent' ],
                                        [ 'label' => 'AB Psychology', 'value' => 'abpsy' ],
                                        [ 'label' => 'BS Biology', 'value' => 'bsbio' ],
                                        [ 'label' => 'BS Psychology', 'value' => 'bspsy' ],
                                        [ 'label' => 'BS Education', 'value' => 'bsed' ],
                                        [ 'label' => 'BS Physical Therapy', 'value' => 'bspt' ],
                                        [ 'label' => 'BS Nursing', 'value' => 'bsn' ],
                                        [ 'label' => 'Graduate of Midwifery', 'value' => 'gradmid' ],
                                        [ 'label' => 'BS Computer Science', 'value' => 'bscomsci' ],
                                        [ 'label' => 'Doctor of Dental Medicine', 'value' => 'docdm' ],
                                        [ 'label' => 'Dental Technician', 'value' => 'dt' ],
                                        [ 'label' => 'Doctor of Optometry', 'value' => 'docop' ],
                                        [ 'label' => 'BS Medical Technology', 'value' => 'bsmt' ],
                                        [ 'label' => 'BS Accountancy', 'value' => 'bsa' ],
                                        [ 'label' => 'BS Business Administration', 'value' => 'bsba' ],
                                        [ 'label' => 'BS Information Technology', 'value' => 'bsit' ],
                                        [ 'label' => 'BS Computer Secretarial', 'value' => 'bscomsec' ],
                                        [ 'label' => 'Doctor of Philosophy', 'value' => 'docphi' ],
                                        [ 'label' => 'BS Chemistry', 'value' => 'bsc' ],
                                        [ 'label' => 'Master of Arts in Education', 'value' => 'mae' ],
                                        [ 'label' => 'Master of Arts in Nursing', 'value' => 'man' ],
                                        [ 'label' => 'Master in Business Administration', 'value' => 'mba' ],
                                        [ 'label' => 'Master of Science in Dentistry', 'value' => 'msd' ],
                                        [ 'label' => 'Doctor of Medicine', 'value' => 'docmed' ],
                                        [ 'label' => 'Elementary/Junior High School/Pre-school', 'value' => 'jhs' ],
                                        [ 'label' => 'Science Technology Engineering and Mathematics (STEM)', 'value' => 'stem' ],
                                        [ 'label' => 'Accountancy Business Management (ABM)', 'value' => 'abm' ],
                                        [ 'label' => 'Humanities and Social Science (HUMSS)', 'value' => 'humss' ],
                                        [ 'label' => 'General Academics (GAS)', 'value' => 'gas' ]
                                    ];
                                ?>
                                <x-bladewind.select
                                    name="program"
                                    required="true"
                                    data="{{ json_encode($programs) }}"
                                    class="text-xs rounded border border-gray2 hover:border-secondary focus:border focus:border-secondary"
                                />
                        </div>
                    </div>

                    <div class="flex gap-4 w-full items-start">
                        <div class="flex-1 min-w-0 flex flex-col">
                            <label for="document" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Document</label>
                            <x-bladewind.filepicker
                                name="document"
                                placeholder="Supported file: PDF - Max 5 MB."
                                max_file_size="5"
                                accepted_file_types=".pdf" />
                        </div>
                    </div>
                </div>
            </form>

            <x-bladewind.processing
                name="adding-student"
                message="Adding student, please wait..." />

            <x-bladewind.process-complete
                name="student-added-yes"
                process_completed_as="passed"
                button_label="Done"
                button_action="hideModal('add-student-modal')"
                message="Student added successfully." />
        </x-bladewind.modal>

        @php
            $student = $students ?? null;
        @endphp

        @if($student)
            <x-bladewind.modal size="omg" title="Student Information" name="view-modal" ok_button_label="" cancel_button_label="" show_close_icon="true">
                <div class="h-[80vh] overflow-hidden p-2">
                    <div class="flex h-full gap-2">
                        <!-- Left Panel -->
                        <div class="w-1/4 flex flex-col space-y-2 h-full">
                            <x-bladewind.card class="!p-2 flex-1 overflow-auto">
                                <x-bladewind.list-view>
                                    <x-bladewind.list-item>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-slate-900 dark:text-gray-200">Full Name</div>
                                            <div class="text-sm text-slate-500 truncate">
                                                @if($student->last_name){{ $student->last_name }},@endif
                                                {{ $student->first_name }} 
                                                @if($student->middle_name){{ substr($student->middle_name, 0, 1) }}.@endif 
                                                {{ $student->extension_name }}
                                            </div>
                                        </div>
                                    </x-bladewind.list-item>
                                </x-bladewind.list-view>

                                <x-bladewind.list-view>
                                    <x-bladewind.list-item>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-slate-900 dark:text-gray-200">Student Number</div>
                                            <div class="text-sm text-slate-500 truncate">{{ $student->student_number }}</div>
                                        </div>
                                    </x-bladewind.list-item>
                                </x-bladewind.list-view>

                                <x-bladewind.list-view>
                                    <x-bladewind.list-item>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-slate-900 dark:text-gray-200">Birth Date</div>
                                            <div class="text-sm text-slate-500 truncate">{{ $student->birth_date }}</div>
                                        </div>
                                    </x-bladewind.list-item>
                                </x-bladewind.list-view>

                                <x-bladewind.list-view>
                                    <x-bladewind.list-item>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-slate-900 dark:text-gray-200">Program</div>
                                            <div class="text-sm text-slate-500 truncate">{{ $student->program }}</div>
                                        </div>
                                    </x-bladewind.list-item>
                                </x-bladewind.list-view>

                                <x-bladewind.list-view>
                                    <x-bladewind.list-item>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-slate-900 dark:text-gray-200">Year Graduated</div>
                                            <div class="text-sm text-slate-500 truncate">{{ $student->year_graduated }}</div>
                                        </div>
                                    </x-bladewind.list-item>
                                </x-bladewind.list-view>
                            </x-bladewind.card>

                            <x-bladewind.card class="!p-2">
                                <div class="flex gap-4">
                                    <button 
                                        onclick="window.location.href='{{ route('document-download', ['selected' => $student->filename]) }}?t={{ now()->timestamp }}'" 
                                        class="bg-primary-500/90 hover:bg-primary-500 text-white font-semibold py-2 px-4 rounded flex-1 flex items-center justify-center gap-2 dark:bg-gray1 dark:text-dafault dark:hover:bg-gray1/90">
                                        <i class="fas fa-download"></i>
                                        Download
                                    </button>
                                    <button 
                                        onclick="openPrintWindow('{{ $student->filename }}')" 
                                        class="bg-secondary hover:bg-secondary/90 text-white font-semibold py-2 px-4 rounded flex-1 flex items-center justify-center gap-2 dark:bg-gray1 dark:text-dafault dark:hover:bg-gray1/90">
                                        <i class="fas fa-print"></i>
                                        Print
                                    </button>
                                </div>
                            </x-bladewind.card>
                        </div>

                        <!-- Right Panel -->
                        <div class="w-3/4 h-full flex flex-col min-h-0">
                            <div class="!p-2 h-full flex-1 flex flex-col min-h-0 bg-white border border-slate-200 rounded-lg dark:bg-dark-800/30 dark:border-dark-600/60">
                                <iframe 
                                    src="/document/view/{{ $student->filename }}?file=true#toolbar=0&zoom=100" 
                                    class="w-full h-full flex-1 min-h-0 rounded-lg" 
                                    style="height: 100% !important;" 
                                    frameborder="0">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </x-bladewind.modal>
        @endif
    </div>

    <iframe id="printIframe" style="display:none;"></iframe>
</x-app-layout>

@if(($openModal ?? null) === 'view-modal' && isset($student))
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            showModal('view-modal');
        });
    </script>
@endif

@if(session('import_message'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const importData = @json(session('import_message'));
            showNotification('Import Summary',
                    `Inserted: ${importData.inserted},
                    Updated: ${importData.updated}`,
                'success');
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
    // Bulk (downloadSelected) Action
    Livewire.on('triggerBulkDownload', selected => {
        fetch('{{ route('documents-download-bulk') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ selected }),
        })
            .then(response => {
                if (response.ok) {
                    return response.blob();
                } else {
                    throw new Error('Failed to download files.');
                }
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = 'documents.zip';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while downloading files.');
            });
    });

    // Print Window
    function openPrintWindow(studentNumber) {
        if (!studentNumber) {
            // Redirect to document page with error via backend
            window.location.href = '/document/print';
            return;
        }

        const pdfCheckUrl = `/document/print/${studentNumber}`;

        fetch(pdfCheckUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.error || 'Unknown error');
                });
            }
            return response.json();
        })
        .then(data => {
            // File exists, proceed to print
            const iframe = document.getElementById('printIframe');
            iframe.onload = () => iframe.contentWindow.print();
            iframe.src = pdfCheckUrl;
        })
        .catch(error => {
            // Redirect to trigger Laravel flash message
            window.location.href = pdfCheckUrl;
        });
    }

    // Import Student
    function importStudent() {
        const form = document.querySelector('.import-student-form');
        const filePicker = form.querySelector('input[name="import_student"]');

        let isValid = validateForm('.import-student-form');

        if (!isValid) {
            if (!filePicker.value.trim()) {
                showNotification('Error', 'Please select a file to import.', 'error');
            }
        }

        if (!isValid) return;

        unhide('.student-importing');
        hide('.import-student-form');
        hideModalActionButtons('import-student-modal');

        const formData = new FormData(form);
        makeAjaxCall(formData);
    }

    function makeAjaxCall(formData) {
        fetch(document.querySelector('.import-student-form').action, {
            method: "POST",
            headers: {
                "Accept": "application/json",
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            hideModal('import-student-modal');

            if (data.success) {
                sessionStorage.setItem('importSummary', JSON.stringify({
                    message: data.message,
                    inserted: data.inserted
                }));

                window.location.href = "{{ route('document') }}";
            } else {
                hideModal('import-student-modal');
                showNotification('Error', data.error || data.message || 'Failed to import students.', 'error');
            }
        })
        .catch(error => {
            hideModal('import-student-modal');
            showNotification('Error', 'Failed to import students.', 'error');
        });
    }
</script>