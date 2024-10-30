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
include_once "info_nutricional_setup.php";


function ldin_explode_args($args, $verbose) {
    if ($verbose==false) return "...";
    else {
        $str="";
        foreach ($args as $clau => $valor) {
            $str=" $clau = ".json_encode($valor);
        }
        return $str;
    }
}

function ldin_fcaca($missatge) {

    $fcaca_verbose=true;

    $backtrace=debug_backtrace ( DEBUG_BACKTRACE_PROVIDE_OBJECT ,4 );
    $str_backtrace="".$backtrace[1]["function"]."(".ldin_explode_args($backtrace[1]["args"],$fcaca_verbose).") on".$backtrace[1]["file"]." line ".$backtrace[1]["line"];
//    $str_backtrace.="".$backtrace[2]["function"]."(".ldin_explode_args($backtrace[2]["args"],$fcaca_verbose).") on".$backtrace[2]["file"]." line ".$backtrace[2]["line"];
//    $str_backtrace.="\n".$backtrace[3]["function"]."(".ldin_explode_args($backtrace[3]["args"],$fcaca_verbose).") on".$backtrace[3]["file"]." line ".$backtrace[3]["line"];
    $myFile = __DIR__."/fcacaws.txt";
    $date = date('Y-m-d H:i:s');
    $fh = fopen($myFile, 'a');
    $stringData = "$str_backtrace:\n$date:\t$missatge\n";
    fwrite($fh, $stringData);
    fclose($fh);
}


function ldin_view_form_recepta($post, $aliments) {
    $email=ldin_get_user_mail();
    if (strcmp($email, "")==0) {
        echo "<H1>please enter a valid email adress in the INFO_NUTRICIONAL menu</h1>";
        return;
    }


    echo $aliments; // Aliments ja ha de venir en format HTML desde WS

    $pack=get_post_meta((int)$post->ID, "recepta", true);
    $elpack=json_decode($pack, true);
    if (isset($elpack['unknown'])) {
        ldin_gestiona_manca_de_email_registrat($post);
        return;
    }
    echo "<hr>".
        ldin_t("MSG_MOSTRAR_CODIGO" ).
       "<br><pre><strong>[etiqueta_nutricional id=$post->ID]</strong><pre><br>";
    if (empty($pack)) {
        echo ldin_form_recepta_buida();
    } else if (strcmp($pack, "cuac")==0) {
         echo ldin_form_recepta_buida();
    } else {
        $pack = json_decode($pack, true);

            echo "<hr><br>" . $pack['errors'] . "<br><hr>";
            echo $pack['form_recepta'];
        echo "<div style='border:1px solid #ccc; font-family: helvetica, arial, sans-serif; font-size: 2.0em; width: 22em; padding: 1em 1.25em 1em 1.25em; line-height: 1.0em; margin: 1em;'>";
            echo $pack['etiqueta_mk'];
        echo "</div>";
     }
}

function ldin_gestiona_manca_de_email_registrat($post) {
    ldin_options_page_bis();
    update_post_meta(
        (int)$post->ID,
        "recepta",
        null );
}

function ldin_form_recepta_buida() {

    $retorn = file_get_contents(
        LD_WS_BASE."?recepta_buida=true&lang=".ldin_get_lang()."&email=".urlencode(ldin_get_user_mail()));
    if (isset($retorn['code']))  {
        if ((int)$retorn['code']!=LDIN_OK) {
            $recepta=$retorn['errormsg'];
            ldin_fcaca("recepta_buida retorna error ".$retorn['errormsg']);
        }
        else {
            $recepta=$retorn['list_aliments'];
        }
    } else {
        ldin_fcaca("recepta_buida json mal format");
        $recepta="Error contacting infonutricional server";
    }

    return $recepta;
}

function ldin_html_select($name, $valors, $defecte) {

    $aux= " <select name='$name'>   ";
    foreach ($valors as $valor) {
        if (strcmp($valor,$defecte)==0) {
            $selected ="selected='selected'";
        } else {
            $selected="";
        }
        $aux.="<option value='$valor' $selected >$valor</option>";
    }
    $aux.="</select>";
    return $aux;
}



function ldin_sanitize_array($unsanitized) {
    $sanitized=array();
    foreach ($unsanitized as $clave=>$nosano) {
        if (is_array($nosano)) {
            ldin_fcaca("WTF??:".json_encode($nosano));
            $sanitized[$clave]=$nosano;
        } else {
            $sanitized[$clave] = strip_tags(stripslashes($nosano));
        }
    }
    return $sanitized;
}

function ldin_get_lang_label() {
    $options = get_option( 'ldin_settings' );

    return $options['ldin_setting_label_lang'];
}

function ldin_get_lang() {
    $options = get_option( 'ldin_settings' );

    return $options['ldin_setting_lang'];
}

function ldin_save_post_recepta($id) {

    $nutrients=null;
    $post_sanitized=ldin_sanitize_array($_POST);
    $enc_post=json_encode($post_sanitized);
    $lang_label=ldin_get_lang_label();
    $lang=ldin_get_lang();
    $email=ldin_get_user_mail();
        $comanda_ws=array(
            "server" => $_SERVER["HTTP_HOST"],
            "email" => $email,
            "lang" => $lang,
            "lang_label" => $lang_label,
            "accio"=>"obtenir valors nutricionals recepta",
            "recepta"=>$enc_post
        );
        $pack=ldin_ws_envia( $comanda_ws, LD_WS_BASE);
    if ($pack!=false) {
        $pack=str_replace('\t'," ",$pack);
        $pack=str_replace('\n'," ",$pack);
    } else {
        $pack=get_post_meta((int)$id, "recepta", true);
        $pack = json_decode($pack, true);
        if (!is_array($pack)) {
            $pack=array();
        }
        $pack['errors']= ldin_t("MSG_ERROR_CONNECT");
        $pack=json_encode($pack);
    }
    ldin_fcaca("pack=$pack");
    update_post_meta(
        (int)$id,
        "recepta",
        $pack );
}


?>