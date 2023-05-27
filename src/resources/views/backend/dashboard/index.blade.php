@extends('backend.layouts.app')

@section('content')
<div class="container-xl">
    <div class="my-3">
        <div class="row g-2">
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                {{-- <span></span> --}}
                                <h1>{{ $OrderFinish }}</h1>
                                <span>Orderan Sudah bayar</span>
                            </div>
                            <div class="bg-success-lt rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md icon-tabler icon-tabler-checklist" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M9.615 20h-2.615a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8"></path>
                                    <path d="M14 19l2 2l4 -4"></path>
                                    <path d="M9 8h4"></path>
                                    <path d="M9 12h2"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1>{{ $OrderWait }}</h1>
                                <span>Orderan yang Belum bayar</span>
                            </div>
                            <div class="bg-yellow-lt rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md icon-tabler icon-tabler-receipt" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1>{{ $OrderTotal }}</h1>
                                <span>Total Orderan</span>
                            </div>
                            <div class=" bg-indigo-lt rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md icon-tabler icon-tabler-packages" width="40" height="40" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M7 16.5l-5 -3l5 -3l5 3v5.5l-5 3z"></path>
                                    <path d="M2 13.5v5.5l5 3"></path>
                                    <path d="M7 16.545l5 -3.03"></path>
                                    <path d="M17 16.5l-5 -3l5 -3l5 3v5.5l-5 3z"></path>
                                    <path d="M12 19l5 3"></path>
                                    <path d="M17 16.5l5 -3"></path>
                                    <path d="M12 13.5v-5.5l-5 -3l5 -3l5 3v5.5"></path>
                                    <path d="M7 5.03v5.455"></path>
                                    <path d="M12 8l5 -3"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <div class="row g-2">
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-status-start bg-red"></div>
                    <div class="card-body">
                        <p class="fw-bold fs-3">Pengeluaran Hari ini</p>
                        <h1 class="text-red fw-bold">{{ $purchase }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-status-start bg-success"></div>
                    <div class="card-body">
                        <p class="fw-bold fs-3">Pemasukan hari ini</p>
                        <h1 class="text-success fw-bold">{{ $AllIncomeNow }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-status-start bg-yellow"></div>
                    <div class="card-body">
                        <p class="fw-bold fs-3">Orderan Belum Bayar </p>
                        <h1 class=" text-yellow fw-bold">{{ $UnpaidOrderNow }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-status-start bg-info"></div>
                    <div class="card-body">
                        <p class="fw-bold fs-3">Total Saldo</p>
                    <h1 class="text-info fw-bold">{{ $SaldoTotal }}</h1>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="my-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class=" table-responsive">
                            <canvas id="purchasesChart" height="100px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

<div class="mt-3">

</div>

</div>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const purchasesChartCtx = document.getElementById('purchasesChart');
        new Chart(purchasesChartCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
                datasets: [{
                label: 'Pengeluaran',
                data: [{{ implode(',', $purchases) }}],
                borderWidth: 1
                },{
                label: 'Pemasukan',
                data: [{{ implode(',', $incomes) }}],
                borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
@endsection
