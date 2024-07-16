<dialog id="modal">
        
        <div id="matchForm">
        <!-- <form id="matchForm" action="change-status-match.php" method="post"> -->
            <h1>Ligový zápas</h1>
            <input type="checkbox" id="checkFinish" name="checkFinish">
            <label for="checkFinish">Ukončiť zápas</label>
            <div id="main-match">
                <div class="matchInfo">
                    <div class="modal-players">
                        <span id="player1-name" class="pl1-span">Jurino</span>
                        <input id="player1-score" type="number" class="pl1-label" min="0" value="20" step="1" name="score_1">
                    </div>
                    
                    <input id="modal-league-id" type="hidden" class="modal_league_id" name="league_id" value="0">
                    <input id="match-id" type="hidden" class="modal_match_id" name="match_id" value="0">
                    <input type="submit" id="saveBtn" value="Uložiť" name="saveMatch">

                    <div class="modal-players">
                        <input id="player2-score" type="number" class="pl2-label" min="0" value="0" step="1" name="score_2">
                        <span id="player2-name" class="pl2-span">Lucka</span>
                    </div>
                </div>
                <div class="chooseTable">
                    <div class="tableName">
                        Výber stola
                    </div>
                    
                    <div class="wrapper">
                        <select name="tableOptions" id="table-number" class="table-options" onfocus='this.size=3;'
                        onblur='this.size=1;' onchange='this.size=1; this.blur();'>
                            <option value="1">Stôl č.1</option>
                            <option value="2">Stôl č.2</option>
                            <option value="3">Stôl č.3</option>
                            <option value="4">Stôl č.4</option>
                            <option value="5">Stôl č.5</option>
                            <option value="6">Stôl č.6</option>
                            <option value="7">Stôl č.7</option>
                            <option value="8">Stôl č.8</option>
                            <option value="9">Stôl č.9</option>
                            <option value="10">Stôl č.10</option>
                            <option value="11">Stôl č.11</option>
                            <option value="12">Stôl č.12</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>
        <!-- </form> -->
    </dialog>