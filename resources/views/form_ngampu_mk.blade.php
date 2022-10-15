<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Select2 Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container">

        <h1 class='mt-5'>Form Mengambil Matakuliah</h1>
        <div class="shadow-sm p-3 mb-5 bg-body rounded">
            <form method="post" id="addForm" action="{{ route('save.form') }}" class='mt-2'>
                <div class='mb-2'>
                    <label> Mahasiswa</label>
                    <select id="selectmhs" name='mahasiswa_id' class="form-select" aria-label="Default select example">

                    </select>
                </div>

                <div class='mb-2'>
                    <label> Matakuliah</label>
                    <select id="selectmk"  name="matakuliah_id[]" class="form-select"
                        aria-label="Default select example">

                    </select>
                </div>
                <button id="saveBtn" type="submit" class='mt-2 form-control btn btn-primary'>SIMPAN</button>
            </form>
        </div>

    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
        integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
    </script>

    <script>
        $(document).ready(function() {
            $("#selectmhs").select2({
                placeholder: 'Pilih Mahasiswa',
                ajax: {
                    url: "{{ route('select.mhs') }}",
                    processResults: function({
                        data
                    }) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.nama
                                }
                            })
                        }
                    }
                }
            });
            $("#selectmhs").change(function() {
                var idmhs = $("#selectmhs").val();

                $("#selectmk").attr("multiple", "multiple");
                $.get("{{ url('ngampumk') }}/" + idmhs, function(data) {
                    $('#selectmk').html(data);
                });
                $("#selectmk").select2({
                    placeholder: 'Pilih Matakuliah',
                    allowClear: true,
                    ajax: {
                        url: "{{ url('selectmk') }}" +'/'+ idmhs,
                        processResults: function({
                            data
                        }) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        id: item.id,
                                        text: item.nama_matakuliah
                                    }
                                })
                            }
                        }
                    }
                });
            });
            //save form
            $("#addForm").on('submit', function(e) {
                e.preventDefault();
                $("#saveBtn").html('Processing...').attr('disabled', 'disabled');
                var link = $("#addForm").attr('action');
                $.ajax({
                    url: link,
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    },
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $("#saveBtn").html('SIMPAN').removeAttr('disabled');
                        if (response.status) {
                            $('#selectmk').val(null).trigger("change")
                            $('#selectmhs').val(null).trigger("change")
                            alert(response.message);
                        }
                    },
                    error: function(response) {
                        $("#saveBtn").html('SIMPAN').removeAttr('disabled');
                        alert(response.message);
                    }
                });
            });
        });
    </script>
</body>

</html>
