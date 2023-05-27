/* Fungsi formatRupiah */
// function rupiahFormatter(angka, prefix) {
//     alert()
//     var number_string = angka.replace(/[^,\d]/g, "").toString(),
//       split = number_string.split(","),
//       sisa = split[0].length % 3,
//       rupiah = split[0].substr(0, sisa),
//       ribuan = split[0].substr(sisa).match(/\d{3}/gi);

//     // tambahkan titik jika yang di input sudah menjadi angka ribuan
//     if (ribuan) {
//       separator = sisa ? "." : "";
//       rupiah += separator + ribuan.join(".");
//     }

//     rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
//     return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
//   }

const rupiahFormatter = (number) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR"
    }).format(number);
}

function getQueryString() {
    var url = document.location.href;
    var qs = url.substring(url.indexOf('?') + 1).split('&');
    for (var i = 0, result = {}; i < qs.length; i++) {
        qs[i] = qs[i].split('=');
        result[qs[i][0]] = decodeURIComponent(qs[i][1]);
    }
    return result;
}

function toMonthInIndonesian(monthNumber) {
    switch (Number(monthNumber)) {
        case 1:
            return "Januari"
            break;
        case 2:
            return "Februari"
            break;
        case 3:
            return "Maret"
            break;
        case 4:
            return "April"
            break;
        case 5:
            return "Mei"
            break;
        case 6:
            return "Juni"
            break;
        case 7:
            return "Juli"
            break;
        case 8:
            return "Agustus"
            break;
        case 9:
            return "September"
            break;
        case 10:
            return "Oktober"
            break;
        case 11:
            return "November"
            break;
        case 12:
            return "Desember"
            break;
        default:
            break;
    }
}