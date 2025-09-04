<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn bg-primary-500/90 text-gray-100 hover:bg-primary-500 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white whitespace-nowrap']) }}>
    {{ $slot }}
</button>
