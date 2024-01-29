<?php

class LeagueGroup {


    /**
     *
     * RETURN ASOC ARRAY FOR EACH LEAGUE GROUP
     *
     * @param array $registered_players - all registered players
     * @param integer $number_of_groups - number of league group
     * 
     * @return array asoc array with random players group
     */
    public static function shuffleRandomGroups($registered_players, $number_of_groups, $league_id, $empty_player) {

        shuffle($registered_players);

        $free_day = $empty_player;


        $random_groups = array();

        $x = 0;
        $repeat = intval(floor(count($registered_players)/$number_of_groups));
        while ($x < $number_of_groups){
            for ($i = 0; $i < $repeat; $i++) {
                $element = array_pop($registered_players);
                $temp_array[] = $element;
                if ($i === $repeat - 1){
                    array_push($random_groups, $temp_array);
                    $temp_array = array();
                }
                
            }
            $x++;
        }

        $rest_players = count($registered_players);
        if ($rest_players !== 0){
            for ($i = 0; $i < $rest_players; $i++){
                $element = array_pop($registered_players);
                array_push($random_groups[$i], $element);
            }

            for ($i = 0; $i < count($random_groups); $i++) {
                if (count($random_groups[0]) % 2 !== 0) {
                    array_push($random_groups[0], $free_day);
                } else {
                    while (count($random_groups[0]) > count($random_groups[$i])){
                        array_push($random_groups[$i], $free_day);
                    }
                }
            }
        }

    return $random_groups;
    }
}