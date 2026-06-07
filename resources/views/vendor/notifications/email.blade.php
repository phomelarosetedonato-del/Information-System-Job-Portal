@component('mail::message')
{{-- Header --}}
@if (!empty($greeting))
# {{ $greeting }}
@endif

{{-- Introductory Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
@component('mail::button', ['url' => $actionUrl, 'color' => isset($actionColor) ? $actionColor : 'primary'])
{{ $actionText }}
@endcomponent
@endisset

{{-- Body Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (!empty($salutation))
{{ $salutation }}
@else
Best regards,<br>
**{{ config('app.name') }}**
@endif

@component('mail::subcopy')
@isset($actionText)
If you're having trouble clicking the "{{ $actionText }}" button, copy and paste the URL below into your web browser:

[{{ $displayableActionUrl }}]({{ $actionUrl }})
@endisset
@endcomponent
@endcomponent
