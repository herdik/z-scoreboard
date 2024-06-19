let selectContainer = document.querySelector(".select-container")
let selected1 = document.querySelector(".single")
let selected2 = document.querySelector(".doubles")
let selected3 = document.querySelector(".doubles-partA")
let selected4 = document.querySelector(".doubles-partB")

let selectPlayersLeague = document.querySelector(".selected-players-league")
let selectPlayersLeague2 = document.querySelector(".selected-player2-league")

let selectedPlayer1 = document.querySelectorAll(".doubles-partA input")
let selectedPlayer2 = document.querySelectorAll(".doubles-partB input")

let playerArrayObjects = []

// console.log(selectContainer.children.length)

// for (let i = 0; i < selectContainer.children.length; i++) {
//     selectContainer.children[i].firstElementChild.checked = false
// }

selectContainer.addEventListener("click", (event) => {
    if (event.target.value){

        if (event.target.checked) {
            if (selected2 != null || selected3 != null || selected4 != null){
                playerArrayObjects = []
            }
            playerArrayObjects.push({
                playerchecked: event.target.checked,
                playerId: event.target.value,
                playerName: event.target.parentElement.children[1].textContent
            }) 

        } else {
            playerArrayObjects = playerArrayObjects.filter(function(playerObject) {
                return playerObject.playerId !== event.target.value;
              });
        }
    
    }

    selectPlayersLeague.innerHTML = ""
    playerArrayObjects.forEach((onePlayer) => {
        let newPlayer = document.createElement("p")
            newPlayer.textContent = onePlayer.playerName
            selectPlayersLeague.appendChild(newPlayer)
    })
    
})

if (selected4 != null){

    selected4.addEventListener("click", (event) => {
        if (event.target.value){
            
            if (event.target.checked) {
                if (selected4 != null){
                    playerArrayObjects = []
                }
                playerArrayObjects.push({
                    playerchecked: event.target.checked,
                    playerId: event.target.value,
                    playerName: event.target.parentElement.children[1].textContent
                }) 

            } else {
                playerArrayObjects = playerArrayObjects.filter(function(playerObject) {
                    return playerObject.playerId !== event.target.value;
                });
            }
        
        }

        selectPlayersLeague2.innerHTML = ""
        playerArrayObjects.forEach((onePlayer) => {
            let newPlayer = document.createElement("p")
                newPlayer.textContent = onePlayer.playerName
                selectPlayersLeague2.appendChild(newPlayer)
        })
        
    })


    // if players one in doubles are selected/checked, same player to must be hide - FOR ADMIN OR MANAGER
    selected3.addEventListener("change", (event) => {

        selectedPlayer2.forEach((oneInput) => {
            if (oneInput.value == event.target.value){
                oneInput.style.display = "none"
                oneInput.nextElementSibling.style.display = "none"
            } else {
                oneInput.style.display = "inline"
                oneInput.nextElementSibling.style.display = "inline"
            }
        })

    })

    // if players two in doubles are selected/checked, same player to must be hide - FOR ADMIN OR MANAGER OF LEAGUE
    selected4.addEventListener("change", (event) => {

        selectedPlayer1.forEach((oneInput) => {
            if (oneInput.value == event.target.value){
                oneInput.style.display = "none"
                oneInput.nextElementSibling.style.display = "none"
            } else {
                oneInput.style.display = "inline"
                oneInput.nextElementSibling.style.display = "inline"
            }
        })

        
    })

} else {
    // if player one in doubles are loged in registration form , same player must be hide for choice to select second player to make couples- FOR NORMAL PLAYER 
    let first_name_reg_form = document.getElementById("f_name_reg")
    let second_name_reg_form = document.getElementById("s_name_reg")


    let optionsForPlayer2 = document.querySelectorAll(".player-line")

    for (let i = 0; i < optionsForPlayer2.length; i++){
        let player2optionToCheck = optionsForPlayer2[i].children[1].textContent

        if (player2optionToCheck.includes(first_name_reg_form.value) && player2optionToCheck.includes(second_name_reg_form.value)) {
            optionsForPlayer2[i].children[0].style.display = "none"
            optionsForPlayer2[i].children[1].style.display = "none"
            break
        } 
    }
}









