<?php
error_reporting(E_ALL);
include_once('simple_html_dom.php');

$html = new simple_html_dom();

$html->load_file('1377503895.Rawdata_10_1_40_1.html');

//玩法
$span_event_header_market = $html->find('span.event-header-market');

$span_event_header_market[0]->innertext = trim(strip_tags($span_event_header_market[0]->innertext));

echo '<pre>'; print_r($span_event_header_market[0]->innertext);

//時間 賽事 比賽成績
$table_thead_trs = $html->find('table thead tr');

$table_thead_tr_ths = null;

foreach ($table_thead_trs as $tr) {
    $ths = $tr->find('th');

    $table_thead_tr_ths['title_time'] = trim(strip_tags($ths[0]->innertext));
    $table_thead_tr_ths['title_game'] = trim(strip_tags($ths[1]->innertext));

    for ($i = 2;$i < sizeof($ths);$i++) {
        $scores = trim(strip_tags($ths[$i]->innertext));

        if ($value != '其它') {
            //$scores = explode(":", $value);

            //echo $scores[0].' '.$scores[1].'<br>';

            $table_thead_tr_ths['title_odds'][] = $scores;
        } else {
            $table_thead_tr_ths['other'] = $value;
        }
    }
}

echo '<pre>'; print_r($table_thead_tr_ths);

//表格內容
$table_tbodys = $html->find('table tbody');

$table_tbody_tr_tds = null;

$game_number = 1;

foreach ($table_tbodys as $tbody) {
    $trs = $tbody->find('tr');

    foreach ($trs as $tr) {
        $tds = $tr->find('td');
        
        if (sizeof($tds) == 1) {
            if ( ! is_null($table_tbody_tr_tds['game'.$game_number]['league_name'])) {
                $game_number++;
            }
            
            $table_tbody_tr_tds['game'.$game_number]['league_name'] = trim(strip_tags($tds[0]->innertext));
        } else if (sizeof($tds) > 1) {
            //抓時間
            $divs = $tds[0]->find('div.time-column-content');
            
            $date_and_time = explode('<br>', trim($divs[0]->innertext));
            
            $table_tbody_tr_tds['game'.$game_number]['time'][] = array('date' => $date_and_time[0], 'time' => $date_and_time[1]);
            
            //抓比賽隊伍
            $spans = $tds[1]->find('span');
            
            $table_tbody_tr_tds['game'.$game_number]['teams'][] = array('home' => $spans[0]->innertext, 'away' => $spans[1]->innertext);
            
            $odds = array();
            
            //抓賠率
            for ($i = 2; $i < sizeof($tds); $i++) {
                $odds[] = trim(strip_tags($tds[$i]->innertext));
            }
            
            $table_tbody_tr_tds['game'.$game_number]['odds'][] = $odds;
        }
    }
}

echo '<pre>'; print_r($table_tbody_tr_tds);
