@extends('backend.layouts.app')
@section('content')
<div class="container">
    <div class="row g-3">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class=" table-responsive">
                        <table class="table" id="categoriTable">
                            <thead>
                                <tr>
                                    <th>{{ __('No') }}</th>
                                    <th>{{ __('Kategori') }}</th>
                                    <th>{{ __('Aksi') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <div class=" card-title" id="text-card-title"></div>
                </div>
                <form id="form-add-category">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Nama Kategori:</label>
                            <input type="text" id="name" class="form-control mt-2" required placeholder="Masukkan Nama Kategori ..." required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class=" btn btn-primary" id="btn-submit-add-categori"></button>
                        <button type="reset" class="btn btn-warning ms-1" id="btn-reset-form">Reset</button>
                </form>
            </div>
        </div>
        </form>
    </div>
</div>


<script>
    $(document).ready(function() {
        let formMode = 'create'
        let tmpID = ''
        showData()
        manipulateForm()

        $('#btn-reset-form').on('click', function() {
            formMode = 'create'
            manipulateForm()
        })

        $('#form-add-category').on('submit', function(e) {
            e.preventDefault()

            let name = $('#name').val()
            let token = $("meta[name='csrf-token']").attr("content")

            $.ajax({
                url: formMode == 'create' ? "{{ url('backend/category/create') }}" : "{{ url('backend/category/update') }}" + "/" + tmpID,
                data: {
                    "_method": formMode == 'create' ? "POST" : "PUT",
                    "_token": token,
                    name: name,
                },
                type: 'POST',
                dataType: "JSON",
                success: function(response) {
                    if (response.status) {
                        if (formMode == 'create') {
                            Toast.fire({
                                icon: 'success',
                                title: 'Data berhasil ditambahkan'
                            })
                        } else {
                            Toast.fire({
                                icon: 'success',
                                title: 'Data berhasil diubah'
                            })
                        }

                        formMode = 'create'
                        manipulateForm()
                        $('#categoriTable').DataTable().ajax.reload()
                        $('#form-add-category').trigger('reset')
                    }
                }
            })
        })

        $('#categoriTable').on('click', '.btn-delete', function() {
            Swal.fire({
                title: 'Hapus data?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6D7A91',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let id = $(this).data("id")
                    let token = $("meta[name='csrf-token']").attr("content")
                    $.ajax({
                        url: "{{ url('backend/category/destroy') }}" + "/" + id,
                        data: {
                            "_token": token
                        },
                        type: 'DELETE',
                        dataType: "JSON",
                        success: function(response) {
                            if (response.status) {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Data berhasil dihapus'
                                })
                            }

                            $('#categoriTable').DataTable().ajax.reload()
                        },
                        error: function(response) {
                            console.log(response)
                        }
                    })
                }
            })
        })

        $('#categoriTable').on('click', '.btn-edit', function() {
            let id = $(this).data("id")
            let data = $(this).data("detail")

            tmpID = id
            formMode = 'edit'
            manipulateForm()

            $('#name').val(data.name)
        })

        function showData() {
            $('#categoriTable').DataTable({
                processing: true,
                serverSide: true,
                // responsive: true,
                ajax: "{{ url('backend/category') }}",
                lengthMenu: [5, 10, 25, 50],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            })
        }

        function manipulateForm() {
            if (formMode == 'create') {
                $('#text-card-title').text('Tambah Kategori')
                $('#btn-submit-add-categori').text('Tambahkan')
            } else if (formMode == 'edit') {
                $('#text-card-title').text('Edit data Kategori')
                $('#btn-submit-add-categori').text('Ubah data')
            }
        }

    })
</script>
@endsection
