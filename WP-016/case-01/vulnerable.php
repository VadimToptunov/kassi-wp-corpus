<?php
/**
 * Synthetic reconstruction of CVE-2026-3300 (Everest Forms Pro <= 1.9.12): the Calculation Addon's
 * process_filter() concatenates a submitted form-field value into a PHP code string and passes it to
 * eval(), so an attacker controls the evaluated code. See meta.yml for the NVD reference.
 */
function evf_calculation_process_filter() {
    $formula = $_POST['evf_calc_expr'];
    eval( '$result = ' . $formula . ';' );
    return $result;
}
