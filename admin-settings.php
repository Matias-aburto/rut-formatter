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
        <h2>RUT Formatter Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('rut-formatter-settings-group'); ?>
            <?php do_settings_sections('rut-formatter-settings'); ?>

            <!-- Estilo CSS para el switch on/off -->
            <style>
                .switch {
                    position: relative;
                    display: inline-block;
                    width: 60px;
                    height: 34px;
                }
                .switch input {
                    display: none;
                }
                .slider {
                    position: absolute;
                    cursor: pointer;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: #ccc;
                    transition: 0.4s;
                }
                .slider:before {
                    position: absolute;
                    content: "";
                    height: 26px;
                    width: 26px;
                    left: 4px;
                    bottom: 4px;
                    background-color: white;
                    transition: 0.4s;
                }
                input:checked + .slider {
                    background-color: #2196F3;
                }
                input:checked + .slider:before {
                    transform: translateX(26px);
                }
                .slider.round {
                    border-radius: 34px;
                }
                .slider.round:before {
                    border-radius: 50%;
                }
            </style>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Registra la configuración

// Crea una función para registrar la configuración
function rut_formatter_register_settings() {
    register_setting('rut-formatter-settings-group', 'billing_rut_field_name');
    register_setting('rut-formatter-settings-group', 'enable_dv_validation'); // Registrar la opción en la base de datos.

    add_settings_section('rut-formatter-main-section', 'Configuración principal', null, 'rut-formatter-settings');
    
    add_settings_field('billing_rut_field_name', 'Nombre del campo RUT', 'rut_formatter_field_name_callback', 'rut-formatter-settings', 'rut-formatter-main-section');
    add_settings_field('enable_dv_validation', 'Validación de dígito verificador', 'rut_formatter_dv_validation_callback', 'rut-formatter-settings', 'rut-formatter-main-section');
}
add_action('admin_init', 'rut_formatter_register_settings');

// Función de callback para mostrar el campo
function rut_formatter_field_name_callback() {
    $billing_rut_field_name = get_option('billing_rut_field_name', 'billing_rut'); // 'billing_rut' es el valor por defecto.
    echo "<input type='text' name='billing_rut_field_name' value='$billing_rut_field_name' />";
}

// Función de callback para mostrar el switch
function rut_formatter_dv_validation_callback() {
    $enable_dv_validation = get_option('enable_dv_validation', 'off'); // 'off' es el valor por defecto.
    echo "<label class='switch'>
            <input type='checkbox' name='enable_dv_validation' value='on' " . checked($enable_dv_validation, 'on', false) . " />
            <span class='slider round'></span>
          </label>";
}
?>
