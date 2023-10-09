<?php
// Crear una página de ajustes para el plugin

// Registra una página de ajustes
function rut_formatter_settings_page() {
    add_options_page(
        'Ajustes del Formateador de RUT',
        'Formateador de RUT',
        'manage_options',
        'rut-formatter-settings',
        'rut_formatter_settings_content'
    );
}
add_action('admin_menu', 'rut_formatter_settings_page');

// Muestra el contenido de la página de ajustes
function rut_formatter_settings_content() {
    ?>
    <div class="wrap">
        <h2>Ajustes del Formateador de RUT</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('rut-formatter-settings-group');
            do_settings_sections('rut-formatter-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Registra la configuración

// Crea una función para registrar la configuración
function rut_formatter_register_settings() {
    register_setting('rut-formatter-settings-group', 'billing_rut_field_name');

    add_settings_section('rut-formatter-main-section', 'Configuración principal', null, 'rut-formatter-settings');
    
    add_settings_field('billing_rut_field_name', 'Nombre del campo RUT', 'rut_formatter_field_name_callback', 'rut-formatter-settings', 'rut-formatter-main-section');
}
add_action('admin_init', 'rut_formatter_register_settings');

// Función de callback para mostrar el campo
function rut_formatter_field_name_callback() {
    $billing_rut_field_name = get_option('billing_rut_field_name', 'billing_rut'); // 'billing_rut' es el valor por defecto.
    echo "<input type='text' name='billing_rut_field_name' value='$billing_rut_field_name' />";
}
?>
