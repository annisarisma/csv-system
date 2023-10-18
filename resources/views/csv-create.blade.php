@extends('layouts.main')

@section('content')
<form action="/product-store" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row mb-3">
        <div class="col-sm-12">
            <input id="csv" type="file" name="csv" multiple data-allow-reorder="true" data-max-file-size="1000MB" data-max-files="10" />
            @error('csv')
                <div class="text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <button class="btn btn-primary" type="submit">Upload file</button>
    </div>
</form>

@endsection

@section('script')
    @include('layouts.flash-message')
@endsection