@props(['mode' => 'search'])
@php
  $mode === 'search' ? $routeName = 'person.search' : $routeName = 'person.find';
@endphp
<div
  x-data="{
    query: '',
    search(){
      axios.get('{{route($routeName)}}', { params: { search: this.query, prop: this.prop } })
      .then(response => { $dispatch('items-updated', response.data) })
    },
    open: false,
    prop: 'id',
    label: 'All categories',
  }"
  {{$attributes}}
>
  <form class="max-w-2xl mx-auto m-3">
    @csrf
    <div class="flex shadow-xs rounded-base -space-x-0.5">
      <button @click="open = !open" id="dropdown-button" type="button" class="flex items-center shrink-0 text-body bg-gray-400 box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading font-medium leading-5 rounded-s-base text-sm px-4 py-2.5">
        <svg class="w-4 h-4 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.143 4H4.857A.857.857 0 0 0 4 4.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 10 9.143V4.857A.857.857 0 0 0 9.143 4Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 20 9.143V4.857A.857.857 0 0 0 19.143 4Zm-10 10H4.857a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286A.857.857 0 0 0 9.143 14Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286a.857.857 0 0 0-.857-.857Z"/></svg>
        All categories
        <svg class="w-4 h-4 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
      </button>
      <div x-show="open" @click.outside="open = false" id="dropdown" class="absolute z-10 bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44">
          <ul class="p-2 text-sm text-body font-medium" aria-labelledby="dropdown-button">
            <li class="p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md"
              @click="
              prop = 'id';
              label = 'ID';
              open = false;
              "
            >
              ID
            </li>
            <li class="p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md"
              @click="
              prop = 'name';
              label = 'Name';
              open = false;
              "
            >
              Name
            </li>
          </ul>
      </div>
      <input 
      type="search" id="search-dropdown" id="input-group-1" class="px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm focus:ring-brand focus:border-brand block w-full placeholder:text-body rounded-r-[12px]" placeholder="Search..." required
        x-model="query" @input.debounce.500ms="search">
    </div>
  </form>  
</div>