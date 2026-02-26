@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-4 md:px-8 py-12">

    <div class="bg-gray-900/80 p-6 md:p-8 rounded-2xl border border-gray-800 shadow-xl">
        <h1 class="text-2xl md:text-3xl font-bold mb-6 flex items-center gap-3">
            <span>👤</span> Edit Profile
        </h1>

        <form method="POST"
              action="{{ route('profile.update') }}"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf

            {{-- AVATAR SECTION --}}
            <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-gray-800">
                {{-- CURRENT AVATAR --}}
                <div class="relative group">
                    @if(auth()->user()->avatar)
                        <img id="avatarPreview"
                             src="{{ asset('storage/' . auth()->user()->avatar) }}"
                             class="w-28 h-28 rounded-full object-cover border-4 border-red-500/50 group-hover:border-red-500 transition">
                    @else
                        <div id="avatarPreview"
                             class="w-28 h-28 rounded-full flex items-center justify-center text-3xl font-bold text-white animated-avatar"
                             style="background: linear-gradient(135deg, #ef4444, #f97316);">
                            {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-black/50 rounded-full opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                        <span class="text-white text-sm">Ubah</span>
                    </div>
                </div>

                {{-- UPLOAD --}}
                <div class="flex-1 w-full">
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Foto Profile
                    </label>
                    <input type="file"
                           name="avatar"
                           id="avatarInput"
                           accept="image/*"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl p-3 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 outline-none transition text-sm">
                    <p class="text-xs text-gray-500 mt-2">
                        📁 JPG, PNG. Maks 2MB.
                    </p>
                </div>
            </div>

            {{-- NAME --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Nama Lengkap</label>
                <input type="text"
                       name="name"
                       value="{{ auth()->user()->name }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl p-3 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 outline-none transition">
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                <input type="email"
                       value="{{ auth()->user()->email }}"
                       disabled
                       class="w-full bg-gray-800/50 border border-gray-700 rounded-xl p-3 opacity-60 cursor-not-allowed">
                <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah</p>
            </div>

            {{-- PHONE --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Nomor HP</label>
                <input type="text"
                       name="phone"
                       value="{{ auth()->user()->phone ?? '' }}"
                       placeholder="0812-3456-7890"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl p-3 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 outline-none transition">
            </div>

            <hr class="border-gray-800">

            {{-- PASSWORD --}}
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        🔒 Ganti Password (Opsional)
                    </label>
                    <input type="password"
                           name="password"
                           placeholder="Password baru"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl p-3 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Konfirmasi Password
                    </label>
                    <input type="password"
                           name="password_confirmation"
                           placeholder="Konfirmasi password baru"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl p-3 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 outline-none transition">
                </div>
            </div>

            <button type="submit"
                    class="btn-primary w-full py-3 rounded-xl font-semibold transition">
                💾 Simpan Perubahan
            </button>

        </form>
    </div>

</div>

<script>
document.getElementById('avatarInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('avatarPreview');
        preview.src = e.target.result;
        preview.classList.remove('animated-avatar');
        preview.style.background = 'none';
    };
    reader.readAsDataURL(file);
});
</script>

@endsection
