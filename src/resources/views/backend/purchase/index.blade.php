@extends('backend.layouts.app')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="purchaseDatatable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>{{ __('No') }}</th>
                                        <th>{{ __('Item') }}</th>
                                        <th>{{ __('Qty') }}</th>
                                        <th>{{ __('Harga') }}</th>
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
                        <div class="card-title" id="text-card-title">Tambah Pengeluaran</div>
                    </div>
                    <div class="card-body">
                        <form id="form-add-purchase">
                            <div class="mb-3">
                                <label for="date" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="date" required>
                            </div>
                            <div class="mb-3">
                                <label for="name_item" class="form-label">Nama barang</label>
                                <input type="text" class="form-control" id="name_item" placeholder="Nama barang" autocapitalize="on" required>
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Qty</label>
                                <input type="text" class="form-control" id="quantity" placeholder="Cth. 2 kg | 10 pcs | 1 lusin" required>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">Rp.</span>
                                    <input type="number" class="form-control" id="price" placeholder="Harga" required>
                                    <span class="input-group-text" id="basic-addon1">,00</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="desc" class="form-label">Deskripsi <sup><span class="text-muted">(opsional)</span></sup></label>
                                <textarea class="form-control" name="desc" id="desc" placeholder="Deskripsi pengeluaran"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="photo_invoice" class="form-label">Foto invoice <sup><span class="text-muted">(opsional)</span></sup></label>
                                <figure class="figure">
                                    <img src="https://dummyimage.com/200x200/787878/fff.png&text=Preview" width="200" height="200" class="figure-img img-fluid rounded" id="img-invoice-preview" alt="...">
                                    <figcaption class="figure-caption">Preview invoice.</figcaption>
                                </figure>

                                <input type="file" class="form-control" name="photo_invoice" id="photo_invoice">
                            </div>
                            <button type="submit" class="btn btn-primary" id="btn-submit-form-add-purchase">Tambahkan</button>
                            <button type="reset" class="btn btn-warning ms-1" id="btn-reset-form-add-purchase">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail pengeluaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- <script>
fsLightboxInstances["gallery"].props.onOpen = function () {
	console.log("The first lightbox has opened.");
}
</script> -->

<script>
    $(document).ready(function() {
        let token = $("meta[name='csrf-token']").attr("content")
        let formMode = 'create'
        let tmpID = ''
        showData()
        manipulateForm()

        $('#purchaseDatatable').on('click', '.btn-edit', function() {
            let data = $(this).data("detail")
            tmpID = $(this).data("id")
            formMode = 'edit'
            manipulateForm()

            $('#date').val(data.date)
            $('#name_item').val(data.name_item)
            $('#quantity').val(data.quantity)
            $('#price').val(data.price)
            $('#desc').val(data.desc)
            $('#img-invoice-preview').attr('src', data.photo_invoice != undefined ? data.photo_invoice : 'https://dummyimage.com/200x200/787878/fff.png&text=No+Image')
        })

        $('#purchaseDatatable').on('click', '.btn-detail', function() {
            let data = $(this).data("detail")

            $('#modal-body').html(`
            <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td><strong>Tanggal pembelian</strong></td>
                                    <td>${data.date}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama barang</strong></td>
                                    <td>${data.name_item}</td>
                                </tr>
                                <tr>
                                    <td><strong>Qty</strong></td>
                                    <td>${data.quantity}</td>
                                </tr>
                                <tr>
                                    <td><strong>Harga</strong></td>
                                    <td>${rupiahFormatter(data.price)}</td>
                                </tr>
                                <tr>
                                    <td><strong>Deskripsi</strong></td>
                                    <td>${data.desc == null ? `<span class="text-muted"><small>Tidak ada deskripsi.</small></span>` : data.desc}</td>
                                </tr>
                                <tr>
                                    <td><strong>Invoice</strong></td>
                                    <td>
                                    ${data.photo_invoice != null ? `   
                                        <a data-fslightbox href="${data.photo_invoice}" target="_blank" rel="noopener noreferrer">                                     
                                        <figure class="figure">
                                            <img src="${data.photo_invoice}" width="200" height="200" class="figure-img img-fluid rounded img-zoomable" id="img-invoice-preview" alt="...">
                                            <figcaption class="figure-caption">Klik untuk memperbesar.</figcaption>
                                        </figure>` : `<span class="text-muted"><small>Tidak ada gambar.</small></span>`}
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
            `)
        })

        $('#purchaseDatatable').on('click', '.btn-delete', function() {
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

                    $.ajax({
                        url: "{{ url('backend/finance/purchase') }}" + "/" + id,
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

                            $('#purchaseDatatable').DataTable().ajax.reload();
                        },
                        error: function(response) {
                            console.log(response)
                        }
                    })
                }
            })
        })

        $('#form-add-purchase').on('submit', function(e) {
            e.preventDefault()

            let photoInvoice = $('#photo_invoice').prop('files')[0]

            let formData = new FormData()

            if (formMode == 'edit') {
                formData.append('_method', 'PUT')
            }

            formData.append('_token', token)
            formData.append('date', $('#date').val())
            formData.append('name_item', $('#name_item').val())
            formData.append('quantity', $('#quantity').val())
            formData.append('price', $('#price').val())
            formData.append('desc', $('#desc').val())
            formData.append('photo_invoice', photoInvoice)

            $.ajax({
                url: formMode == 'create' ? "{{ url('backend/finance/purchase') }}" : "{{ url('backend/finance/purchase') }}" + "/" + tmpID,
                data: formData,
                type: 'POST',
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(response) {
                    if (formMode == 'create') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Pengeluaran berhasil ditambah'
                        })
                    } else if (formMode == 'edit') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Pengeluaran berhasil diubah'
                        })
                    }

                    $("#form-add-purchase").trigger('reset')
                    $('#img-invoice-preview').attr('src', 'https://dummyimage.com/200x200/787878/fff.png&text=Preview')
                    $('#purchaseDatatable').DataTable().ajax.reload()
                    manipulateForm()
                },
                error: function(error) {
                    console.log(error)
                }
            })
        })

        $('#btn-reset-form-add-purchase').on('click', function() {
            formMode = 'create'
            $('#img-invoice-preview').attr('src', 'https://dummyimage.com/200x200/787878/fff.png&text=Preview')
            manipulateForm()
        })

        $('#photo_invoice').on('change', function() {
            const file = this.files[0]
            if (file) {
                let reader = new FileReader()
                reader.onload = function(event) {
                    $('#img-invoice-preview').attr('src', event.target.result)
                }
                reader.readAsDataURL(file)
            }
        })

        function showData() {
            $('#purchaseDatatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('backend/finance/purchases') }}",
                lengthMenu: [30, 100],
                searching: false,
                paging: false,
                ordering: false,
                info: false,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name_item',
                        name: 'name_item',
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'price',
                        render: function(data, type, row, meta) {
                            return rupiahFormatter(data)
                        }
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
            })
        }

        function manipulateForm() {
            if (formMode == 'create') {
                $('#text-card-title').text('Tambah pengeluaran')
                $('#btn-submit-form-add-purchase').text('Tambahkan')
            } else if (formMode == 'edit') {
                $('#text-card-title').text('Edit pengeluaran')
                $('#btn-submit-form-add-purchase').text('Ubah')
            }
        }
    })
</script>

@endsection