/*!
* Start Bootstrap - Shop Homepage v5.0.6 (https://startbootstrap.com/template/shop-homepage)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-shop-homepage/blob/master/LICENSE)
*/
// This file is intentionally blank
// Use this file to add JavaScript to your project

window.addEventListener('DOMContentLoaded', function () {
    var colorSelect = document.getElementById('input-color');
    var tshirtImage = document.getElementById('tshirt-color');

    function updateTshirtColor() {
        tshirtImage.src = "/storage/tshirt_base/" + colorSelect.value + ".jpg";
    }

    // Atualiza a cor da tshirt quando a página é carregada
    updateTshirtColor();

    // Atualiza a cor da t-shirt quando o user muda a cor
    colorSelect.addEventListener('change', function () {
        updateTshirtColor();
    });
});


