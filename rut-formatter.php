<?php
/*
Plugin Name: Formateador de RUT
Description: Formatea y valida el RUT chileno en formularios.
Version: 1.1
Author: <a href="https://simetry.cl" target="_blank">Simetry Code</a>
*/

// Incluir el archivo de configuración de la administración
include_once(plugin_dir_path(__FILE__) . 'admin-settings.php');

// Agregar enlace a la página de ajustes del plugin
function rut_formatter_add_settings_link($links) {
    $settings_link = '<a href="admin.php?page=rut-formatter-settings">Ajustes</a>';
    $changelog_link = '<a href="' . plugin_dir_url(__FILE__) . 'changelog.txt">Changelog</a>';
    array_unshift($links, $settings_link);
    array_unshift($links, $changelog_link);
    return $links;
}

$plugin_basename = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin_basename", 'rut_formatter_add_settings_link');

// Agregar scripts y estilos
function rut_formatter_enqueue_scripts() {
    wp_enqueue_script('rut-formatter', plugin_dir_url(__FILE__) . 'rut-formatter.js', array('jquery'), '1.0', true);

    $localizations = array(
        'billing_rut_field_name' => get_option('billing_rut_field_name', 'billing_rut')
    );

    wp_localize_script('rut-formatter', 'RUTFormatter', $localizations);
}
add_action('wp_enqueue_scripts', 'rut_formatter_enqueue_scripts');

// Validación del RUT en el lado del servidor
function rut_formatter_validate_rut() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['woocommerce_checkout_place_order'])) {
        $billing_rut = sanitize_text_field($_POST[get_option('billing_rut_field_name', 'billing_rut')]);

        if (!rut_formatter_validate_rut_format($billing_rut)) {
            // RUT no válido, muestra un mensaje de error o realiza la acción correspondiente.
            wc_add_notice("El RUT ingresado no es válido.", 'error');
        }
    }
}
add_action('woocommerce_review_order_before_payment', 'rut_formatter_validate_rut');

// Validación del formato del RUT
function rut_formatter_validate_rut_format($rut) {
    $rut = preg_replace('/[^0-9Kk]/', '', $rut);
    $rut = str_replace('K', 'k', $rut);

    if (strlen($rut) !== 9) {
        return false;
    }

    $rutDigits = substr($rut, 0, 8);
    $rutVerifier = substr($rut, 8, 1);

    $rutVerifierCalculated = rut_formatter_calculate_verifier_digit($rutDigits);

    return $rutVerifier === $rutVerifierCalculated;
}

// Cálculo del dígito verificador del RUT
function rut_formatter_calculate_verifier_digit($rutDigits) {
    $rutDigits = strrev($rutDigits);
    $factor = 2;
    $sum = 0;

    for ($i = 0; $i < strlen($rutDigits); $i++) {
        $sum += intval($rutDigits[$i]) * $factor;
        $factor = ($factor % 7) + 1;
    }

    $remainder = $sum % 11;
    $verifier = 11 - $remainder;

    return ($verifier == 10) ? 'k' : strval($verifier);
}
?>
