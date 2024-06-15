let confirm_btn = document.querySelector("#doubles-reg-btn")
console.log(confirm_btn.value)

let second_player_doubles = document.querySelector(".second-player-doubles")
console.log(second_player_doubles)
if (confirm_btn.value === "Odregistrovať"){
    second_player_doubles.classList.add("hide")
}