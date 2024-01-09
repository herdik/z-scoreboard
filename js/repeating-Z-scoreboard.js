// ***function to repeat and print name of league system
let indexLetter = 1
let speed = 250
let mainText = `BK MANILA " Z-scoreboard "     `

let repeatingText = function(){
    document.querySelector(".app-name").textContent = mainText.slice(0, indexLetter)
    indexLetter ++

    indexLetter > mainText.length ? indexLetter = 1 : indexLetter = indexLetter
    
    setTimeout(repeatingText, speed)
}
repeatingText()