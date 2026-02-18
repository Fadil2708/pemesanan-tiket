@extends('layouts.app')

@section('content')

<div class="bg-gray-900 p-8 rounded-2xl border border-gray-800">

    <form method="POST"
          action="{{ route('profile.update') }}"
          enctype="multipart/form-data"
          class="space-y-8">

        @csrf

        {{-- AVATAR SECTION --}}
        <div class="flex items-center gap-6">

            {{-- CURRENT AVATAR --}}
            <div class="relative">

                @if(auth()->user()->avatar)
                    <img id="avatarPreview"
                         src="{{ asset('storage/' . auth()->user()->avatar) }}"
                         class="w-24 h-24 rounded-full object-cover border-2 border-red-500">
                @else
                    <div id="avatarPreview"
                         class="w-24 h-24 rounded-full flex items-center justify-center text-2xl font-bold text-white animated-avatar"
                         style="background: linear-gradient(135deg, #ef4444, #f97316);">
                        {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                    </div>
                @endif

            </div>

            {{-- UPLOAD --}}
            <div class="flex-1">
                <label class="block text-sm text-gray-400 mb-2">
                    Foto Profile
                </label>

                <input type="file"
                       name="avatar"
                       id="avatarInput"
                       accept="image/*"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 focus:border-red-500 outline-none">

                <p class="text-xs text-gray-500 mt-2">
                    JPG, PNG. Maks 2MB.
                </p>
            </div>

        </div>

        {{-- NAME --}}
        <div>
            <label class="block text-sm text-gray-400 mb-2">Nama</label>
            <input type="text"
                   name="name"
                   value="{{ auth()->user()->name }}"
                   class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 focus:border-red-500 outline-none">
        </div>

        {{-- EMAIL --}}
        <div>
            <label class="block text-sm text-gray-400 mb-2">Email</label>
            <input type="email"
                   value="{{ auth()->user()->email }}"
                   disabled
                   class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 opacity-60">
        </div>

        {{-- PHONE --}}
        <div>
            <label class="block text-sm text-gray-400 mb-2">No HP</label>
            <input type="text"
                   name="phone"
                   value="{{ auth()->user()->phone }}"
                   class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 focus:border-red-500 outline-none">
        </div>

        <hr class="border-gray-800">

        {{-- PASSWORD --}}
        <div>
            <label class="block text-sm text-gray-400 mb-3">
                Ganti Password (Opsional)
            </label>

            <input type="password"
                   name="password"
                   placeholder="Password baru"
                   class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 mb-3 focus:border-red-500 outline-none">

            <input type="password"
                   name="password_confirmation"
                   placeholder="Konfirmasi password"
                   class="w-full bg-gray-800 border border-gray-700 rounded-lg p-3 focus:border-red-500 outline-none">
        </div>

        <button type="submit"
                class="w-full bg-red-600 hover:bg-red-700 py-3 rounded-lg font-semibold transition">
            Simpan Perubahan
        </button>

    </form>

</div>

<script>
document.getElementById('avatarInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('avatarPreview');

        preview.innerHTML = '';
        preview.classList.remove('animated-avatar');
        preview.style.background = 'none';

        preview.outerHTML =
            `<img id="avatarPreview"
                  src="${e.target.result}"
                  class="w-24 h-24 rounded-full object-cover border-2 border-red-500">`;
    };

    reader.readAsDataURL(file);
});
</script>

@endsection
