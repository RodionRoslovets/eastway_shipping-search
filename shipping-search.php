<?php
    // формируем путь к
    $url = (!empty($_SERVER['HTTPS'])) ? 
        "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] 
        : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    $url = $_SERVER['REQUEST_URI'];
    $my_url = explode('wp-content' , $url);
    $path = $_SERVER['DOCUMENT_ROOT']."/".$my_url[0];

    // подключаем
    include_once $path . '/wp-config.php';
    include_once $path . '/wp-includes/wp-db.php';
    include_once $path . '/wp-includes/pluggable.php';

    global $wpdb;

    $id = $_GET["id"];

    $req = $wpdb->get_results("SELECT * FROM wp_tracking_info WHERE client_id=$id");

    $response = [];

    foreach($req as $cargo){
        $response[] = array(
            "cargo_id" => $cargo->cargo_id,
            "cargo_weight" => $cargo->cargo_weight,
            "cargo_size" => $cargo->cargo_size,
            "cargo_count" => $cargo->cargo_count,
            "cargo_current_status" => $cargo->cargo_curr_status,
            "cargo_dates" => array(
                "0" => array(
                    "status_name" => $cargo->status_1,
                    "status_date" => $cargo->status_1_date,
                ),
                "1" => array(
                    "status_name" => $cargo->status_2,
                    "status_date" => $cargo->status_2_date,
                ),
                "2" => array(
                    "status_name" => $cargo->status_3,
                    "status_date" => $cargo->status_3_date,
                ),
                "3" => array(
                    "status_name" => $cargo->status_4,
                    "status_date" => $cargo->status_4_date,
                ),
                "4" => array(
                    "status_name" => $cargo->status_5,
                    "status_date" => $cargo->status_5_date,
                )
            ),
        );
    }

    echo json_encode($response);
