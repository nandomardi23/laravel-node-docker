import { Link, usePage } from "@inertiajs/inertia-react"
import axios from "axios";
import { Fragment, useEffect } from "react"

const Layout = ({ children }) => {

    const { settings } = usePage().props

    useEffect(() => {
        // const navLinks = document.querySelectorAll('.nav-item')
        // const menuToggle = document.getElementById('navbarSupportedContent')
        // const bsCollapse = new bootstrap.Collapse(menuToggle)
        // navLinks.forEach((l) => {
        //     l.addEventListener('click', () => { bsCollapse.toggle() })
        // })

        // var url = new URL(window.location.href)

        // url = url.pathname.split("/")
        // alert(url[2])
    }, [])

    const handleLogout = () => {
        const csrfElement = window.document.getElementsByName('csrf-token')[0]
        const csrf = csrfElement.getAttribute('content')

        axios.post(`${window.location.origin}/logout`, {
            "_token": csrf
        })
            .then(function (response) {
                window.location.replace("/login")
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    return (
        <Fragment>
            <nav className="navbar navbar-expand-lg bg-white fixed-top shadow-sm">
                <div className="container" id="navbar-container">
                    <a className="navbar-brand" id="navbar-brand" href="/frontend/order" style={{ fontWeight: '700' }}>{settings.name}</a>
                    <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span className="navbar-toggler-icon"></span>
                    </button>
                    <div className="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul className="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li className="nav-item">
                                <Link className="nav-link" href="/frontend/order">Order</Link>
                            </li>
                            <li className="nav-item">
                                <Link className="nav-link" href="/frontend/order-history">Riwayat Order</Link>
                            </li>
                            <li className="nav-item">
                                <Link className="nav-link" href="/frontend/profile">Profile</Link>
                            </li>
                            <li className="nav-item">
                                <a className="nav-link text-danger" href="#" onClick={() => handleLogout()}>Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div className="content">
                {children}
            </div>
            {/* <footer>Ini footer</footer> */}
        </Fragment>
    )
}

export default Layout