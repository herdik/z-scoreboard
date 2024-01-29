let rematchCheckBox = document.querySelector("#rematch")

if (rematchCheckBox.value === "1") {
    rematchCheckBox.checked = true
} else {
    rematchCheckBox.checked = false
}

rematchCheckBox.addEventListener("change", () => {
    if (rematchCheckBox.checked) {
        rematchCheckBox.value = "1"
    } else {
        rematchCheckBox.value = "0" 
    }
})

