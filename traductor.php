<?php
/**
 * Created by PhpStorm.
 * User: ludo
 * Date: 29/12/2016
 * Time: 15:16
 */

include_once "receptes.php";
include_once "info_nutricional_setup.php";
include_once "valors_nutricionals.php";
include_once "infonutricional_settings.php";
include_once "traductor.php";

   $ldin_lang='NO';

   function ldin_t($string) {
       global $ldin_lang;
       if (strcmp($ldin_lang,'NO')==0){
           $options = get_option( 'ldin_settings' );
           if (isset($options['ldin_setting_lang'])) {
                $ldin_lang=$options['ldin_setting_lang'];
            } else {
                $ldin_lang = "EN";
            }
       }
       if ($ldin_lang[1]=='S') {
           return ldin_trad($string,ldin_es());
       } else {
           return ldin_trad($string, ldin_en());
       }
   }

   function ldin_trad($string, $traduccions) {
       if (isset($traduccions[$string])) {
           return $traduccions[$string];
       } else {
           return $string;
       }
   }

   function ldin_es() {
       $es=array(
         "MSG_MOSTRAR_CODIGO" =>  "Para mostrar la etiqueta nutricional debes introducir este código en tu p&aacutegina o entrada",
          "MSG_ERROR_CONNECT" => "<h1><font color='red'>Error en conexi&oacute;n con el servidor, </font></h1><h2>Contacte con soporte t&eacute;cnico en</h2><strong>info@infonutricional.net</strong>"
      //     'MSG_ENTER_EMAIL'   =>  'Introduce el correo elecrt&oacute;nico que has registrado en <a href=http://infonutricional.net/descarga>http://infonutricional.net/descarga</a>',
      /*     'MSG_OPTIONS_PAGE'  =>   '     <h2>Información Nutricional</h2>
        Debes activar tu email para usar el plug-in.  <br>
        M&aacute;s informaci&oacute;n  en <a href=http://infonutricional.net/descarga>
            InfoNutricional.net/Descarga</a>.<br><hr>',
           'MSG_REGISTER_PLEASE' => '<hr>
        <strong>Información Nutricional<strong>
        Debes activar tu email para usar el plug-in.  <br>
    <hr>
       Si ya tienes el email activado <strong><a href="admin.php?page=info_nutricional">
           debes introducir tu email en las opciones de administración.
        </a></strong><br>
       Si ya has introducido el email no olvides ACTUALIZAR ESTA P&Aacute;GINA.'
*/
       );

       return $es;
   }

    function ldin_en() {
        $en=array(
          " Receta"                         =>  'Recipe',
            "Info-Nutricional Recetas"      =>  'Info-Nutricional Recipes',
            'Añadir'                        =>  'Add New',
            'Añadir Nueva Receta'           =>  'Add New Recipe',
            'Editar Receta'                 =>  'Edit Recipe',
            'Nueva Receta'                  =>  'New Recipe',
            'Todas las Recetas'             =>  'All Recipes',
            'Ver Receta'                    =>  'View Recipe',
            'Buscar Recetas'                =>  'Search Recicpes',
            'No se han encontrado Recetas'  =>  'No Recipes Found',
            'Recetas'                       =>  'Recipes',
            "MSG_MOSTRAR_CODIGO" =>  "To display the Nutrition Facts Label you need to add this short code to your page or post:"
        );
        return $en;
    }

?>