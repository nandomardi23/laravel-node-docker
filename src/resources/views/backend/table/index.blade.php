@extends('backend.layouts.app')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <table class="table" id="tableDatatable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>{{ __('No. Meja') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title" id="text-card-title">Tambah meja</div>
                    </div>
                    <div class="card-body">
                        <form id="form-add-table">
                            <div class="mb-3">
                                <label for="table-count" class="form-label">Tambah meja</label>
                                <select class="form-select" aria-label="Tambah meja" id="table-count" required>
                                    <option selected disabled>Pilih jumlah meja</option>
                                    @for ($i = 1; $i <= 15; $i++) <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary" id="btn-submit-form-add-table">Tambahkan</button>
                            <button type="reset" class="btn btn-warning ms-1" id="btn-reset-form-add-table">Reset</button>
                        </form>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <div class="card-title" id="text-card-title">Kurangi meja</div>
                    </div>
                    <div class="card-body">
                        <form id="form-decrease-table">
                            <button type="button" class="btn btn-danger" id="btn-submit-form-decrese-table">- Kurangi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        showData()

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        $('#btn-submit-form-add-table').on('click', function() {
            let tableCount = $('#table-count').val()
            let token = $("meta[name='csrf-token']").attr("content")

            if (tableCount > 0) {
                $.ajax({
                    url: "{{ url('backend/table-increase') }}",
                    data: {
                        "_token": token,
                        addTablesCount: tableCount,
                    },
                    type: 'POST',
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status == true) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Meja berhasil ditambah'
                            })

                            $("#form-add-table").trigger('reset');
                        }

                        $('#tableDatatable').DataTable().ajax.reload();
                    },
                    error: function(response) {
                        console.log(response)
                    }
                })
            }
        })

        $('#btn-submit-form-decrese-table').on('click', function() {
            let token = $("meta[name='csrf-token']").attr("content")

            $.ajax({
                url: "{{ url('backend/table-decrease') }}",
                data: {
                    "_method": "PUT",
                    "_token": token,
                },
                type: 'POST',
                dataType: "JSON",
                success: function(response) {
                    if (response.status) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Meja berhasil dikurangi'
                        })
                    }

                    $('#tableDatatable').DataTable().ajax.reload();
                },
                error: function(response) {
                    console.log(response)
                }
            })
        })

        function showData() {
            $('#tableDatatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('backend/tables') }}",
                lengthMenu: [30, 100],
                searching: false,
                paging: false,
                ordering: false,
                info: false,
                columns: [{
                    data: 'number',
                    name: 'number'
                }, ]
            })
        }
    })
</script>

@endsection