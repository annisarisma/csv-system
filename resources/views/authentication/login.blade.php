@extends('layouts.main')

@section('content')
<div class="container">
    <form class="shadow-lg body-tertiary rounded" method="POST" action="/login/store">
        @csrf
        <h2 class="mb-4">Login</h2>
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" placeholder="johndoe" name="username" value="{{ old('username') }}">
          @error('username')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Type your password here" name="password" value="{{ old('password') }}">
          @error('password')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="mb-3">
          <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" name="remember" checked />
          <label class="form-check-label" for="flexSwitchCheckChecked">Remember me</label>
        </div>
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary">Login</button>
        </div>
    
        <p class="mt-4">Doesn't have an account? <a href="/register">Register</a></p>
    </form>
</div>
@endsection

@section('script')
    {{-- Calling flash message toastr --}}
    @include('layouts.flash-message')
@endsection