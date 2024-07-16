$(document).ready(function () {
        
    $('.match_button').click(function (e) { 
        e.preventDefault();


        if ($(this).val() === "Zapnúť"){
            
            let match_id = $(this).closest('.btnAndGame').find('.match_id').val();
            let league_id = $(this).closest('.btnAndGame').find('.league_id').val();
            
            let match_button = $(this);

            $.ajax({
                method: "POST",
                url: "change-status-match.php",
                data: {
                    'click_start_btn': true,
                    'match_id': match_id,
                    'league_id': league_id,
                },
                success: function (response) {

                    if (response) {
                        
                        $.each(response, function (Key, value) { 

                            
                            // added color for current league match Table number text
                            match_button.parents('div').parent().eq(3).find('.tableNr h3').css(value['csstext'], value['csscolor']);

                            // added color for current league match Table number background
                            match_button.parents('div').parent().eq(3).find('.tableNr').addClass(value['addedclass']);

                            // added color for current league match - match info
                            match_button.parents('div').eq(1).addClass(value['addedclass']);

                            // change value for match button after current match is started
                            match_button.val(value['match_button']);

                        }); 

                    }        
                                
                }
            });

        }

        
        if ($(this).val() === "Čaká"){
            
            let match_id = $(this).closest('.btnAndGame').find('.match_id').val();
            let league_id = $(this).closest('.btnAndGame').find('.league_id').val();
            $('#checkFinish').hide();
            $('#checkFinish').prop('checked',false);
            $('#player1-score').prop("readonly", true);
            $('#player2-score').prop("readonly", true);
            $('#saveBtn').val("Potvrdiť");

            $('.chooseTable').show();


            $.ajax({
                method: "POST",
                url: "change-status-match.php",
                data: {
                    'click_view_btn': true,
                    'match_id': match_id,
                    'league_id': league_id,
                },
                success: function (response) {

                    $.each(response, function (Key, value) { 

                        $('#modal-league-id').val((value['league_id']));
                        $('#match-id').val((value['match_id']));
                        $('#player1-name').text((value['player1_firstname']) + " " + (value['player1_second_name']));
                        $('#player1-score').val((value['score_1']))
                        $('#player2-name').text((value['player2_firstname']) + " " + (value['player2_second_name']));
                        $('#player2-score').val((value['score_2']))

                    });

                    document.querySelector("#modal").showModal();
                    
                        
                }
            });
        };
        if ($(this).val() === "Upraviť"){
            
            let match_id = $(this).closest('.btnAndGame').find('.match_id').val();
            let league_id = $(this).closest('.btnAndGame').find('.league_id').val();

            $('#checkFinish').show();
            $('#checkFinish').prop('checked',false);
            $('#player1-score').prop("readonly", false);
            $('#player2-score').prop("readonly", false);
            $('#saveBtn').val("Uložiť");

            $('.chooseTable').hide();
            
            $.ajax({
                method: "POST",
                url: "change-status-match.php",
                data: {
                    'click_view_btn': true,
                    'match_id': match_id,
                    'league_id': league_id,
                },
                success: function (response) {

                    $.each(response, function (Key, value) { 

                        $('#modal-league-id').val((value['league_id']));
                        $('#match-id').val((value['match_id']));
                        $('#player1-name').text((value['player1_firstname']) + " " + (value['player1_second_name']));
                        $('#player1-score').val((value['score_1']));
                        $('#player2-name').text((value['player2_firstname']) + " " + (value['player2_second_name']));
                        $('#player2-score').val((value['score_2']));

                    });
                    // $('#main-match').html(response);
                    document.querySelector("#modal").showModal();
                    
                    
                        
                }
            });
        };
        
        
    });


    $('#saveBtn').click(function (e) { 
        e.preventDefault();


        

        if ($(this).val() === "Uložiť"){
            
            let modal_match_id = $(this).closest('.matchInfo').find('.modal_match_id').val();
            let modal_league_id = $(this).closest('.matchInfo').find('.modal_league_id').val();
            let modal_score_1 = $(this).closest('.matchInfo').find('#player1-score').val();
            let modal_score_2 = $(this).closest('.matchInfo').find('#player2-score').val();
            let checked = $('input[name=checkFinish]:checked');
            

            if(checked.length){
                checked = true;
            } else {
                checked = false;
            };

            $.ajax({
                method: "POST",
                url: "change-status-match.php",
                data: {
                    'save_data_btn': true,
                    'match_id': modal_match_id,
                    'league_id': modal_league_id,
                    'score_1': modal_score_1,
                    'score_2': modal_score_2,
                    'match_finished': checked,
                },
                success: function (response) {

                    let match_id_to_change = $('.match_id').filter(function() { return this.value == modal_match_id });

                    let score_1 = match_id_to_change.parents('div').eq(1).find('.pl1-label');
                    let score_2 = match_id_to_change.parents('div').eq(1).find('.pl2-label');

                    $.each(response, function (Key, value) { 

                        score_1.text((value['score_1']));
                        score_2.text((value['score_2']));

                        if (checked) {

                            match_id_to_change.parents('div').parent().eq(3).find('.tableNr h3').text((value['closeMatch']));

                            // added color for current league match Table number background
                            match_id_to_change.parents('div').parent().eq(3).find('.tableNr').removeClass(value['removeclass']).addClass(value['addedclass']);
                    
                            
                            // added color for current league match - match info
                            match_id_to_change.parents('div').eq(1).removeClass(value['removeclass']).addClass(value['addedclass']);
                            

                            // change visibility for match button after current match is finished
                            match_id_to_change.closest('.btnAndGame').find('.match_button').hide();
                        }

                    });

                    document.querySelector("#modal").close();
                        
                }
            });

        }


        if ($(this).val() === "Potvrdiť"){

            let modal_match_id = $(this).closest('.matchInfo').find('.modal_match_id').val();
            let modal_league_id = $(this).closest('.matchInfo').find('.modal_league_id').val();
            let modal_score_1 = $(this).closest('.matchInfo').find('#player1-score').val();
            let modal_score_2 = $(this).closest('.matchInfo').find('#player2-score').val();
            let modal_table_number = $('#table-number').find(":selected").val();


            $.ajax({
                method: "POST",
                url: "change-status-match.php",
                data: {
                    'conf_table_and_match': true,
                    'match_id': modal_match_id,
                    'league_id': modal_league_id,
                    'score_1': modal_score_1,
                    'score_2': modal_score_2,
                    'table_number': modal_table_number,
                },
                success: function (response) {
                    
                    let match_id_to_change = $('.match_id').filter(function() { return this.value == modal_match_id });

                    let score_1 = match_id_to_change.parents('div').eq(1).find('.pl1-label');
                    let score_2 = match_id_to_change.parents('div').eq(1).find('.pl2-label');
                    let table_nr_match = match_id_to_change.parents('div').parent().eq(3).find('.tableNr h3')

                    $.each(response, function (Key, value) { 
                        
                        score_1.text((value['score_1']));
                        score_2.text((value['score_2']));
                        table_nr_match.text("T " + (value['table_number']));

                        // added color for current league match Table number text
                        table_nr_match.css(value['csstext'], value['csscolor']);

                        // added color for current league match Table number background
                        match_id_to_change.parents('div').parent().eq(3).find('.tableNr').removeClass(value['removeclass']).addClass(value['addedclass']);
                        
                        

                        // added color for current league match - match info
                        match_id_to_change.parents('div').eq(1).removeClass(value['removeclass']).addClass(value['addedclass']);
                        

                        // change value for match button after current match is started
                        // match_id_to_change.val(value['match_button']);
                        match_id_to_change.closest('.btnAndGame').find('.match_button').val(value['match_button']);

                    });

                    document.querySelector("#modal").close();
                        
                }
            });
        }
        

    });

});