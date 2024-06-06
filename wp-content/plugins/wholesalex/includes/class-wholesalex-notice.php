<?php
/**
 * Admin Action.
 *
 * @package WHOLESALEX
 * @since 1.0.0
 */

namespace WHOLESALEX;

/**
 * WholesaleX Notice Class
 */
class WHOLESALEX_Notice {

	/**
	 * Contains Notice Version
	 *
	 * @var string
	 */
	private $notice_version = 'v9';

	/**
	 * Contains Notice type
	 *
	 * @var string
	 */
	private $type = '';
	/**
	 * Contains notice is force or not
	 *
	 * @var string
	 */
	private $force = '';
	/**
	 * Contain Notice content.
	 *
	 * @var string
	 */
	private $content = '';

	private $heading = '';

	private $subheading = '';

	private $days_remaining = '';

	private $available_notice = array();

	private $price_id = false;

	/**
	 * Admin WooCommerce Installation Notice Action
	 *
	 * @since 1.0.0
	 */
	public function install_notice() {
		add_action( 'wp_ajax_wc_install', array( $this, 'wc_install_callback' ) );
		add_action( 'admin_notices', array( $this, 'wc_installation_notice_callback' ) );
	}


	/**
	 * Admin WooCommerce Activation Notice Action
	 *
	 * @since 1.0.0
	 */
	public function active_notice() {
		add_action( 'admin_notices', array( $this, 'wc_activation_notice_callback' ) );
		add_action( 'admin_action_wc_activate', array( $this, 'wc_activate_action' ) );
	}

	/**
	 * Promotional Notice Callback
	 *
	 * @since 1.0.0
	 */
	public function promotion() {

        
	}

	private function get_notice_by_id( $id ) {
		if ( isset( $this->available_notice[ $id ] ) ) {
			return $this->available_notice[ $id ];
		}
	}

	/**
	 * WooCommerce Installation Notice
	 *
	 * @since 1.0.0
	 */
	public function wc_installation_notice_callback() {
		if ( ! get_option( 'wholesalex_dismiss_notice' ) ) {
			$this->wc_notice_css();
			?>
			<div class="wholesalex-wc-install">
				<img loading="lazy" width="200" src="<?php echo esc_url( WHOLESALEX_URL . 'assets/img/woocommerce.png' ); ?>" alt="logo" />
				<div class="wholesalex-wc-install-body">
					<h3><?php /* translators: %s: Plugin Name */ echo sprintf( esc_html__( 'Welcome to %s.', 'wholesalex' ), esc_html( wholesalex()->get_plugin_name() ) ); ?></h3>
					<p><?php /* translators: %s: Plugin Name */ echo sprintf( esc_html__( 'WooCommerce %s is a WooCommerce plugin. To use this plugins you have to install and activate WooCommerce.', 'wholesalex' ), esc_html( wholesalex()->get_plugin_name() ) ); ?></p>
					<a class="wholesalex-wc-install-btn button button-primary button-hero" href="<?php echo esc_url( add_query_arg( array( 'action' => 'wc_install' ), admin_url( 'admin-ajax.php' ) ) ); ?>"><span class="dashicons dashicons-image-rotate"></span><?php esc_html_e( 'Install WooCommerce', 'wholesalex' ); ?></a>
					<div id="installation-msg"></div>
				</div>
			</div>
			<?php
		}
	}


	/**
	 * WooCommerce Activation Notice
	 *
	 * @since 1.0.0
	 */
	public function wc_activation_notice_callback() {
		if ( ! get_option( 'wholesalex_dismiss_notice' ) ) {
			$this->wc_notice_css();
			?>
			<div class="wholesalex-wc-install">
				<img loading="lazy" width="200" src="<?php echo esc_url( WHOLESALEX_URL . 'assets/img/woocommerce.png' ); ?>" alt="logo" />
				<div class="wholesalex-wc-install-body">
				<h3><?php /* translators: %s: Plugin Name */ echo sprintf( esc_html__( 'Welcome to %s.', 'wholesalex' ), esc_html( wholesalex()->get_plugin_name() ) ); ?></h3>
				<p><?php /* translators: %s: Plugin Name */ echo sprintf( esc_html__( 'WooCommerce %s is a WooCommerce plugin. To use this plugins you have to install and activate WooCommerce.', 'wholesalex' ), esc_html( wholesalex()->get_plugin_name() ) ); ?></p>
					<a class="button button-primary button-hero" href="<?php echo esc_url( add_query_arg( array( 'action' => 'wc_activate' ), admin_url() ) ); ?>"><?php esc_html_e( 'Activate WooCommerce', 'wholesalex' ); ?></a>
				</div>
			</div>
			<?php
		}
	}


	/**
	 * WooCommerce Notice Styles
	 *
	 * @since 1.0.0
	 */
	public function wc_notice_css() {
		?>
		<style type="text/css">

			/* .wholesalex-wc-install.wholesalex-pro-notice-v2 {
				padding-bottom: 0px;
			} */
			.wholesalex-content-notice {
				color: white;
				background-color: #6C6CFF;
				position: relative;
				font-size: 16px;
				padding-left: 10px;
				line-height: 23px;
			}

			.wholesalex-notice-content-wrapper {
				margin-bottom: 0px !important;
				padding: 10px 5px;
			}

			.wholesalex-wc-install .wholesalex-content-notice .wholesalex-btn-notice-pro {
				margin-left: 5px;
				background-color: #3c3cb7 !important;
				border-radius: 4px;
				max-height: 30px !important;
				padding: 8px 12px !important;
				font-size: 14px;
				position: relative;
				top: -4px;
			}
			.wholesalex-wc-install .wholesalex-content-notice .wholesalex-btn-notice-pro:hover {
				background-color: #29298c !important;
			}

			/* .wholesalex-content-notice .content-notice-dissmiss {
				position: absolute;
				top: 0;
				right: 0;
				color: white;
				background-color: black;
				padding: 5px;
				font-size: 12px;
				line-height: 1;
				border-bottom-left-radius: 5px;
			} */

			/* .whx-new-dismiss{
				position: absolute;
				top: 0;
				right: 0;
				color: white;
				background-color: black;
				padding: 4px 5px 5px;
				font-size: 12px;
				line-height: 1;
				border-bottom-left-radius: 3px;
				text-decoration: none;
			} */

			.wholesalex-content-notice .content-notice-dissmiss {
				position: absolute;
				top: 0;
				right: 0;
				color: white;
				background-color: #3f3fa6;
				padding: 4px 5px 5px;
				font-size: 12px;
				line-height: 1;
				border-bottom-left-radius: 3px;
				text-decoration: none;
			}
			.wholesalex-image-banner-v2{
				padding:0;
			}
			.wholesalex-wc-install {
				display: -ms-flexbox;
				display: flex;
				align-items: center;
				background: #fff;
				margin-top: 40px;
				width: calc(100% - 20px);
				border: 1px solid #ccd0d4;
				padding: 4px;
				border-radius: 4px;
				gap:40px;
			}   
			.wholesalex-wc-install img {
				margin-right: 0;
				max-width: 100%; 
			}
			.wholesalex-image-banner-v2.wholesalex-wc-install-body{
				position: relative;
			}
			.wholesalex-wc-install-body {
				-ms-flex: 1;
				flex: 1;
			}
			.wholesalex-wc-install-body > div {
				max-width: 450px;
				margin-bottom: 20px;
			}
			.wholesalex-wc-install-body h3 {
				margin-top: 0;
				font-size: 24px;
				margin-bottom: 15px;
			}
			.wholesalex-install-btn {
				margin-top: 15px;
				display: inline-block;
			}
			.wholesalex-wc-install .dashicons{
				display: none;
				animation: dashicons-spin 1s infinite;
				animation-timing-function: linear;
			}
			.wholesalex-wc-install.loading .dashicons {
				display: inline-block;
				margin-top: 12px;
				margin-right: 5px;
			}
			@keyframes dashicons-spin {
				0% {
					transform: rotate( 0deg );
				}
				100% {
					transform: rotate( 360deg );
				}
			}
			.wholesalex-image-banner-v2 .wc-dismiss-notice {
				color: #fff;
				background-color: #000000;
				padding-top: 0px;
				position: absolute;
				right: 0;
				top: 0px;
				padding:5px;
				/* padding: 10px 10px 14px; */
				border-radius: 0 0 0 4px;
				display: inline-block;
				transition: 400ms;
				font-size: 12px;
			}
			.wholesalex-image-banner-v2 .wc-dismiss-notice:focus{
				outline: none;
				box-shadow: unset;
			}
			.wholesalex-btn-image:focus{
				outline: none;
				box-shadow: unset;
			}
			.wc-dismiss-notice {
				position: relative;
				text-decoration: none;
				float: right;
				right: 26px;
			}
			.wc-dismiss-notice .dashicons{
				display: inline-block;
				text-decoration: none;
				animation: none;
			}

			.wholesalex-pro-notice-v2 .wholesalex-wc-install-body h3 {
				font-size: 20px;
				margin-bottom: 5px;
			}
			.wholesalex-pro-notice-v2 .wholesalex-wc-install-body > div {
				max-width: 100%;
				margin-bottom: 10px;
			}
			.wholesalex-pro-notice-v2 .button-hero {
				padding: 8px 14px !important;
				min-height: inherit !important;
				line-height: 1 !important;
				box-shadow: none;
				border: none;
				transition: 400ms;
			}
			.wholesalex-pro-notice-v2 .wholesalex-btn-notice-pro {
				background: #2271b1;
				color: #fff;
			}
			.wholesalex-pro-notice-v2 .wholesalex-btn-notice-pro:hover,
			.wholesalex-pro-notice-v2 .wholesalex-btn-notice-pro:focus {
				background: #185a8f;
			}
			.wholesalex-pro-notice-v2 .button-hero:hover,
			.wholesalex-pro-notice-v2 .button-hero:focus {
				border: none;
				box-shadow: none;
			}
			.wc-dismiss-notice:hover {
				color:red;
			}
			.wc-dismiss-notice .dashicons{
				display: inline-block;
				text-decoration: none;
				animation: none;
				font-size: 16px;
			}
		</style>
		<?php
	}

	/**
	 * WooCommerce Force Install Action
	 *
	 * @since 1.0.0
	 */
	public function wc_install_callback() {
        if(! current_user_can( 'manage_options' )) {
            die();
        }
		include ABSPATH . 'wp-admin/includes/plugin-install.php';
		include ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		if ( ! class_exists( 'Plugin_Upgrader' ) ) {
			include ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
		}
		if ( ! class_exists( 'Plugin_Installer_Skin' ) ) {
			include ABSPATH . 'wp-admin/includes/class-plugin-installer-skin.php';
		}

		$plugin = 'woocommerce';

		$api = plugins_api(
			'plugin_information',
			array(
				'slug'   => $plugin,
				'fields' => array(
					'short_description' => false,
					'sections'          => false,
					'requires'          => false,
					'rating'            => false,
					'ratings'           => false,
					'downloaded'        => false,
					'last_updated'      => false,
					'added'             => false,
					'tags'              => false,
					'compatibility'     => false,
					'homepage'          => false,
					'donate_link'       => false,
				),
			)
		);

		if ( is_wp_error( $api ) ) {
			wp_die( esc_html__( 'Error!', 'wholesalex' ) );
		}
		/* translators: %s: API Name and Version */
		$title = sprintf( __( 'Installing Plugin: %s', 'wholesalex' ), $api->name . ' ' . $api->version );
		$nonce = 'install-plugin_' . $plugin;
		$url   = 'update.php?action=install-plugin&plugin=' . rawurlencode( $plugin );

		$upgrader = new \Plugin_Upgrader( new \Plugin_Installer_Skin( compact( 'title', 'url', 'nonce', 'plugin', 'api' ) ) );
		$upgrader->install( $api->download_link );
		die();
	}

	/**
	 * WooCommerce Redirect After Active Action
	 *
	 * @since 1.0.0
	 */
	public function wc_activate_action() {
		if ( wp_doing_ajax() ) {
            return;
        }
		activate_plugin( 'woocommerce/woocommerce.php' );
		wp_safe_redirect( admin_url( 'plugins.php' ) );
		exit();
	}

	public function set_notice( $key = '', $value = '', $expiration = '' ) {
		if ( $key ) {
			$option_name = 'wholesalex_notice';
			$notice_data = wholesalex()->get_option_without_cache( $option_name, array() );
			if ( ! isset( $notice_data ) || ! is_array( $notice_data ) ) {
				$notice_data = array();
			}

			$notice_data[ $key ] = $value;

			if ( $expiration ) {
				$expire_notice_key                 = 'timeout_' . $key;
				$notice_data[ $expire_notice_key ] = time() + $expiration;
			}
			update_option( $option_name, $notice_data );
		}
	}

	public function get_notice( $key = '' ) {
		if ( $key ) {
			$option_name = 'wholesalex_notice';
			$notice_data = wholesalex()->get_option_without_cache( $option_name, array() );
			if ( ! isset( $notice_data ) || ! is_array( $notice_data ) ) {
				return false;
			}

			if ( isset( $notice_data[ $key ] ) ) {
				$expire_notice_key = 'timeout_' . $key;
				if ( isset( $notice_data[ $expire_notice_key ] ) && $notice_data[ $expire_notice_key ] < time() ) {
					unset( $notice_data[ $key ] );
					unset( $notice_data[ $expire_notice_key ] );
					update_option( $option_name, $notice_data );
					return false;
				}
				return $notice_data[ $key ];
			}
		}
		return false;
	}

	/**
	 * Sort the notices based on the given priority of the notice.
	 *
	 * @since 1.5.2
	 * @param array $notice_1 First notice.
	 * @param array $notice_2 Second Notice.
	 * @return array
	 */
	public function sort_notices( $notice_1, $notice_2 ) {
		if ( ! isset( $notice_1['priority'] ) ) {
			$notice_1['priority'] = 10;
		}
		if ( ! isset( $notice_2['priority'] ) ) {
			$notice_2['priority'] = 10;
		}

		return $notice_1['priority'] - $notice_2['priority'];
	}

	private function set_new_notice( $id = '', $type = '', $design_type = '', $start = '', $end = '', $repeat = false, $priority = 10, $show_if = false ) {

		return array(
			'id'                        => $id,
			'type'                      => $type,
			'design_type'               => $design_type,
			'start'                     => $start, // Start Date
			'end'                       => $end, // End Date
			'repeat_notice_after'       => $repeat, // Repeat after how many days
			'priority'                  => $priority, // Notice Priority
			'display_with_other_notice' => false, // Display With Other Notice
			'show_if'                   => $show_if, // Notice Showing Conditions
			'capability'                => 'manage_options', // Capability of users, who can see the notice
		);
	}

	private function get_price_id() {
		if ( wholesalex()->is_pro_active() ) {
			$license_data = get_option( 'edd_wholesalex_license_data', false );
			$license_data = (array) $license_data;
			if ( is_array( $license_data ) && isset( $license_data['price_id'] ) ) {
				return $license_data['price_id'];
			} else {
				return false;
			}
		}
		return false;
	}

	public function is_valid_notice( $notice ) {
		$is_data_collect = isset( $notice['type'] ) && 'data_collect' == $notice['type'];
		$notice_status   = $is_data_collect ? $this->get_notice( $notice['id'] ) : $this->get_user_notice( $notice['id'] );

		if ( ! current_user_can( $notice['capability'] ) || 'off' === $notice_status ) {
			return false;
		}

		$current_time = gmdate( 'U' ); // Todays Data
		// $current_time = 1710493466;
		if ( $current_time > strtotime( $notice['start'] ) && $current_time < strtotime( $notice['end'] ) && isset( $notice['show_if'] ) && true === $notice['show_if'] ) { // Has Duration
			// Now Check Max Duration
			return true;
		}
	}



	public function set_user_notice_meta( $key = '', $value = '', $expiration = '' ) {
		if ( $key ) {
			$user_id     = get_current_user_id();
			$meta_key    = 'wholesalex_notice';
			$notice_data = get_user_meta( $user_id, $meta_key, true );
			if ( ! isset( $notice_data ) || ! is_array( $notice_data ) ) {
				$notice_data = array();
			}

			$notice_data[ $key ] = $value;

			if ( $expiration ) {
				$expire_notice_key                 = 'timeout_' . $key;
				$notice_data[ $expire_notice_key ] = $expiration;
			}

			update_user_meta( $user_id, $meta_key, $notice_data );

		}
	}

	public function get_user_notice( $key = '' ) {
		if ( $key ) {
			$user_id     = get_current_user_id();
			$meta_key    = 'wholesalex_notice';
			$notice_data = get_user_meta( $user_id, $meta_key, true );
			if ( ! isset( $notice_data ) || ! is_array( $notice_data ) ) {
				return false;
			}

			if ( isset( $notice_data[ $key ] ) ) {
				$expire_notice_key = 'timeout_' . $key;
				$current_time      = time();
				if ( isset( $notice_data[ $expire_notice_key ] ) && $notice_data[ $expire_notice_key ] < $current_time ) {
					unset( $notice_data[ $key ] );
					unset( $notice_data[ $expire_notice_key ] );
					update_user_meta( $user_id, $meta_key, $notice_data );
					return false;
				}
				return $notice_data[ $key ];
			}
		}
		return false;
	}
}
