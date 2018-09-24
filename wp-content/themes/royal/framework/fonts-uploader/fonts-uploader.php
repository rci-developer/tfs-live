<?php 
if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );

// **********************************************************************// 
// ! Etheme fonts uploader
// **********************************************************************//

class Etheme_fonts_uploader{

    function __construct() {
        // ! Set data
        $this->post_data = $_POST;
        $this->file_data = $_FILES;
        $this->errors = array();
        $this->result = false;

        // ! Remove font file by ajax
        add_action( 'wp_ajax_et_ajax_fonts_remove', array( $this, 'et_ajax_fonts_remove') );

        // ! Set notices
        if ( isset( $this->post_data['et-upload'] ) ){
            if ( isset( $this->post_data['et-upload'] ) ) $this->upload_action();
            add_action( 'admin_notices', array( $this, 'admin_notices' ), 999 );
        }
    }

    // ! Set notices
    public function admin_notices(){
        $type = ( $this->result ) ? 'notice-success' : 'notice-error et_font-notice-error';
        $out = '<div class="notice ' . $type . ' is-dismissible" >';

        if ( count( $this->errors ) > 0 ) {
            foreach ( $this->errors as $value ) $out .= '<p>' . $value . '</p>';
        }

        $out .= '</div>';
        echo $out;
    }

    // ! Enqueue scripts/styles
    public function enqueue() {
        wp_enqueue_script(
            'fonts-uploader-js',
            get_template_directory_uri() . '/framework/fonts-uploader/fonts-uploader.js',
            array( 'jquery' ),
            time(),
            true
        );

        wp_enqueue_style(
            'fonts-uploader-css',
            get_template_directory_uri() . '/framework/fonts-uploader/fonts-uploader.css',
            time(),
            true
        );
    }

    // ! Remove font by ajax
    public function et_ajax_fonts_remove() {

        $post_data = $_POST;
        $fonts = get_option( 'etheme-fonts', false );
        $out = array(
            'messages' => array(),
            'status' => 'error'
        );

        if ( ! isset( $post_data['id'] ) || empty( $post_data['id'] ) ) {
            $out['messages'][] = esc_html__( 'File ID does not exist', 'royal' );
            echo json_encode( $out );
            die();
        }

        if ( ! function_exists( 'wp_delete_file' ) ) require_once ABSPATH . WPINC . '/functions.php';

        foreach ( $fonts as $key => $value ) {

            if ( $value['id'] == $post_data['id'] ) {

                $file = $value['file']['name'];

                // ! Change upload dir
                add_filter( 'upload_dir', array( $this, 'etheme_upload_dir' ) );

                $upload_dir = wp_upload_dir();
                $file = str_replace( ' ', '-', $file );
                $file = $upload_dir['basedir'] . $upload_dir['subdir'] . '/' . $file;

                // ! Set upload dir to default
                remove_filter( 'upload_dir', array( $this, 'etheme_upload_dir' ) );

                wp_delete_file( $file );

                if ( $this->etheme_file_exists( $value['file']['name'] ) ) {
                    $out['messages'][] = esc_html__( 'File was\'t deleted', 'royal' );
                    echo json_encode( $out );
                    die();
                } else {
                    unset( $fonts[$key] );
                }
            }
        }

        update_option( 'etheme-fonts', $fonts );

        if ( count( $out['messages'] ) < 1 ){
            $out['status'] = 'success';
            $out['messages'][] = esc_html__( 'File was deleted', 'royal' );
        } 

        echo json_encode( $out );
        die();
    }

    // ! Check file exists by name
    public function etheme_file_exists( $name ) {

        // ! Change upload dir
        add_filter( 'upload_dir', array( $this, 'etheme_upload_dir' ) );

        $upload_dir = wp_upload_dir();
        $name = str_replace( ' ', '-', $name );
        $file = $upload_dir['basedir'] . $upload_dir['subdir'] . '/' . $name;
        $file = file_exists( $file );

        // ! Set upload dir to default
        remove_filter( 'upload_dir', array( $this, 'etheme_upload_dir' ) );

        return $file;
    }

    // ! Field Render Function
    public function render() {

        $out = $style = '';

        $fonts = get_option( 'etheme-fonts', false );

        $out .= '<div><div class="etheme-fonts-section">';
            $out .= '<span class="add-form">' . esc_html__( 'Upload font', 'royal' ) . '</span>';

            // ! Out font information
            if ( $fonts ) {
                $out .= '<div class="et_fonts-info">';
                    $out .= '<h3>' . esc_html__( 'Uploaded fonts', 'royal' ) . '</h3>';
                    $out .= '<ul>';

                        $style .= '<style>';

                        foreach ( $fonts as $value ) {

                            // ! Set HTML
                            $out .= '<li>';
                                $out .= '<p>';
                                    $out .= '<span class="et_font-name">' . $value['name'] . '</span>';
                                    $out .= '<i class="et_font-remover dashicons dashicons-no" data-id="' . $value['id'] . '"></i>';
                                $out .= '</p>';

                                if ( ! $this->etheme_file_exists( $value['file']['name'] ) ){
                                    $out .= '<p class="et_font-error">';
                                        $out .= esc_html__( 'It looks like font file was removed from the folder directly', 'royal' );
                                    $out .= '</p>';
                                    continue;
                                }

                                $out .= '<p class="et_font-preview" style="font-family: ' . $value['name'] . ';"> 1 2 3 4 5 6 7 8 9 0 A B C D E F G H I J K L M N O P Q R S T U V W X Y Z a b c d e f g h i j k l m n o p q r s t u v w x y z </p>';
                                $out .= '<details>';
                                    $out .= '<summary>' . esc_html__( 'Font details', 'royal' ) . '</summary>';
                                    $out .= '<ul>';
                                        $out .= '<li>' . esc_html__( 'Uploaded at', 'royal' ) . ' : ' . $value['file']['time'] . '</li>';
                                        $out .= '<li>';
                                            $out .= esc_html__( 'Uploaded by', 'royal' ) . ' : ' . $value['user']['user_login'];
                                            $out .= ' "' . $value['user']['user_email'] . '"';
                                            foreach ( $value['user']['roles'] as $role ) $out .= ' ' . $role;
                                        $out .='</li>';
                                        $out .= '<li>' . esc_html__( 'File name', 'royal' ) . ' : ' . $value['file']['name'] . '</li>';
                                        $out .= '<li>' . esc_html__( 'File size', 'royal' ) . ' : ' . $this->file_size( $value['file']['size'] ) . '</li>';
                                    $out .= '</ul>';
                                $out .= '</details>';
                            $out .= '</li>';

                            // ! Validate format
                            switch ( $value['file']['extension'] ) {
                                case 'ttf':
                                    $format = 'truetype';
                                    break;
                                case 'otf':
                                    $format = 'opentype';
                                    break;
                                case 'eot':
                                    $format = false;
                                    break;
                                case 'eot?#iefix':
                                    $format = 'embedded-opentype';
                                    break;
                                case 'woff2':
                                    $format = 'woff2';
                                    break;
                                case 'woff':
                                    $format = 'woff';
                                    break;
                                default:
                                    $format = false;
                                    break;
                            } 

                            $format = ( $format ) ? 'format("' . $format . '")' : '';

                            // ! Set fonts
                            $style .= '
                                @font-face {
                                    font-family: ' . $value['name'] . ';
                                    src: url(' . $value['file']['url'] . ') ' . $format . ';
                                }
                            ';
                        }

                        $style .= '</style>';

                    $out .= '</ul>';
                $out .= '</div>';
            }

        $out .= '</div>';

        $out .= '
            <div class="etheme-fonts-section fonts-notifications">
                <h4 clas="et_fonts-table-title">' . esc_html__( 'Browser Support for Font Formats', 'royal' ) . '</h4>
                <p>' . esc_html__( 'Please, make sure that you upload font formats that are supported by all the browsers.', 'royal' ) . '</p>
                <table>
                    <tbody>
                        <tr>
                            <th>' . esc_html__( 'Font format', 'royal' ) . '</th>
                            <th class="et_fonts-br-name et_ie">ie/edge</th>
                            <th class="et_fonts-br-name et_chrome">chrome</th>
                            <th class="et_fonts-br-name et_firefox">firefox</th>
                            <th class="et_fonts-br-name et_safari">safari</th>
                            <th class="et_fonts-br-name et_opera">opera</th>                
                        </tr>
                        <tr>
                            <td>TTF/OTF</td>
                            <td>9.0*</td>
                            <td>4.0</td>
                            <td>3.5</td>
                            <td>3.1</td>
                            <td>10.0</td>
                        </tr>
                        <tr>
                            <td>WOFF</td>
                            <td>9.0</td>
                            <td>5.0</td>
                            <td>3.6</td>
                            <td>5.1</td>
                            <td>11.1</td>
                        </tr>
                        <tr>
                            <td>WOFF2</td>
                            <td><i class="et_deprecated dashicons dashicons-no"></i></td>
                            <td>36.0</td>
                            <td>35.0*</td>
                            <td><i class="et_deprecated dashicons dashicons-no"></i></td>
                            <td>26.0</td>
                        </tr>
                        <tr>
                            <td>EOT</td>
                            <td>6.0</td>
                            <td><i class="et_deprecated dashicons dashicons-no"></i></td>
                            <td><i class="et_deprecated dashicons dashicons-no"></i></td>
                            <td><i class="et_deprecated dashicons dashicons-no"></i></td>
                            <td><i class="et_deprecated dashicons dashicons-no"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>';
        echo $style . $out;
    }

    // ! Upload file
    private function upload_action(){

        // ! Return if name file
        if ( ! isset( $this->file_data['et-fonts'] ) || empty( $this->file_data['et-fonts'] ) ){
            $this->errors[] = esc_html__( 'Empty Font file field', 'royal' );
            return;
        } 

        // ! Require file
        if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );

        // ! Set Valid file formats
        $valid_formats = array( 'eot', 'woff2', 'woff', 'ttf', 'otf' );

        $file = $this->file_data['et-fonts'];
        
        // ! Get file extension
        $extension = pathinfo( $file['name'], PATHINFO_EXTENSION );

        // ! Check file extension
        if ( ! in_array( strtolower( $extension ), $valid_formats ) ){
            $this->errors[] = esc_html__( 'Wrong file extension "use only: eot, woff2, woff, ttf, otf"', 'royal' );
            return;
        } 

        // ! Check size 5mb limit
        if ( $file['size'] > ( 1048576 * 5 ) ){
            $this->errors[] = esc_html__( 'File size more then 5MB', 'royal' );
            return;
        } 
        
        if ( $file['name'] ) {

            // ! Set overrides
            $overrides = array( 
                'test_form' => false,
                'test_type' => false,
            );

            // ! Set font user data
            $user  = wp_get_current_user();
            $by = array();
            $by['user_email'] = $user->user_email;
            $by['user_login'] = $user->user_login;
            $by['roles'] = array();
            foreach ( $user->roles as $value ) $by['roles'][] = $value;
           
            $font_file = array(
                'name' => $file['name'],
                'type' => $file['type'],
                'size' => $file['size'],
                'extension' => $extension,
                'time' => current_time( 'mysql' ),
            );

            // ! Change upload dir
            add_filter( 'upload_dir', array( $this, 'etheme_upload_dir' ) );

            $status = wp_handle_upload( $file, $overrides );

            // ! Set upload dir to default
            remove_filter( 'upload_dir', array( $this, 'etheme_upload_dir' ) );

            if ( $status && ! isset( $status['error'] ) ) {
                $font_file['url'] = $status['url'];
                $this->gafq_files[] = $font_file;
                $this->errors[] = esc_html__( 'Font was successfully uploaded.', 'royal' );
                $this->result = true;

                // ! Update fonts
                $fonts = get_option( 'etheme-fonts', false );
                $font = array();

                $font['id'] = mt_rand( 1000000,9999999 );
                $font['name'] = str_replace( '.' . $extension, '', $file['name'] );
                $font['file'] = $font_file;
                $font['user'] = $by;
                $fonts[] = $font;
                update_option( 'etheme-fonts', $fonts );

            }
        }
        return;
    }

    // ! Upload dir filter function
    public function etheme_upload_dir($dir){
        $time = current_time( 'mysql' );
        $y = substr( $time, 0, 4 );
        $m = substr( $time, 5, 2 );
        $subdir = "/$y/$m";

        return array(
            'path' => $dir['basedir'] . '/custom-fonts' . $subdir,
            'url' => $dir['baseurl'] . '/custom-fonts' . $subdir ,
            'subdir' => '/custom-fonts' . $subdir,
        ) + $dir;
    }

    // Get formated file size
    public function file_size( $bytes ){
        if ( $bytes  >= 1073741824 ) {
            $bytes  = number_format( $bytes  / 1073741824, 2 ) . ' GB';
        } elseif ( $bytes  >= 1048576) {
            $bytes  = number_format( $bytes  / 1048576, 2 ) . ' MB';
        } elseif ( $bytes  >= 1024 ) {
            $bytes  = number_format( $bytes  / 1024, 2 ) . ' KB';
        } elseif ( $bytes  > 1 ) {
            $bytes  = $bytes  . ' bytes';
        } elseif ( $bytes  == 1 ) {
            $bytes  = $bytes  . ' byte';
        } else {
            $bytes  = '0 bytes';
        }
        return $bytes;
    }
}
new Etheme_fonts_uploader();