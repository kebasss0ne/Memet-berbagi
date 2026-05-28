<x-guest-layout>

    <div class="mb-4 text-sm text-gray-600 text-center">
        Masukkan kode OTP yang dikirim ke email kamu
    </div>

    {{-- ERROR MESSAGE --}}
    @if(session('error'))
        <div class="mb-4 text-sm text-red-600 text-center">
            {{ session('error') }}
        </div>
    @endif

    {{-- SUCCESS / STATUS --}}
    @if(session('status'))
        <div class="mb-4 text-sm text-green-600 text-center">
            {{ session('status') }}
        </div>
    @endif

    {{-- FORM VERIFY OTP --}}
    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf

        <!-- OTP INPUT -->
        <div>
            <x-input-label for="otp" value="OTP Code" />

            <x-text-input 
                id="otp"
                class="block mt-1 w-full text-center text-lg tracking-widest"
                type="text"
                name="otp"
                maxlength="6"
                inputmode="numeric"
                pattern="[0-9]*"
                required
                autofocus
                placeholder="••••••"
            />

            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <!-- VERIFY BUTTON -->
        <div class="flex items-center justify-center mt-4">
            <x-primary-button>
                Verify OTP
            </x-primary-button>
        </div>
    </form>

    {{-- RESEND OTP --}}
    <div class="flex flex-col items-center justify-center mt-6">

        <form method="POST" action="{{ route('otp.resend') }}">
            @csrf

            <button type="submit"
                class="text-sm text-blue-600 hover:underline">
                Kirim ulang OTP
            </button>
        </form>

        <p class="text-xs text-gray-400 mt-2">
            Tidak menerima kode? cek spam email kamu
        </p>

    </div>

</x-guest-layout>
