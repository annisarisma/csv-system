@extends('layouts.main')

@section('content')
<div class="container">
    <form class="shadow-lg body-tertiary rounded" method="POST" action="/register/store">
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
          <label for="email" class="form-label">Email Address</label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="name@example.com" name="email" value="{{ old('email') }}">
          @error('email')
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
          <label for="confirm_password" class="form-label">Confirm Password</label>
          <input type="password" class="form-control @error('confirm_password') is-invalid @enderror" id="confirm_password" placeholder="Re-type your password here" name="confirm_password" value="{{ old('confirm_password') }}">
          @error('confirm_password')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary">Register</button>
        </div>
    
        <p class="mt-4">Have an account? <a href="/login">Login</a></p>
    </form>
</div>
@endsection

@section('script')
    {{-- Calling flash message toastr --}}
    @include('layouts.flash-message')
@endsection