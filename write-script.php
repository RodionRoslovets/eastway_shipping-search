<?php

    // формируем путь к базе
    $url = (!empty($_SERVER['HTTPS'])) ? 
        "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] 
        : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    $url = $_SERVER['REQUEST_URI'];
    $my_url = explode('wp-content' , $url);
    $path = $_SERVER['DOCUMENT_ROOT']."/".$my_url[0];

    // подключаем базу
    include_once $path . '/wp-config.php';
    include_once $path . '/wp-includes/wp-db.php';
    include_once $path . '/wp-includes/pluggable.php';

    global $wpdb;

    //получаем отправленый файл

    $filenam = $_FILES['csv-import']['tmp_name'];

    //Удаляем все записи из бд

    $sql = "TRUNCATE TABLE `wp_tracking_info`";

    $wpdb->query($sql);

    $arr;

    //формируем новый запрос

    $new_sql = 'INSERT INTO `wp_tracking_info` (`id`, `client_id`, `cargo_id`, `cargo_weight`, `cargo_size`, `cargo_count`, `cargo_curr_status`, `status_1`, `status_1_date`, `status_2`, `status_2_date`, `status_3`, `status_3_date`, `status_4`, `status_4_date`, `status_5`, `status_5_date`) VALUES ';

    //Циклами вытаскиваем данные из файла и вставляем в запрос
    if (($handle = fopen($filenam, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);

            for ($c=0; $c < $num; $c++) {
                if($c == 0){
                    $new_sql .= '( null, ';
                } else if($c == 1){
                    $new_sql .= '\''.$data[$c].'\',';
                }else if($c == 2){
                    $new_sql .= '\''.$data[$c].'\',';
                }else if($c == 3){
                    $new_sql .= '\''.$data[$c].'\',';
                }else if($c == 4){
                    $new_sql .= '\''.$data[$c].'\', ';
                }else if($c == 5){
                    $new_sql .= '\''.$data[$c].'\', ';
                }else if($c == 6){
                    $new_sql .= '\''.$data[$c].'\', ';
                }else if($c == 7){
                    $new_sql .= '\''.$data[$c].'\', ';
                }else if($c == 8){
                    $new_sql .= '\''.$data[$c].'\', ';
                }else if($c == 9){
                    $new_sql .= '\''.$data[$c].'\', ';
                }else if($c == 10){
                    $new_sql .= '\''.$data[$c].'\', ';
                }else if($c == 11){
                    $new_sql .= '\''.$data[$c].'\', ';
                }else if($c == 12){
                    $new_sql .= '\''.$data[$c].'\', ';
                }else if($c == 13){
                    $new_sql .= '\''.$data[$c].'\', ';
                }else if($c == 14){
                    $new_sql .= '\''.$data[$c].'\', ';
                }else if($c == 15){
                    $new_sql .= '\''.$data[$c].'\', ';
                }else if($c == 16){
                    $new_sql .= '\''.$data[$c].'\'), ';
                }
            }

        }
        fclose($handle);
    }

    // Удаляем пробелы по краям

    $new_sql = trim( $new_sql );

    // Удаляем загловки столбцов csv файла

    $new_sql = substr( $new_sql, 0, 281) . substr( $new_sql, 522);

    // Удаляем последнюю запятую

    $new_sql = substr( $new_sql, 0, -1 );

    // Отправляем запрос

    $wpdb->query($new_sql);

    echo json_encode($new_sql);
?>