$(document).ready(function () {

});

const showErrorDialog = (message) => {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: message,
    })
}

const showAutoCloseDialog = (title, message, callback) => {
    let timerInterval
    Swal.fire({
        title: title,
        html: message,
        timer: 2000,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
            const b = Swal.getHtmlContainer().querySelector('b')
            timerInterval = setInterval(() => {
                b.textContent = Swal.getTimerLeft()
            }, 100)
        },
        willClose: () => {
            clearInterval(timerInterval)
        }
    }).then(callback)
}