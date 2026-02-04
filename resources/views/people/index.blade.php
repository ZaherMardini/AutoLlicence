<x-app-layout>
  {{-- <x-custom.list :models="collect()" :columns="$columns"/> --}}
  <x-custom.list :models="$people" :columns="$columns"/>
  {{-- {{ $people->links() }} --}}
</x-app-layout>