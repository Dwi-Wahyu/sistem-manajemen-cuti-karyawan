@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between items-center">
    {{-- Tombol Previous --}}
    @if ($paginator->onFirstPage())
    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
        {!! __('Sebelumnya') !!}
    </span>
    @else
    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:text-gray-200 dark:active:bg-gray-700">
        {!! __('Sebelumnya') !!}
    </a>
    @endif

    {{-- Tombol Next --}}
    @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:text-gray-200 dark:active:bg-gray-700">
        {!! __('Selanjutnya') !!}
    </a>
    @else
    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
        {!! __('Selanjutnya') !!}
    </span>
    @endif
</nav>
@endif