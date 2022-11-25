<?php


/*
Plugin Name: Palabras Malsonantes
Plugin URI: http://wordpress.org/plugins/palabras_malsonantes/
Description: Mi primer plugin
Author: Endermaiter
Version: 1.4
Author URI: http://10.0.9.24
 */

//FUNCION QUE CREA LA TABLA Y HACE LOS INSERTS
function dataBase() {

    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    // le añado el prefijo a la tabla
    $malsonantes = $wpdb->prefix . 'malsonantes';

    // CREAMOS LA TABLA

    $sqlCreate = "CREATE TABLE IF NOT EXISTS $malsonantes (
    id mediumint(9) NOT NULL,
    palabrasSustitutas text NOT NULL,
    PRIMARY KEY (id)
    ) $charset_collate;";

    //antes de hacer los inserts, realizamos un vaciado de la tabla para que no de error de clave primaria duplicada

    $sqlDelete = "DELETE FROM $malsonantes";
    $wpdb->query($sqlDelete);
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sqlDelete);

    /*

        //OTRA FORMA DE HACER LOS INSERTS

    $sqlInsert =  "INSERT INTO $malsonantes (id, palabraSustituta)
    VALUES (1, 'popo'),
           (2,'orina'),
           (3,'hombre de poca belleza'),
           (4,'rayos y centellas'),
           (5,'ventosidad');";

    */

    //HACEMOS LOS INSERTS

    $result = $wpdb->insert(
        $malsonantes,
        array(
            "id"               => 1,
            "palabrasSustitutas" => "popo",
        )
    );

    $result = $wpdb->insert(
        $malsonantes,
        array(
            "id"               => 2,
            "palabrasSustitutas" => "orina",
        )
    );

    $result = $wpdb->insert(
        $malsonantes,
        array(
            "id"               => 3,
            "palabrasSustitutas" => "hombre de poca belleza",
        )
    );

    $result = $wpdb->insert(
        $malsonantes,
        array(
            "id"               => 4,
            "palabrasSustitutas" => "rayos y centellas",
        )
    );

    $result = $wpdb->insert(
        $malsonantes,
        array(
            "id"               => 5,
            "palabrasSustitutas" => "ventosidad",
        )
    );

    error_log( "Plugin malsonantes: ", $result );

    //EJECUTAMOS EL CREATE
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sqlCreate );
    //dbDelta($sqlInsert);
}
//EJECUTAMOS EL METODO
add_action( 'plugins_loaded', 'dataBase' );

/**
 * Reemplaza palabras
 */

//FILTRO DE PALABRAS TOMADAS DE LA BASE DE DATOS
function cambiar_malsonantes( $text ) {

    //array de palabras malsonantes
    $arrayPalabrasMalsonantes = array( "caca", "pis", "feo", "ostia", "pedo" );
    //objeto de conexion a la base de datos
    global $wpdb;
    //nombre de la tabla
    $malsonantes = $wpdb->prefix . "malsonantes";
    //consulta (SELECT)
    $resultado = $wpdb->get_results( "SELECT palabrasSustitutas FROM " . $malsonantes, ARRAY_A);

    //recorremos el resultado obtenido y sacamos los valores
    //los metemos en el array de las palabrasSustitutas
    foreach ($resultado as $fila){
        error_log( "Recorremos resultado: " . $fila['palabrasSustitutas'] );
        $arraySustitutos[] = $fila['palabrasSustitutas'];
    }

//reemplaza las palabras
    return str_replace($arrayPalabrasMalsonantes, $arraySustitutos, $text );

}

/*
 * Cambia el contenido del post
 */
//ejecucion del metodo
add_action( 'plugins_loaded', 'cambiar_malsonantes' );
add_filter( 'the_content', 'cambiar_malsonantes' );

