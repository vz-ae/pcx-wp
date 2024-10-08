<?php
/**
 * LearnDash Quiz Builder Metabox.
 *
 * @since 2.6.0
 * @package LearnDash\Builder
 */

use LearnDash\Core\Utilities\Cast;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ( ! class_exists( 'Learndash_Admin_Metabox_Quiz_Builder' ) ) && ( class_exists( 'Learndash_Admin_Builder' ) ) ) {
	/**
	 * Class LearnDash Quiz Builder Metabox.
	 *
	 * @since 2.6.0
	 * @uses Learndash_Admin_Builder
	 */
	class Learndash_Admin_Metabox_Quiz_Builder extends Learndash_Admin_Builder {
		/**
		 * LearnDash quiz question object
		 *
		 * @var object
		 */
		public $ld_quiz_questions_object;

		/**
		 * Public constructor for class
		 *
		 * @since 2.6.0
		 */
		public function __construct() {
			$this->builder_post_type   = 'sfwd-quiz';
			$this->selector_post_types = array(
				learndash_get_post_type_slug( 'question' ),
			);
			$this->builder_init();
			parent::__construct();
		}

		/**
		 * Initialize builder for specific Quiz Item.
		 *
		 * @since 2.6.0
		 *
		 * @param integer $post_id Post ID to load.
		 */
		public function builder_init( $post_id = 0 ) {
			if ( ! empty( $post_id ) ) {
				$this->builder_post_id          = intval( $post_id );
				$this->ld_quiz_questions_object = LDLMS_Factory_Post::quiz_questions( $this->builder_post_id );
			}
		}

		/**
		 * Prints content for Quiz Builder meta box for admin
		 * This function is called from other add_meta_box functions
		 *
		 * @since 2.6.0
		 *
		 * @param object $post WP_Post.
		 */
		public function show_builder_box( $post ) {
			if ( ( is_a( $post, 'WP_Post' ) ) && ( $this->builder_post_type === $post->post_type ) ) {
				$this->builder_init( $post->ID );
				parent::show_builder_box( $post );
				?>

				<style>
					#learndash_builder_box_wrap .learndash_selectors #learndash-selector-post-listing-sfwd-question:empty::after {
						content: "
						<?php
						printf(
							// translators: placeholder: Question.
							esc_html_x( 'Click the \'+\' to add a new %s', 'placeholder: Question', 'learndash' ),
							LearnDash_Custom_Label::get_label( 'question' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method escapes output
						);
						?>
						";
					}
					#learndash_builder_box_wrap .learndash_builder_items .ld-course-builder-question-items:empty::after {
						content: "
						<?php
						printf(
							// translators: placeholder: Questions.
							esc_html_x( 'Drop %s Here', 'placeholder: Questions', 'learndash' ),
							LearnDash_Custom_Label::get_label( 'questions' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method escapes output
						);
						?>
						";
					}
				</style>
				<?php
			}
		}

		/**
		 * Get the selected items for a post type.
		 *
		 * @since 2.6.0
		 *
		 * @param string $selector_post_type Post Type is selector being processed.
		 *
		 * @return array Selector post IDs.
		 */
		public function get_selector_selected_steps( $selector_post_type = '' ) {
			$selector_post_type_steps = array();
			if ( ! empty( $selector_post_type ) ) {
				$questions = $this->ld_quiz_questions_object->get_questions( 'post_ids' );
				if ( ! empty( $questions ) ) {
					$selector_post_type_steps = array_keys( $questions );
				}
			}
			return $selector_post_type_steps;
		}

		/**
		 * Get the number of current items in the builder.
		 *
		 * @since 2.6.0
		 */
		public function get_build_items_count() {
			?>
			<span class="learndash_builder_items_total">
			<?php
				printf(
					// translators: placeholder: Questions label, number of questions.
					esc_html_x( 'Total %1$s: %2$s', 'placeholder: Questions label, number of questions', 'learndash' ),
					LearnDash_Custom_Label::get_label( 'questions' ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method escapes output
					'<span class="learndash_builder_items_total_value">' . intval( $this->ld_quiz_questions_object->get_questions_count() ) . '</span>'
				);
			?>
			</span>
			<?php
		}

		/**
		 * Call via the WordPress admin_footer action hook.
		 *
		 * @since 2.6.0
		 */
		public function builder_admin_footer() {
			$builder_post_type_label = $this->get_label_for_post_type( $this->builder_post_type );

			$this->builder_assets[ $this->builder_post_type ]['messages']['learndash_unload_message'] = sprintf(
				// translators: placeholder: Quiz.
				esc_html_x( 'You have unsaved %s Builder changes. Are you sure you want to leave?', 'placeholder: Quiz', 'learndash' ),
				LearnDash_Custom_Label::get_label( $builder_post_type_label ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method escapes output
			);

			foreach ( $this->selector_post_types as $selector_post_type ) {
				$post_type_object = get_post_type_object( $selector_post_type );
				if ( is_a( $post_type_object, 'WP_Post_Type' ) ) {
					$this->builder_assets[ $this->builder_post_type ]['messages'][ 'confirm_remove_' . $selector_post_type ] = sprintf(
						// translators: placeholders: post type labels like Question, second Quiz.
						esc_html_x( 'Are you sure you want to remove this %1$s from the %2$s?', 'placeholders: post type labels like Question, second Quiz', 'learndash' ),
						LearnDash_Custom_Label::get_label( $this->get_label_for_post_type( $selector_post_type ) ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method escapes output
						LearnDash_Custom_Label::get_label( $builder_post_type_label ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method escapes output
					);

					$this->builder_assets[ $this->builder_post_type ]['messages'][ 'confirm_trash_' . $selector_post_type ] = sprintf(
						// translators: placeholders: post type labels like Question.
						esc_html_x( 'Are you sure you want to move this %s to Trash?', 'placeholders: post type labels like Question', 'learndash' ),
						LearnDash_Custom_Label::get_label( $this->get_label_for_post_type( $selector_post_type ) ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method escapes output
					);
				}
			}

			parent::builder_admin_footer();
		}


		/**
		 * Utility function to get the label for Post Type.
		 *
		 * @since 2.6.0
		 *
		 * @param string  $post_type Post Type slug.
		 * @param boolean $singular True if singular label needed. False for plural.
		 */
		public function get_label_for_post_type( $post_type = '', $singular = true ) {
			switch ( $post_type ) {
				case 'sfwd-quiz':
					if ( true === $singular ) {
						return 'quiz';
					}
					return 'quizzes';

				case 'sfwd-question':
					if ( true === $singular ) {
						return 'question';
					}
					return 'questions';

				default:
					return '';
			}
		}

		/** Utility function to build the selector query args array.
		 *
		 * @since 2.6.0
		 *
		 * @param array $args Array of query args.
		 *
		 * @return array
		 */
		public function build_selector_query( $args = array() ) {
			$per_page = LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Quizzes_Builder', 'per_page' );
			if ( empty( $per_page ) ) {
				$per_page = 10;
			}

			$step_post_statuses = learndash_get_step_post_statuses();
			if ( ! empty( $step_post_statuses ) ) {
				$step_post_statuses = array_keys( $step_post_statuses );
			} else {
				$step_post_statuses = array( 'publish' );
			}

			$defaults = array(
				'post_status'    => $step_post_statuses,
				'posts_per_page' => $per_page,
				'paged'          => 1,
				'orderby'        => 'title',
				'order'          => 'ASC',
			);

			$args = wp_parse_args( $args, $defaults );

			/**
			 * If we are not sharing steps then we limit the query results to only show items associated with the quiz or items
			 * not associated with any quiz.
			 */
			if ( LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Quizzes_Builder', 'shared_questions' ) !== 'yes' ) {
				$m_include_ids = array();
				$m_args        = array( 'posts_per_page' => -1 );

				if ( isset( $args['post_type'] ) ) {
					$m_args['post_type'] = $args['post_type'];
				}
				if ( isset( $args['post_status'] ) ) {
					$m_args['post_status'] = $args['post_status'];
				} else {
					$m_args['post_status'] = array( 'public' );
				}
				$m_args['fields'] = 'ids';

				if ( ( isset( $args['post__not_in'] ) ) && ( ! empty( $args['post__not_in'] ) ) ) {
					$m_args['post__not_in'] = $args['post__not_in'];
					unset( $args['post__not_in'] );
				}

				$m_args['meta_query'] = array(); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query

				// First get all the items related to the quiz ID.
				$m_args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'     => 'quiz_id',
						'value'   => $this->builder_post_id,
						'compare' => '=',
					),
				);

				$m_post_type_query = new WP_Query( $m_args );
				if ( ( property_exists( $m_post_type_query, 'posts' ) ) && ( ! empty( $m_post_type_query->posts ) ) ) {
					$m_include_ids = array_merge( $m_include_ids, $m_post_type_query->posts );
					if ( ! isset( $m_args['post__not_in'] ) ) {
						$m_args['post__not_in'] = array();
					}
					$m_args['post__not_in'] = array_merge( $m_args['post__not_in'], $m_include_ids );
				}

				/**
				 * Filters whether to include orphaned steps or not. Orphaned steps are the steps that are not attached to a quiz.
				 *
				 * @since 2.6.0
				 *
				 * @param boolean $include_orphaned_steps Whether to include orphaned steps.
				 * @param array   $args                   An array of query arguments.
				 */
				$include_orphaned_questions = apply_filters( 'learndash_quiz_builder_include_orphaned_questions', true, $args );
				if ( true === $include_orphaned_questions ) {
					// Next get any quiz where the 'quiz_id' is zero.
					$m_args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						array(
							'key'     => 'quiz_id',
							'value'   => 0,
							'compare' => '=',
						),
					);
					$m_post_type_query    = new WP_Query( $m_args );
					if ( ( property_exists( $m_post_type_query, 'posts' ) ) && ( ! empty( $m_post_type_query->posts ) ) ) {
						$m_include_ids = array_merge( $m_include_ids, $m_post_type_query->posts );
						if ( ! isset( $m_args['post__not_in'] ) ) {
							$m_args['post__not_in'] = array();
						}
						$m_args['post__not_in'] = array_merge( $m_args['post__not_in'], $m_include_ids );
					}

					// Finally get any quiz where the 'quiz_id' does not exist.
					$m_args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						array(
							'key'     => 'quiz_id',
							'compare' => 'NOT EXISTS',
						),
					);
					$m_post_type_query    = new WP_Query( $m_args );
					if ( ( property_exists( $m_post_type_query, 'posts' ) ) && ( ! empty( $m_post_type_query->posts ) ) ) {
						$m_include_ids = array_merge( $m_include_ids, $m_post_type_query->posts );
						if ( ! isset( $m_args['post__not_in'] ) ) {
							$m_args['post__not_in'] = array();
						}
						$m_args['post__not_in'] = array_merge( $m_args['post__not_in'], $m_include_ids );
					}
				}

				if ( ! empty( $m_include_ids ) ) {
					$args['post__in'] = $m_include_ids;
				} else {
					$args['post__in'] = array( 0 );
				}
			}
			/**
			 * Filters quiz builder query arguments.
			 *
			 * @since 2.6.0
			 *
			 * @param array $args An array of query arguments.
			 */
			return apply_filters( 'learndash_quiz_builder_selector_args', $args );
		}

		/**
		 * Common function to show Selector pager buttons.
		 *
		 * @since 2.6.0
		 *
		 * @param object $post_type_query WP_Query instance.
		 *
		 * @return string Button(s) HTML.
		 */
		public function build_selector_pages_buttons( $post_type_query ) {
			$pager_buttons = '';

			if ( $post_type_query instanceof WP_Query ) {
				$first_page = 1;

				$current_page = intval( $post_type_query->query['paged'] );
				$last_page    = intval( $post_type_query->max_num_pages );
				if ( empty( $last_page ) ) {
					$last_page = 1;
				}

				if ( $current_page <= 1 ) {
					$prev_page     = 1;
					$prev_disabled = ' disabled="disabled" ';
				} else {
					$prev_page     = $current_page - 1;
					$prev_disabled = '';
				}

				if ( $current_page >= $last_page ) {
					$next_page     = $last_page;
					$next_disabled = ' disabled="disabled" ';
				} else {
					$next_page     = $current_page + 1;
					$next_disabled = '';
				}

				$pager_buttons .= '<button ' . $prev_disabled . ' class="button button-simple first" data-page="' . $first_page . '" title="' . esc_attr__( 'First Page', 'learndash' ) . '">&laquo;</button>';
				$pager_buttons .= '<button ' . $prev_disabled . ' class="button button-simple prev" data-page="' . $prev_page . '" title="' . esc_attr__( 'Previous Page', 'learndash' ) . '">&lsaquo;</button>';
				$pager_buttons .= '<span><span class="pagedisplay"><span class="current_page">' . $current_page . '</span> / <span class="total_pages">' . $last_page . '</span></span></span>';
				$pager_buttons .= '<button ' . $next_disabled . ' class="button button-simple next" data-page="' . $next_page . '" title="' . esc_attr__( 'Next Page', 'learndash' ) . '">&rsaquo;</button>';
				$pager_buttons .= '<button ' . $next_disabled . ' class="button button-simple last" data-page="' . $last_page . '" title="' . esc_attr__( 'Last Page', 'learndash' ) . '" >&raquo;</button>';
			}

			return $pager_buttons;
		}

		/**
		 * Common function to show Selector pager buttons.
		 *
		 * @since 3.0.0
		 *
		 * @param object $post_type_query WP_Query instance.
		 *
		 * @return array Page Button(s) array.
		 */
		public function build_selector_pages_buttons_json( $post_type_query ) {
			$pager_buttons = array(
				'first_page'    => 1,
				'last_page'     => 1,
				'prev_page'     => null,
				'prev_disabled' => false,
				'next_page'     => null,
				'next_disabled' => false,
				'current_page'  => null,
			);

			if ( $post_type_query instanceof WP_Query ) {
				$pager_buttons['first_page'] = 1;

				$current_page = intval( $post_type_query->query['paged'] );
				$last_page    = intval( $post_type_query->max_num_pages );

				$pager_buttons['current_page'] = $current_page;
				if ( empty( $last_page ) ) {
					$pager_buttons['last_page'] = 1;
				}

				if ( $current_page <= 1 ) {
					$pager_buttons['prev_page'] = 1;
					$pager_buttons['has_prev']  = false;
				} else {
					$pager_buttons['prev_page'] = $current_page - 1;
					$pager_buttons['has_prev']  = true;
				}

				if ( $current_page >= $last_page ) {
					$pager_buttons['next_page'] = $last_page;
					$pager_buttons['has_next']  = false;
				} else {
					$pager_buttons['next_page'] = $current_page + 1;
					$pager_buttons['has_next']  = true;
				}
			}

			return $pager_buttons;
		}

		/**
		 * Show selector rows.
		 *
		 * @since 2.6.0
		 *
		 * @param object $post_type_query WP_Query instance.
		 */
		public function build_selector_rows( $post_type_query ) {
			$selector_rows = '';

			if ( $post_type_query instanceof WP_Query ) {
				$selector_post_type        = $post_type_query->query['post_type'];
				$selector_post_type_object = get_post_type_object( $selector_post_type );

				$selector_label = $selector_post_type_object->label;
				$selector_slug  = $this->get_label_for_post_type( $selector_post_type );

				foreach ( $post_type_query->posts as $p ) {
					$selector_rows .= $this->build_selector_row_single( $p, $selector_post_type );
				}
			}

			return $selector_rows;
		}

		/**
		 * Show selector rows.
		 *
		 * @since 3.0.0
		 *
		 * @param object $post_type_query WP_Query instance.
		 */
		public function build_selector_rows_json( $post_type_query ) {
			$selector_rows = array();

			if ( $post_type_query instanceof WP_Query ) {
				$selector_post_type = $post_type_query->query['post_type'];

				foreach ( $post_type_query->posts as $p ) {
					// Get Answers from Question.
					$question_pro_id = (int) get_post_meta( $p->ID, 'question_pro_id', true );
					$question_mapper = new \WpProQuiz_Model_QuestionMapper();

					if ( ! empty( $question_pro_id ) ) {
						$answers_raw = $question_mapper->fetch( $question_pro_id );
					} else {
						$answers_raw = $question_mapper->fetch( null );
					}

					$question_data = $answers_raw->get_object_as_array();

					$controller_question = new \WpProQuiz_Controller_Question();

					if ( ( $answers_raw ) && is_a( $answers_raw, 'WpProQuiz_Model_Question' ) ) {
						$answers_data = $controller_question->setAnswerObject( $answers_raw );
					} else {
						$answers_data = $controller_question->setAnswerObject();
					}

					$processed_answers = array();

					foreach ( $answers_data as $answer_type => $answers ) {
						foreach ( $answers as $answer ) {
							$processed_answers[ $answer_type ][] = array(
								'answer'             => $answer->getAnswer(),
								'html'               => $answer->isHtml(),
								'points'             => $answer->getPoints(),
								'correct'            => $answer->isCorrect(),
								'sortString'         => $answer->getSortString(),
								'sortStringHtml'     => $answer->isSortStringHtml(),
								'graded'             => $answer->isGraded(),
								'gradingProgression' => $answer->getGradingProgression(),
								'gradedType'         => $answer->getGradedType(),
								'type'               => 'answer',
							);
						}
					}

					$selector_rows[] = array(
						'ID'                    => $p->ID,
						'expanded'              => false,
						'post_title'            => wp_kses_post( $question_data['_title'] ),
						'post_status'           => learndash_get_step_post_status_slug( $p ),
						'post_content'          => $question_data['_question'],
						'edit_link'             => get_edit_post_link( $p->ID, '' ),
						'type'                  => $selector_post_type,
						'question_type'         => $question_data['_answerType'],
						'points'                => learndash_format_course_points( $question_data['_points'] ),
						'answers'               => $processed_answers,
						'correctMsg'            => $question_data['_correctMsg'],
						'incorrectMsg'          => $question_data['_incorrectMsg'],
						'correctSameText'       => $question_data['_correctSameText'],
						'tipEnabled'            => $question_data['_tipEnabled'],
						'tipMsg'                => $question_data['_tipMsg'],
						'answerPointsActivated' => $question_data['_answerPointsActivated'],
					);
				}
			}

			return $selector_rows;
		}

		/**
		 * Show selector single row.
		 *
		 * @since 2.6.0
		 *
		 * @param object $p WP_Post object to show.
		 * @param string $selector_post_type Post type slug.
		 *
		 * @return string Row HTML.
		 */
		protected function build_selector_row_single( $p = null, $selector_post_type = '' ) {
			global $learndash_question_types;

			$selector_row = '';

			if ( empty( $selector_post_type ) ) {
				return $selector_row;
			}

			$selector_post_type_object = get_post_type_object( $selector_post_type );

			$selector_label = $selector_post_type_object->label;
			$selector_slug  = $this->get_label_for_post_type( $selector_post_type );

			$selector_sub_actions = '';

			$p_id           = '';
			$p_title        = '';
			$edit_post_link = '';
			$view_post_link = '';

			if ( $p ) {
				$p_id    = $p->ID;
				$p_title = get_the_title( $p->ID );

				/**
				 * We add this to force the quiz_id to zero for the selectors as we don't
				 * want the the 'view' URL to reflect the nested quiz.
				 */
				add_filter(
					'learndash_post_link_course_id',
					function ( $course_id ) {
						return 0;
					}
				);

				$view_post_link = get_permalink( $p->ID );
				if ( current_user_can( 'edit_courses' ) ) {
					$edit_post_link = get_edit_post_link( $p->ID );
					$edit_post_link = remove_query_arg( 'quiz_id', $edit_post_link );
				}
			} else {
				// We need a unique ID.
				$p_id    = $selector_post_type . '-placeholder';
				$p_title = $selector_post_type_object->labels->singular_name;
			}

			$question_pro_id = get_post_meta( $p_id, 'question_pro_id', true );
			if ( ! empty( $question_pro_id ) ) {
				$question_pro_fields = learndash_get_question_pro_fields( $question_pro_id, array( 'quiz_id', 'points', 'answer_type' ) );
			}

			$question_type_string = '';
			if ( ( ! isset( $question_pro_fields['answer_type'] ) ) || ( empty( $question_pro_fields['answer_type'] ) ) || ( ! isset( $learndash_question_types[ $question_pro_fields['answer_type'] ] ) ) ) {
				$question_pro_fields['answer_type'] = 'single';
			}

			$question_type_string = $learndash_question_types[ $question_pro_fields['answer_type'] ];

			$question_points_string = '';
			if ( ( isset( $question_pro_fields['points'] ) ) && ( ! empty( $question_pro_fields['points'] ) ) ) {
				$question_points = learndash_format_course_points( $question_pro_fields['points'] );
			} else {
				$question_points = 1; // Default to 1 point.
			}

			// translators: placeholder: Question Points.
			$question_points_string = sprintf( _nx( '(%.2fpt)', '(%.2fpts)', absint( $question_points ), 'placeholder: Question Points', 'learndash' ), number_format_i18n( $question_points, 2 ) ); // cspell: disable-line .

			$selector_sub_actions .= '<a target="_blank" class="ld-course-builder-action ld-course-builder-action-edit ld-course-builder-action-' . $selector_slug . '-edit dashicons" href="' . $edit_post_link . '"><span class="screen-reader-text">' . sprintf(
				// translators: placeholder: will contain post type label.
				esc_html_x( 'Edit %s Settings (new window)', 'placeholder: will contain post type label', 'learndash' ),
				LearnDash_Custom_Label::get_label( $selector_slug )
			) . '</span></a>';

			if ( current_user_can( 'delete_courses' ) ) {
				$selector_sub_actions .= '<span class="ld-course-builder-action ld-course-builder-action-trash ld-course-builder-action-' . $selector_slug . '-trash dashicons" title="' . sprintf(
					// translators: placeholder: will contain post type label.
					esc_html_x( 'Move %s to Trash', 'placeholder: will contain post type label', 'learndash' ),
					LearnDash_Custom_Label::get_label( $selector_slug )
				) . '"></span>';
			}
			$selector_sub_actions .= '<span class="ld-course-builder-action ld-course-builder-action-remove ld-course-builder-action-' . $selector_slug . '-remove dashicons" title="' . sprintf(
				// translators: placeholders: Question, Quiz.
				esc_html_x( 'Remove %1$s from %2$s', 'placeholders: Question, Quiz', 'learndash' ),
				LearnDash_Custom_Label::get_label( $selector_slug ),
				LearnDash_Custom_Label::get_label( 'quiz' )
			) . '"></span>';

			$selector_action_expand = '';

			$selector_row .= '<li id="ld-post-' . $p_id . '" class="ld-course-builder-item ld-course-builder-' . $selector_slug . '-item " data-ld-type="' . $selector_post_type . '" data-ld-id="' . $p_id . '">
				<div class="ld-course-builder-' . $selector_slug . '-header ld-course-builder-header">
					<span class="ld-course-builder-actions">
						<span class="ld-course-builder-action ld-course-builder-action-move ld-course-builder-action-' . $selector_slug . '-move dashicons" title="' . sprintf(
				// translators: placeholder: will contain post type label.
					esc_html_x( 'Move %s', 'placeholder: will contain post type label', 'learndash' ),
					LearnDash_Custom_Label::get_label( $selector_slug )
				) . '"></span>
						<span class="ld-course-builder-sub-actions">' . $selector_sub_actions . '</span>
					</span>
					<span class="ld-course-builder-title">
						<span class="ld-course-builder-title-text">' . $p_title . '</span>
						<span class="ld-course-builder-action ld-course-builder-edit-title-pencil dashicons" title="' . esc_html__( 'Edit Title', 'learndash' ) . '" ></span>
						<span class="ld-course-builder-action ld-course-builder-edit-title-ok dashicons" title="' . esc_html__( 'Ok', 'learndash' ) . '" ></span>
						<span class="ld-course-builder-action ld-course-builder-edit-title-cancel dashicons" title="' . esc_html__( 'Cancel', 'learndash' ) . '" ></span>
						<span class="ld-course-builder-title-right" style="float: right;" >
						<span class="ld-course-builder-type">' . $question_type_string . '</span>
						<span class="ld-course-builder-points" data-ld-points="' . learndash_format_course_points( $question_points ) . '">' . $question_points_string . '</span>
					</span>
				</div>
				</li>';

			return $selector_row;
		}

		/**
		 * This function is empty on purpose and overrides the parent function
		 * with the same name. The purpose is to prevent the default output.
		 *
		 * @since 2.6.0
		 */
		public function show_builder_header_right() {
			$total_question_points = 0;
			$quiz_questions        = $this->ld_quiz_questions_object->get_questions( 'post_ids' );
			if ( ! empty( $quiz_questions ) ) {
				$quiz_mapper           = new WpProQuiz_Model_QuizMapper();
				$total_question_points = $quiz_mapper->sumQuestionPointsFromArray( $quiz_questions );
			}
			?>
			<div class="learndash-header-right">
				<?php
				printf(
					// translators: placeholder: Total of question points.
					esc_html_x( 'Total Points: %s', 'placeholder: Total of question points', 'learndash' ),
					'<span class="learndash_builder_points_total_value">' . esc_html( (string) $total_question_points ) . '</span>'
				);
				?>
			</div>
			<?php
		}

		/**
		 * Build Course Steps HTML.
		 *
		 * @since 2.6.0
		 */
		public function build_course_steps_html() {
			$questions_html = '';

			$quiz_questions = $this->ld_quiz_questions_object->get_questions( 'post_ids' );

			$questions_html .= $this->process_quiz_questions( $quiz_questions );

			return $questions_html;
		}

		/**
		 * Build course steps HTML.
		 *
		 * @since 2.6.0
		 *
		 * @param array $questions Array of current Quiz questions.
		 *
		 * @return string Steps HTML.
		 */
		protected function process_quiz_questions( $questions = array() ) {
			global $learndash_question_types;

			$questions_section_html = '';

			$steps_type = 'sfwd-question';

			if ( ! empty( $questions ) ) {
				foreach ( $questions as $question_id => $q_pro_id ) {
					$edit_post_link = get_edit_post_link( $question_id );
					$edit_post_link = add_query_arg( 'quiz_id', $this->builder_post_id, $edit_post_link );
					$view_post_link = learndash_get_step_permalink( $question_id, $this->builder_post_id );

					$question_pro_fields = learndash_get_question_pro_fields( $q_pro_id, array( 'quiz_id', 'points', 'answer_type' ) );

					$question_type_string = '';
					if ( ( ! isset( $question_pro_fields['answer_type'] ) ) || ( empty( $question_pro_fields['answer_type'] ) ) || ( ! isset( $learndash_question_types[ $question_pro_fields['answer_type'] ] ) ) ) {
						$question_pro_fields['answer_type'] = 'single';
					}

					// translators: placeholder: Question Type.
					$question_type_string = $learndash_question_types[ $question_pro_fields['answer_type'] ];

					$question_points_string = '';
					if ( ( isset( $question_pro_fields['points'] ) ) && ( ! empty( $question_pro_fields['points'] ) ) ) {
						$question_points = learndash_format_course_points( $question_pro_fields['points'] );
					} else {
						$question_points = 1; // Default to 1 point.
					}

					// translators: placeholder: Question Points.
					$question_points_string = sprintf( _nx( '(%.2fpt)', '(%.2fpts)', absint( $question_points ), 'placeholder: Question Points', 'learndash' ), number_format_i18n( $question_points, 2 ) ); // cspell: disable-line .

					$questions_section_item_html = '<div id="ld-post-' . $question_id . '" class="ld-course-builder-item ld-course-builder-question-item" data-ld-type="' . $steps_type . '" data-ld-id="' . $question_id . '">
									<div class="ld-course-builder-quiz-header ld-course-builder-header">
										<span class="ld-course-builder-actions">
											<span class="ld-course-builder-action ld-course-builder-action-move ld-course-builder-action-question-move dashicons" title="' . esc_html__( 'Move', 'learndash' ) . '"></span>
											<span class="ld-course-builder-sub-actions">
												<a target="_blank" class="ld-course-builder-action ld-course-builder-action-edit ld-course-builder-action-quiz-edit dashicons" href="' . $edit_post_link . '"><span class="screen-reader-text">' .
												// translators: placeholder: Topic.
												sprintf( esc_html_x( 'Edit %s Settings (new window)', 'placeholder: Topic', 'learndash' ), LearnDash_Custom_Label::get_label( 'Quiz' ) ) . '" ></span></a>
												<span class="ld-course-builder-action ld-course-builder-action-remove ld-course-builder-action-quiz-remove dashicons" title="' .
												// translators: placeholders: Question, Quiz.
												sprintf( esc_html_x( 'Remove %1$s from %2$s', 'placeholders: Question, Quiz', 'learndash' ), LearnDash_Custom_Label::get_label( 'question' ), LearnDash_Custom_Label::get_label( 'quiz' ) ) . '"></span>
											</span>
										</span>
										<span class="ld-course-builder-title">
											<span class="ld-course-builder-title-text">' . get_the_title( $question_id ) . '</span>
											<span class="ld-course-builder-action ld-course-builder-edit-title-pencil dashicons" title="' . esc_html__( 'Edit Title', 'learndash' ) . '" ></span>
											<span class="ld-course-builder-action ld-course-builder-edit-title-ok dashicons" title="' . esc_html__( 'Ok', 'learndash' ) . '" ></span>
											<span class="ld-course-builder-action ld-course-builder-edit-title-cancel dashicons" title="' . esc_html__( 'Cancel', 'learndash' ) . '" ></span>
											<span class="ld-course-builder-title-right" style="float: right;" >
												<span class="ld-course-builder-type">' . $question_type_string . '</span>
												<span class="ld-course-builder-points" data-ld-points="' . learndash_format_course_points( $question_points ) . '">' . $question_points_string . '</span>
											</span>
										</span>
									</div>
								</div>';
					$questions_section_html .= $questions_section_item_html;
				}
			}

			$questions_section_html = '<div class="ld-course-builder-' . $this->get_label_for_post_type( $steps_type ) . '-items">' . $questions_section_html . '</div>';

			return $questions_section_html;
		}

		/** Save Course Builder steps
		 *
		 * @since 2.6.0
		 *
		 * @param integer $post_id Post ID of course being saved.
		 * @param object  $post WP_Post object instance being saved.
		 * @param boolean $update False is an update. True if new post.
		 */
		public function save_course_builder( $post_id, $post, $update ) {
			$return_status = false;

			$cb_nonce_key   = $this->builder_prefix . '_nonce';
			$cb_nonce_value = $this->builder_prefix . '_' . $post->post_type . '_' . $post_id . '_nonce';

			if ( ( isset( $_POST[ $cb_nonce_key ] ) ) && ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $cb_nonce_key ] ) ), $cb_nonce_value ) ) ) {
				if ( isset( $_POST[ $this->builder_prefix ][ $this->builder_post_type ][ $post_id ] ) ) {
					$quiz_questions_data = wp_unslash( $_POST[ $this->builder_prefix ][ $this->builder_post_type ][ $post_id ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

					if ( '' !== $quiz_questions_data ) {
						$this->ld_quiz_questions_object = LDLMS_Factory_Post::quiz_questions( $post_id );

						$quiz_questions = (array) json_decode( $quiz_questions_data, true );

						if ( ( is_array( $quiz_questions ) ) && ( ! empty( $quiz_questions ) ) ) {
							$quiz_questions_split = LDLMS_Quiz_Questions::questions_split_keys( $quiz_questions );
						} else {
							$quiz_questions_split = array();
						}
						$this->ld_quiz_questions_object->set_questions( $quiz_questions_split );
						$return_status = true;
					}
				}
			}

			return $return_status;
		}

		/**
		 * Handle AJAX pager requests.
		 *
		 * @since 2.6.0
		 *
		 * @param array $query_args array of values for AJAX request.
		 */
		public function learndash_builder_selector_pager( $query_args = array() ) {
			$reply_data = array();

			if ( isset( $query_args['format'] ) && 'json' === $query_args['format'] ) {
				$reply_data['selector_pager'] = array();
				$reply_data['selector_rows']  = array();
			} else {
				$reply_data['selector_pager'] = '';
				$reply_data['selector_rows']  = '';
			}

			if ( ! empty( $query_args ) ) {
				$post_type_query_args = $this->build_selector_query( $query_args );
				if ( ! empty( $post_type_query_args ) ) {
					$post_type_query = new WP_Query( $post_type_query_args );
					if ( $post_type_query->have_posts() ) {
						if ( isset( $query_args['format'] ) && 'json' === $query_args['format'] ) {
							$reply_data['selector_pager'] = $this->build_selector_pages_buttons_json( $post_type_query );
							$reply_data['selector_rows']  = $this->build_selector_rows_json( $post_type_query );
						} else {
							$reply_data['selector_pager'] = $this->build_selector_pages_buttons( $post_type_query );
							$reply_data['selector_rows']  = $this->build_selector_rows( $post_type_query );
						}
					}
				}
			}

			echo wp_json_encode( $reply_data );

			wp_die();
		}

		/**
		 * Handle AJAX search requests.
		 *
		 * @since 2.6.0
		 *
		 * @param array $query_args array of values for AJAX request.
		 */
		public function learndash_builder_selector_search( $query_args = array() ) {
			$reply_data = array();
			if ( isset( $query_args['format'] ) && 'json' === $query_args['format'] ) {
				$reply_data['selector_pager'] = array();
				$reply_data['selector_rows']  = array();
			} else {
				$reply_data['selector_pager'] = '';
				$reply_data['selector_rows']  = '';
			}

			if ( ! empty( $query_args ) ) {
				$post_type_query_args = $this->build_selector_query( $query_args );
				if ( ! empty( $post_type_query_args ) ) {
					$post_type_query = new WP_Query( $post_type_query_args );
					if ( $post_type_query->have_posts() ) {
						if ( isset( $query_args['format'] ) && 'json' === $query_args['format'] ) {
							$reply_data['selector_pager'] = $this->build_selector_pages_buttons_json( $post_type_query );
							$reply_data['selector_rows']  = $this->build_selector_rows_json( $post_type_query );
						} else {
							$reply_data['selector_pager'] = $this->build_selector_pages_buttons( $post_type_query );
							$reply_data['selector_rows']  = $this->build_selector_rows( $post_type_query );
						}
					}
				}
			}

			echo wp_json_encode( $reply_data );

			wp_die();
		}

		/**
		 * Handle AJAX new step requests.
		 *
		 * @since 2.6.0
		 *
		 * @param array $query_args array of values for AJAX request.
		 */
		public function learndash_builder_selector_step_new( $query_args = array() ) {
			global $wpdb;

			$reply_data              = array();
			$reply_data['new_steps'] = array();

			if ( ( isset( $query_args['new_steps'] ) ) && ( ! empty( $query_args['new_steps'] ) ) ) {
				foreach ( $query_args['new_steps'] as $old_step_id => $step_set ) {
					if ( ( isset( $step_set['post_type'] ) ) && ( ! empty( $step_set['post_type'] ) ) && ( false !== in_array( $step_set['post_type'], array( 'sfwd-question' ), true ) ) ) {
						$post_args = array(
							'action'       => 'new_step',
							'post_type'    => esc_attr( $step_set['post_type'] ),
							'post_status'  => 'publish',
							'post_title'   => '',
							'post_content' => '',
						);

						if ( ( isset( $step_set['post_title'] ) ) && ( ! empty( $step_set['post_title'] ) ) ) {
							$post_args['post_title'] = $step_set['post_title'];
						} else {
							$post_type_object = get_post_type_object( $step_set['post_type'] );
							if ( $post_type_object ) {
								$post_args['post_title'] = $post_type_object->labels->singular_name;
							}
						}
						/** This filter is documented in includes/admin/classes-builders/class-learndash-admin-course-builder-metabox.php */
						// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
						$new_step_id = wp_insert_post( apply_filters( 'course_builder_selector_new_step_post_args', $post_args ) );
						if ( $new_step_id ) {
							/**
							 * We have to set the guid manually because the one assigned within wp_insert_post is non-unique.
							 * See LEARNDASH-3853
							 */
							$wpdb->update( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
								$wpdb->posts,
								array(
									'guid' => add_query_arg(
										array(
											'post_type' => $step_set['post_type'],
											'p'         => $new_step_id,
										),
										home_url()
									),
								),
								array( 'ID' => $new_step_id )
							);

							if ( 'sfwd-question' === $post_args['post_type'] ) {
								$question_pro_id = learndash_update_pro_question( 0, $post_args );
								if ( ! empty( $question_pro_id ) ) {
									update_post_meta( $new_step_id, 'question_pro_id', absint( $question_pro_id ) );
									learndash_proquiz_sync_question_fields( $new_step_id, $question_pro_id );
								}
							}

							$reply_data['status'] = true;

							$reply_data['new_steps'][ $old_step_id ]                = array();
							$reply_data['new_steps'][ $old_step_id ]['post_id']     = $new_step_id;
							$reply_data['new_steps'][ $old_step_id ]['view_url']    = get_permalink( $new_step_id );
							$reply_data['new_steps'][ $old_step_id ]['edit_url']    = get_edit_post_link( $new_step_id );
							$reply_data['new_steps'][ $old_step_id ]['post_status'] = get_post_status( $new_step_id );

							learndash_update_setting( $new_step_id, 'quiz', '0' );
							update_post_meta( $new_step_id, 'quiz_id', '0' );
						}
					}
				}
			}
			echo wp_json_encode( $reply_data );

			wp_die();
		}

		/**
		 * Handle AJAX trash step requests.
		 *
		 * @since 2.6.0
		 *
		 * @param array $query_args array of values for AJAX request.
		 */
		public function learndash_builder_selector_step_trash( $query_args = array() ) {
			$reply_data = array();

			$post_args = array(
				'post_id'   => 0,
				'post_type' => '',
			);

			$post_args = wp_parse_args( $query_args, $post_args );

			$post_args['post_id']   = intval( $query_args['post_id'] );
			$post_args['post_type'] = esc_attr( $query_args['post_type'] );

			if ( ( empty( $post_args['post_type'] ) ) || ( empty( $post_args['post_id'] ) ) ) {
				$reply_data['status']        = false;
				$reply_data['error_message'] = esc_html__( '#1: Invalid post data', 'learndash' );
			} elseif ( in_array( $post_args['post_type'], $this->selector_post_types, true ) === false ) {
				$reply_data['status']        = false;
				$reply_data['error_message'] = esc_html__( '#2: Invalid post data', 'learndash' );
			} else {
				$new_step_id          = wp_trash_post( $post_args['post_id'] );
				$reply_data['status'] = true;
			}
			echo wp_json_encode( $reply_data );

			wp_die();
		}

		/**
		 * Handle AJAX set title requests.
		 *
		 * @since 2.6.0
		 *
		 * @param array $query_args array of values for AJAX request.
		 */
		public function learndash_builder_selector_step_title( $query_args = array() ) {
			$reply_data = array();

			$post_args               = array(
				'action'     => 'edit_title',
				'post_title' => '',
				'post_id'    => 0,
				'post_type'  => '',
			);
			$post_args               = wp_parse_args( $query_args, $post_args );
			$post_args['action']     = 'edit_title';
			$post_args['post_id']    = absint( $query_args['post_id'] );
			$post_args['post_type']  = esc_attr( $query_args['post_type'] );
			$post_args['post_title'] = wp_unslash( sanitize_post_field( 'post_title', $query_args['new_title'], $post_args['post_id'], 'db' ) );

			if ( ( empty( $post_args['post_title'] ) ) || ( empty( $post_args['post_type'] ) ) || ( empty( $post_args['post_id'] ) ) ) {
				$reply_data['status']        = false;
				$reply_data['error_message'] = esc_html__( '#1: Invalid post data', 'learndash' );
			} elseif ( in_array( $post_args['post_type'], $this->selector_post_types, true ) === false ) {
				$reply_data['status']        = false;
				$reply_data['error_message'] = esc_html__( '#2: Invalid post data', 'learndash' );
			} else {
				$edit_post = array(
					'ID'         => $post_args['post_id'],
					'post_title' => $post_args['post_title'],
					'post_name'  => '',
				);
				wp_update_post( $edit_post );
				$reply_data['status'] = true;

				if ( 'sfwd-question' === $post_args['post_type'] ) {
					$question_pro_id = get_post_meta( $post_args['post_id'], 'question_pro_id', true );
					if ( ! empty( $question_pro_id ) ) {
						$question_pro_id = absint( $question_pro_id );
					} else {
						$question_pro_id = 0;
					}

					$question_pro_id_new = learndash_update_pro_question( $question_pro_id, $post_args );
					$question_pro_id_new = absint( $question_pro_id_new );
					if ( ( ! empty( $question_pro_id_new ) ) && ( $question_pro_id_new !== $question_pro_id ) ) {
						update_post_meta( $post_args['post_id'], 'question_pro_id', absint( $question_pro_id_new ) );
						learndash_set_question_quizzes_dirty( $post_args['post_id'] );
					}
				}
			}

			echo wp_json_encode( $reply_data );

			wp_die();
		}

		// End of functions.
	}
}
add_action(
	'learndash_builders_init',
	function () {
		Learndash_Admin_Metabox_Quiz_Builder::add_instance();
	}
);

