import { toNumber } from "lodash";
import { InertiaLink, usePage, Link } from '@inertiajs/inertia-react';
import React, { Fragment, useState, useEffect } from "react";
import Layout from "../layouts/app";
import { rupiahFormatter } from "../Utils/Helper";

const Order = (menus) => {

    const { title, settings } = usePage().props

    const [menusData, setMenusData] = useState(menus)
    const [filtertedData, setFiltertedData] = useState(menus.menus)
    const [orderMenuModal, setOrderMenuModal] = useState([])
    const [cartModal, setCartModal] = useState(false)
    const [quantityCount, setQuantityCount] = useState(1)
    const [menuTmp, setMenuTmp] = useState({})
    const [orderedMenus, setOrderedMenus] = useState([])

    useEffect(() => {
        document.title = `${settings.name} - ${title}`

        var orderMenuModal = new bootstrap.Modal(document.getElementById('modalQuantity'), {
            keyboard: false
        })
        setOrderMenuModal(orderMenuModal)

        var cartModal = new bootstrap.Modal(document.getElementById('previewPreCheckoutModal'), {
            keyboard: false
        })
        setCartModal(cartModal)

        // setOrderedMenus()
        const params = new Proxy(new URLSearchParams(window.location.search), {
            get: (searchParams, prop) => searchParams.get(prop),
        });

        // setMenusData(menus)

        if (params.ordered_menus) {
            let data = JSON.parse(params.ordered_menus)

            for (let i = 0; i < data.length; i++) {
                orderedMenus.push(data[i])
            }

            console.log(params.ordered_menus)
            console.log(JSON.parse(params.ordered_menus))

        }

        // window.onbeforeunload = function () {
        //     return "Leave this page ?";
        // }

        if (document.getElementById('input-search-mobile') == null && document.getElementById('input-group-search-mobile') == null) {

            const input = `
            <div class="input-group mt-3 mb-2" id="input-group-search-mobile">
                                    <input class="form-control border rounded-pill border-primary-custom shadow-sm" placeholder="Cari menu apa...?" id="input-search-mobile" />
                                    <span class="input-group-append">
                                        <button class="btn rounded-pill ms-n5" type="button" style={{ width: "50px" }}>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search text-muted" width="20" height="24" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" fill="none" strokeLinecap="round" strokeLinejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <circle cx="10" cy="10" r="7"></circle>
                                                <line x1="21" y1="21" x2="15" y2="15"></line>
                                            </svg>
                                        </button>
                                    </span>
                                </div>
            `

            const navbarContainer = document.getElementById('navbar-container')
            navbarContainer.innerHTML += input
        }

        handleOnSearchTyping()

        return () => {
            const input = document.getElementById('input-search-mobile')
            const group = document.getElementById('input-group-search-mobile')
            group.remove()
            input.remove()
        }
    }, [])

    const handleOnSearchTyping = () => {
        const inputMobile = document.getElementById('input-search-mobile')
        inputMobile.addEventListener('keyup', function () {
            let data = menusData.menus.filter(menu => menu.name.toLowerCase().includes(inputMobile.value.toLowerCase()))
            setFiltertedData(data)
        })

        const inputDesktop = document.getElementById('input-search-desktop')
        inputDesktop.addEventListener('keyup', function () {
            let data = menusData.menus.filter(menu => menu.name.toLowerCase().includes(inputDesktop.value.toLowerCase()))
            setFiltertedData(data)
        })
    }

    const handleSelectMenu = (menu) => {

        if (menu.status == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Menu ini sedang tidak tersedia!',
                confirmButtonColor: '#4A374B',
            })

            return
        }

        orderMenuModal.toggle()

        document.getElementsByClassName('modal-title')[0].innerHTML = menu.name

        setMenuTmp({ ...menuTmp, name_customer: `Customer ${quantityCount}`, quantity: quantityCount, menu_id: menu.id, menu_name: menu.name, price: menu.price, category_id: menu.category.id, category_name: menu.category.name })
    }

    const handleCancelSelectMenu = () => {
        setMenuTmp({})

        orderMenuModal.hide()
        setQuantityCount(1)
    }

    const addToOrderedMenus = () => {
        orderMenuModal.hide()

        // check if this menu in orderedMenus
        const findObjectIndex = orderedMenus.findIndex(key => key.menu_id == menuTmp.menu_id)

        if (findObjectIndex != '-1') {
            // edit value on state
            let newDataArray = orderedMenus.map((menu) => {
                if (menu.menu_id == menuTmp.menu_id) {
                   return {...menu, quantity: menu.quantity + quantityCount}
                }
                return menu
            })

            // set value and replace state
            setOrderedMenus([...newDataArray])
        } else {
            setOrderedMenus([...orderedMenus,
            {
                name_customer: menuTmp.name_customer,
                quantity: quantityCount,
                menu_id: menuTmp.menu_id,
                menu_name: menuTmp.menu_name,
                price: menuTmp.price,
                category_id: menuTmp.category_id,
                category_name: menuTmp.category_name
            }
            ])
        }

        setQuantityCount(1)
    }

    const removeFromOrderedMenus = (menu) => {
        const findObjectIndex = orderedMenus.findIndex(key => key.menu_id == menu.menu_id)
        // orderedMenus.splice(findObjectIndex)

        setOrderedMenus([
            ...orderedMenus.slice(0, findObjectIndex),
            ...orderedMenus.slice(findObjectIndex + 1, orderedMenus.length)
        ])

        console.log(orderedMenus)
    }

    const calculateTotalPrice = () => {
        let total = 0

        orderedMenus.map((menu) => {
            return total += (toNumber(menu.price) * menu.quantity)
        })

        return rupiahFormatter(total)
    }

    const decreaseQuantityOnOrderedMenu = (menu) => {
        let decreaseQuantity = orderedMenus.map(item => {
            if (item.menu_id == menu.menu_id) {
                return { ...item, quantity: item.quantity != 1 ? item.quantity - 1 : item.quantity }
            }

            return item
        })

        setOrderedMenus(decreaseQuantity)
    }

    const increaseQuantityOnOrderedMenu = (menu) => {
        let increaseQuantity = orderedMenus.map(item => {
            if (item.menu_id == menu.menu_id) {
                return { ...item, quantity: item.quantity + 1 }
            }

            return item
        })

        setOrderedMenus(increaseQuantity)
    }

    return (
        <Fragment>
            <div>
                <div className="container">
                    <div className="row my-5">

                        <div className="row mt-5">
                            <div className="col-md-6 offset-md-3">
                                <div className="input-group">
                                    <input className="form-control border rounded-pill border-primary-custom shadow mb-5" placeholder="Cari menu apa...?" id="input-search-desktop" />
                                    <span className="input-group-append">
                                        <button className="btn rounded-pill ms-n5" type="button" style={{ width: "50px" }}>
                                            <svg xmlns="http://www.w3.org/2000/svg" className="icon icon-tabler icon-tabler-search text-muted" width="20" height="24" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" fill="none" strokeLinecap="round" strokeLinejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <circle cx="10" cy="10" r="7"></circle>
                                                <line x1="21" y1="21" x2="15" y2="15"></line>
                                            </svg>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div className="col-md-12">
                            <table className="table" id="menuFrontendTable" style={{ marginTop: '10px', marginBottom: '50px' }}>
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th className="text-end">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {filtertedData.map((menu, index) => (
                                        <tr key={menu.id} onClick={() => handleSelectMenu(menu)}>
                                            <td className={menu.status == 0 ? "text-muted text-decoration-line-through " : ""}>{menu.name}</td>
                                            <td className={menu.status == 0 ? "text-muted text-decoration-line-through text-end" : "text-end"}>{rupiahFormatter(menu.price)}</td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>

                            <div id="modalQuantity" className="modal fade" tabIndex="-1">
                                <div className="modal-dialog modal-dialog-centered">
                                    <div className="modal-content">
                                        <div className="modal-header">
                                            <h5 className="modal-title text-primary-darken-custom" style={{ fontWeight: 600 }}>Modal title</h5>
                                        </div>
                                        <div className="modal-body">
                                            <div className="form-group">
                                                <label htmlFor="">Jumlah</label>
                                                <div className="input-group mt-2 mb-3">
                                                    <button className="btn btn-outline-secondary" type="button" id="button-addon2" onClick={() => setQuantityCount(quantityCount == 1 ? 1 : quantityCount - 1)}>-</button>
                                                    <input type="text" className="form-control" value={quantityCount} />
                                                    <button className="btn btn-outline-secondary" type="button" id="button-addon2" onClick={() => setQuantityCount(quantityCount + 1)}>+</button>
                                                </div>
                                            </div>

                                        </div>
                                        <div className="modal-footer">
                                            <button type="button" className="btn btn-outline-secondary" onClick={() => handleCancelSelectMenu()} >Batal</button>
                                            <button type="button" className="btn btn-primary btn-primary-custom shadow-sm" onClick={() => addToOrderedMenus()}>Tambahkan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <nav className="navbar navbar-expand-lg navbar-light bg-dark fixed-bottom shadow-lg">
                    <div className="container">
                        <a className="navbar-brand modal-title text-white" href="#">Pesanan</a>

                        <button className="btn btn-primary btn-primary-custom shadow-sm" onClick={() => cartModal.show()}>
                            <svg xmlns="http://www.w3.org/2000/svg" className="icon icon-tabler icon-tabler-shopping-cart me-2" width="24" height="24" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" fill="none" strokeLinecap="round" strokeLinejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="6" cy="19" r="2"></circle>
                                <circle cx="17" cy="19" r="2"></circle>
                                <path d="M17 17h-11v-14h-2"></path>
                                <path d="M6 5l14 1l-1 7h-13"></path>
                            </svg>

                            Keranjang <span className="ms-2 badge text-primary-custom bg-white rounded-pill">{orderedMenus.length}</span>
                        </button>
                    </div>
                </nav>

                <div id="previewPreCheckoutModal" className="modal" tabIndex="-1">
                    <div className="modal-dialog modal-fullscreen">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5 className="modal-title">Keranjang
                                    <svg xmlns="http://www.w3.org/2000/svg" className="icon icon-tabler icon-tabler-shopping-cart ms-1" width="24" height="24" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" fill="none" strokeLinecap="round" strokeLinejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="6" cy="19" r="2"></circle>
                                        <circle cx="17" cy="19" r="2"></circle>
                                        <path d="M17 17h-11v-14h-2"></path>
                                        <path d="M6 5l14 1l-1 7h-13"></path>
                                    </svg>
                                </h5>
                                <button type="button" className="btn-close" onClick={() => cartModal.hide()}></button>
                            </div>
                            <div className="modal-body">
                                {orderedMenus.length <= 0 && (
                                    <div className="text-muted text-center" style={{ marginTop: '50px' }}>Keranjang kosong</div>
                                )}

                                {orderedMenus.map((menu, index) => (
                                    <div className="card mb-3 shadow-sm">
                                        <div className="card-body">
                                            <div className="row">
                                                <div className="col-9">
                                                    <p><strong>{menu.menu_name}</strong></p>
                                                    <p className="text-muted" style={{ fontSize: '15px' }}>{rupiahFormatter(menu.price)} x {menu.quantity}  pcs -> {rupiahFormatter(menu.price * menu.quantity)}</p>
                                                </div>
                                                <div className="col-3 text-end">
                                                    <a href="javascript: void(0)" className="btn btn-outline-danger" onClick={() => removeFromOrderedMenus(menu)}><svg xmlns="http://www.w3.org/2000/svg" className="m-0 icon icon-tabler icon-tabler-trash-x" width="24" height="24" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" fill="none" strokeLinecap="round" strokeLinejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M4 7h16"></path>
                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                                        <path d="M10 12l4 4m0 -4l-4 4"></path>
                                                    </svg></a>
                                                </div>
                                            </div>

                                        </div>
                                        <div className="card-footer pt-0 pb-0">
                                            <div className="input-group mb-3">
                                                <button className="btn btn-outline-secondary" type="button" id="button-addon2" onClick={() => decreaseQuantityOnOrderedMenu(menu)}>-</button>
                                                <input type="text" className="form-control" value={menu.quantity} style={{ maxWidth: '65px' }} />
                                                <button className="btn btn-outline-secondary" type="button" id="button-addon2" onClick={() => increaseQuantityOnOrderedMenu(menu)}>+</button>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                            <div className="modal-footer bg-body-tertiary shadow border">

                                <h5 className="modal-title me-auto">{calculateTotalPrice()}</h5>

                                {/* <button type="button" className="btn btn-outline-secondary" onClick={() => cartModal.hide()}>
                                    <svg xmlns="http://www.w3.org/2000/svg" className="icon icon-tabler icon-tabler-circle-chevron-left me-2" width="24" height="24" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" fill="none" strokeLinecap="round" strokeLinejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M13 15l-3 -3l3 -3"></path>
                                        <path d="M21 12a9 9 0 1 0 -18 0a9 9 0 0 0 18 0z"></path>
                                    </svg>

                                    Kembali
                                </button> */}

                                <Link className={"btn btn-primary btn-primary-custom" + (orderedMenus.length <= 0 ? " disabled" : "")} onClick={() => cartModal.hide()} href={`/frontend/checkout?ordered_menus=${JSON.stringify(orderedMenus)}`}>
                                    Checkout
                                    <svg xmlns="http://www.w3.org/2000/svg" className="icon icon-tabler icon-tabler-cash ms-2" width="24" height="24" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" fill="none" strokeLinecap="round" strokeLinejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <rect x="7" y="9" width="14" height="10" rx="2"></rect>
                                        <circle cx="14" cy="14" r="2"></circle>
                                        <path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2"></path>
                                    </svg>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Fragment>
    )
}

Order.layout = page => <Layout children={page} title="Tes title order" />

export default Order;