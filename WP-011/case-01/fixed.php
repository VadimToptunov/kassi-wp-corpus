<?php
// SPDX-License-Identifier: GPL-2.0-only
// Source: funnelforms-free 3.7.4.1, admin/menu_ajax_functions/formularbuilder_fonts.php (excerpt; elided context marked '...')
// CVE-2024-6311 (fixed). Copyright the funnelforms-free authors.
class Fnsf_Af2AjaxFormularbuilderFonts {
    // ...
    public function af2_add_font() {
        if ( !current_user_can( 'edit_others_posts' ) ) {
            die( 'Permission denied' );
        }

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'af2_FE_nonce' ) ) {
            die( 'Permission denied' );
        }

        $upload_dir = wp_upload_dir();
        $af2_fonts_dir = $upload_dir['basedir'] . '/af2_fonts';
    
        if (!file_exists($af2_fonts_dir)) {
            wp_mkdir_p($af2_fonts_dir);
        }
    
        if ($_FILES['af2FontFile']['error'] === UPLOAD_ERR_OK) {

            $originalFileName = $_FILES['af2FontFile']['name'];
            $validate = wp_check_filetype( $originalFileName );
            if ( $validate['type'] == false ) {
                die(__("File type is not allowed.", "plugin-slug"));
            }


            $tempFile = $_FILES['af2FontFile']['tmp_name'];
            $originalFileName = $_FILES['af2FontFile']['name'];
            $targetFile = $af2_fonts_dir . '/' . $originalFileName;
    
            if (move_uploaded_file($tempFile, $targetFile)) {
                echo 'Datei erfolgreich hochgeladen und gespeichert.';
            } else {
                echo 'Fehler beim Speichern der Datei.';
            }
        } else {
            echo 'Fehler beim Hochladen der Datei.';
        }
    
        wp_die();
    }
}
