@props([
    'item',
    'namedRoutes' => true
])

<li class="relative group">

    {{-- Item With Children --}}
    @if(isset($item['children']) && count($item['children']) > 0)

        <div class="flex items-center justify-between w-full p-2 rounded cursor-pointer hover:bg-neutral-tertiary-medium hover:text-heading">
            {{ $item['label'] }}

            <svg class="w-3 h-3 ms-2"
                 xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 5l7 7-7 7"/>
            </svg>
        </div>

        <ul class="absolute top-0 left-full ml-1 hidden group-hover:block bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-56 p-2 text-sm font-medium">

            @foreach($item['children'] as $child)
                <x-custom.menu-item :item="$child" :namedRoutes="$namedRoutes" />
            @endforeach

        </ul>

    {{-- Simple Link Item --}}
    @else

        <a href="{{ $namedRoutes ? route($item['route']) : $item['route'] }}"
           class="inline-flex items-center w-full p-2 rounded hover:bg-neutral-tertiary-medium hover:text-heading">
            {{ $item['label'] }}
        </a>

    @endif

</li>