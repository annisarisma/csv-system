<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CSV System | {{ $title }}</title>
    @include('layouts.style')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>
    @include('layouts.navbar')
    <div class="wrapper">
        {{-- Content --}}
        @yield('content')
        {{-- End of Content --}}
    </div>

    {{-- Script --}}
    @include('layouts.script')
    @yield('script')
</body>

</html>