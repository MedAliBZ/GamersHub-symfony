
$(document).ready(function () {
    $('.js-datepicker').datepicker({
        format: 'dd-mm-yyyy',
    });
})

document.querySelector(".input-file").addEventListener("change", (event) => {
    let input = event.target;
    let reader = new FileReader();
    reader.onload = function () {
        let dataURL = reader.result;
        document.querySelector('.defaultImage').style.display = 'none';
        document.querySelector('.gameImage').style.backgroundImage = `url(${dataURL})`;
        document.querySelector('.gameImage').style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);

})