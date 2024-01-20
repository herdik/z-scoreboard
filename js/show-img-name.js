let message = document.querySelector(".registration-form form p");
let img_file = document.querySelector("#playerIMG");


console.log(message);
console.log(img_file);

img_file.addEventListener("input", ()=> {
    if (img_file.files.length) {
        message.textContent = "Zvolený obrázok: " + img_file.files[0].name
        message.style.opacity = "1";
    } 
    else {
        message.style.opacity = "0";
    }
})

