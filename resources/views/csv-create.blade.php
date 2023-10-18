@extends('layouts.main')

@section('content')
<form action="/csv-store" method="POST" enctype="multipart/form-data">
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

<div class="table-responsive">
    <div class="card">
        <table id="example" class="table table-striped table-bordered hover" style="width: 100%">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Filename</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="content">
                @php
                    use Carbon\Carbon;
                @endphp
                @foreach ($csvFiles as $csvFile)
                    <tr>
                        <td id="time-{{ $csvFile['id'] }}">{{ Carbon::parse($csvFile['created_at'])->format('d-m-Y H:i') }} {{ '(' . $csvFile['timeAgo'] . ')' }}</td>
                        <td>{{ $csvFile['filename'] }}</td>
                        <td id="status_csv-{{ $csvFile['id'] }}">{{ $csvFile['status'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<input id="username" type="hidden" value="{{ auth()->user()->username }}">

@endsection

@section('script')
    @include('layouts.flash-message')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher('f0b924ed624e024f80e6', {
            cluster: 'ap1'
        });

        var usernameInput = document.getElementById("username");
        var usernameValue = usernameInput.value;
        
        var channel = pusher.subscribe('csvImport-channel');
        channel.bind('csvImport-event', function(data) {
            if (usernameValue !== data.name) {
                toastr.info(JSON.stringify(data.name) + ' recently upload a new file')

                // Append new record to the table
                $("#content").append(`
                    <tr>
                        <td id="time-${data.csvFile.id}">${data.timeSet}</td>
                        <td>${data.csvFile.filename}</td>
                        <td id="status_csv-${data.csvFile.id}">${data.csvFile.status}</td>
                    </tr>
                `)
            }
        });

        // Notify user a real time data if status was updated
        var channel = pusher.subscribe('jobStatus-channel');
        channel.bind('jobStatus-event', function(data) {
            $(`#status_csv-${data.csvFileId}`).text(`${data.status}`);
        });
    </script>

    {{-- Real Time Data --}}
    <script>
        function updateRealTimeData() {
            $.ajax({
                url: '/csv-create/real-time',
                method: 'GET',
                success: function(data) {
                    data.csvFileDatas.forEach(function(item) {
                        $(`#time-${item.id}`).text(`${item.timeSet} (${item.timeAgo})`);
                        if (item.status === "Processing") {
                            $(`#status_csv-${item.id}`).text(`${item.status}`);
                        }
                    });
                }
            });
        } setInterval(updateRealTimeData, 1000);
    </script>
@endsection