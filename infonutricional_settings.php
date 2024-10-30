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




function ldin_add_admin_menu(  ) {
    add_menu_page( 'info_nutricional', 'info_nutricional', 'manage_options', 'info_nutricional', 'ldin_options_page');

}

function ldin_get_user_mail() {
    $options = get_option( 'ldin_settings' );
    return $options['ldin_setting_email'];
}
function ldin_settings_init(  ) {

    register_setting( 'pluginPage', 'ldin_settings' );

    add_settings_section(
        'ldin_pluginPage_section',
        'Configuraci&oacute;n',
        'ldin_settings_section_callback',
        'pluginPage'
    );

    add_settings_field(
        'ldin_setting_email',
        'E-mail',
        'ldin_setting_email_render',
        'pluginPage',
        'ldin_pluginPage_section'
    );


    add_settings_field(
        'ldin_setting_label_lang',
        'label_lang',
        'ldin_setting_label_lang_render',
        'pluginPage',
        'ldin_pluginPage_section'
    );


    add_settings_field(
        'ldin_setting_lang',
        'lang',
        'ldin_setting_lang_render',
        'pluginPage',
        'ldin_pluginPage_section'
    );




}



function ldin_setting_lang_render() {
    $options = get_option( 'ldin_settings' );

    ?>
    Select the language for the ADMIN INTERFACE<br>
    Elige el lenguaje para la INTERFAZ DE ADMINISTRACI&Oacute;N<br>
    <select name="ldin_settings[ldin_setting_lang]">
        <option value="<?php echo $options['ldin_setting_lang']; ?>" selected>
            <?php echo $options['ldin_setting_lang']; ?>
        </option>
        <option value="EN">EN</option>
        <option value="ES">ES</option>
    </select>

    <?php

}

function ldin_setting_label_lang_render() {
    $options = get_option( 'ldin_settings' );

    ?>Select the language for the NUTRITION FACTS LABEL<br>
      Elige el lenguaje para la ETIQUETA DE INFORMACI&Oacute;N NUTRICIONAL<br>
    <select name="ldin_settings[ldin_setting_label_lang]">
        <option value="<?php echo $options['ldin_setting_label_lang']; ?>" selected>
            <?php echo $options['ldin_setting_label_lang']; ?>
        </option>
        <option value="EN">EN</option>
        <option value="ES">ES</option>
        <option value="CAT">CAT</option>
        <option value="IT">IT</option>
    </select>

    <?php

}



function ldin_setting_email_render(  ) {


    echo "<input type='email' name='ldin_settings[ldin_setting_email]' value=".ldin_get_user_mail().'></input>';


}



function ldin_settings_section_callback(  ) {


}


function ldin_options_page(  ) {

    ?>
    <form action='options.php' method='post'>

        <?php
        settings_fields( 'pluginPage' );
        do_settings_sections( 'pluginPage' );
        submit_button();
        ?>

    </form>
    <?php

}

function ldin_options_page_bis(  ) {

    ?>


<hr>
        <strong>Información Nutricional<strong>
        Debes activar tu email para usar el plug-in.  <br>
    <hr>
    Si ya tienes el email activado <strong><a href="admin.php?page=info_nutricional">
            debes introducir tu email en las opciones de administración.
        </a></strong><br>
    Si ya has introducido el email no olvides ACTUALIZAR ESTA P&Aacute;GINA.

    <?php

}

?>