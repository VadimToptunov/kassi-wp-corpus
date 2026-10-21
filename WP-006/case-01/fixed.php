<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: woo-preview-emails 2.0.0, views/form.php (excerpt; elided context marked '...')
// CVE-2021-42363 (fixed). Copyright the woo-preview-emails authors.
?>
                <select name="search_order" id="woo_preview_search_orders" class="woo_preview_search_orders" class="regular-text" style="width: 35%;">
					<?php
					if ( ! empty( $_POST['search_order'] ) ) {
						?>
                        <option value="<?php echo esc_attr( $_POST['search_order'] ); ?>" selected="selected">#order : <?php echo esc_attr( $_POST['search_order'] ); ?></option>
						<?php
					}
					?>
