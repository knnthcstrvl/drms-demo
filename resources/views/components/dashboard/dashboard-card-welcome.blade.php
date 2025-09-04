<div class="col-span-12 bg-white dark:bg-gray-800 shadow-sm rounded-xl flex items-start px-8 py-6 min-h-[180px] h-full overflow-hidden">
    <div class="flex-1 flex flex-col justify-between pr-32 h-full">
        <div>
            <div class="text-base md:text-lg font-semibold text-primary-500 dark:text-primary-100 mb-2 flex items-center gap-2">
                Welcome, {{ Auth::user()->full_name }}!
            </div>
            <div class="text-xs md:text-sm text-gray-600 dark:text-gray-300 mb-4 leading-normal">
                You have accessed <span class="font-semibold text-primary-500 dark:text-primary-100">more student documents</span> today.<br>
                Check out the latest documents available for you to review and manage. Stay updated with recent uploads and ensure all records are accurate and up to date for student needs.
            </div>
        </div>
        <div class="flex items-start mt-4">
            <a href="{{ route('document') }}">
                <button class="px-5 py-2 rounded-lg bg-primary-100 text-primary-500 hover:!bg-[#decef3] transition text-xs font-medium w-auto">
                    View Documents
                </button>
            </a>
        </div>
    </div>
    <div class="hidden md:flex flex-shrink-0 ml-8 pr-8 h-full items-end">
        <img src="{{ asset('images/welcome.svg') }}" alt="MCU" class="w-56 h-56 object-contain" style="margin-bottom: -32px;" />
    </div>
</div>