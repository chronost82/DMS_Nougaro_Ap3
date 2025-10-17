"use strict";

function isValidEmail(email) {
    let regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return regex.test(email);
}

function isValidPhone(tel) {
    let regex = /^((\+|00)33\s?|0)[67](\s?\d{2}){4}$/;
    return regex.test(tel);
}

window.onload = function () {
    this.document.getElementById("name").focus();
}

valid.onclick = function () {
    let formValide = true;

    if (!isValidEmail(email.value)) {
        formValide = false;
        errorEmail.textContent = "Email incorrect";
    } else {
        errorEmail.textContent = "Email";
    }
    
    if (!isValidPhone(tel.value)) {
        formValide = false;
        errorTel.textContent = "Téléphone incorrect";
    } else {
        errorTel.textContent = "Téléphone";
    }

    return formValide;
}






