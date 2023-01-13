<?php 
/**
 * Plugin Name: Pastacode
 * Plugin URI: http://pastacode.wabeo.fr
 * Description: Embed GitHub, Gist, Pastebin, Bitbucket or whatever remote files and even your own code by copy/pasting.
 * Version: 2.0
 * Author: Willy Bahuaud
 * Author URI: https://wabeo.fr
 * Contributors: juliobox, willybahuaud
 * Text Domain: pastacode
 * Domain Path: /languages
 * Stable tag: 2.0
 */

define( 'PASTACODE_VERSION', '2.0' );

add_action( 'plugins_loaded', 'pastacode_load_languages' );
function pastacode_load_languages() {
	load_plugin_textdomain( 'pastacode', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_shortcode( 'pastacode', 'sc_pastacode' );
function sc_pastacode( $atts, $content = '' ) {

	$atts = shortcode_atts( array(
		'provider'      => '',
		'user'          => '',
		'path_id'       => '',
		'repos'         => '',
		'revision'      => 'master',
		'lines'         => '',
		'lang'          => 'markup',
		'highlight'     => '',
		'message'       => '',
		'file'          => '',
		'manual'        => '',
		'linenumbers'   => 'n',
		'showinvisible' => 'n',
	), $atts, 'sc_pastacode' );

	$source = pastacode_get_source( $atts, $content );

	if ( ! empty( $source['code'] ) ) {

		//Load scripts
		wp_enqueue_style( 'prismcss' );
		wp_enqueue_script( 'prismjs' );
		wp_enqueue_script( 'prism-normalize-whitespace' );

		$ln_class = '';
		if ( 'y' === get_option( 'pastacode_linenumbers', 'n' ) ) {
			wp_enqueue_style( 'prism-linenumbercss' );
			wp_enqueue_script( 'prism-linenumber' );
			$ln_class = ' line-numbers';
		}
		if ( 'y' === get_option( 'pastacode_showinvisible', 'n' ) ) {
			wp_enqueue_style( 'prism-show-invisiblecss' );
			wp_enqueue_script( 'prism-show-invisible' );
		}
		//highlight
		if ( preg_match( '/([0-9-,]+)/', $atts['highlight'] ) ) {
			$highlight_val = ' data-line="' . $atts['highlight'] . '"';
			wp_enqueue_script( 'prism-highlight' );
			wp_enqueue_style( 'prism-highlightcss' );
		} else {
			$highlight_val = '';
		}

		//Code info
		$about_code = array();
		$about_code[] = '<div class="code-embed-infos">';
		if ( isset( $source['url'] ) ) {
			$about_code[] = '<a href="' . esc_url( $source['url'] ) . '" title="' . sprintf( esc_attr__( 'See %s', 'pastacode' ), $source['name'] ) . '" target="_blank" class="code-embed-name">' . esc_html( $source['name'] ) . '</a>';
		}
		if ( isset( $source['raw'] ) ) {
			$about_code[] = '<a href="' . esc_url( $source['raw'] ) . '" title="' . sprintf( esc_attr__( 'Back to %s' ), $source['name'] ) . '" class="code-embed-raw" target="_blank">' . __( 'view raw', 'pastacode' ) . '</a>';
		}
		if ( ! isset( $source['url'] ) && ! isset( $source['raw'] ) && isset( $source['name'] ) ) {
			$about_code[] = '<span class="code-embed-name">' . $source['name'] . '</span>';
		}
		$about_code[] = '</div>';

		//Wrap
		$output = array();
		$output[] = '<div class="code-embed-wrapper">';

		$data_start = isset( $source['start'] ) && is_int( $source['start'] ) ? intval( $source['start'] ) : '1';
		$data_line_offset = isset( $source['start'] ) && is_int( $source['start'] ) ? intval( $source['start'] ) - 1 : '0';
		$output[] = '<pre class="language-' . sanitize_html_class( $atts['lang'] ) . ' code-embed-pre' . $ln_class . '" ' . $highlight_val . ' data-start="' . $data_start . '" data-line-offset="' . $data_line_offset . '"><code class="language-' . sanitize_html_class( $atts['lang'] ) . ' code-embed-code">'
		. $source['code'] .
		'</code></pre>';
		$output[] = '</div>';

		$pos = ( 'top' == get_option( 'pastacode_aboutcode_pos' ) ) ? 1 : 2;
		array_splice( $output, $pos, 0, $about_code );

		$output = implode( ' ', $output );

		return $output;
	} elseif ( ! empty( $atts['message'] ) ) {
		return '<span class="pastacode_message">' . esc_html( $atts['message'] ) . '</span>';
	}
}

function pastacode_get_source( $atts, $content ) {
	if ( empty( $atts['provider'] ) && ! empty( $content ) ) {
		$atts['provider'] = md5( $content );
	}

	$code_embed_transient = 'pastacode_' . substr( md5( serialize( $atts ) ), 0, 14 );

	$time = get_option( 'pastacode_cache_duration', DAY_IN_SECONDS * 7 );

	if ( 'manual' == $atts['provider'] ) {
		$time = -1;
	}

	if ( -1 == $time || ! $source = get_transient( $code_embed_transient ) ) {

		$source = apply_filters( 'pastacode_'.$atts['provider'], array(), $atts, $content );

		if ( ! empty( $source['code'] ) ) {
			$source['code'] = rtrim( $source['code'], "\n" );

			//Wrap lines
			if ( $lines = $atts['lines'] ) {
				$lines = array_map( 'intval', explode( '-', $lines ) );
				if ( ! isset( $lines[1] ) && isset( $lines[0] ) ) {
					$lines[1] = $lines[0];
				}
				$source['code'] = implode( "\n", array_slice( preg_split( '/\r\n|\r|\n/', $source['code'] ), $lines[0] - 1, ( $lines[1] - $lines[0] ) + 1 ) );
				$source['start'] = $lines[0];
			}
			if ( $time >- 1 ) {
				set_transient( $code_embed_transient, $source, $time );
			}
		}
	}
	return $source;
}

add_action( 'wp_ajax_pastacode-get-source-code', 'pastacode_ajax_get_source_code' );
add_action( 'wp_ajax_nopriv_pastacode-get-source-code', 'pastacode_ajax_get_source_code' );
function pastacode_ajax_get_source_code() {
	$args = $_POST;
	unset( $args['action'] );
	$source = pastacode_get_source_code_ajax( $args );
	wp_send_json_success( $source );
}

function pastacode_get_source_code_ajax( $args ) {
	$args = wp_parse_args( $args, array(
		'provider'      => '',
		'user'          => '',
		'path_id'       => '',
		'repos'         => '',
		'revision'      => 'master',
		'lines'         => '',
		'lang'          => 'markup',
		'highlight'     => '',
		'message'       => '',
		'file'          => '',
		'manual'        => '',
		'linenumbers'   => 'n',
		'showinvisible' => 'n',
	) );

	$source = pastacode_get_source( $args );

	if ( ! empty( $source['code'] ) ) {
		$code = preg_split( '/\r\n|\r|\n/', $source['code'] );
		$source['more'] = count( $code ) > 10;
		$source['code'] = implode( PHP_EOL, array_slice( $code, 0, 10 ) );
		$source['code'] = stripslashes_deep( $source['code'] );
	}

	return $source;
}

add_filter( 'pastacode_github', '_pastacode_github', 10, 2 );
function _pastacode_github( $source, $atts ) {
	extract( $atts );
	if ( $user && $repos && $path_id ) {
		$req  = wp_sprintf( 'https://api.github.com/repos/%s/%s/contents/%s', $user, $repos, $path_id );
		if ( isset( $revision ) && $revision ) {
			$req = add_query_arg( array( 'ref' => $revision ), $req );
		} else {
			$revision = 'master';
		}
		$code = wp_remote_get( $req, array(
			'headers' => array(
				'Accept' => 'application/vnd.github.v3.raw+json',
			),
		) );
		if ( ! is_wp_error( $code ) && 200 == wp_remote_retrieve_response_code( $code ) ) {
			$name = explode( '/', $path_id );
			$source['name'] = $name[ count( $name ) - 1 ];
			$source['code'] = esc_html( wp_remote_retrieve_body( $code ) );
			$source['url']  = wp_sprintf( 'https://github.com/%s/%s/blob/%s/%s', $user, $repos, $revision, $path_id );
			$source['raw']  = wp_sprintf( 'https://raw.github.com/%s/%s/%s/%s', $user, $repos, $revision, $path_id );
		} else {
			$req2 = wp_sprintf( 'https://raw.github.com/%s/%s/%s/%s', $user, $repos, $revision, $path_id );
			$code = wp_remote_get( $req2 );
			if ( ! is_wp_error( $code ) && 200 == wp_remote_retrieve_response_code( $code ) ) {
				$name = explode( '/', $path_id );
				$source['name'] = $name[ count( $name ) - 1 ];
				$source['code'] = esc_html( wp_remote_retrieve_body( $code ) );
				$source['url']  = wp_sprintf( 'https://github.com/%s/%s/blob/%s/%s', $user, $repos, $revision, $path_id );
				$source['raw']  = $req2;
			}
		}
	}
	return $source;
}

add_filter( 'pastacode_gist', '_pastacode_gist', 10, 2 );
function _pastacode_gist( $source, $atts ) {
	extract( $atts );
	if ( $path_id ) {
		$req  = wp_sprintf( 'https://api.github.com/gists/%s', $path_id );
		$code = wp_remote_get( $req );
		if ( ! is_wp_error( $code ) && 200 == wp_remote_retrieve_response_code( $code ) ) {
			$data = json_decode( wp_remote_retrieve_body( $code ) );
			$source['url']  = $data->html_url;
			if ( $file && isset( $data->files->$file ) ) {
				$data = $data->files->$file;
			} else {
				$data = (array) $data->files;
				$data = reset( $data );
			}
			$source['name'] = $data->filename;
			$source['code'] = esc_html( $data->content );
			$source['raw']  = $data->raw_url;
		}
	}
	return $source;
}


add_filter( 'pastacode_bitbucketsnippets', '_pastacode_bitbucketsnippets', 10, 2 );
function _pastacode_bitbucketsnippets( $source, $atts ) {
	extract( $atts );
	if ( $path_id && $user ) {
		$req  = wp_sprintf( 'https://api.bitbucket.org/2.0/snippets/%s/%s', $user, $path_id );
		$code = wp_remote_get( $req );
		if ( ! is_wp_error( $code ) && 200 == wp_remote_retrieve_response_code( $code ) ) {
			$data = json_decode( wp_remote_retrieve_body( $code ) );
			if ( ! $data->is_private ) {
				if ( $file && isset( $data->files->$file ) ) {
					$source['name'] = $file;
					$data = $data->files->$file;
				} else {
					$source['name'] = key( $data->files );
					$data = (array) $data->files;
					$data = reset( $data );
				}
				$source['url']  = $data->links->html->href;
				$source['raw']  = $data->links->self->href;
				$source_code = wp_remote_get( $source['raw'] );
				if ( ! is_wp_error( $source_code ) && 200 == wp_remote_retrieve_response_code( $source_code ) ) {
					$source['code'] = esc_html( wp_remote_retrieve_body( $source_code ) );
				}
			}
		}
	}
	return $source;
}

add_filter( 'pastacode_bitbucket', '_pastacode_bitbucket', 10, 2 );
function _pastacode_bitbucket( $source, $atts ) {
	extract( $atts );
	if ( $user && $repos && $path_id ) {
		$req  = wp_sprintf( 'https://bitbucket.org/api/1.0/repositories/%s/%s/raw/%s/%s', $user, $repos, $revision, $path_id );

		$code = wp_remote_get( $req );
		if ( ! is_wp_error( $code ) && 200 == wp_remote_retrieve_response_code( $code ) ) {
			$source['name'] = basename( $path_id );
			$source['code'] = esc_html( wp_remote_retrieve_body( $code ) );
			$source['url']  = wp_sprintf( 'https://bitbucket.org/%s/%s/src/%s/%s', $user, $repos, $revision, $path_id );
			$source['raw']  = $req;
		}
	}
	return $source;
}

add_filter( 'pastacode_file', '_pastacode_file', 10, 2 );
function _pastacode_file( $source, $atts ) {
	extract( $atts );
	if ( $path_id ) {
		$upload_dir = wp_upload_dir();
		$path_id = str_replace( '../', '', $path_id );
		$req  = esc_url( trailingslashit( $upload_dir['baseurl'] ) . $path_id );
		$code = wp_remote_get( $req );
		if ( ! is_wp_error( $code ) && 200 == wp_remote_retrieve_response_code( $code ) ) {

			$source['name'] = basename( $path_id );
			$source['code'] = esc_html( wp_remote_retrieve_body( $code ) );
			$source['url']  = ( $req );
		}
	}
	return $source;
}

add_filter( 'pastacode_pastebin', '_pastacode_pastebin', 10, 2 );
function _pastacode_pastebin( $source, $atts ) {
	extract( $atts );
	if ( $path_id ) {
		$req  = wp_sprintf( 'http://pastebin.com/raw.php?i=%s', $path_id );
		$code = wp_remote_get( $req );
		if ( ! is_wp_error( $code ) && 200 == wp_remote_retrieve_response_code( $code ) ) {
			$source['name'] = $path_id;
			$source['code'] = esc_html( wp_remote_retrieve_body( $code ) );
			$source['url']  = wp_sprintf( 'http://pastebin.com/%s', $path_id );
			$source['raw']  = wp_sprintf( 'http://pastebin.com/raw.php?i=%s', $path_id );
		}
	}
	return $source;
}

add_filter( 'pastacode_manual', '_pastacode_manual', 10, 3 );
function _pastacode_manual( $source, $atts, $content ) {
	extract( $atts );
	if ( $manual ) {
		$source['code'] = esc_html( urldecode( $manual ) );
	} elseif ( ! empty( $content ) ) {
		$reg = "/<code>(?:[\\n\\r]*)?([\\s\\S]+?)(?:[\\n\\r]*)?<\\/code>/mi";
		if ( preg_match( $reg, $content, $code ) ) {
			$source['code'] = esc_html( $code[1] );
		}
	}
	if ( isset( $atts['message'] ) && $atts['message'] ) {
		$source['name'] = esc_html( $message );
	}
	return $source;
}


add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'pastacode_settings_action_links', 10, 2 );
function pastacode_settings_action_links( $links, $file ) {
	if ( current_user_can( 'manage_options' ) )
		array_unshift( $links, '<a href="' . admin_url( 'options-general.php?page=pastacode' ) . '">' . __( 'Settings' ) . '</a>' );
	return $links;
}

add_filter( 'plugin_row_meta', 'pastacode_plugin_row_meta', 10, 2 );
function pastacode_plugin_row_meta( $plugin_meta, $plugin_file ) {
	if ( plugin_basename( __FILE__ ) == $plugin_file ){
		$last = end( $plugin_meta );
		$plugin_meta = array_slice( $plugin_meta, 0, -2 );
		$a = array();
		$authors = array(
			array( 'name' => 'Willy Bahuaud', 'url' => 'https://wabeo.fr' ),
			array( 'name' => 'Julio Potier', 'url' => 'http://www.boiteaweb.fr' ),
		);
		foreach ( $authors as $author ) {
			$a[] = '<a href="' . $author['url'] . '" title="' . esc_attr__( 'Visit author homepage' ) . '">' . $author['name'] . '</a>';
		}
		$a = sprintf( __( 'By %s' ), wp_sprintf( '%l', $a ) );
		$plugin_meta[] = $a;
		$plugin_meta[] = $last;
	}
	return $plugin_meta;
}

//Register scripts
add_action( 'wp_enqueue_scripts', 'pastacode_enqueue_prismjs' );
function pastacode_enqueue_prismjs() {
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	wp_register_script( 'prismjs', plugins_url( '/js/prism.js', __FILE__ ), false, PASTACODE_VERSION, true );
	wp_register_script( 'prism-highlight', plugins_url( '/plugins/line-highlight/prism-line-highlight' . $suffix . '.js', __FILE__ ), array( 'prismjs' ), PASTACODE_VERSION, true );
	wp_register_script( 'prism-normalize-whitespace', plugins_url( '/plugins/normalize-whitespace/prism-normalize-whitespace' . $suffix . '.js', __FILE__ ), array( 'prismjs' ), PASTACODE_VERSION, true );
	wp_register_script( 'prism-linenumber', plugins_url( '/plugins/line-numbers/prism-line-numbers' . $suffix . '.js', __FILE__ ), array( 'prismjs' ), PASTACODE_VERSION, true );
	wp_register_script( 'prism-show-invisible', plugins_url( '/plugins/show-invisibles/prism-show-invisibles' . $suffix . '.js', __FILE__ ), array( 'prismjs' ), PASTACODE_VERSION, true );
	wp_register_style( 'prismcss', plugins_url( '/css/' . get_option( 'pastacode_style', 'prism' ) . '.css', __FILE__ ), false, PASTACODE_VERSION, 'all' );
	wp_register_style( 'prism-highlightcss', plugins_url( '/plugins/line-highlight/prism-line-highlight.css', __FILE__ ), false, PASTACODE_VERSION, 'all' );
	wp_register_style( 'prism-linenumbercss', plugins_url( '/plugins/line-numbers/prism-line-numbers.css', __FILE__ ), false, PASTACODE_VERSION, 'all' );
	wp_register_style( 'prism-show-invisiblecss', plugins_url( '/plugins/show-invisibles/prism-show-invisibles.css', __FILE__ ), false, PASTACODE_VERSION, 'all' );

	if ( apply_filters( 'pastacode_ajax', false ) ) {
		wp_enqueue_script( 'prismjs' );
		wp_enqueue_style( 'prismcss' );
		wp_enqueue_style( 'prism-highlightcss' );
		wp_enqueue_script( 'prism-normalize-whitespace' );
		wp_enqueue_script( 'prism-highlight' );

		if ( 'y' === get_option( 'pastacode_linenumbers', 'n' ) ) {
			wp_enqueue_style( 'prism-linenumbercss' );
			wp_enqueue_script( 'prism-linenumber' );
			// $ln_class = ' line-numbers';
		}
		if ( 'y' === get_option( 'pastacode_showinvisible', 'n' ) ) {
			wp_enqueue_style( 'prism-show-invisiblecss' );
			wp_enqueue_script( 'prism-show-invisible' );
		}
	}
}

add_filter( 'admin_post_pastacode_drop_transients', 'pastacode_drop_transients', 10, 2 );
function pastacode_drop_transients() {
	if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'pastacode_drop_transients' ) ) {
		global $wpdb;
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_pastacode_%'" );
		wp_redirect( wp_get_referer() );
	} else {
		wp_nonce_ays( '' );
	}
}

/**
//Admin Settings
*/
add_action( 'admin_menu', 'pastacode_create_menu' );
function pastacode_create_menu() {
	add_options_page( 'Pastacode '. __( 'Settings' ), 'Pastacode', 'manage_options', 'pastacode', 'pastacode_settings_page' );
	register_setting( 'pastacode', 'pastacode_cache_duration' );
	register_setting( 'pastacode', 'pastacode_style' );
	register_setting( 'pastacode', 'pastacode_bo_style' );
	register_setting( 'pastacode', 'pastacode_linenumbers' );
	register_setting( 'pastacode', 'pastacode_showinvisible' );
	register_setting( 'pastacode', 'pastacode_aboutcode_pos' );
	register_setting( 'pastacode', 'pastacode_preview' );
	register_setting( 'pastacode', 'pastacode_comments_opt' );
}

function pastacode_setting_callback_function( $args ) {

	extract( $args );

	$value_old = get_option( $name );

	echo '<select name="' . esc_attr( $name ) . '" id="' . esc_attr( $name ) . '">';
	foreach ( $options as $key => $option ) {
		echo '<option value="' . esc_attr( $key ) . '" ' . selected( $value_old==$key, true, false ) . '>' . esc_html( $option ) . '</option>';
	}
	echo '</select>';
	if ( $desc ) {
		echo '<p>' . wp_kses( $desc, array( 'a' => array( 'href' => array() ), 'br' => array(), 'em' => array() ) ) . '</p>';
	}
}


function pastacode_settings_page() {
?>
<div class="wrap">
	<?php screen_icon(); ?>
<h2>Pastacode v<?php echo PASTACODE_VERSION; ?></h2>

<?php
	add_settings_section( 'pastacode_setting_section',
		__( 'General Settings', 'pastacode' ),
		'__return_false',
		'pastacode' );

	add_settings_field( 'pastacode_cache_duration',
		__( 'Caching duration', 'pastacode' ),
		'pastacode_setting_callback_function',
		'pastacode',
		'pastacode_setting_section',
		array(
			'options' => array(
				HOUR_IN_SECONDS      => sprintf( __( '%s hour' ), '1' ),
				HOUR_IN_SECONDS * 12 => __( 'Twice Daily' ),
				DAY_IN_SECONDS       => __( 'Once Daily' ),
				DAY_IN_SECONDS * 7   => __( 'Once Weekly', 'pastacode' ),
				0                    => __( 'Never reload', 'pastacode' ),
				-1                   => __( 'No cache (dev mode)', 'pastacode' ),
				),
			'name' => 'pastacode_cache_duration',
		) );

	add_settings_field( 'pastacode_style',
		__( 'Syntax Coloration Style', 'pastacode' ),
		'pastacode_setting_callback_function',
		'pastacode',
		'pastacode_setting_section',
		array(
			'options' => array(
				'prism'          => 'Prism',
				'prism-dark'     => 'Dark',
				'prism-funky'    => 'Funky',
				'prism-coy'      => 'Coy',
				'prism-okaidia'  => 'Okaïdia',
				'prism-tomorrow' => 'Tomorrow',
				'prism-twilight' => 'Twilight',
				),
			'name' => 'pastacode_style',
		) );

	add_settings_field( 'pastacode_aboutcode_pos',
		__( 'Code description location', 'pastacode' ),
		'pastacode_setting_callback_function',
		'pastacode',
		'pastacode_setting_section',
		array(
			'options' => array(
				'bottom' => __( 'Below code', 'pastacode' ),
				'top'    => __( 'Above code', 'pastacode' ),
				),
			'name' => 'pastacode_aboutcode_pos',
		) );

	add_settings_field( 'pastacode_linenumbers',
		__( 'Show line numbers', 'pastacode' ),
		'pastacode_setting_callback_function',
		'pastacode',
		'pastacode_setting_section',
		array(
			'options' => array(
				'y' => __( 'Yes', 'pastacode' ),
				'n' => __( 'No', 'pastacode' ),
				),
			'name' => 'pastacode_linenumbers',
		) );

	add_settings_field( 'pastacode_showinvisible',
		__( 'Show invisible chars', 'pastacode' ),
		'pastacode_setting_callback_function',
		'pastacode',
		'pastacode_setting_section',
		array(
			'options' => array(
				'y' => __( 'Yes', 'pastacode' ),
				'n' => __( 'No', 'pastacode' ),
				),
			'name' => 'pastacode_showinvisible',
		) );

	add_settings_field( 'pastacode_preview',
		__( 'Show preview on editor', 'pastacode' ),
		'pastacode_setting_callback_function',
		'pastacode',
		'pastacode_setting_section',
		array(
			'options' => array(
				'y' => __( 'Yes', 'pastacode' ),
				'n' => __( 'No', 'pastacode' ),
				),
			'name' => 'pastacode_preview',
		) );

	add_settings_field( 'pastacode_comments_opt',
		__( 'Activate Pastacode for comments', 'pastacode' ),
		'pastacode_setting_callback_function',
		'pastacode',
		'pastacode_setting_section',
		array(
			'options' => array(
				'y' => __( 'Yes', 'pastacode' ),
				'n' => __( 'No', 'pastacode' ),
				),
			'name' => 'pastacode_comments_opt',
			'desc' => '<em>' . esc_html__( 'Experimental mode, can slow down website on front-end...', 'pastacode' ) . '</em>',
		) );


	add_settings_section( 'pastacode_setting_bo_section',
		__( 'Code editor settings', 'pastacode' ),
		'__return_false',
		'pastacode' );

	add_settings_field( 'pastacode_bo_style',
		__( 'Editor appareance', 'pastacode' ),
		'pastacode_setting_callback_function',
		'pastacode',
		'pastacode_setting_bo_section',
		array(
			'options' => array(
				'ambiance'                => 'Ambiance',
				'3024-day'                => '3024 day',
				'3024-night'              => '3024 night',
				'abcdef'                  => 'abcdef',
				'abcdef'                  => 'abcdef',
				'base16-dark'             => 'Base16 Dark',
				'base16-light'            => 'Base16 Light',
				'bespin'                  => 'Bespin',
				'blackboard'              => 'Blackboard',
				'dracula'                 => 'Dracula',
				'eclipse'                 => 'Eclipse',
				'elegant'                 => 'Elegant',
				'erlang-dark'             => 'Erlang Dark',
				'hopscotch'               => 'Hopscotch',
				'icecoder'                => 'Icecoder',
				'isotope'                 => 'Isotope',
				'lesser-dark'             => 'Lesser Dark',
				'liquibyte'               => 'Liquibyte',
				'material'                => 'Material',
				'mbo'                     => 'Mbo',
				'midnight'                => 'Midnight',
				'monokai'                 => 'Monokai',
				'neat'                    => 'Neat',
				'neo'                     => 'Neo',
				'night'                   => 'Night',
				'paraiso-dark'            => 'Paraiso Dark',
				'paraiso-light'           => 'Paraiso Light',
				'pastel-on-dark'          => 'Pastel on Dark',
				'railscasts'              => 'railscasts',
				'rubyblue'                => 'Rubyblue',
				'seti'                    => 'Seti',
				'solarized'               => 'Solarized',
				'the-matrix'              => 'The Matrix',
				'tomorrow-night-bright'   => 'Tomorrow Night Bright',
				'tomorrow-night-eighties' => 'Tomorrow Night Eighties',
				'twilight'                => 'Twilight',
				'vibrant-ink'             => 'Vibrant Ink',
				'xq-dark'                 => 'XQ Dark',
				'xq-light'                => 'XQ Light',
				'yeti'                    => 'Yeti',
				'zenburn'                 => 'Zenburn',
				),
			'name' => 'pastacode_bo_style',
		) );

	?>
	<form method="post" action="options.php">
		<?php
		settings_fields( 'pastacode' );
		do_settings_sections( 'pastacode' );
		$url = wp_nonce_url( admin_url( 'admin-post.php?action=pastacode_drop_transients' ), 'pastacode_drop_transients' );
		global $wpdb;
		$transients = $wpdb->get_var( "SELECT count(option_name) FROM $wpdb->options WHERE option_name LIKE '_transient_pastacode_%'" );
		echo '<p class="submit">';
			submit_button( '', 'primary large', 'submit', false );
			echo ' <a href="' . esc_attr( $url ) . '" class="button button-large button-secondary">' . esc_html__( 'Purge cache', 'pastacode' ) . ' (' . (int) $transients . ')</a>';
		echo '</p>';
		?>
	</form>
</div>
<?php
}

register_activation_hook( __FILE__, 'pastacode_activation' );
function pastacode_activation() {
	add_option( 'pastacode_cache_duration', DAY_IN_SECONDS * 7 );
	add_option( 'pastacode_style', 'prism' );
	add_option( 'pastacode_showinvisible', 'n' );
	add_option( 'pastacode_linenumbers', 'n' );
	add_option( 'pastacode_preview', 'y' );
	add_option( 'pastacode_comments_opt', 'n' );
}

register_uninstall_hook( __FILE__, 'pastacode_uninstaller' );
function pastacode_uninstaller() {
	delete_option( 'pastacode_cache_duration' );
	delete_option( 'pastacode_style' );
}

/**
Add button to tinymce
*/
//Button
add_action( 'admin_init', 'pastacode_button_editor' );
function pastacode_button_editor() {

	// Don't bother doing this stuff if the current user lacks permissions
	if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
		return false;
	}

	if ( get_user_option( 'rich_editing' ) == 'true' ) {
		add_filter( 'mce_external_plugins', 'pastacode_script_tiny' );
		add_filter( 'mce_buttons', 'pastacode_register_button' );
	}
}

function pastacode_register_button( $buttons ) {
	array_splice( $buttons, -2, 1, 'pcb' );
	return $buttons;
}

function pastacode_script_tiny( $plugin_array ) {
	global $wp_version;
	if ( version_compare( $wp_version, '4.2.3', '>=' ) ) {
		$plugin_array['pcb'] = plugins_url( '/js/tinymce2.js?v=' . PASTACODE_VERSION, __FILE__ );
	} else {
		$plugin_array['pcb'] = plugins_url( '/js/tinymce.js?v=' . PASTACODE_VERSION, __FILE__ );
	}
	return $plugin_array;
}

add_action( 'admin_enqueue_scripts', 'pastacode_shortcodes_mce_css' );
function pastacode_shortcodes_mce_css() {

	wp_enqueue_style( 'pastacode-codemirror', plugins_url( '/js/tinymce-plugins/codemirror-wp.css', __FILE__ ) );
	wp_enqueue_style( 'pastacode-tinymce', plugins_url( '/css/pastacode-tinymce.css', __FILE__ ) );
	$editor_theme = get_option( 'pastacode_bo_style', 'ambiance' );
	wp_enqueue_style( 'pastacode-codemirror-theme', plugins_url( '/js/tinymce-plugins/codemirror/theme/' . $editor_theme . '.css', __FILE__ ) );

	wp_register_script( 'labjs', plugins_url( '/js/LAB.min.js', __FILE__ ) );
	wp_enqueue_script( 'labjs' );
	wp_register_script( 'jquery-linenumbers', plugins_url( '/js/jquery-linenumbers.js', __FILE__ ), array( 'jquery' ) );
	wp_enqueue_script( 'jquery-linenumbers' );
}

add_filter( 'mce_css', 'pastacode_plugin_mce_css' );
function pastacode_plugin_mce_css( $mce_css ) {
	if ( ! empty( $mce_css ) ) {
		$mce_css .= ',';
	}
	$mce_css .= plugins_url( '/css/pastacode-tinymce.css', __FILE__ );
	return $mce_css;
}

add_action( 'before_wp_tiny_mce', 'pastacode_text' );
function pastacode_text() {
	// I10n
	$text = json_encode( array(
		'window-title'       => __( 'Past\'a code', 'pastacode' ),
		'label-provider'     => __( 'Select a provider', 'pastacode' ),
		'label-langs'        => __( 'Select a syntax', 'pastacode' ),
		'image-placeholder'  => plugins_url( '/images/pastacode-placeholder.png', __FILE__ ),
		'window-manuel-full' => __( 'Manual Code Editor', 'pastacode' ),
		'label-lines'        => __( 'Lines:', 'pastacode' ),
		'label-title'        => __( 'Title:', 'pastacode' ),
		'label-lang'         => __( 'Syntax:', 'pastacode' ),
		'label-type'         => __( 'Provider:', 'pastacode' ),
	) );

	// Services
	$services = array(
		'manual'            => __( 'Write code', 'pastacode' ),
		'github'            => sprintf( __( 'Import code (%s)', 'pastacode' ), 'Github' ),
		'gist'              => sprintf( __( 'Import code (%s)', 'pastacode' ), 'Gist' ),
		'bitbucket'         => sprintf( __( 'Import code (%s)', 'pastacode' ), 'Bitbucket' ),
		'bitbucketsnippets' => sprintf( __( 'Import code (%s)', 'pastacode' ), 'Bitbucket Snippets' ),
		'pastebin'          => sprintf( __( 'Import code (%s)', 'pastacode' ), 'Pastebin' ),
		'file'              => sprintf( __( 'Import code (%s)', 'pastacode' ), __( 'File from uploads', 'pastacode' ) ),
	);
	$services = apply_filters( 'pastacode_services', $services );

	// Languages
	$langs  = array(
		'markup'       => 'HTML',
		'css'          => 'CSS',
		'javascript'   => 'JavaScript',
		'php'          => 'PHP',
		'c'            => 'C',
		'cpp'          => 'C++',
		'java'         => 'Java',
		'sass'         => 'Sass',
		'python'       => 'Python',
		'sql'          => 'SQL',
		'ruby'         => 'Ruby',
		'coffeescript' => 'CoffeeScript',
		'bash'         => 'Bash',
		'apacheconf'   => 'Apache',
		'less'         => 'Less',
		'haml'         => 'HAML',
		'markdown'     => 'Markdown',
	);
	$langs = apply_filters( 'pastacode_langs', $langs );

	$upload_dir = wp_upload_dir();

	// Other fields
	$fields = array(
		'username' => array( 'classes' => array( 'github','bitbucket', 'bitbucketsnippets' ), 'label' => __('User of repository', 'pastacode'), 'placeholder' => __( 'John Doe', 'pastacode' ), 'name' => 'user' ),
		'repository' => array( 'classes' => array( 'github','bitbucket' ), 'label' => __('Repository', 'pastacode'), 'placeholder' => __( 'pastacode', 'pastacode' ), 'name' => 'repos' ),
		'path-id' => array( 'classes' => array( 'gist', 'pastebin', 'bitbucketsnippets' ), 'label' => __('Code ID', 'pastacode'), 'placeholder' => '123456', 'name' => 'path_id' ),
		'path-repo' => array( 'classes' => array( 'github','bitbucket' ), 'label' => __('File path inside the repository', 'pastacode'), 'placeholder' => __( 'bin/foobar.php', 'pastebin' ), 'name' => 'path_id'  ),
		'path-up' => array( 'classes' => array( 'file' ), 'label' => sprintf( __('File path relative to %s', 'pastacode'), esc_html( $upload_dir['baseurl'] ) ), 'placeholder' => date( 'Y/m' ).'/source.txt', 'name' => 'path_id'  ),
		'revision' => array( 'classes' => array( 'github','bitbucket' ), 'label' => __('Revision', 'pastacode'), 'placeholder' => __('master', 'pastacode'), 'name' => 'revision'  ),
		'manual' => array( 'classes' => array( 'manual' ), 'label' => __('Code', 'pastacode'), 'name' => 'manual'  ),
		'message' => array( 'classes' => array( 'manual' ), 'label' => __('Code title', 'pastacode'),'placeholder' => __('title', 'pastacode'), 'name' => 'message'  ),
		'file' => array( 'classes' => array( 'gist', 'bitbucketsnippets' ), 'label' => __('Filename (with extension)', 'pastacode'), 'placeholder' => 'foobar.txt', 'name' => 'file'  ),
		'pastacode-highlight' => array( 'classes' => array( 'manual', 'github', 'gist', 'bitbucket', 'pastebin', 'file', 'bitbucketsnippets' ), 'label' => __('Highlited lines', 'pastacode'), 'placeholder' => '1,2,5-6', 'name' => 'highlight' ),
		'pastacode-lines' => array( 'classes' => array( 'github', 'gist', 'bitbucket', 'pastebin', 'file', 'bitbucketsnippets' ), 'label' => __('Visibles lines', 'pastacode'), 'placeholder' => '1-20', 'name' => 'lines' )
	);
	$fields = apply_filters( 'pastacode_fields', $fields );

	$new_fields = array();
	$new_langs = array();
	foreach ( $langs as $k => $s ) {
		$new_langs[] = array( 'text' => $s, 'value' => $k );
	}
	$new_fields[] = array( 'type' => 'listbox', 'label' => __( 'Select a syntax', 'pastacode' ), 'name' => 'lang', 'values' => $new_langs );

	$pvars['providers'] = $services;

	$pvars['scripts'] = array(
		'codemirror'    => plugins_url( 'js/tinymce-plugins/codemirror/lib/codemirror.js', __FILE__ ),
		// 'comment'       => plugins_url( 'js/tinymce-plugins/codemirror/addon/comment/comment.js', __FILE__ ),
		'matchbrackets' => plugins_url( 'js/tinymce-plugins/codemirror/addon/edit/matchbrackets.js', __FILE__ ),
		// 'matchtags'     => plugins_url( 'js/tinymce-plugins/codemirror/addon/edit/matchtags.js', __FILE__ ),
		'coffeescript'  => plugins_url( 'js/tinymce-plugins/codemirror/mode/coffeescript/coffeescript.js', __FILE__ ),
		'css'           => plugins_url( 'js/tinymce-plugins/codemirror/mode/css/css.js', __FILE__ ),
		'clike'         => plugins_url( 'js/tinymce-plugins/codemirror/mode/clike/clike.js', __FILE__ ),
		'htmlmixed'     => plugins_url( 'js/tinymce-plugins/codemirror/mode/htmlmixed/htmlmixed.js', __FILE__ ),
		'haml'          => plugins_url( 'js/tinymce-plugins/codemirror/mode/haml/haml.js', __FILE__ ),
		'javascript'    => plugins_url( 'js/tinymce-plugins/codemirror/mode/javascript/javascript.js', __FILE__ ),
		'php'           => plugins_url( 'js/tinymce-plugins/codemirror/mode/php/php.js', __FILE__ ),
		'python'        => plugins_url( 'js/tinymce-plugins/codemirror/mode/python/python.js', __FILE__ ),
		'ruby'          => plugins_url( 'js/tinymce-plugins/codemirror/mode/ruby/ruby.js', __FILE__ ),
		'sass'          => plugins_url( 'js/tinymce-plugins/codemirror/mode/sass/sass.js', __FILE__ ),
		'shell'         => plugins_url( 'js/tinymce-plugins/codemirror/mode/shell/shell.js', __FILE__ ),
		'sql'           => plugins_url( 'js/tinymce-plugins/codemirror/mode/sql/sql.js', __FILE__ ),
		'xml'           => plugins_url( 'js/tinymce-plugins/codemirror/mode/xml/xml.js', __FILE__ ),
		);

	$pvars['preview'] = get_option( 'pastacode_preview', 'y' );

	$pvars['language_mode'] = array(
		'php'          => array(
			'libs'      => array( 'xml', 'css', 'htmlmixed', 'clike', 'php' ),
			'mode'      => 'application/x-httpd-php',
			),
		'css'          => array(
			'libs'      => array( 'css' ),
			'mode'      => 'text/css',
			),
		'javascript'   => array(
			'libs'      => array( 'javascript' ),
			'mode'      => 'text/javascript',
			),
		'c'            => array(
			'libs'      => array( 'clike' ),
			'mode'      => 'text/x-csrc',
			),
		'cpp'          => array(
			'libs'      => array( 'clike' ),
			'mode'      => 'text/x-c++src',
			),
		'java'         => array(
			'libs'      => array( 'clike' ),
			'mode'      => 'text/x-java',
			),
		'sass'         => array(
			'libs'      => array( 'sass' ),
			'mode'      => 'text/x-sass',
			),
		'python'       => array(
			'libs'      => array( 'python' ),
			'mode'      => 'text/x-python',
			),
		'sql'          => array(
			'libs'      => array( 'sql' ),
			'mode'      => 'text/x-sql',
			),
		'ruby'         => array(
			'libs'      => array( 'ruby' ),
			'mode'      => 'text/x-ruby',
			),
		'haml'         => array(
			'libs'      => array( 'haml' ),
			'mode'      => 'text/x-haml',
			),
		'markup'       => array(
			'libs'      => array( 'xml', 'css', 'javascript', 'htmlmixed' ),
			'mode'      => 'htmlmixed',
			),
		'coffeescript' => array(
			'libs'      => array( 'coffeescript' ),
			'mode'      => 'text/x-coffeescript',
			),
		'apacheconf'   => array(
			'libs'      => array( 'shell' ),
			'mode'      => 'text/x-sh',
			),
		'bash'         => array(
			'libs'      => array( 'shell' ),
			'mode'      => 'text/x-sh',
			),
		'less'         => array(
			'libs'      => array( 'css' ),
			'mode'      => 'text/x-less',
			),
		'markdown'     => array(
			'libs'      => array( 'xml', 'markdown' ),
			'mode'      => 'text/x-markdown',
			),
		);

	foreach ( $fields as $k => $f ) {
		$field = array(
			'type' => 'textbox',
			'name' => $f['name'],
			'label' => $f['label'],
			'classes' => 'field-to-test field pastacode-args ' . implode( ' ', $f['classes'] ),
			);
		if ( ! isset( $f['placeholder'] ) ) {
			$field['multiline'] = true;
			$field['minWidth'] = 300;
			$field['minHeight'] = 100;
		} else {
			$field['tooltip'] = $f['placeholder'];
		}
		$new_fields[] = $field;
	}

	$pvars['fields']      = $new_fields;
	$pvars['extendIcon']  = plugins_url( 'images/expand-editor.png', __FILE__ );
	$pvars['extendText']  = __( 'Expand editor', 'pastacode' );
	$pvars['base']        = plugins_url( '/', __FILE__ );
	$pvars['textLang']    = $langs;
	$pvars['editorTheme'] = get_option( 'pastacode_bo_style', 'ambiance' );
	$pvars['tooltip']     = __( 'Insert a code', 'pastacode' );

	// Print Vars
	$pvars = json_encode( apply_filters( 'pastacode_tinymcevars', $pvars ) );
	echo '<script>var pastacodeText=' . $text . ';var pastacodeVars=' . $pvars . ';</script>';
}

/**
 * pastacode_bbpress_compat
 *
 * @since  1.7 Pastacode now comaptible with bbPress
 */
add_action( 'template_redirect', 'pastacode_bbpress_compat' );
function pastacode_bbpress_compat() {
	if ( ! is_admin() && function_exists( 'is_bbpress' ) && is_bbpress() ) {
		add_filter( 'bbp_after_get_the_content_parse_args', 'pastacode_bbpress_tinymce_settings' );
		function pastacode_bbpress_tinymce_settings( $r ) {
			$r['tinymce']   = true;
			$r['teeny']     = false;
			$r['quicktags'] = false;
			return $r;
		}

		add_filter( 'pastacode_ajax', '__return_true' );

		add_filter( 'bbp_get_topic_content', 'do_shortcode' );
		add_filter( 'bbp_get_reply_content', 'do_shortcode' );

		wp_enqueue_script( 'mce-view' );
		pastacode_shortcodes_mce_css();

		add_filter( 'mce_buttons', 'bbp_pastacode_register_button', 10, 2 );
		add_filter( 'mce_buttons_2', '__return_empty_array' );
		add_filter( 'mce_external_plugins', 'pastacode_script_tiny' );
	}
}

function bbp_pastacode_register_button( $buttons, $editor_id ) {
	array_push( $buttons, 'pcb' );
	foreach ( array( 'formatselect', 'alignleft', 'aligncenter', 'alignright', 'wp_more', 'hr', 'fullscreen', 'wp_adv' ) as $elem ) {
		if ( false !== ( $key = array_search( $elem, $buttons ) ) ) {
			unset( $buttons[ $key ] );
		}
	}
	return $buttons;
}

add_action( 'template_redirect', 'pastacode_on_comments' );
function pastacode_on_comments() {
	if ( ! is_admin() && is_singular() && 'y' == get_option( 'pastacode_comments_opt' ) ) {
		add_filter( 'comment_form_field_comment', 'wabeo_pastacode_comment_editor' );
		add_filter( 'comment_text', 'pastacode_shortcode_in_comments', 9 );
	}
}

function wabeo_pastacode_comment_editor() {
	global $post;
	wp_enqueue_script( 'mce-view' );
	wp_enqueue_script( 'pastacode-move-comment-form', plugins_url( 'js/front-pastacode-comments.js', __FILE__ ), false, PASTACODE_VERSION );
	pastacode_shortcodes_mce_css();
	add_filter( 'pastacode_ajax', '__return_true' );
	add_filter( 'mce_buttons', 'bbp_pastacode_register_button', 10, 2 );
	add_filter( 'mce_buttons_2', '__return_empty_array' );
	add_filter( 'mce_external_plugins', 'pastacode_script_tiny' );
	ob_start();

	wp_editor( '', 'comment', array(
		'teeny'         => false,
		'quicktags'     => false,
		'media_buttons' => false,
		'tinymce'       => true,
	) );

	$editor = ob_get_contents();

	ob_end_clean();

	$editor = str_replace( 'post_id=0', 'post_id=' . get_the_ID(), $editor );

	return $editor;
}

function pastacode_shortcode_in_comments( $content ) {
	if ( false === strpos( $content, '[' ) ) {
		return $content;
	}

	$tagnames = array( 'pastacode' );
	$content = do_shortcodes_in_html_tags( $content, false, $tagnames );

	$pattern = get_shortcode_regex( $tagnames );
	$content = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $content );
	$content = unescape_invalid_shortcodes( $content );

	return $content;
}

add_filter( 'wp_editor_settings', 'pastacode_admin_comment_editor_settings', 10, 2 );
function pastacode_admin_comment_editor_settings( $settings, $editor_id ) {
	if ( is_admin() && 'content' == $editor_id ) {
		$screen = get_current_screen();
		if ( 'comment' == $screen->base && 'edit-comments' == $screen->parent_base ) {
			wp_enqueue_script( 'mce-view' );
			add_filter( 'mce_buttons_2', '__return_empty_array' );
			add_filter( 'mce_external_plugins', 'pastacode_script_tiny' );
			$settings = array(
				'teeny'         => false,
				'quicktags'     => false,
				'media_buttons' => false,
				'tinymce'       => true,
			);
		}
	}
	return $settings;
}
