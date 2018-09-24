<?php
namespace IGFW\Models\Orders;

use IGFW\Abstracts\Abstract_Main_Plugin_Class;

use IGFW\Interfaces\Model_Interface;

use IGFW\Helpers\Plugin_Constants;
use IGFW\Helpers\Helper_Functions;

use IGFW\Models\Gateways\IGFW_Invoice_Gateway;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Model that houses the logic of order emails.
 * Public Model.
 *
 * @since 1.0.0
 */
class IGFW_Order_Email implements Model_Interface {

    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
    */

    /**
     * Property that holds the single main instance of Bootstrap.
     *
     * @since 1.0.0
     * @access private
     * @var Bootstrap
     */
    private static $_instance;

    /**
     * Model that houses all the plugin constants.
     *
     * @since 1.0.0
     * @access private
     * @var Plugin_Constants
     */
    private $_constants;

    /**
     * Property that houses all the helper functions of the plugin.
     *
     * @since 1.0.0
     * @access private
     * @var Helper_Functions
     */
    private $_helper_functions;

    


    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Class constructor.
     * 
     * @since 1.0.0
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     */
    public function __construct( Abstract_Main_Plugin_Class $main_plugin , Plugin_Constants $constants , Helper_Functions $helper_functions ) {
        
        $this->_constants        = $constants;
        $this->_helper_functions = $helper_functions;

        $main_plugin->add_to_all_plugin_models( $this );
        $main_plugin->add_to_public_models( $this );

    }

    /**
     * Ensure that only one instance of this class is loaded or can be loaded ( Singleton Pattern ).
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     * @return Bootstrap
     */
    public static function get_instance( Abstract_Main_Plugin_Class $main_plugin , Plugin_Constants $constants , Helper_Functions $helper_functions ) {

        if ( !self::$_instance instanceof self )
            self::$_instance = new self( $main_plugin , $constants , $helper_functions );
        
        return self::$_instance;

    }
    
    /**
     * Add invoice note to admin new order email.
     *
     * @since 1.0.0
     * @access public
     *
     * @param WC_Order $order         Order object.
     * @param Boolean  $sent_to_admin Flag that determines if sent to admin or not.
     * @param Boolean  $plain_text    Flag that determines if plain text email.
     * @param WC_Email $email         Email object.
     */
    public function add_invoice_note_to_admin_new_order_email( $order , $sent_to_admin , $plain_text , $email ) {

        if ( $email instanceof \WC_Email_New_Order && $order instanceof \WC_Order ) {

            if ( get_post_meta( $order->id , '_payment_method' , true ) == 'igfw_invoice_gateway' ) {

                if ( $plain_text )
                    echo "\nNOTE: This order requires an invoice.\n";
                else
                    echo '<span style="color: red; font-weight: 600;"><p>' . __( 'NOTE: This order requires an invoice.' , 'invoice-gateway-for-woocommerce' ) . '</p></span>';
                
            }
            
        }

    }

    /**
     * Add "paid by invoice" note on customer completed order email.
     *
     * @since 1.0.0
     * @access public
     *
     * @param WC_Order $order         Order object.
     * @param Boolean  $sent_to_admin Flag that determines if sent to admin or not.
     * @param Boolean  $plain_text    Flag that determines if plain text email.
     * @param WC_Email $email         Email object.
     */
    public function add_paid_by_invoice_note_on_customer_completed_order_email( $order , $sent_to_admin , $plain_text , $email ) {

        if ( $email instanceof \WC_Email_Customer_Completed_Order && $order instanceof \WC_Order ) {

            if ( get_post_meta( $order->id , '_payment_method' , true ) == 'igfw_invoice_gateway' ) {

                $invoice_number = get_post_meta( $order->id , Plugin_Constants::Invoice_Number , true );
                
                if ( $invoice_number != "" ) {

                    if ( $plain_text )
                        echo "\n" . __( 'Paid via invoice number: ' , 'invoice-gateway-for-woocommerce' ) . $invoice_number . "\n";
                    else
                        echo sprintf( __( '<br><p>Paid via invoice number: <b>%1$s</b></p>' , 'invoice-gateway-for-woocommerce' ) , $invoice_number );
                    
                }
                
            }

        }

    }

    /**
     * Execute url coupon model.
     * 
     * @inherit IGFW\Interfaces\Model_Interface
     * 
     * @since 1.0.0
     * @access public
     */
    public function run() {

        add_action( 'woocommerce_email_order_details' , array( $this , 'add_invoice_note_to_admin_new_order_email' ) , 9 , 4 );
        add_filter( 'woocommerce_email_order_details' , array( $this , 'add_paid_by_invoice_note_on_customer_completed_order_email' ) , 9 , 4 );

    }

}