let selectContainer = document.querySelector(".select-container")
let selectPlayersLeague = document.querySelector(".selected-players-league")

let playerArrayObjects = []

// console.log(selectContainer.children.length)

// for (let i = 0; i < selectContainer.children.length; i++) {
//     selectContainer.children[i].firstElementChild.checked = false
// }

selectContainer.addEventListener("click", (event) => {
    if (event.target.value){
        
        if (event.target.checked) {
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
        console.log(playerArrayObjects)
    }

    selectPlayersLeague.innerHTML = ""
    playerArrayObjects.forEach((onePlayer) => {
        let newPlayer = document.createElement("p")
            newPlayer.textContent = onePlayer.playerName
            selectPlayersLeague.appendChild(newPlayer)
    })
    
})






