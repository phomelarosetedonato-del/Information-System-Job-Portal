<x-mail::message>
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# 🚨 {{ __('Whoops!') }}
@else
# 👋 {{ __('Hello!') }}
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
🔐 {{ $actionText }}
</x-mail::button>
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Security Notice --}}
<x-mail::panel>
🔒 **Security Notice:** This is an automated email from the Alaminos City PWD Information System. If you did not request this action, please contact our support team immediately.
</x-mail::panel>

{{-- Salutation --}}
@if (! empty($salutation))
{!! $salutation !!}
@else
{{ __('Best regards,') }}

{{ config('app.name') }} Team

Alaminos City PWD Affairs Office
@endif

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
{{ __(
    "If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    'into your web browser:',
    [
        'actionText' => $actionText,
    ]
) }} <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
