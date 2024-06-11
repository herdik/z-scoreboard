let selectContainer = document.querySelector(".select-container")
let selected1 = document.querySelector(".single")
let selected2 = document.querySelector(".doubles")
let selected3 = document.querySelector(".doubles-partA")
let selected4 = document.querySelector(".doubles-partB")
let selectPlayersLeague = document.querySelector(".selected-players-league")
let selectPlayersLeague2 = document.querySelector(".selected-player2-league")

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

}




