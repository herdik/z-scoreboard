let revengeCheckBox = document.querySelector("#revenge")

if (revengeCheckBox.value === "1") {
    revengeCheckBox.checked = true
} else {
    revengeCheckBox.checked = false
}

revengeCheckBox.addEventListener("change", () => {
    if (revengeCheckBox.checked) {
        revengeCheckBox.value = "1"
    } else {
        revengeCheckBox.value = "0" 
    }
})

