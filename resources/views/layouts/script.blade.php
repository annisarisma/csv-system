<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

{{-- Toastr --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

{{-- Filepond --}}
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src='https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js'></script>
<script src='https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js'></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src='https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js'></script>
<script>
    FilePond.registerPlugin(
        FilePondPluginFileEncode,
        FilePondPluginFileValidateSize,
        FilePondPluginImageExifOrientation,
        FilePondPluginFilePoster,
        FilePondPluginFileValidateType
    )

    //configuration filepond
    const inputElement = document.querySelector('input[id="csv"]');
    // Create a FilePond instance
    if (inputElement) {
        const initialValue = inputElement.dataset.file;
        const pond = FilePond.create(inputElement);
    }

    //Store Filepond
    FilePond.setOptions({
        server: {
            process: '{{ route('csv-upload') }}', //upload
            revert: (uniqueFileId, load, error) => {
                //delete file
                deleteImage(uniqueFileId);
                error('Error while delete file');
                load();
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }
    });

    // Destroy Filepond
    function deleteImage(nameFile) {
        $.ajax({
            url: '{{ route('csv-destroy') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "DELETE",
            data: {
                csv: nameFile,
            },
            success: function(response) {
                console.log(response);
            },
            error: function(response) {
                console.log('error')
            }
        });
    }

    $(document).ready(function() {
        $("#addForm").on('submit', function(e) {
            e.preventDefault();
            $("#saveBtn").html('Processing...').attr('disabled', 'disabled');
            var link = $("#addForm").attr('action');
            $.ajax({
                url: link,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(response) {
                    $("#saveBtn").html('Save').removeAttr('disabled');
                    pond.removeFiles(); //clear
                    alert('Berhasil')
                },
                error: function(response) {
                    $("#saveBtn").html('Save').removeAttr('disabled');
                    alert(response.error);
                }
            });
        });
    });
</script>