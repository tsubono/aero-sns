@props(['id', 'title'])

<div id="{{ $id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-black/40" onclick="closeModal('{{ $id }}')"></div>
    <div class="relative bg-white rounded-[16px] shadow-xl w-full max-w-[400px] p-6 flex flex-col gap-5">
        <h3 class="text-[16px] font-bold m-0 text-[#1a1f27]">{{ $title }}</h3>
        {{ $slot }}
    </div>
</div>

@once
<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
</script>
@endonce
