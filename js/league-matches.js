document.querySelector(".leagueHeading h1").addEventListener("click", () => {
    document.querySelector(".leagueHeading .left-icon").classList.toggle("active-left")
    document.querySelector(".leagueHeading .right-icon").classList.toggle("active-right")
    document.querySelector(".league-matches").classList.toggle("show-league-matches")
    
    

})