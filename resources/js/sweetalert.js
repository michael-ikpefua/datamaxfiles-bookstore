import Swal from 'sweetalert2'

window.Swal = Swal

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (Toast) => {
        Toast.addEventListener('mouseenter', Swal.stopTimer)
        Toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

window.Toast = Toast;
