<?php
/**

Plugin Name: Info Nutricional
Pluguin URI: http://infonutricional.net/descarga
Description: Genera una etiqueta de informaci&aacute;n nutricional a partir de los ingredientes introducidos. En <a href="http://infonutricional.net/descarga">http://infonutricional.net/descarga</a> hay documentación y soporte de usuarios. Para usar el plugin debes <a href="http://infonutricional.net/descarga">registrarte</a> e introducir tu email en el apartado de administración <a href="admin.php?page=info_nutricional">Info_Nutricional"</a>
Author: Marc Alier
Author URI: http://infonutricional.net
Version: 1.2.3
Author URI:  granludo+infonutricional@gmail.com
License:     GPL2
Text Domain: infonutricional

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
include_once "infonutricional_settings.php";
include_once "traductor.php";

class LDIN_Info_Nutricional_Post_Type {

    public function __construct()  {
        $this->register_post_type();
        $this->metaboxes();
    }

    public function metaboxes() {
        add_action("add_meta_boxes", 'ldin_add_metabox_recepta' );
        add_action("save_post", "ldin_save_post_recepta");
    }
    public function register_post_type()  {

        $labels = array(
            'name' => ldin_t('Info-Nutricional Recetas'),
            'singular_name' => ldin_t('Receta'),
            'add_new' => ldin_t('Añadir' ),
            'add_new_item' => ldin_t('Añadir Nueva Receta' ),
            'edit_item' => ldin_t('Editar Receta', 'infonutricional' ),
            'new_item' => ldin_t('Nueva Receta', 'infonutricional' ),
            'all_items' => ldin_t('Todas las Recetas', 'infonutricional' ),
            'view_item' => ldin_t('Ver Receta', 'infonutricional' ),
            'search_items' => ldin_t('Buscar Recetas', 'infonutricional' ),
            'not_found' => ldin_t('No se han encontrado Recetas', 'infonutricional' ),
            'not_found_in_trash' => ldin_t('No se han encontrado Recetas', 'infonutricional' ),
            'parent_item_colon' => '',
            'menu_name' => ldin_t('Recetas', 'infonutricional' )
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => null,
            'menu_icon' => plugins_url('info_nutricional_plugin_icon.gif', __FILE__),
            'supports' => array(
                'title',
                'thumbnail',
                'excerpt',


            )
        );
        register_post_type('info_nutricional', $args);
    }
}

function ldin_init() {
    new LDIN_Info_Nutricional_Post_Type();
}


function ldin_add_metabox_recepta() {


    add_meta_box("ldin_metabox_recepta",ldin_t("Receta"), "ldin_handle_recepta", "info_nutricional" );
}



function ldin_handle_recepta($post) {
    $temp= LD_WS_BASE."?list_aliments=".ldin_get_lang()."&server=".$_SERVER['HTTP_HOST']."&email=".
        urlencode(ldin_get_user_mail());

    $retorn = file_get_contents($temp);
    $retorn=json_decode($retorn, true);
    if (isset($retorn['code']))  {
        if ((int)$retorn['code']!=LDIN_OK) {
            $aliments="";
            ldin_fcaca("list_aliments retorna error");
        }
        else {
            $aliments=$retorn['list_aliments'];
        }
    } else {
        ldin_fcaca("list_aliments json mal format");
    }
    ldin_view_form_recepta($post, $aliments);
}



add_action('wp_head', 'ldin_estil_receptes');
add_action('init', 'ldin_init');
add_shortcode( 'etiqueta_nutricional', 'ldin_etiqueta_nutricional_shortcode');
// settings codi auto generat a http://wpsettingsapi.jeroensormani.com
add_action( 'admin_menu', 'ldin_add_admin_menu');
add_action( 'admin_init', 'ldin_settings_init');




?>