import { toNumber } from "lodash";
import { InertiaLink, usePage, Link } from '@inertiajs/inertia-react';
import React, { Fragment, useState, useEffect } from "react";
import Layout from "../layouts/app";
import axios from "axios";
import Pagination from '../components/Pagination';
import parse from 'html-react-parser';
import Toast from "../components/Toast";
import { rupiahFormatter } from "../Utils/Helper";

const OrderHistory = () => {

    const { title, settings } = usePage().props
    // const [dataPerPage, setDataPerPage] = useState(20)
    const [currentPage, setCurrentPage] = useState('')
    const [links, setLinks] = useState([])
    const [orders, setOrders] = useState([])
    const [paymentModal, setPaymentModal] = useState(false)
    const [orderDetailModal, setOrderDetailModal] = useState(false)

    const token = document.getElementsByName('csrf-token')[0].getAttribute('content')

    const fetchHistoryOrders = (perPage, page, keyword, dateFrom, dateTo) => {
        axios.get(`${window.location.origin}/api/orders?per_page=${perPage}&page=${page}&date_from=${dateFrom}&date_to=${dateTo}`)
            .then(function (response) {
                setCurrentPage(response.data.current_page)
                setLinks(response.data.links)
                setOrders(response.data.data)

                console.log(response.data.data)
            })
            .catch(function (error) {
                console.log(error)
            })
    }

    useEffect(() => {
        document.title = `${settings.name} - ${title}`
        
        fetchHistoryOrders(20, 1, '', '', '')

        var paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'), {
            keyboard: false
        })
        setPaymentModal(paymentModal)

        var orderDetailModal = new bootstrap.Modal(document.getElementById('orderDetailModal'), {
            keyboard: false
        })
        setOrderDetailModal(orderDetailModal)
    }, [])

    const paginate = (url) => {
        let params = (new URL(url)).searchParams

        fetchHistoryOrders(20, params.get('page') != null ? params.get('page') : 1, '', '', '')
    }

    const handleFilterForm = (e) => {
        e.preventDefault()

        let dateFrom = document.querySelector('#date-from').value
        let dateTo = document.querySelector('#date-to').value

        fetchHistoryOrders(20, 1, '', dateFrom, dateTo)
    }

    const resetData = () => {
        fetchHistoryOrders(20, 1, '', '', '')
    }

    const handleInvoicePreview = (orderNumber) => {
        document.querySelector('#embed-pdf-invoice').setAttribute('src', `${window.location.origin}/backend/finance/order/${orderNumber}/invoice`)
    }

    const renderStatusPayment = (status) => {
        switch (status) {
            case 'complete':
                return <span className="text-success">Pembayaran selesai</span>
                break;
            case 'waiting':
                return <span className="text-warning">Menunggu pembayaran</span>
                break;
            case 'canceled':
                return <span className="text-muted">Pesanan dibatalkan
                    <svg xmlns="http://www.w3.org/2000/svg" className="icon icon-tabler icon-tabler-wash-dryclean-off ms-2" width="24" height="24" viewBox="0 0 24 24" strokeWidth="3" stroke="currentColor" fill="none" strokeLinecap="round" strokeLinejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M20.048 16.033a9 9 0 0 0 -12.094 -12.075m-2.321 1.682a9 9 0 0 0 12.733 12.723"></path>
                        <path d="M3 3l18 18"></path>
                    </svg>
                </span>
                break;
            default:
                break;
        }
    }

    const showPaymentModal = (order) => {
        paymentModal.show()

        document.getElementById('order_number').value = order.order_number
        document.getElementById('status_payment').value = order.status_payment
        document.getElementById('id_order').value = order.id
    }

    const handleUpdatePayment = (e) => {
        e.preventDefault()

        let id = document.getElementById('id_order').value
        let statusPayment = document.getElementById('status_payment').value

        axios.post(`${window.location.origin}/backend/finance/order/payment/${id}`, {
            "_token": token,
            "_method": 'PUT',
            status_payment: statusPayment
        })
            .then(function (response) {
                resetData()
                paymentModal.hide()

                Toast.fire({
                    icon: 'success',
                    title: 'Pembayaran berhasil diubah'
                })
            })
            .catch(function (error) {
                console.log(error)
            })
    }

    const handleDetailOrder = (order) => {
        orderDetailModal.show()

        let body = document.getElementById('table-body-detail-order')

        document.getElementById('list-order-number').textContent = order.order_number
        document.getElementById('list-table-number').textContent = order.table_number == 'takeaway' ? 'Bawa pulang' : order.table_number
        document.getElementById('list-cashier-name').textContent = order.cashier_name
        document.getElementById('list-customer-number').textContent = order.customer_number
        document.getElementById('list-desc').textContent = order.desc

        if (order.status_payment == 'complete') {
            document.getElementById('list-status-payment').innerHTML = `
            <span className="badge text-bg-success">Pembayaran selesai</span>`
        } else if (order.status_payment == 'waiting') {
            document.getElementById('list-status-payment').innerHTML = `
            <span className="badge text-bg-warning">Menunggu pembayaran</span>`
        } else if (order.status_payment == 'canceled') {
            document.getElementById('list-status-payment').innerHTML = `
            <span className="badge text-bg-secondary">Pembayaran dibatalkan</span>`
        }

        document.getElementById('list-total-price').textContent = rupiahFormatter(order.total_price)

        let html = ``
        let data = order.ordered_menus
        for (let i = 0; i < data.length; i++) {

            html += `
                <tr>
                    <td>${i + 1}</td>
                    <td>${data[i].menu_name}</td>
                    <td>${data[i].quantity}</td>
                    <td>${rupiahFormatter(data[i].price)}</td>
                </tr>
            `
        }

        body.innerHTML = html
    }

    if (orders != {}) {
        return (
            <Fragment>
                <div>
                    <div className="container">
                        <div className="row">
                            <div className="col-md-6" style={{ marginTop: '100px' }}>

                                <div className="card shadow-sm">
                                    <div className="card-header">Fiter data</div>
                                    <form id="form-filter" onSubmit={(e) => handleFilterForm(e)}>
                                        <div className="card-body">
                                            <div className="row">
                                                <div className="col-md-6">
                                                    <div className="input-group mb-3">
                                                        <span className="input-group-text" id="date-from-label">Dari</span>
                                                        <input type="date" className="form-control" id="date-from" required />
                                                    </div>
                                                </div>
                                                <div className="col-md-6">
                                                    <div className="input-group mb-3">
                                                        <span className="input-group-text" id="date-to-label">Sampai</span>
                                                        <input type="date" className="form-control" id="date-to" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="card-footer">
                                            <button type="submit" className="btn btn-outline-primary-custom" id="btn-submit-filter">
                                                <svg xmlns="http://www.w3.org/2000/svg" className="icon icon-tabler icon-tabler-filter" width="24" height="24" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" fill="none" strokeLinecap="round" strokeLinejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M5.5 5h13a1 1 0 0 1 .5 1.5l-5 5.5l0 7l-4 -3l0 -4l-5 -5.5a1 1 0 0 1 .5 -1.5"></path>
                                                </svg>
                                                Terapkan
                                            </button>

                                            <button type="reset" className="btn btn-outline-warning-custom ms-2" id="btn-filter-reset" onClick={() => resetData()}>Reset</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {/* <div className="row mt-4">
                            <div className="col-2">
                                <div className="form-group">
                                    <label htmlhtmlFor="">Data per halaman</label>
                                    <br />
                                    <select name="" id="" className="mt-1" onChange={(e) => changeDataPerPage(e)}>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="75">75</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                            <div className="col-md-6"></div>
                        </div> */}
                        <div className="row mt-5">
                            <div className="col-md-12">
                                <div className="table-container p-3">
                                    <div className="table-responsive">
                                        <table className="table" id="">
                                            <thead>
                                                <tr className="text-center">
                                                    <th>No. order</th>
                                                    <th>Kasir</th>
                                                    <th>No. customer</th>
                                                    <th>Total harga</th>
                                                    <th>Status pembayaran</th>
                                                    <th>Tanggal</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody className="text-center">
                                                {orders.map((order, index) => (
                                                    <tr key={order.id}>
                                                        <td className={(order.status_payment == 'canceled' ? 'text-muted text-decoration-line-through' : '')}>{order.order_number}</td>
                                                        <td className={(order.status_payment == 'canceled' ? 'text-muted text-decoration-line-through' : '')}>{order.cashier_name}</td>
                                                        <td className={(order.status_payment == 'canceled' ? 'text-muted text-decoration-line-through' : '')}>{order.customer_number}</td>
                                                        <td className={(order.status_payment == 'canceled' ? 'text-muted text-decoration-line-through' : '')}>{rupiahFormatter(order.total_price)}</td>
                                                        <td>{renderStatusPayment(order.status_payment)}</td>
                                                        <td className={(order.status_payment == 'canceled' ? 'text-muted text-decoration-line-through' : '')}>{order.created_at}</td>
                                                        <td>
                                                            <div className="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                                                <button type="button" className={"btn btn-outline-dark " + (order.status_payment == 'canceled' ? 'disabled' : '')} data-bs-toggle="modal" data-bs-target="#exampleModal" onClick={() => handleInvoicePreview(order.order_number)}>
                                                                    <svg xmlns="http://www.w3.org/2000/svg" className="icon icon-tabler icon-tabler-file-invoice me-2" width="24" height="24" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" fill="none" strokeLinecap="round" strokeLinejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                        <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                                                                        <line x1="9" y1="7" x2="10" y2="7"></line>
                                                                        <line x1="9" y1="13" x2="15" y2="13"></line>
                                                                        <line x1="13" y1="17" x2="15" y2="17"></line>
                                                                    </svg>
                                                                    Invoice
                                                                </button>

                                                                <button type="button" className={"btn btn-outline-dark"} onClick={() => showPaymentModal(order)}>
                                                                    <svg xmlns="http://www.w3.org/2000/svg" className="icon icon-tabler icon-tabler-credit-card me-2" width="24" height="24" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" fill="none" strokeLinecap="round" strokeLinejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                        <rect x="3" y="5" width="18" height="14" rx="3"></rect>
                                                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                                                        <line x1="7" y1="15" x2="7.01" y2="15"></line>
                                                                        <line x1="11" y1="15" x2="13" y2="15"></line>
                                                                    </svg>
                                                                    Pembayaran
                                                                </button>

                                                                <button type="button" className="btn btn-outline-dark" onClick={() => handleDetailOrder(order)}>
                                                                    <svg xmlns="http://www.w3.org/2000/svg" className="icon icon-tabler icon-tabler-list-details me-2" width="24" height="24" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" fill="none" strokeLinecap="round" strokeLinejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                        <path d="M13 5h8"></path>
                                                                        <path d="M13 9h5"></path>
                                                                        <path d="M13 15h8"></path>
                                                                        <path d="M13 19h5"></path>
                                                                        <rect x="3" y="4" width="6" height="6" rx="1"></rect>
                                                                        <rect x="3" y="14" width="6" height="6" rx="1"></rect>
                                                                    </svg>
                                                                    Detail
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div className="row mt-3">
                                    <div className="col-md-12">
                                        <nav aria-label="...">
                                            <ul className="pagination">
                                                {links.map((link, index) => (
                                                    <li className="page-item" key={index}>
                                                        <a className={"page-link " + (link.active ? 'active' : '')} href="#" onClick={() => paginate(link.url)}>
                                                            {parse(link.label)}
                                                        </a>
                                                    </li>
                                                ))}
                                            </ul>
                                        </nav>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>

                <div className="modal fade" id="exampleModal" tabIndex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div className="modal-dialog modal-lg modal-dialog-centered">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h1 className="modal-title fs-5" id="exampleModalLabel">Invoice</h1>
                            </div>
                            <div className="modal-body">
                                <embed type="application/pdf" id="embed-pdf-invoice" src="" width="100%" height="600"></embed>
                            </div>
                            <div className="modal-footer">
                                <button type="button" className="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="modal fade" id="paymentModal" tabIndex="-1">
                    <div className="modal-dialog modal-dialog-centered">
                        <div className="modal-content">
                            <form id="form-update-payment" onSubmit={(e) => handleUpdatePayment(e)}>
                                <div className="modal-header">
                                    <h5 className="modal-title">Update pembayaran</h5>
                                </div>
                                <div className="modal-body">
                                    <input type="hidden" id="id_order" className="form-control" readOnly />
                                    <div className="form-group mb-3">
                                        <label className="mb-1">No. Order</label>
                                        <input type="text" id="order_number" className="form-control" readOnly />
                                    </div>

                                    <div className="form-group">
                                        <label className="mb-1" htmlFor="status_payment">Status pembayaran</label>
                                        <select name="status_payment" id="status_payment" className="form-select" required>
                                            <option defaultValue={true}>Pilih status pembayaran</option>
                                            <option value="complete">Selesai</option>
                                            <option value="waiting">Menunggu</option>
                                            <option value="canceled">Pesanan dibatalkan</option>
                                        </select>
                                    </div>
                                </div>
                                <div className="modal-footer">
                                    <button type="button" className="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" className="btn btn-primary">Update pembayaran</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div className="modal fade" id="orderDetailModal" tabIndex="-1">
                    <div className="modal-dialog modal-xl modal-dialog-centered">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5 className="modal-title">Detail pesanan</h5>
                            </div>
                            <div className="modal-body" id="modal-body-detail-order">

                                <ol className="list-group list-group">
                                    <li className="list-group-item d-flex justify-content-between align-items-start">
                                        <div className="ms-2 me-auto">
                                            <div className="fw-bold mb-1">No order</div>
                                            <span id="list-order-number" className="font-monospace text-muted"></span>
                                        </div>
                                    </li>
                                    <li className="list-group-item d-flex justify-content-between align-items-start">
                                        <div className="ms-2 me-auto">
                                            <div className="fw-bold mb-1">No Meja</div>
                                            <span id="list-table-number" className="font-monospace text-muted"></span>
                                        </div>
                                    </li>
                                    <li className="list-group-item d-flex justify-content-between align-items-start">
                                        <div className="ms-2 me-auto">
                                            <div className="fw-bold mb-1">Nama kasir</div>
                                            <span id="list-cashier-name" className="text-muted"></span>
                                        </div>
                                    </li>
                                    <li className="list-group-item d-flex justify-content-between align-items-start">
                                        <div className="ms-2 me-auto">
                                            <div className="fw-bold mb-1">No customer</div>
                                            <span id="list-customer-number" className="font-monospace text-muted"></span>
                                        </div>
                                    </li>
                                    <li className="list-group-item d-flex justify-content-between align-items-start">
                                        <div className="ms-2 me-auto">
                                            <div className="fw-bold mb-1">Status pembayaran</div>
                                            <span id="list-status-payment" className="font-monospace text-muted"></span>
                                        </div>
                                    </li>
                                </ol>

                                <div className="card mt-3">
                                    <div className="card-header">Menu dipesan</div>
                                    <div className="card-body">
                                        <div className="table responsive">
                                            <table className="table table-striped">
                                                <thead>
                                                    <th>No</th>
                                                    <th>Menu</th>
                                                    <th>Qty</th>
                                                    <th>Harga</th>
                                                </thead>
                                                <tbody id="table-body-detail-order">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <ol className="list-group list-group mt-3">
                                    <li className="list-group-item d-flex justify-content-between align-items-start bg-light">
                                        <div className="ms-2 me-auto">
                                            <div className="fw-bold mb-2">

                                                <svg xmlns="http://www.w3.org/2000/svg" className="icon icon-tabler icon-tabler-notes me-1" width="24" height="24" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" fill="none" strokeLinecap="round" strokeLinejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <rect x="5" y="3" width="14" height="18" rx="2"></rect>
                                                    <line x1="9" y1="7" x2="15" y2="7"></line>
                                                    <line x1="9" y1="11" x2="15" y2="11"></line>
                                                    <line x1="9" y1="15" x2="13" y2="15"></line>
                                                </svg>

                                                Catatan tambahan
                                            </div>
                                            <span id="list-desc" className="text-muted" style={{ fontSize: '15px' }}></span>
                                        </div>
                                    </li>
                                </ol>

                                <ol className="list-group list-group mt-3">
                                    <li className="list-group-item d-flex justify-content-between align-items-start bg-light">
                                        <div className="ms-2 me-auto">
                                            <div className="fw-bold mb-2">Total harga</div>
                                            <span id="list-total-price" className="h5 text-primary"></span>
                                        </div>
                                    </li>
                                </ol>

                            </div>
                            <div className="modal-footer">
                                <button type="button" className="btn btn-secondary" onClick={() => orderDetailModal.hide()}>Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </Fragment>
        )
    }


}

OrderHistory.layout = page => <Layout children={page} title="Tes title order" />

export default OrderHistory;