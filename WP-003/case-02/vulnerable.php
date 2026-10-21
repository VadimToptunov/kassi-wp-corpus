<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: checkout-mestres-wp 8.7.5, backend/core/base/ajax.php (excerpt; elided context marked '...')
// CVE-2025-2266. Copyright the checkout-mestres-wp authors.
add_action( 'wp_ajax_cwmpUpdateOptions', 'cwmpUpdateOptions' );
add_action( 'wp_ajax_nopriv_cwmpUpdateOptions', 'cwmpUpdateOptions' );
function cwmpUpdateOptions() {
    if (isset($_POST['data'])) {
        parse_str($_POST['data'], $parsed_data);
        $campos_com_html = array('cwmp_remember_password_body', 'parcelas_mwp_payment_second_pre', 'parcelas_mwp_payment_list_format_s_juros', 'parcelas_mwp_payment_list_format_c_juros');
        foreach ($parsed_data as $campo => $valor) {
            if (in_array($campo, $campos_com_html)) {
                $safe_value = $valor;
            } else {
                $safe_value = sanitize_text_field($valor);
            }
            update_option(sanitize_text_field($campo), $safe_value);
        }
        echo "Opções atualizadas com sucesso.";
    } else {
        echo "Nenhum dado foi enviado.";
    }

    wp_die();
}
