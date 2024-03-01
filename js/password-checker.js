let passwordPlayer = document.getElementById("password-player")
let passwordConfirmed = document.getElementById("password-confirmed")
let passwordStatus = document.getElementById("password-status")
let sentButton = document.querySelector(".btn")

passwordStatus.style.opacity = 0
sentButton.disabled = true



passwordPlayer.addEventListener("input", () => {

    passwordConfirmed.addEventListener("input", () => {
        console.log(passwordPlayer.value.length )
        console.log(passwordConfirmed.value.length )
        if (passwordPlayer.value.length == 0 && passwordConfirmed.value.length == 0) {
            passwordStatus.style.opacity = 0
        }
        else if (passwordPlayer.value === passwordConfirmed.value){
            passwordStatus.style.color = "green"
            passwordStatus.style.opacity = 1
            passwordStatus.textContent = "Heslá sa zhodujú"
            sentButton.disabled = false
        } 
        else {
            passwordStatus.style.color = "red"
            passwordStatus.style.opacity = 1
            passwordStatus.textContent = "Heslá sa nezhodujú"
            sentButton.disabled = true
        }
    })
})
passwordConfirmed.addEventListener("input", () => {

    passwordPlayer.addEventListener("input", () => {
        console.log(passwordPlayer.value.length )
        console.log(passwordConfirmed.value.length )
        if (passwordPlayer.value.length == 0 && passwordConfirmed.value.length == 0) {
            passwordStatus.style.opacity = 0
        }
        else if (passwordPlayer.value === passwordConfirmed.value){
            passwordStatus.style.color = "green"
            passwordStatus.style.opacity = 1
            passwordStatus.textContent = "Heslá sa zhodujú"
            sentButton.disabled = false
        } 
        else {
            passwordStatus.style.color = "red"
            passwordStatus.style.opacity = 1
            passwordStatus.textContent = "Heslá sa nezhodujú"
            sentButton.disabled = true
        }
    })
})

