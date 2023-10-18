{{-- Check the session --}}
@if(session('success-alert'))
    <script>
        toastr.success( '{{ session('success-alert')['message'] }}' )
    </script>
@endif