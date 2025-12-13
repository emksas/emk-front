<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accounting Account') }}
        </h2>
    </x-slot>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-medium text-gray-900">
                        Financial Planning
                    </h1>
                    <p class="mt-6 text-gray-500 leading-relaxed">
                        Welcome to your financial planning dashboard. Here you can manage and review your financial
                        plans and associated operations.
                    </p>
                </div>

                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <div id="accordion-card" data-accordion="collapse" class="mx-4">
                        @foreach ( $financialPlannings as $financialPlanning )
                        <h2 id="accordion-card-heading-1">
                            <button type="button"
                                class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-body rounded-base shadow-xs border border-default hover:text-heading hover:bg-neutral-secondary-medium gap-3 [&[aria-expanded='true']]:rounded-b-none [&[aria-expanded='true']]:shadow-none"
                                data-accordion-target="#accordion-card-body-1" aria-expanded="true"
                                aria-controls="accordion-card-body-1">
                                <span> {{ $financialPlanning['planName'] }} - Total projected value: {{ $financialPlanning['projectedValue'] }} </span>
                                <svg data-accordion-icon class="w-5 h-5 rotate-180 shrink-0" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m5 15 7-7 7 7" />
                                </svg>
                            </button>
                        </h2>
                        <div id="accordion-card-body-1"
                            class="hidden border border-t-0 border-default rounded-b-base shadow-xs"
                            aria-labelledby="accordion-card-heading-1">
                            <div class="p-4 md:p-5">
                                <p class="mb-2 text-body">
                                    {{ $financialPlanning['description'] }}
                                </p>

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>