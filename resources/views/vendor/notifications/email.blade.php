@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level == 'error')
# Whoops!
@else
# ¡Hola!
@endif
@endif

{{-- Intro Lines --}}
Estas recibiendo este correo porque alguien ha solicitado restaurar tu contraseña de 1.5 Onzas

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
            $color = 'green';
            break;
        case 'error':
            $color = 'red';
            break;
        default:
            $color = 'blue';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
Restaurar contraseña
@endcomponent
@endisset

{{-- Outro Lines --}}
Si no solicitaste este cambio, ignora este correo.

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
Saludos,<br>{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')
Si tienes problemas con el botón de "{{ $actionText }}", copia y pega el siguiente enlace en tu navegador: [{{ $actionUrl }}]({{ $actionUrl }})
@endcomponent
@endisset
@endcomponent
