<?php

class WebFlower_Form {

	const post_type = 'webflower';

	private static $found_items = 0;
	private static $current = null;

	private $id;
	private $name;
	private $title;
	private $subtitle;

	private $locale;

	private $qcount;
	private $rcount;

	private $questions;
	private $results;

	private $result_type;
	private $total_score;
	private $answer_index;

	private $properties = array();
	private $responses_count = 0;
	private $shortcode_atts = array();


	public static function count() {
		return self::$found_items;
	}

	public static function get_current() {
		return self::$current;
	}

	public static function register_post_type() {
		register_post_type( self::post_type, array(
			'labels' => array(
				'name' => __( 'flows', 'webflower' ),
				'singular_name' => __( 'flows', 'webflower' ),
			),
			'rewrite' => false,
			'query_var' => false,
		) );
	}

	public static function find( $args = '' ) {
		$defaults = array(
			'post_status' => 'any',
			'posts_per_page' => -1,
			'offset' => 0,
			'orderby' => 'ID',
			'order' => 'ASC',
		);

		$args = wp_parse_args( $args, $defaults );

		$args['post_type'] = self::post_type;

		$q = new WP_Query();
		$posts = $q->query( $args );

		self::$found_items = $q->found_posts;

		$objs = array();

		foreach ( (array) $posts as $post ) {
			$objs[] = new self( $post );
		}

		// print_r($objs);

		return $objs;
	}

	public static function get_template( $args = '' ) {
		global $l10n;

		$defaults = array( 'locale' => null, 'title' => '' );
		$args = wp_parse_args( $args, $defaults );

		$locale = $args['locale'];
		$title = $args['title'];

		if ( $locale ) {
			$mo_orig = $l10n['webflower'];
		}

		self::$current = $webflower = new self;

		$webflower->title = ( $title ? $title : __( 'Untitled', 'webflower' ) );
		$webflower->locale = ( $locale ? $locale : get_locale() );


		// $webflower = apply_filters( 'wpcf7_contact_form_default_pack', $webflower, $args );

		if ( isset( $mo_orig ) ) {
			$l10n['webflower'] = $mo_orig;
		}

		return $webflower;

    }

	public static function get_instance( $post ) {
		$post = get_post( $post );

		if ( ! $post || self::post_type != get_post_type( $post ) ) {
			return false;
		}

		return self::$current = new self( $post );
	}

	private function __construct( $post = null ) {
		$post = get_post( $post );

		if ( $post && self::post_type == get_post_type( $post ) ) {
			$this->id = $post->ID;
			$this->name = $post->post_name;
			$this->title = $post->post_title;

			$this->subtitle = get_post_meta( $post->ID, '_subtitle', true );
			$this->locale = get_post_meta( $post->ID, '_locale', true );
			$this->qcount = get_post_meta( $post->ID, '_qcount', true );

			$this->rcount = get_post_meta( $post->ID, '_rcount', true );
			$this->questions = array();
			$this->results = array();

			$this->result_type = get_post_meta( $post->ID, '_result_type', true );

			for ($i = 0 ; $i < $this->qcount ; $i++) {
				$this->questions[] = array(
					'qscore' => get_post_meta( $post->ID, '_qscore_' . $i, true),
					'q1' => get_post_meta( $post->ID, '_q1_' . $i, true),
					'q2' => get_post_meta( $post->ID, '_q2_' . $i, true),
				);
			}

			for ($i = 0 ; $i < $this->rcount ; $i++) {
				$this->results[] = array(
					'rmessage' => get_post_meta( $post->ID, '_rmessage_' . $i, true),
					'r1' => (int)get_post_meta( $post->ID, '_r1_' . $i, true),
					'r2' => (int)get_post_meta( $post->ID, '_r2_' . $i, true),
				);
			}

			$properties = $this->get_properties();

			foreach ( $properties as $key => $value ) {
				if ( metadata_exists( 'post', $post->ID, '_' . $key ) ) {
					$properties[$key] = get_post_meta( $post->ID, '_' . $key, true );
				} elseif ( metadata_exists( 'post', $post->ID, $key ) ) {
					$properties[$key] = get_post_meta( $post->ID, $key, true );
				}
			}

			$this->properties = $properties;
			$this->upgrade();
		}

		do_action( 'webflower_form', $this );
	}

	private function upgrade() {

	}

	public function __get( $name ) {
        $message = __( '<code>%1$s</code> property of a <code>WebFlower</code> object is <strong>no longer accessible</strong>. Use <code>%2$s</code> method instead.', 'webflower' );

		if ( 'id' == $name ) {
			if ( WP_DEBUG ) {
				trigger_error( sprintf( $message, 'id', 'id()' ) );
			}

			return $this->id;
		} elseif ( 'title' == $name ) {
			if ( WP_DEBUG ) {
				trigger_error( sprintf( $message, 'title', 'title()' ) );
			}

			return $this->title;
		} elseif ( $prop = $this->prop( $name ) ) {
			if ( WP_DEBUG ) {
				trigger_error(
					sprintf( $message, $name, 'prop(\'' . $name . '\')' ) );
			}

			return $prop;
		}
    }

	public function initial() {
		return empty( $this->id );
	}

	public function qcount() {
		return $this->qcount;
	}

	public function rcount() {
		return $this->rcount;
	}

	public function questions() {
		return $this->questions;
	}

	public function result_type() {
		return !$this->result_type || $this->result_type == '' ? 'link' : $this->result_type;
		// return $this->result_type;
	}

	public function results() {
		return $this->results;
	}

	public function resultsToJson() {
		return json_encode($this->results);
	}


	public function prop( $name ) {
		$props = $this->get_properties();
		return isset( $props[$name] ) ? $props[$name] : null;
	}

	public function get_properties() {
		$properties = (array) $this->properties;

		$properties = wp_parse_args( $properties, array(
			'form' => '',
		) );

		$properties = (array) apply_filters( 'webflower_form_properties',
			$properties, $this );

		return $properties;
	}

	public function set_properties( $properties ) {
		$defaults = $this->get_properties();

		$properties = wp_parse_args( $properties, $defaults );
		$properties = array_intersect_key( $properties, $defaults );

		$this->properties = $properties;
	}

	public function id() {
		return $this->id;
	}

	public function name() {
		return $this->name;
	}

	public function title() {
		return $this->title;
	}

	public function subtitle() {
		return $this->subtitle;
	}

	public function total_score(){
		return $this->total_score;
	}

	public function set_total_score($total_score) {
		$this->total_score = $total_score;
	}

	public function get_answer() {
		return $this->results()[$this->answer_index];
	}

	public function set_title( $title ) {
		$title = strip_tags( $title );
		$title = trim( $title );

		if ( '' === $title ) {
			$title = __( 'Untitled', 'webflower' );
		}

		$this->title = $title;
	}

	public function locale() {
		return '';
	}

	public function set_locale( $locale ) {
		$locale = trim( $locale );

		$this->locale = 'en_US';
	}

	public function shortcode_attr( $name ) {
		if ( isset( $this->shortcode_atts[$name] ) ) {
			return (string) $this->shortcode_atts[$name];
		}
	}

	public function form_html( $args = '' ) {

		$post = $this;

		require_once WEBFLOWER_PLUGIN_DIR . '/includes/templates/questions.php';

		return $args;

    }

	public function select_index(){
		$post = $this;

        $results = $post->results();

		$total_score = $this->total_score();

        $answer_index = 0;

		for ($i = 0 ; $i < $post->rcount() ; $i++){
            $obj = $results[$i];
            if (($obj['r1'] <= $total_score) && ($total_score < $obj['r2'])) {
                $answer_index = $i;
                break;
            }
        }

		$this->answer_index = $answer_index;

		return $answer_index;

	}

	public function answer_json( ) {

        $answer_index = $this->select_index();

        $result_message = $this->results()[$answer_index]['rmessage'];

        return json_encode(array(
	            'result_type' => $result_type,
	            'contents' => $result_message));

	}

	public function answer_html( ) {

		$this->select_index();

		$post = $this;

		require_once WEBFLOWER_PLUGIN_DIR . '/includes/templates/answers.php';

    }


    private function form_hidden_fields() {
		$hidden_fields = array(
			'_webflower' => $this->id(),
			'_webflower_version' => WEBFLOWER_VERSION,
			'_webflower_locale' => $this->locale(),
			// '_webflower_unit_tag' => $this->unit_tag,
			'_webflower_container_post' => 0,
		);

		if ( in_the_loop() ) {
			$hidden_fields['_webflower_container_post'] = (int) get_the_ID();
		}

		if ( $this->nonce_is_active() ) {
			$hidden_fields['_wpnonce'] = webflower_create_nonce();
		}

		$hidden_fields += (array) apply_filters(
			'webflower_form_hidden_fields', array() );

		$content = '';

		foreach ( $hidden_fields as $name => $value ) {
			$content .= sprintf(
				'<input type="hidden" name="%1$s" value="%2$s" />',
				esc_attr( $name ), esc_attr( $value ) ) . "\n";
		}

		return '<div style="display: none;">' . "\n" . $content . '</div>' . "\n";
	}



	public function shortcode( $args = '' ) {
		$args = wp_parse_args( $args, array('use_old_format' => false ) );

		$title = str_replace( array( '"', '[', ']' ), '', $this->title );

		if ( $args['use_old_format'] ) {
			$old_unit_id = (int) get_post_meta( $this->id, '_old_wf_unit_id', true );

			if ( $old_unit_id ) {
				$shortcode = sprintf( '[webflower %1$d]', $old_unit_id);
			} else {
				$shortcode = '';
			}
		} else {
			$shortcode = sprintf( '[webflower id="%1$d"]', $this->id);
		}

		return apply_filters( 'webflower_shortcode', $shortcode, $args, $this );
	}

}

?>
