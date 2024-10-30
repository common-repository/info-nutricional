<?php
/**


License:     GPL2

Info Nutricional is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

{Plugin Name} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {License URI}.

 *
 *   Info Nutricional : By Ludo (Marc Alier) 2016
 *
 *   LICENSE GPL 2.0
 */


include_once "receptes.php";
include_once "info_nutricional_setup.php";
include_once "valors_nutricionals.php";

function ldin_etiqueta_nutricional_shortcode($attrs) {

    return ldin_genera_etiqueta($attrs["id"]);

}


function ldin_genera_etiqueta($id) {
    $pack=get_post_meta((int)$id, "recepta", true);
    $pack=json_decode($pack, true);
    return $pack['nutrients'];
}

function ldin_carrega_nutrients($id) {
    $nutrients=null;
    if (is_numeric($id)) {
        $pack = get_post_meta((int)$id, "recepta", true);

        $pack = json_decode($pack, true);
        $nutrients = $pack["nutrients"]["nutrients"];
    }
    return $nutrients;
}

function ldin_estil_receptes() {
    ?>

    <style type='text/css'>
        .etiqueta_info_nutricional { border: 1px solid #ccc; font-family: helvetica, arial, sans-serif; font-size: .9em; width: 22em; padding: 1em 1.25em 1em 1.25em; line-height: 1.4em; margin: 1em; }
        .etiqueta_info_nutricional hr { border:none; border-bottom: solid 8px #666; margin: 3px 0px; width: 18em;}
        .etiqueta_info_nutricional .heading { font-size: 2.6em; font-weight: 900; margin: 0; line-height: 1em; }
        .etiqueta_info_nutricional .indent { margin-left: 1em; }
        .etiqueta_info_nutricional .small { font-size: .8em; line-height: 1.2em; }
        .etiqueta_info_nutricional .item_row { border-top: solid 1px #ccc; padding: 3px 0; }
        .etiqueta_info_nutricional .amount-per { padding: 0 0 8px 0; }
        .etiqueta_info_nutricional .daily-value { padding: 0 0 8px 0; font-weight: bold; text-align: right; border-top: solid 4px #666; }
        .etiqueta_info_nutricional .f-left { float: left; }
        .etiqueta_info_nutricional .f-right { float: right; }
        .etiqueta_info_nutricional .noborder { border: none; }

        .cf:before,.cf:after { content: " "; display: table;}
        .cf:after { clear: both; }
        .cf { *zoom: 1; }
    </style>
    <?php
}


function ldin_ws_envia($data, $url)
{
    $patata=json_encode($data);
    ldin_fcaca("DATA:$patata");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER,
        array("X-HTTP-Method-Override:'POST'",
            'Content-Type:application/x-www-form-urlencoded',
            'Content-Length: ' . strlen(http_build_query($data))));
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    if ($response===false) return false;
    $response = ldin_extreure_json_de_response($response);
    // $dec=json_decode($response,true);
    return $response;
}

function ldin_extreure_json_de_response($response) {
    $pos=strpos($response,"{");
    if ($pos!==false) {
        return substr($response,$pos);
    } else
        return ""; // no hi ha json
}



?>