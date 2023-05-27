import { at, toNumber } from "lodash";
import { InertiaLink, usePage, Link } from '@inertiajs/inertia-react';
import React, { Fragment, useState, useEffect } from "react";
import Layout from "../layouts/app";
import axios from "axios";
import Pagination from '../components/Pagination';
import parse from 'html-react-parser';
import Toast from "../components/Toast";

const Profile = () => {


    const { userLoggedIn } = usePage().props
    const { title, settings } = usePage().props

    const [profile, setProfile] = useState({})
    const [ editProfileModal, setEditProfileModal] = useState(false)

    const token = document.getElementsByName('csrf-token')[0].getAttribute('content')

    useEffect(() => {
        document.title = `${settings.name} - ${title}`
        
        fetchProfile()

        var modal = new bootstrap.Modal(document.getElementById('editProfileModal'), {
            keyboard: false
        })
        setEditProfileModal(modal)

        let email = document.getElementById('email')
        let emailFeedback = document.getElementById('email-feedback')
        let timeout;

        email.addEventListener('keyup', function () {
            let elem = this

            if (elem.value.length >= 4) {
                clearTimeout(timeout)
                timeout = setTimeout(() => {

                    if (elem.value.length == 0) {
                        normalizeEmailField()
                    } else {
                        axios.post(`${window.location.origin}/backend/user/email-validator`, {
                            "_token": token,
                            "email": elem.value
                        })
                            .then((response) => {
                                emailFeedback.hidden = false
                                // // $('#email-feedback').show()
                                if (response.data.status) {
                                    email.classList.remove('is-invalid')
                                    email.classList.add('is-valid')
                                    emailFeedback.classList.remove('invalid-feedback')
                                    emailFeedback.classList.add('valid-feedback')
                                } else {
                                    email.classList.remove('is-valid')
                                    email.classList.add('is-invalid')
                                    emailFeedback.classList.remove('valid-feedback')
                                    emailFeedback.classList.add('invalid-feedback')
                                }
                                emailFeedback.innerHTML = response.data.message
                            })
                    }
                }, 1000);
            }
        })
    }, [])

    const normalizeEmailField = () => {
        let email = document.getElementById('email')
        let emailFeedback = document.getElementById('email-feedback')

        email.classList.remove('is-invalid')
        email.classList.remove('is-valid')
        emailFeedback.classList.remove('invalid-feedback')
        emailFeedback.classList.remove('valid-feedback')
        emailFeedback.hidden = true
    }

    const fetchProfile = () => {
        axios.get(`${window.location.origin}/frontend/user/me`)
            .then(function (response) {
                setProfile(response.data.data)
            })
            .catch(function (error) {
                console.log(error)
            })
    }

    const handleShowPassword = () => {
        let attr = document.querySelector('#password').getAttribute('type')

        if (attr == 'password') {
            document.querySelector('#password').setAttribute('type', 'text')
        } else {
            document.querySelector('#password').setAttribute('type', 'password')
        }
    }

    const handlePreviewProfile = () => {
        let input = document.querySelector('#input-image-profile')
        let image = document.querySelector('#image-profile')
        const file = input.files[0]

        if (file) {
            let reader = new FileReader()
            reader.onload = function (event) {
                image.setAttribute('src', event.target.result)
            }
            reader.readAsDataURL(file)
        }
    }

    const setFieldEdit = () => {
        document.getElementById('name').value = profile.name
        document.getElementById('email').value = profile.email
        document.getElementById('image-profile').setAttribute('src', profile.photo)
    }

    const handleFormSubmit = (e) => {
        e.preventDefault()

        let formData = new FormData()
        formData.append('_token', token)
        formData.append('_method', 'POST')
        formData.append('id', userLoggedIn.id)
        formData.append('name', document.getElementById('name').value)
        formData.append('email', document.getElementById('email').value)
        formData.append('password', document.getElementById('password').value)
        formData.append('photo', document.getElementById('input-image-profile').files[0])

        axios({
            method: "post",
            url: `${window.location.origin}/api/user/`,
            data: formData,
            headers: {
                "Content-Type": "multipart/form-data" 
            },
        },)
            .then((response) => {
                fetchProfile()
                editProfileModal.hide()
                
                Toast.fire({
                    icon: 'success',
                    title: 'Pembayaran berhasil diubah'
                })
            })
            .catch((error) => {
                console.log(error)
            })
    }

    return (
        <Fragment>
            <div className="container">
                <div className="row" style={{ marginTop: '65px' }}>
                    <div className="col-md-6 offset-md-3">
                        <div className="d-flex mt-4">
                            <div className="flex-shrink-0">
                                <img src={profile.photo} alt="image-profile" style={{ maxWidth: '100px' }} />
                            </div>
                            <div className="flex-grow-1 ms-3">
                                <h3 className="mt-0">{profile.name}</h3>
                                <p className="text-muted my-0">Kasir &bull; {profile.email}</p>
                                <small className="text-muted">Joined since {profile.joined_at}</small>
                            </div>
                            <div className="flex-grow-1 ms-3">
                                <button className="btn btn-outline-primary-custom btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal" onClick={() => setFieldEdit()}>Edit
                                    <svg xmlns="http://www.w3.org/2000/svg" className="icon icon-tabler icon-tabler-pencil ms-1" width="24" height="24" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" fill="none" strokeLinecap="round" strokeLinejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4"></path>
                                        <line x1="13.5" y1="6.5" x2="17.5" y2="10.5"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="row">
                    <div className="col-md-4">
                        {/* <div className="card">
                            <div className="card-body">
                                <div className="d-flex align-items-center">
                                    <div className="subheader">Revenue</div>
                                    <div className="ms-auto lh-1">
                                        <div className="dropdown">
                                            <a className="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Last 7 days</a>
                                            <div className="dropdown-menu dropdown-menu-end">
                                                <a className="dropdown-item active" href="#">Last 7 days</a>
                                                <a className="dropdown-item" href="#">Last 30 days</a>
                                                <a className="dropdown-item" href="#">Last 3 months</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="d-flex align-items-baseline">
                                    <div className="h1 mb-0 me-2">$4,300</div>
                                    <div className="me-auto">
                                        <span className="text-green d-inline-flex align-items-center lh-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" className="icon ms-1" width="24" height="24" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" fill="none" strokeLinecap="round" strokeLinejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><polyline points="3 17 9 11 13 15 21 7"></polyline><polyline points="14 7 21 7 21 14"></polyline></svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div id="chart-revenue-bg" className="chart-sm" style="min-height: 40px;"><div id="apexchartsclobewi9" className="apexcharts-canvas apexchartsclobewi9 apexcharts-theme-light" style="width: 407px; height: 40px;"><svg id="SvgjsSvg1001" width="407" height="40" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlnsXlink="http://www.w3.org/1999/xlink" xmlnsSvgjs="http://svgjs.dev" className="apexcharts-svg" xmlnsData="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG1003" className="apexcharts-inner apexcharts-graphical" transform="translate(0, 0)"><defs id="SvgjsDefs1002"><clipPath id="gridRectMaskclobewi9"><rect id="SvgjsRect1039" width="413" height="42" x="-3" y="-1" rx="0" ry="0" opacity="1" strokeWidth="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaskclobewi9"></clipPath><clipPath id="nonForecastMaskclobewi9"></clipPath><clipPath id="gridRectMarkerMaskclobewi9"><rect id="SvgjsRect1040" width="411" height="44" x="-2" y="-2" rx="0" ry="0" opacity="1" strokeWidth="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath></defs><line id="SvgjsLine1008" x1="280.18965517241384" y1="0" x2="280.18965517241384" y2="40" stroke="#b6b6b6" stroke-dasharray="3" strokeLinecap="butt" className="apexcharts-xcrosshairs" x="280.18965517241384" y="0" width="1" height="40" fill="#b1b9c4" filter="none" fill-opacity="0.9" strokeWidth="1"></line><g id="SvgjsG1059" className="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG1060" className="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g id="SvgjsG1047" className="apexcharts-grid"><g id="SvgjsG1048" className="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine1052" x1="0" y1="8" x2="407" y2="8" stroke="#e0e0e0" stroke-dasharray="4" strokeLinecap="butt" className="apexcharts-gridline"></line><line id="SvgjsLine1053" x1="0" y1="16" x2="407" y2="16" stroke="#e0e0e0" stroke-dasharray="4" strokeLinecap="butt" className="apexcharts-gridline"></line><line id="SvgjsLine1054" x1="0" y1="24" x2="407" y2="24" stroke="#e0e0e0" stroke-dasharray="4" strokeLinecap="butt" className="apexcharts-gridline"></line><line id="SvgjsLine1055" x1="0" y1="32" x2="407" y2="32" stroke="#e0e0e0" stroke-dasharray="4" strokeLinecap="butt" className="apexcharts-gridline"></line></g><g id="SvgjsG1049" className="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine1058" x1="0" y1="40" x2="407" y2="40" stroke="transparent" stroke-dasharray="0" strokeLinecap="butt"></line><line id="SvgjsLine1057" x1="0" y1="1" x2="0" y2="40" stroke="transparent" stroke-dasharray="0" strokeLinecap="butt"></line></g><g id="SvgjsG1041" className="apexcharts-area-series apexcharts-plot-series"><g id="SvgjsG1042" className="apexcharts-series" seriesName="Profits" data:longestSeries="true" rel="1" data:realIndex="0"><path id="SvgjsPath1045" d="M 0 40 L 0 25.2C 4.912068965517241 25.2 9.12241379310345 26 14.03448275862069 26C 18.946551724137933 26 23.156896551724138 22.4 28.06896551724138 22.4C 32.98103448275862 22.4 37.19137931034483 28.8 42.10344827586207 28.8C 47.015517241379314 28.8 51.22586206896552 25.6 56.13793103448276 25.6C 61.050000000000004 25.6 65.26034482758621 30.4 70.17241379310346 30.4C 75.0844827586207 30.4 79.2948275862069 14 84.20689655172414 14C 89.11896551724139 14 93.32931034482759 27.6 98.24137931034484 27.6C 103.15344827586208 27.6 107.36379310344829 25.2 112.27586206896552 25.2C 117.18793103448277 25.2 121.39827586206897 24.4 126.31034482758622 24.4C 131.22241379310347 24.4 135.43275862068967 15.2 140.34482758620692 15.2C 145.25689655172414 15.2 149.46724137931037 19.6 154.3793103448276 19.6C 159.29137931034484 19.6 163.50172413793103 26 168.41379310344828 26C 173.32586206896553 26 177.53620689655173 23.6 182.44827586206898 23.6C 187.36034482758623 23.6 191.57068965517243 26 196.48275862068968 26C 201.3948275862069 26 205.60517241379313 29.2 210.51724137931035 29.2C 215.4293103448276 29.2 219.6396551724138 2.799999999999997 224.55172413793105 2.799999999999997C 229.4637931034483 2.799999999999997 233.6741379310345 18.8 238.58620689655174 18.8C 243.498275862069 18.8 247.7086206896552 15.600000000000001 252.62068965517244 15.600000000000001C 257.53275862068966 15.600000000000001 261.7431034482759 29.2 266.65517241379314 29.2C 271.56724137931036 29.2 275.7775862068966 18.4 280.68965517241384 18.4C 285.60172413793106 18.4 289.81206896551726 22.8 294.7241379310345 22.8C 299.6362068965517 22.8 303.84655172413795 32.4 308.7586206896552 32.4C 313.6706896551724 32.4 317.88103448275865 21.6 322.7931034482759 21.6C 327.7051724137931 21.6 331.91551724137935 24.4 336.82758620689657 24.4C 341.7396551724138 24.4 345.95000000000005 15.2 350.86206896551727 15.2C 355.7741379310345 15.2 359.98448275862074 19.6 364.89655172413796 19.6C 369.8086206896552 19.6 374.01896551724144 26 378.93103448275866 26C 383.8431034482759 26 388.05344827586214 23.6 392.96551724137936 23.6C 397.8775862068966 23.6 402.08793103448284 13.2 407.00000000000006 13.2C 407.00000000000006 13.2 407.00000000000006 13.2 407.00000000000006 40M 407.00000000000006 13.2z" fill="rgba(32,107,196,0.16)" fill-opacity="1" stroke-opacity="1" strokeLinecap="round" strokeWidth="0" stroke-dasharray="0" className="apexcharts-area" index="0" clip-path="url(#gridRectMaskclobewi9)" pathTo="M 0 40 L 0 25.2C 4.912068965517241 25.2 9.12241379310345 26 14.03448275862069 26C 18.946551724137933 26 23.156896551724138 22.4 28.06896551724138 22.4C 32.98103448275862 22.4 37.19137931034483 28.8 42.10344827586207 28.8C 47.015517241379314 28.8 51.22586206896552 25.6 56.13793103448276 25.6C 61.050000000000004 25.6 65.26034482758621 30.4 70.17241379310346 30.4C 75.0844827586207 30.4 79.2948275862069 14 84.20689655172414 14C 89.11896551724139 14 93.32931034482759 27.6 98.24137931034484 27.6C 103.15344827586208 27.6 107.36379310344829 25.2 112.27586206896552 25.2C 117.18793103448277 25.2 121.39827586206897 24.4 126.31034482758622 24.4C 131.22241379310347 24.4 135.43275862068967 15.2 140.34482758620692 15.2C 145.25689655172414 15.2 149.46724137931037 19.6 154.3793103448276 19.6C 159.29137931034484 19.6 163.50172413793103 26 168.41379310344828 26C 173.32586206896553 26 177.53620689655173 23.6 182.44827586206898 23.6C 187.36034482758623 23.6 191.57068965517243 26 196.48275862068968 26C 201.3948275862069 26 205.60517241379313 29.2 210.51724137931035 29.2C 215.4293103448276 29.2 219.6396551724138 2.799999999999997 224.55172413793105 2.799999999999997C 229.4637931034483 2.799999999999997 233.6741379310345 18.8 238.58620689655174 18.8C 243.498275862069 18.8 247.7086206896552 15.600000000000001 252.62068965517244 15.600000000000001C 257.53275862068966 15.600000000000001 261.7431034482759 29.2 266.65517241379314 29.2C 271.56724137931036 29.2 275.7775862068966 18.4 280.68965517241384 18.4C 285.60172413793106 18.4 289.81206896551726 22.8 294.7241379310345 22.8C 299.6362068965517 22.8 303.84655172413795 32.4 308.7586206896552 32.4C 313.6706896551724 32.4 317.88103448275865 21.6 322.7931034482759 21.6C 327.7051724137931 21.6 331.91551724137935 24.4 336.82758620689657 24.4C 341.7396551724138 24.4 345.95000000000005 15.2 350.86206896551727 15.2C 355.7741379310345 15.2 359.98448275862074 19.6 364.89655172413796 19.6C 369.8086206896552 19.6 374.01896551724144 26 378.93103448275866 26C 383.8431034482759 26 388.05344827586214 23.6 392.96551724137936 23.6C 397.8775862068966 23.6 402.08793103448284 13.2 407.00000000000006 13.2C 407.00000000000006 13.2 407.00000000000006 13.2 407.00000000000006 40M 407.00000000000006 13.2z" pathFrom="M -1 40 L -1 40 L 14.03448275862069 40 L 28.06896551724138 40 L 42.10344827586207 40 L 56.13793103448276 40 L 70.17241379310346 40 L 84.20689655172414 40 L 98.24137931034484 40 L 112.27586206896552 40 L 126.31034482758622 40 L 140.34482758620692 40 L 154.3793103448276 40 L 168.41379310344828 40 L 182.44827586206898 40 L 196.48275862068968 40 L 210.51724137931035 40 L 224.55172413793105 40 L 238.58620689655174 40 L 252.62068965517244 40 L 266.65517241379314 40 L 280.68965517241384 40 L 294.7241379310345 40 L 308.7586206896552 40 L 322.7931034482759 40 L 336.82758620689657 40 L 350.86206896551727 40 L 364.89655172413796 40 L 378.93103448275866 40 L 392.96551724137936 40 L 407.00000000000006 40"></path><path id="SvgjsPath1046" d="M 0 25.2C 4.912068965517241 25.2 9.12241379310345 26 14.03448275862069 26C 18.946551724137933 26 23.156896551724138 22.4 28.06896551724138 22.4C 32.98103448275862 22.4 37.19137931034483 28.8 42.10344827586207 28.8C 47.015517241379314 28.8 51.22586206896552 25.6 56.13793103448276 25.6C 61.050000000000004 25.6 65.26034482758621 30.4 70.17241379310346 30.4C 75.0844827586207 30.4 79.2948275862069 14 84.20689655172414 14C 89.11896551724139 14 93.32931034482759 27.6 98.24137931034484 27.6C 103.15344827586208 27.6 107.36379310344829 25.2 112.27586206896552 25.2C 117.18793103448277 25.2 121.39827586206897 24.4 126.31034482758622 24.4C 131.22241379310347 24.4 135.43275862068967 15.2 140.34482758620692 15.2C 145.25689655172414 15.2 149.46724137931037 19.6 154.3793103448276 19.6C 159.29137931034484 19.6 163.50172413793103 26 168.41379310344828 26C 173.32586206896553 26 177.53620689655173 23.6 182.44827586206898 23.6C 187.36034482758623 23.6 191.57068965517243 26 196.48275862068968 26C 201.3948275862069 26 205.60517241379313 29.2 210.51724137931035 29.2C 215.4293103448276 29.2 219.6396551724138 2.799999999999997 224.55172413793105 2.799999999999997C 229.4637931034483 2.799999999999997 233.6741379310345 18.8 238.58620689655174 18.8C 243.498275862069 18.8 247.7086206896552 15.600000000000001 252.62068965517244 15.600000000000001C 257.53275862068966 15.600000000000001 261.7431034482759 29.2 266.65517241379314 29.2C 271.56724137931036 29.2 275.7775862068966 18.4 280.68965517241384 18.4C 285.60172413793106 18.4 289.81206896551726 22.8 294.7241379310345 22.8C 299.6362068965517 22.8 303.84655172413795 32.4 308.7586206896552 32.4C 313.6706896551724 32.4 317.88103448275865 21.6 322.7931034482759 21.6C 327.7051724137931 21.6 331.91551724137935 24.4 336.82758620689657 24.4C 341.7396551724138 24.4 345.95000000000005 15.2 350.86206896551727 15.2C 355.7741379310345 15.2 359.98448275862074 19.6 364.89655172413796 19.6C 369.8086206896552 19.6 374.01896551724144 26 378.93103448275866 26C 383.8431034482759 26 388.05344827586214 23.6 392.96551724137936 23.6C 397.8775862068966 23.6 402.08793103448284 13.2 407.00000000000006 13.2" fill="none" fill-opacity="1" stroke="#206bc4" stroke-opacity="1" strokeLinecap="round" strokeWidth="2" stroke-dasharray="0" className="apexcharts-area" index="0" clip-path="url(#gridRectMaskclobewi9)" pathTo="M 0 25.2C 4.912068965517241 25.2 9.12241379310345 26 14.03448275862069 26C 18.946551724137933 26 23.156896551724138 22.4 28.06896551724138 22.4C 32.98103448275862 22.4 37.19137931034483 28.8 42.10344827586207 28.8C 47.015517241379314 28.8 51.22586206896552 25.6 56.13793103448276 25.6C 61.050000000000004 25.6 65.26034482758621 30.4 70.17241379310346 30.4C 75.0844827586207 30.4 79.2948275862069 14 84.20689655172414 14C 89.11896551724139 14 93.32931034482759 27.6 98.24137931034484 27.6C 103.15344827586208 27.6 107.36379310344829 25.2 112.27586206896552 25.2C 117.18793103448277 25.2 121.39827586206897 24.4 126.31034482758622 24.4C 131.22241379310347 24.4 135.43275862068967 15.2 140.34482758620692 15.2C 145.25689655172414 15.2 149.46724137931037 19.6 154.3793103448276 19.6C 159.29137931034484 19.6 163.50172413793103 26 168.41379310344828 26C 173.32586206896553 26 177.53620689655173 23.6 182.44827586206898 23.6C 187.36034482758623 23.6 191.57068965517243 26 196.48275862068968 26C 201.3948275862069 26 205.60517241379313 29.2 210.51724137931035 29.2C 215.4293103448276 29.2 219.6396551724138 2.799999999999997 224.55172413793105 2.799999999999997C 229.4637931034483 2.799999999999997 233.6741379310345 18.8 238.58620689655174 18.8C 243.498275862069 18.8 247.7086206896552 15.600000000000001 252.62068965517244 15.600000000000001C 257.53275862068966 15.600000000000001 261.7431034482759 29.2 266.65517241379314 29.2C 271.56724137931036 29.2 275.7775862068966 18.4 280.68965517241384 18.4C 285.60172413793106 18.4 289.81206896551726 22.8 294.7241379310345 22.8C 299.6362068965517 22.8 303.84655172413795 32.4 308.7586206896552 32.4C 313.6706896551724 32.4 317.88103448275865 21.6 322.7931034482759 21.6C 327.7051724137931 21.6 331.91551724137935 24.4 336.82758620689657 24.4C 341.7396551724138 24.4 345.95000000000005 15.2 350.86206896551727 15.2C 355.7741379310345 15.2 359.98448275862074 19.6 364.89655172413796 19.6C 369.8086206896552 19.6 374.01896551724144 26 378.93103448275866 26C 383.8431034482759 26 388.05344827586214 23.6 392.96551724137936 23.6C 397.8775862068966 23.6 402.08793103448284 13.2 407.00000000000006 13.2" pathFrom="M -1 40 L -1 40 L 14.03448275862069 40 L 28.06896551724138 40 L 42.10344827586207 40 L 56.13793103448276 40 L 70.17241379310346 40 L 84.20689655172414 40 L 98.24137931034484 40 L 112.27586206896552 40 L 126.31034482758622 40 L 140.34482758620692 40 L 154.3793103448276 40 L 168.41379310344828 40 L 182.44827586206898 40 L 196.48275862068968 40 L 210.51724137931035 40 L 224.55172413793105 40 L 238.58620689655174 40 L 252.62068965517244 40 L 266.65517241379314 40 L 280.68965517241384 40 L 294.7241379310345 40 L 308.7586206896552 40 L 322.7931034482759 40 L 336.82758620689657 40 L 350.86206896551727 40 L 364.89655172413796 40 L 378.93103448275866 40 L 392.96551724137936 40 L 407.00000000000006 40" fill-rule="evenodd"></path><g id="SvgjsG1043" className="apexcharts-series-markers-wrap" data:realIndex="0"><g className="apexcharts-series-markers"><circle id="SvgjsCircle1076" r="0" cx="280.68965517241384" cy="18.4" className="apexcharts-marker wm6don4kc no-pointer-events" stroke="#ffffff" fill="#206bc4" fill-opacity="1" strokeWidth="2" stroke-opacity="0.9" default-marker-size="0"></circle></g></g></g><g id="SvgjsG1044" className="apexcharts-datalabels" data:realIndex="0"></g></g><g id="SvgjsG1050" className="apexcharts-grid-borders" style="display: none;"><line id="SvgjsLine1051" x1="0" y1="0" x2="407" y2="0" stroke="#e0e0e0" stroke-dasharray="4" strokeLinecap="butt" className="apexcharts-gridline"></line><line id="SvgjsLine1056" x1="0" y1="40" x2="407" y2="40" stroke="#e0e0e0" stroke-dasharray="4" strokeLinecap="butt" className="apexcharts-gridline"></line></g><line id="SvgjsLine1071" x1="0" y1="0" x2="407" y2="0" stroke="#b6b6b6" stroke-dasharray="0" strokeWidth="1" strokeLinecap="butt" className="apexcharts-ycrosshairs"></line><line id="SvgjsLine1072" x1="0" y1="0" x2="407" y2="0" stroke-dasharray="0" strokeWidth="0" strokeLinecap="butt" className="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG1073" className="apexcharts-yaxis-annotations"></g><g id="SvgjsG1074" className="apexcharts-xaxis-annotations"></g><g id="SvgjsG1075" className="apexcharts-point-annotations"></g></g><rect id="SvgjsRect1007" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" strokeWidth="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g id="SvgjsG1070" className="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g id="SvgjsG1004" className="apexcharts-annotations"></g></svg><div className="apexcharts-legend" style="max-height: 20px;"></div><div className="apexcharts-tooltip apexcharts-theme-dark" style="left: 167.682px; top: 0px;"><div className="apexcharts-tooltip-title" style="font-family: inherit; font-size: 12px;">11 Jul</div><div className="apexcharts-tooltip-series-group apexcharts-active" style="order: 1; display: flex;"><span className="apexcharts-tooltip-marker" style="background-color: rgb(32, 107, 196);"></span><div className="apexcharts-tooltip-text" style="font-family: inherit; font-size: 12px;"><div className="apexcharts-tooltip-y-group"><span className="apexcharts-tooltip-text-y-label">Profits: </span><span className="apexcharts-tooltip-text-y-value">54</span></div><div className="apexcharts-tooltip-goals-group"><span className="apexcharts-tooltip-text-goals-label"></span><span className="apexcharts-tooltip-text-goals-value"></span></div><div className="apexcharts-tooltip-z-group"><span className="apexcharts-tooltip-text-z-label"></span><span className="apexcharts-tooltip-text-z-value"></span></div></div></div></div><div className="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-dark"><div className="apexcharts-yaxistooltip-text"></div></div></div></div>
                        </div> */}
                    </div>
                </div>
            </div>

            <div className="modal fade" id="editProfileModal" tabIndex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
                <div className="modal-dialog">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h1 className="modal-title fs-5" id="editProfileModalLabel">Edit profile</h1>
                            <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="form-add-user" className="needs-validation" onSubmit={(e) => handleFormSubmit(e)}>
                            <div className="modal-body">
                                <div className="mb-3">
                                    <img src="https://dummyimage.com/200x200/787878/fff.png&text=Preview" className="mb-3" id="image-profile" alt="image-profile" style={{ width: '100px', height: '100px', objectFit: 'fill', backgroundSize: 'cover', borderRadius: '50px' }} />
                                    <input className="form-control" type="file" id="input-image-profile" onChange={() => handlePreviewProfile()} />
                                </div>

                                <div className="mb-3">
                                    <label htmlFor="name" className="form-label">Nama</label>
                                    <input type="text" className="form-control" id="name" placeholder="Nama" required />
                                </div>
                                <div className="mb-3">
                                    <label htmlFor="email" className="form-label">Alamat email</label>
                                    <input type="email" className="form-control" id="email" placeholder="Alamat email valid" aria-describedby="emailHelp" required />
                                    <div id="email-feedback"></div>
                                </div>
                                <div className="mb-3">
                                    <label htmlFor="password" className="form-label">Password</label>
                                    <input type="password" className="form-control" id="password" placeholder="Password" />
                                    <div id="passwordHelp" className="form-text">Isi hanya jika ingin merubah password.</div>
                                </div>
                                <div className="mb-3 form-check">
                                    <input type="checkbox" className="form-check-input" id="checkbox-showPassword" onChange={() => handleShowPassword()} />
                                    <label className="form-check-label text-gray" htmlFor="checkbox-showPassword">Tampilkan password</label>
                                </div>
                                {/* <button type="submit" className="btn btn-primary" id="btn-submit-form-add-user"></button>
                            <button type="reset" className="btn btn-warning ms-1" id="btn-reset-form-add-user">Reset</button> */}

                            </div>
                            <div className="modal-footer">
                                <button type="button" className="btn btn-outliine-secondary" onClick={() => editProfileModal.hide()}>Batal</button>
                                <button type="submit" className="btn btn-primary">Ubah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </Fragment>
    )
}

Profile.layout = page => <Layout children={page} title="Tes title order" />

export default Profile;