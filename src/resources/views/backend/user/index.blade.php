@extends('backend.layouts.app')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" id="table-header-loading">
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="userTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>{{ __('No') }}</th>
                                        <th>{{ __('Nama') }}</th>
                                        <th>{{ __('Email Address') }}</th>
                                        <th>{{ __('Level') }}</th>
                                        <th>{{ __('Aksi') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title" id="text-card-title"></div>
                    </div>
                    <div class="card-body">
                        <form id="form-add-user" class="needs-validation">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" placeholder="Nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Alamat email</label>
                                <input type="email" class="form-control" id="email" placeholder="Alamat email valid" aria-describedby="emailHelp" required>
                                <div id="email-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Akses level</label>
                                <select class="form-select" aria-label="Akses level" id="role_id" required>
                                    <option selected disabled>Pilih akses</option>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" placeholder="Password" required>
                                <div id="passwordHelp" class="form-text" hidden>Isi hanya jika ingin merubah password.</div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="checkbox-showPassword">
                                <label class="form-check-label text-gray" for="checkbox-showPassword">Tampilkan password</label>
                            </div>
                            <button type="submit" class="btn btn-primary" id="btn-submit-form-add-user"></button>
                            <button type="reset" class="btn btn-warning ms-1" id="btn-reset-form-add-user">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let formMode = 'create'
        let tmpID = ''
        showData()
        manipulateForm()

        $('#btn-reset-form-add-user').on('click', function() {
            formMode = 'create'
            manipulateForm()
        })

        $('#form-add-user').on('submit', function(e) {
            e.preventDefault()

            let name = $('#name').val()
            let email = $('#email').val()
            let password = $('#password').val()
            let roleID = $('#role_id').val()
            let token = $("meta[name='csrf-token']").attr("content")

            // if (formMode == 'create') {
                $.ajax({
                    url: formMode == 'create' ? "{{ url('backend/user') }}" : "{{ url('backend/user') }}" + "/" + tempID,
                    data: {
                    "_method": formMode == 'create' ? "POST" : "PUT",
                        "_token": token,
                        name: name,
                        email: email,
                        password: password,
                        role_id: roleID
                    },
                    type: 'POST',
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Data berhasil dibuat'
                            })

                            normalizeEmailField()
                            $("#form-add-user").trigger('reset');
                        }

                        $('#userTable').DataTable().ajax.reload();
                    },
                    error: function(response) {
                        console.log(response)
                    }
                })
            // } else if (formMode == 'edit') {

            //     $.ajax({
            //         url: "{{ url('backend/user') }}" + "/" + tempID,
            //         data: {
            //             "_method": "PUT",
            //             "_token": token,
            //             name: name,
            //             email: email,
            //             password: password,
            //             role_id: roleID
            //         },
            //         type: 'POST',
            //         dataType: "JSON",
            //         success: function(response) {
            //             if (response.status) {
            //                 Toast.fire({
            //                     icon: 'success',
            //                     title: 'Data berhasil diubah'
            //                 })

            //                 $("#form-add-user").trigger('reset')
            //             }

            //             $('#userTable').DataTable().ajax.reload()
            //             formMode = 'create'
            //             manipulateForm()
            //         },
            //         error: function(response) {
            //             console.log(response)
            //         }
            //     })
            // }
        })

        $('#userTable').on('click', '.btn-edit', function() {
            let id = $(this).data("id")
            let data = $(this).data("detail")

            $('#name').val(data.name)
            $('#email').val(data.email)
            $('#role_id').val(data.role_id)
            $('#password').val('')

            formMode = 'edit'
            tempID = data.id
            manipulateForm()
        })

        $('#userTable').on('click', '.btn-delete', function() {
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
                        url: "{{ url('backend/user') }}" + "/" + id,
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

                            $('#userTable').DataTable().ajax.reload();
                        },
                        error: function(response) {
                            console.log(response)
                        }
                    })
                }
            })
        })

        let timeout;
        $('#email').keyup(function() {
            var elem = $(this)
            if (elem.val().length >= 4) {
                clearTimeout(timeout)
                timeout = setTimeout(function() {
                    let token = $("meta[name='csrf-token']").attr("content")

                    $('#email').removeClass('is-valid')
                    $('#email').removeClass('is-invalid')
                    $('#email-feedback').attr('class', '')

                    if (elem.val().length == 0) {
                        normalizeEmailField()
                    } else {
                        $.ajax({
                            url: "{{ url('backend/user/email-validator') }}",
                            data: {
                                "_token": token,
                                "email": elem.val()
                            },
                            type: 'POST',
                            dataType: "JSON",
                            success: function(response) {
                                $('#email-feedback').show()
                                if (response.status) {
                                    $('#email').removeClass('is-invalid')
                                    $('#email').addClass('is-valid')
                                    $('#email-feedback').removeClass('invalid-feedback')
                                    $('#email-feedback').addClass('valid-feedback')
                                } else {
                                    $('#email').removeClass('is-valid')
                                    $('#email').addClass('is-invalid')
                                    $('#email-feedback').removeClass('valid-feedback')
                                    $('#email-feedback').addClass('invalid-feedback')
                                }
                                $('#email-feedback').text(response.message)
                            },
                            error: function(response) {
                                console.log(response)
                            }
                        })
                    }
                }, 1000); // <-- choose some sensible value here                                      
            }
        })

        $('#checkbox-showPassword').on('click', function() {
            if ($('#password').attr('type') == 'password') {
                $('#password').attr('type', 'text')
            } else {
                $('#password').attr('type', 'password')
            }
        })

        function showData() {
            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('backend/users') }}",
                lengthMenu: [20, 50, 100],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'nama',
                        orderable: false,
                    },
                    {
                        data: 'email',
                        name: 'email',
                        orderable: false,
                    },
                    {
                        data: 'role',
                        name: 'role'
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

        function normalizeEmailField() {
            $('#email').removeClass('is-invalid')
            $('#email').removeClass('is-valid')
            $('#email-feedback').removeClass('invalid-feedback')
            $('#email-feedback').removeClass('valid-feedback')
            $('#email-feedback').hide()
        }

        function manipulateForm() {
            if (formMode == 'create') {
                $('#text-card-title').text('Tambah user')
                $('#btn-submit-form-add-user').text('Tambahkan')
                $('#password').attr('required', true)
                $('#passwordHelp').attr('hidden', true)
            } else if (formMode == 'edit') {
                $('#text-card-title').text('Edit data user')
                $('#btn-submit-form-add-user').text('Ubah')
                $('#password').attr('required', false)
                $('#passwordHelp').attr('hidden', false)
            }

        }

    })
</script>

@endsection