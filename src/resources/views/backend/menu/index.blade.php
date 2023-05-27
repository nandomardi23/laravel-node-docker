@extends('backend.layouts.app')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-end">
                    <a href="" class="btn btn-primary" id="btn-modal-add" data-bs-toggle="modal" data-bs-target="#tambah-data-modal">Tambah Data</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="menuTable" class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('No')}}</th>
                                    <th>{{ __('Name')}}</th>
                                    <th>{{ __('Kategori')}}</th>
                                    <th>{{ __('harga')}}</th>
                                    <th>{{ __('Status')}}</th>
                                    <th>{{ __('Aksi')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- modal untuk tambah data menu --}}
            <x-backend.menu.modal />
            {{-- modal untuk tambah data menu --}}
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        let token = $("meta[name='csrf-token']").attr("content")
        let formMode = 'create'
        let tmpID = ''
        showData()
        manipulateForm()

        const modal = new bootstrap.Modal(document.querySelector('#tambah-data-modal'), {
            backdrop: 'static'
        })

        function showData() {
            $('#menuTable').DataTable({
                processing: true,
                serverside: true,
                ajax: "{{ route('backend.menu') }}",
                lengthMenu: [25, 50, 100],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'categories',
                        name: 'categories'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        seacrhable: false,
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        seacrhable: false,
                    },
                ],
            });
        }

        $('#modalMenu').on('submit', function(e) {
            e.preventDefault()

            $.ajax({
                url: formMode == 'create' ? "{{ url('backend/menu/store') }}" : "{{ url('backend/menu/update') }}" + "/" + tmpID,
                data: {
                    "_method": formMode == 'create' ? "POST" : "PUT",
                    "_token": token,
                    name: $('#name').val(),
                    category_id: $('#category_id').val(),
                    price: $('#price').val(),
                    desc: $('#desc').val(),
                },
                type: 'POST',
                success: function(response) {
                    if (formMode == 'create') {
                        Toast.fire({
                            icon: 'success',
                            title: "Data menu berhasil ditambahkan",
                        })
                    } else if (formMode == 'edit') {
                        Toast.fire({
                            icon: 'success',
                            title: "Data menu berhasil di update",
                        })
                    }

                    modal.hide()
                    $("#modalMenu").trigger('reset')
                    $('#menuTable').DataTable().ajax.reload()

                    manipulateForm()
                },
                error: function(error) {
                    console.log(error)
                }
            })
        })

        // Delete Ajax
        $('#menuTable').on('click', '.btn-delete', function() {
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
                        url: "/backend/menu/destroy/" + id,
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
                            $('#menuTable').DataTable().ajax.reload();
                        },
                        error: function(response) {
                            console.log(response)
                        }
                    })
                }
            })
        })


        $('#menuTable').on('click', '.btn-edit', function() {
            let id = $(this).data("id")
            let data = $(this).data("detail")

            tmpID = id
            $('#name').val(data.name)
            $('#category_id').val(data.category_id)
            $('#price').val(data.price)
            $('#desc').val(data.desc)

            formMode = 'edit'
            manipulateForm()
        })

        $('#btn-modal-add').on('click', function() {
            formMode = 'create'
            manipulateForm()
        })

        // manipulasi form supayah dinamis
        function manipulateForm() {
            if (formMode == 'create') {
                $('#text-card-title').text('Tambah Menu baru ')
                $('#btn-submit-form-add-menu').text('Tambahkan')
            } else if (formMode == 'edit') {
                $('#text-card-title').text('Edit data Menu')
                $('#btn-submit-form-add-menu').text('Update')
            }
        }
    })
</script>
@endpush

@endsection
