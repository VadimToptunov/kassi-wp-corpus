<?php
/**
 * Fixed form: the formula is evaluated by a safe arithmetic parser instead of eval(), so request data
 * is never executed as PHP.
 */
function evf_calculation_process_filter() {
    $formula = $_POST['evf_calc_expr'];
    return evf_safe_math_eval( $formula );
}
