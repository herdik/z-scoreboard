const menuIcon = document.querySelector(".menu-icon")
const burger = document.querySelector(".fa-solid")
const navigation = document.querySelector("header nav")


menuIcon.addEventListener('click', () => {
    if (burger.classList[1] === "fa-bars"){
        burger.classList.remove("fa-bars")
        burger.classList.add("fa-xmark")
        navigation.style.display = "block"
    } else {
        burger.classList.remove("fa-xmark")
        burger.classList.add("fa-bars")
        navigation.style.display = "none"
    }
})