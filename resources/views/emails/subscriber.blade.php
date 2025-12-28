@component('mail::message')
    # Subscriber Notification

    Hello,

    Subject : {{ $title }}
    {{ $message }}

    Thanks,
    {{ config('app.name') }}
@endcomponent
