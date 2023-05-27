@extends('backend.layouts.app')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="card shadow-sm">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link button-tab" data-id="yearly" id="yearly-tab" data-bs-toggle="tab" data-bs-target="#yearly-tab-pane" type="button" role="tab" aria-controls="yearly-tab-pane" aria-selected="false">Per tahun</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link button-tab" data-id="monthly" id="monthly-tab" data-bs-toggle="tab" data-bs-target="#monthly-tab-pane" type="button" role="tab" aria-controls="monthly-tab-pane" aria-selected="false">Per bulan</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link button-tab" data-id="custom" id="custom-tab" data-bs-toggle="tab" data-bs-target="#custom-tab-pane" type="button" role="tab" aria-controls="custom-tab-pane" aria-selected="false">Filter data</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade" id="yearly-tab-pane" role="tabpanel" aria-labelledby="yearly-tab" tabindex="0">
                            <div class="row my-3">
                                <div class="col-md-6 offset-md-3">
                                    <table class="table table-striped">
                                        <thead>
                                            <th>No</th>
                                            <th>Tahun</th>
                                            <th>Aksi</th>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1 ?>
                                            @for($year = date('Y'); $year >= $year_start; $year--)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $year }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outliine-light btn-show-modal-recap" data-name="year" data-year="{{ $year }}" data-bs-toggle="modal" data-bs-target="#recapModal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye me-1" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <circle cx="12" cy="12" r="2"></circle>
                                                            <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"></path>
                                                        </svg>Lihat
                                                    </button>
                                                </td>
                                            </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="monthly-tab-pane" role="tabpanel" aria-labelledby="monthly-tab" tabindex="1">
                            <div class="row my-3">
                                <div class="col-md-6 offset-md-3">
                                    <table class="table table-striped">
                                        <thead>
                                            <th>No</th>
                                            <th>Tahun</th>
                                            <th>Aksi</th>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1 ?>
                                            @for($year = date('Y'); $year >= $year_start; $year--)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $year }}</td>
                                                <td>
                                                    <form class="form-select-month-on-recap">
                                                        <!-- <input type="hidden" name="input-month-on-recap" class="input-month-on-recap" value="{{ $year }}" required> -->
                                                        <select id="select-month-on-recap-{{ $year }}" required>
                                                            <option value="" selected disabled>Pilih bulan</option>
                                                            <?php $monthArr = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] ?>
                                                            @for($month = 0; $month < count($monthArr); $month++) <option value="{{ $month + 1 }}">{{ $monthArr[$month] }}</option>
                                                                @endfor
                                                        </select>

                                                        <button type="submit" class="btn btn-sm btn-outliine-light btn-show-modal-recap ms-1" data-name="month" data-year="{{ $year }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye me-1" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <circle cx="12" cy="12" r="2"></circle>
                                                                <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"></path>
                                                            </svg>
                                                            Lihat
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-tab-pane" role="tabpanel" aria-labelledby="custom-tab" tabindex="2">
                            <div class="row my-3">
                                <div class="col-md-6 offset-md-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Fiter data</div>
                                        </div>
                                        <form id="form-filter-on-recap">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text" id="date-from-label">Dari</span>
                                                            <input type="date" class="form-control" id="date-from" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text" id="date-to-label">Sampai</span>
                                                            <input type="date" class="form-control" id="date-to" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-outline-primary btn-show-modal-recap" data-name="custom" id="btn-submit-filter">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-filter" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M5.5 5h13a1 1 0 0 1 .5 1.5l-5 5.5l0 7l-4 -3l0 -4l-5 -5.5a1 1 0 0 1 .5 -1.5"></path>
                                                    </svg>
                                                    Terapkan
                                                </button>

                                                <button type="reset" class="btn btn-outline-warning ms-2" id="btn-filter-reset">Reset</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="table-responsive">
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
                    </div> -->
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="recapModal" tabindex="-1" aria-labelledby="recapModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="recapModalLabel">Lihat data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body-recap">
                <div id="modal-recap-file">
                    <div class="card mb-3 card-recap-file-touchable" id="card-recap-file-touchable-pdf" data-url="" style="border-radius: 12px;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-auto">
                                    <span class="bg-danger text-white avatar shadow" style="border-radius: 12px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-text" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                                            <line x1="9" y1="9" x2="10" y2="9"></line>
                                            <line x1="9" y1="13" x2="15" y2="13"></line>
                                            <line x1="9" y1="17" x2="15" y2="17"></line>
                                        </svg>
                                    </span>
                                </div>

                                <div class="col-md-9">
                                    <h4 class="m-0 label-file-name-recap"></h4>
                                    <small class="text-muted m-0">Tipe file: PDF</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-recap-file-touchable mb-3" id="card-recap-file-touchable-xlsx" data-url="" style="border-radius: 12px;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-auto">
                                    <span class="bg-success text-white avatar shadow" style="border-radius: 12px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-spreadsheet" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                                            <path d="M8 11h8v7h-8z"></path>
                                            <path d="M8 15h8"></path>
                                            <path d="M11 11v7"></path>
                                        </svg>
                                    </span>
                                </div>

                                <div class="col-md-9">
                                    <h4 class="m-0 label-file-name-recap"></h4>
                                    <small class="text-muted m-0">Tipe file: XLSX</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="modal-recap-info"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const lastUri = `{{ request()->segment(4) }}`
        setTabActive(lastUri == '' ? 'yearly' : lastUri)
        const modal = new bootstrap.Modal(document.getElementById('recapModal'), {})

        $('.button-tab').on('click', function() {
            const id = $(this).data('id')
            history.pushState('', 'Admin {{ $title }}', `{{ url('backend/finance/recapitulations/${id}') }}`)

            setTabActive($(this).data('id'))
        })

        $('.btn-show-modal-recap').on('click', function() {
            let data = $(this).data('name')
            const dateNow = new Date()

            switch (data) {
                case 'year':
                    let yearSelected = $(this).data('year')
                    $('#modal-recap-info').html(``)
                    if (dateNow.getFullYear() == yearSelected) {
                        if ((dateNow.getMonth() + 1) < '12') {
                            $('#modal-recap-info').html(`
                                <div class="alert alert-warning" role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>

                                    Tahun yang dipilih belum sampai 12 bulan.
                                </div>
                            `)
                        }
                    }

                    $('.label-file-name-recap').text(`Rekap_${yearSelected}`)
                    $('#card-recap-file-touchable-pdf').data('url', `{{ url('/backend/finance/recapitulation/year/pdf?at=${yearSelected}') }}`)
                    $('#card-recap-file-touchable-xlsx').data('url', `{{ url('/backend/finance/recapitulation/year/xlsx?at=${yearSelected}') }}`)

                    break;
                case 'month':
                    let yearSelected2 = $(this).data('year')
                    let monthSelected = $(`#select-month-on-recap-${yearSelected2}`).val()

                    // modal.show()
                    $('#modal-recap-info').html(``)
                    $('.label-file-name-recap').text(`Rekap_${toMonthInIndonesian(monthSelected)}_${yearSelected2}`)

                    $('#card-recap-file-touchable-pdf').data('url', `{{ url('/backend/finance/recapitulation/month/pdf?on_month=${monthSelected}&at_year=${yearSelected2}') }}`.replace('&amp;', '&'))
                    $('#card-recap-file-touchable-xlsx').data('url', `{{ url('/backend/finance/recapitulation/month/xlsx?on_month=${monthSelected}&at_year=${yearSelected2}') }}`.replace('&amp;', '&'))

                    break;

                case 'custom':
                    let dateFrom = $('#date-from').val()
                    let dateTo = $('#date-to').val()

                    if (dateFrom == '' && dateTo == '') {
                        return
                    }

                    modal.show()
                    $('#modal-recap-info').html(``)

                    let dateConverterFrom = new Date(dateFrom)
                    let dayFrom = dateConverterFrom.getDate()
                    let monthFrom = toMonthInIndonesian(dateConverterFrom.getMonth() + 1)
                    let yearFrom = dateConverterFrom.getFullYear()

                    let dateConverterTo = new Date(dateTo)
                    let dayTo = dateConverterTo.getDate()
                    let monthTo = toMonthInIndonesian(dateConverterTo.getMonth() + 1)
                    let yearTo = dateConverterTo.getFullYear()

                    $('.label-file-name-recap').text(`Rekap_${dayFrom} ${monthFrom} ${yearFrom} - ${dayTo} ${monthTo} ${yearTo}`)

                    $('#card-recap-file-touchable-pdf').data('url', `{{ url('/backend/finance/recapitulation/custom/pdf?date_from=${dateFrom}&date_to=${dateTo}') }}`.replace('&amp;', '&'))
                    $('#card-recap-file-touchable-xlsx').data('url', `{{ url('/backend/finance/recapitulation/custom/xlsx?date_from=${dateFrom}&date_to=${dateTo}') }}`.replace('&amp;', '&'))

                    break;
                default:
                    break;
            }
        })

        $('.form-select-month-on-recap').on('submit', function(e) {
            e.preventDefault()

            modal.show()
        })

        $('#form-filter-on-recap').on('submit', function(e) {
            e.preventDefault()

            modal.show()
        })

        $('.card-recap-file-touchable').on('click', function() {
            window.open($(this).data('url'), '_blank')
        })

        function setTabActive(name) {
            $(`[data-id="${name}"]`).addClass('active')

            $(`#${name}-tab-pane`).addClass('show active')
        }
    })
</script>
@endpush

@endsection