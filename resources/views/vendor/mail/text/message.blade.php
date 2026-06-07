<x-mail::layout>
    {{-- Green Header --}}
    <x-slot:header>
        <div style="background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%); color: #fff; padding: 20px 0; text-align: center; border-radius: 8px 8px 0 0; font-size: 1.5rem; font-weight: bold; letter-spacing: 0.5px;">
            Alaminos City PWD Portal
        </div>
    </x-slot:header>

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        <x-slot:subcopy>
            <x-mail::subcopy>
                {{ $subcopy }}
            </x-mail::subcopy>
        </x-slot:subcopy>
    @endisset

    {{-- Green Footer --}}
    <x-slot:footer>
        <div style="background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%); color: #fff; padding: 16px 0 12px 0; text-align: center; border-radius: 0 0 8px 8px; font-size: 1rem; font-weight: 500; margin-top: 32px;">
            <div style="font-size: 1.1rem; font-weight: 600;">Best regards,</div>
            <div style="font-size: 1rem;">Alaminos City PWD Affairs Office</div>
            <div style="color: #fff; font-size: 0.98rem; margin-top: 8px; margin-bottom: 0;">📧 pwd.support@alaminoscity.gov.ph &nbsp;|&nbsp; 📞 (075) 123-4567</div>
            <div style="color: #e0f2ef; font-size: 0.93rem; margin-top: 4px;">🔒 Secure email • Protected by SSL encryption</div>
        </div>
    </x-slot:footer>
</x-mail::layout>
