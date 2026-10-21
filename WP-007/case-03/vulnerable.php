<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: duplicator 1.3.26, ctrls/class.web.services.php (excerpt; elided context marked '...')
// CVE-2020-11738. Copyright the duplicator authors.
class DUP_Web_Services
{
	// ...
    public static function duplicator_download() {
        $file = sanitize_text_field($_GET['file']);
        $filepath = DUPLICATOR_SSDIR_PATH.'/'.$file;
        // Process download
        if(file_exists($filepath)) {
            // Clean output buffer
            if (ob_get_level() !== 0 && @ob_end_clean() === FALSE) {
                @ob_clean();
            }

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            flush(); // Flush system output buffer

            try {
                $fp = @fopen($filepath, 'r');
                if (false === $fp) {
                    throw new Exception('Fail to open the file '.$filepath);
                }
                while (!feof($fp) && ($data = fread($fp, DUPLICATOR_BUFFER_READ_WRITE_SIZE)) !== FALSE) {
                    echo $data;
                }
                @fclose($fp);
            } catch (Exception $e) {
                readfile($filepath);
            }
            exit;
        } else {
            wp_die('Invalid installer file name!!');
        }
    }
}
