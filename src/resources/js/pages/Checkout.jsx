
import { Inertia } from '@inertiajs/inertia';
import { InertiaLink, usePage, Link } from '@inertiajs/inertia-react';
import axios from 'axios';
import { toNumber } from 'lodash';
import { Fragment, useEffect, useState } from "react"
import Layout from "../layouts/app"
import { rupiahFormatter } from "../Utils/Helper";

const Checkout = () => {
    // let data = JSON.parse(orderedMenus.orderedMenus)
    const { orderedMenus, userLoggedIn, tables } = usePage().props
    // const { userLoggedIn } = usePage().props
    const data = JSON.parse(orderedMenus)

    const [filteredMenus, setFilteredMenus] = useState(data)
    const [filterActive, setFilterActive] = useState('all')
    const [takeaway, setTakeAway] = useState(false)
    const [descriptionModal, setDescriptionModal] = useState(false)

    useEffect(() => {
        appendNavCategory()

        var descriptionModal = new bootstrap.Modal(document.getElementById('descriptionModal'), {
            keyboard: false
        })
        setDescriptionModal(descriptionModal)

        const links = document.getElementsByClassName('nav-link-filter')
        switchFilterActive(links, filterActive)

        for (var i = 0; i < links.length; i++) {
            links[i].addEventListener('click', (link) => {
                let label = link.target.getAttribute('data-filter-label')
                setFilterActive(label)
                switchFilterActive(links, label)
                handleShowData(label)
            })
        }

        return () => {
            const nav = document.getElementById('nav-filter-mobile')
            nav.remove()
        }
    }, [])

    const switchFilterActive = (links, label) => {
        switch (label) {
            case "all":
                links[0].classList.add('active')
                links[1].classList.remove('active')
                links[2].classList.remove('active')
                links[3].classList.remove('active')
                break;
            case "makanan":
                links[0].classList.remove('active')
                links[1].classList.add('active')
                links[2].classList.remove('active')
                links[3].classList.remove('active')
                break;
            case "minuman":
                links[0].classList.remove('active')
                links[1].classList.remove('active')
                links[2].classList.add('active')
                links[3].classList.remove('active')
                break;

            default:
                links[0].classList.remove('active')
                links[1].classList.remove('active')
                links[2].classList.remove('active')
                links[3].classList.add('active')
                break;
        }
    }

    const appendNavCategory = () => {
        if (document.getElementById('nav-filter-mobile') == null) {
            const nav = `<ul class="nav nav-pills nav-fill m-2 w-100" id="nav-filter-mobile">
            <li class="nav-item">
              <a class="nav-link nav-link-filter" data-filter-label="all" aria-current="page" href="#">Semua</a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-link-filter" data-filter-label="makanan" href="#">Makanan</a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-link-filter" data-filter-label="minuman" href="#">Minuman</a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-link-filter" data-filter-label="lain-lain">Lain-lain</a>
            </li>
          </ul>`

            const navbarContainer = document.getElementById('navbar-container')
            navbarContainer.innerHTML += nav
        }
    }

    const handleShowData = (label) => {
        if (label != 'all') {
            if (label != 'makanan' || label != 'minuman') {
                let dataNew = data.filter(menu => menu.category_name.toLowerCase().includes(label.toLowerCase()))
                setFilteredMenus(dataNew)
            } else {
                let dataNew = data.filter(menu => menu.category_name.toLowerCase().includes("lain-lain".toLowerCase()))
                setFilteredMenus(dataNew)
            }
        } else {
            setFilteredMenus(data)
        }
    }

    const attemptOrderToPaymentData = () => {
        const dataTotal = {
            table_number: takeaway ? 'takeaway' : document.getElementById('table_number').value,
            ordered_menus: data,
            desc: document.getElementById('desc').value,
            cashier_name: userLoggedIn.name,
            total_price: calculateTotalPrice()
        }

        axios.post(`${window.location.origin}/api/order`, dataTotal)
            .then(function (response) {
                descriptionModal.hide()

                Swal.fire({
                    icon: 'success',
                    title: 'Pemesanan berhasil',
                    text: 'Menu berhasil dipesan',
                    showConfirmButton: false,
                })

                Inertia.visit('/frontend/order', { method: 'get' })
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    const calculateTotalPrice = () => {
        let total = 0

        data.map((menu) => {
            return total += (toNumber(menu.price) * menu.quantity)
        })

        return total
    }

    return (
        <Fragment>
            <div className="container">
                <div className="row" style={{ marginTop: '130px' }}>
                    <div className="col-md-12">
                        {filteredMenus.map((menu, index) => (
                            <ol className="list-group mb-3 bg-white shadow-sm" style={{ borderRadius: '12px' }} key={index}>
                                <li className="list-group-item d-flex justify-content-between align-items-start">
                                    <div className="ms-2 me-auto">
                                        <div className="fw-bold">{menu.menu_name}</div>
                                        <ul>
                                            <li>Harga: {menu.price}</li>
                                            <li>Qty: {menu.quantity}</li>
                                        </ul>
                                    </div>
                                </li>
                            </ol>
                        ))}

                        <nav className="fixed-bottom bg-light p-2">
                            <div className="container">
                                <div className="row align-items-center mt-1">
                                    <div className="col-md-6 mb-2">
                                        <select name="table_number" id="table_number" className="form-select" disabled={takeaway}>
                                            <option disabled value={''} defaultValue={true}>Pilih meja</option>
                                            {tables.map((table, index) => (
                                                <option value={table.number} key={index}>Meja {table.number}</option>
                                            ))}
                                        </select>
                                    </div>
                                    <div className="col-md-6 mb-2">
                                        <div className="form-check">
                                            <input className="form-check-input" type="checkbox" id="checkbox-takeaway" defaultChecked={takeaway} onChange={() => setTakeAway(takeaway ? false : true)} />
                                            <label className="form-check-label" htmlFor="checkbox-takeaway">
                                                Bawa pulang?
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div className="row align-items-center">
                                    <div className="col-6 text-start">
                                        <a className="navbar-brand modal-title" href="#">{rupiahFormatter(calculateTotalPrice())}</a>
                                    </div>
                                    <div className="col-6 text-end">
                                        <button className="btn btn-primary btn-primary-custom shadow" onClick={() => document.getElementById('table_number').value == '' && document.getElementById('checkbox-takeaway').checked == false ? Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: 'Pilih meja atau bawa pulang!',
                                            confirmButtonColor: '#4A374B',
                                        }) : descriptionModal.show()}>
                                            Lanjut

                                            <svg xmlns="http://www.w3.org/2000/svg" className="icon icon-tabler icon-tabler-chevron-right" width="24" height="24" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" fill="none" strokeLinecap="round" strokeLinejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <polyline points="9 6 15 12 9 18"></polyline>
                                            </svg>
                                        </button>
                                    </div>
                                </div>



                            </div>
                        </nav>
                    </div>
                </div>
            </div>

            <div className="modal fade" id="descriptionModal" tabIndex="-1">
                <div className="modal-dialog modal-dialog-centered">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h5 className="modal-title">Tambahkan deskripsi</h5>
                        </div>
                        <div className="modal-body">
                            <div className="form-group">
                                <label htmlFor="desc" className='mb-2'>Catatan menu <sup>(opsional)</sup></label>
                                <textarea name="desc" id="desc" cols="30" rows="10" className="form-control" style={{ resize: 'none' }} placeholder="Cth. Teh obeng es sedikit"></textarea>
                            </div>
                        </div>
                        <div className="modal-footer">
                            <button type="button" className="btn btn-outline-secondary" onClick={() => descriptionModal.hide()}>Batal</button>
                            <button type="button" className="btn btn-primary" onClick={() => attemptOrderToPaymentData()}>
                                <svg xmlns="http://www.w3.org/2000/svg" className="icon icon-tabler icon-tabler-shopping-cart me-2" width="24" height="24" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" fill="none" strokeLinecap="round" strokeLinejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <circle cx="6" cy="19" r="2"></circle>
                                    <circle cx="17" cy="19" r="2"></circle>
                                    <path d="M17 17h-11v-14h-2"></path>
                                    <path d="M6 5l14 1l-1 7h-13"></path>
                                </svg>

                                Bayar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Fragment>
    )
}

Checkout.layout = page => <Layout children={page} />

export default Checkout