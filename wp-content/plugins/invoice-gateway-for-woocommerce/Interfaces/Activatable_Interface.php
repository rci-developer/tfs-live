<?php
namespace IGFW\Interfaces;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Abstraction that provides contract relating to activation.
 * Any model that needs some sort of activation must implement this interface.
 *
 * @since 1.0.0
 */
interface Activatable_Interface {

    /**
     * Contruct for activation.
     *
     * @since 1.0.0
     * @access public
     */
    public function activate();

}