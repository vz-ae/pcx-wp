<?php
/*
Plugin Name:  PCX Teams - V1
Plugin URI:   https://pcxteams.com
Description:  Custom code for PCX Teams.
Version:      1.0
Author:       PCX Teams
Author URI:   https://pcxteams.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wpb-tutorial
Domain Path:  /languages
*/


// TEST FW SHORTCODE
// 

/**
 * Functions file for stuff related to LD course template.
 *
 * @package LearnDash\Elementor
 */
/*
use Elementor\TemplateLibrary\Source_Local;

function learndash_filtered_lessons_by_section_shortcode( $atts ) {
    $atts_defaults = array(
        'course_id' => 0,
        'section_ids' => '',
    );
    $atts = shortcode_atts( $atts_defaults, $atts );

    if ( empty( $atts['course_id'] ) || empty( $atts['section_ids'] ) ) {
        return '<p>' . esc_html__( 'Course ID and Section IDs are required.', 'learndash' ) . '</p>';
    }

    $course_id = intval( $atts['course_id'] );
    $section_ids = array_map( 'intval', explode( ',', $atts['section_ids'] ) );

    $user_id = get_current_user_id();

    // Check if the user has access to the course
    if ( ! sfwd_lms_has_access( $course_id, $user_id ) ) {
        return '<p>' . esc_html__( 'You do not have access to this course.', 'learndash' ) . '</p>';
    }

    $sections = learndash_30_get_course_sections( $course_id );

    if ( empty( $sections ) ) {
        return '<p>' . esc_html__( 'No sections found in this course.', 'learndash' ) . '</p>';
    }

    $output = '<div class="section-lessons-list"><ul>';

    foreach ( $sections as $section ) {
        if ( in_array( $section->ID, $section_ids ) ) {
            if ( ! empty( $section->steps ) ) {
                foreach ( $section->steps as $lesson_id ) {
                    $lesson_title = get_the_title( $lesson_id );
                    $lesson_permalink = get_permalink( $lesson_id );

                    $output .= '<li><a href="' . esc_url( $lesson_permalink ) . '">' . esc_html( $lesson_title ) . '</a></li>';
                }
            }
        }
    }

    $output .= '</ul></div>';

    return $output;
}

add_shortcode( 'learndash_filtered_lessons', 'learndash_filtered_lessons_by_section_shortcode' );
*/

/* Notes: Created by old development team, not Scottsdale Website Design */

/*

function wpse_165754_avatar_shortcode_wp_enqueue_scripts() {
    wp_register_style( 'slick-slider', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', '1.0.0', 'all' );
    wp_register_style( 'slick-slider-theme', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css', '1.0.0', 'all' );
    wp_register_script( 'slick-slider', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.8.1', true );
}

add_action( 'wp_enqueue_scripts', 'wpse_165754_avatar_shortcode_wp_enqueue_scripts' );
function get_current_user_office(){
	$id = get_current_user_id(  );
   return get_user_meta( $id , 'pcx_office', true );
}

// Add Office Manager Role Wordpress
add_role( 'office_manager', 'Coach', get_role( 'author' )->capabilities );
// remove_role( 'office_manager');
// 
// Ulitmate Member Offices List Dropdown 
function display_offices_list(){
    $choices = array(
        '0' => 'Select Office'
    );
    // Query Offices 
    $offices_args = array(
        'post_type' => 'office',
        'posts_per_page'=> -1
    );
    $offices_query = new WP_Query($offices_args);
    if($offices_query->have_posts(  )){
        while($offices_query->have_posts(  )){
            $offices_query->the_post(  );
            $office_id = get_the_ID(  );
            $office_title = get_the_title();
            $choices[$office_id] = $office_title;
        }
    }else{
        $choices = array(
            '0' => 'No Offices Found'
        );
    }
    wp_reset_postdata(  );
    return $choices;
}

// Redirect User to login Page if User is not Logged in 

function restrict_logged_out_users() {

	if ( is_user_logged_in( ) || current_user_can('manage_options') ) { // If user is not Logged in
        return;
	}
    if ( is_page('register') || is_page('login') || is_page('logout') || is_page('password-reset') ) {
        return;
    }else{
        wp_redirect( home_url( '/login/' ) );
        die;
    }

}
// add_action( 'template_redirect', 'restrict_logged_out_users' );
// Redirect User to login Page if User is not Logged in 

function custom_pcx_redirects() {

    if ( is_page('coach') ) {
        wp_redirect('https://lookerstudio.google.com/reporting/38322aa1-e5ac-4a74-9fbe-bcb9b7f51a0a/page/p_2b5ahjgv1c');
        die;
    }

}
add_action( 'template_redirect', 'custom_pcx_redirects' );
// Hide Menu Items for Office Manager
function hide_siteadmin() {
  
// Use this for specific user role. Change site_admin part accordingly
if (current_user_can('office_manager')) {

   // DASHBOARD 
   remove_submenu_page( 'index.php', 'update-core.php');  // Update
	remove_menu_page( 'upload.php' ); // Media
	remove_menu_page( 'edit-comments.php' ); //Comments
	remove_menu_page( 'tools.php' ); //Tools
  	 remove_menu_page( 'users.php' ); //Users
	remove_menu_page( 'themes.php' ); // Appearance
	remove_menu_page( 'elementor' ); 
	remove_menu_page( 'edit.php?post_type=elementor_library' );
	// remove_menu_page( 'edit.php?post_type=office' );
    }
}
add_action('admin_head', 'hide_siteadmin');

function dsourc_hide_notices(){
	$user = wp_get_current_user();
	if (!($user->roles[0] == 'administrator')) {
		remove_all_actions( 'admin_notices' );
	}
}
add_action( 'admin_head', 'dsourc_hide_notices', 1 );

function remove_dashboard_meta() {
    $user = wp_get_current_user();
    if (!($user->roles[0] == 'administrator')) {
        remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
        // remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
        remove_meta_box( 'hasthemes-dashboard-stories', 'dashboard', 'normal' );
        remove_meta_box( 'e-dashboard-overview', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
    }
}
add_action( 'admin_init', 'remove_dashboard_meta');
add_filter('learndash_course_grid_excluded_post_types', function() {
    return [
    'sfwd-transactions',
    'sfwd-essays',
    'sfwd-assignment',
    'sfwd-certificates',
    'attachment',
    'posts',
    'office',
    'vendor',
    ];
    }, 99);
// add new dashboard widgets
function pcx_teams_new_dashboard_widget() {
    $user = wp_get_current_user();
    if (($user->roles[0] == 'office_manager')) {
	wp_add_dashboard_widget( 'pcx_dashboard_welcome', 'Welcome', 'pcx_add_welcome_widget' );
    }
}
function pcx_add_welcome_widget(){ 
    $user = wp_get_current_user();
    ?>
	Dear, <?php echo $user->display_name; ?> Welcome to Your Dasbhoard.<br> Through this Dashboard You will be able to manage a list Vendors and Edit Your Office Details.
<?php } 
add_action( 'wp_dashboard_setup', 'pcx_teams_new_dashboard_widget' );

// Display Vendor Office Name in Front of Vendor Name
// Register the columns.
add_filter( "manage_vendor_posts_columns", function ( $defaults ) {
	$defaults['vendor-office'] = 'Vendor Office';
	return $defaults;
} );

// Handle the value for each of the new columns.
add_action( "manage_vendor_posts_custom_column", function ( $column_name, $post_id ) {
	
	if ( $column_name == 'vendor-office' ) {
		$office_name =  get_field( 'vendor_office', $post_id );
        echo $office_name[0]->post_title;
	}
	
}, 10, 2 );

function posts_for_current_author($query) {
    global $pagenow;

    if( 'edit.php' != $pagenow || !$query->is_admin )
        return $query;

    if( !current_user_can( 'manage_options' ) ) {
   global $user_ID;
   $query->set('author', $user_ID );
 }
 return $query;
}
add_filter('pre_get_posts', 'posts_for_current_author');


add_action( 'views_vendor', 'remove_edit_post_views' );
function remove_edit_post_views( $views ) {

    unset($views['publish']);
    return $views;

}
add_shortcode( 'vendors-list', 'vendors_list' );
function vendors_list(){
    if(current_user_can( 'manage_options' )){
        $vendors_query_args = array(
            'post_type' => 'vendor',
            'posts_per_page' => 6,
        );
    }else{
        $user_office_id = get_current_user_office();
        if(!$user_office_id || $user_office_id==''){
            return 'Invalid Office ID';
        }
        $coach_id = get_post_field( 'post_author', $user_office_id); 
        $vendors_query_args = array(
            'post_type' => 'vendor',
            'author' => $coach_id,
            'posts_per_page' => -1,
        );
    }
    $vendors_query = new WP_Query($vendors_query_args);
    ob_start(); 
    if($vendors_query->have_posts(  )){
        echo '<div class="vendors-list">';
        while($vendors_query->have_posts(  )){
            $vendors_query->the_post(  ); 
            $vendor_image = 'https://pcxteams.com/wp-content/uploads/2023/05/default-vendor-image.jpg';
            if(has_post_thumbnail(get_the_ID(  ))){
                $vendor_image = get_the_post_thumbnail_url( get_the_ID(  ), 'post-thumbnail' );
            }
            $company_name = get_field('company_name');
            $vendor_phone = get_field('vendor_phone');
            $vendor_email = get_field('vendor_email');
            $vendor_logo = get_field('logo');
            $vendor_desc = get_field('description');
            $vendor_website = get_field('vendor_website');
            ?>
            <!-- Single Vendor  -->
            <div class="single-vendor">
                    <div class="single-vendor-header">
                        <div class="vendor-image">
                            <img src="<?php echo $vendor_image; ?>" alt="<?php echo get_the_title(); ?>">
                        </div>
                        <div class="vendor-basic-info">
                            <?php if($company_name){ ?>
                                <div class="vendor-company"><?php echo $company_name; ?></div>
                            <?php } ?>
                            <div class="vendor-name"><?php echo get_the_title(); ?></div>
                        </div>
                    </div>
                    <div class="single-vendor-details">
                        <div class="vendor-contact-info-wrap">
                            <?php if($vendor_logo){ ?>
                                <img src="<?php echo $vendor_logo; ?>" class="vendor-logo" alt="<?php echo $company_name; ?>">
                            <?php } ?>
                            <?php if($vendor_phone){ ?>
                                <div class="single-contact-info">
                                    <div class="contanct-info-icon">
                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                    </div>
                                    <div class="contact-info-detail">
                                        <?php echo $vendor_phone; ?>
                                    </div>
                                </div>
                            <?php } 
                            if($vendor_email){?>
                                <div class="single-contact-info">
                                    <div class="contanct-info-icon">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                    </div>
                                    <div class="contact-info-detail">
                                        <?php echo "<a href='mailto:$vendor_email'>$vendor_email</a>"; ?>
                                    </div>
                                </div>
                            <?php } 
                            if($vendor_website){ ?>
                                <div class="single-contact-info">
                                    <div class="contanct-info-icon">
                                        <i class="fa fa-globe" aria-hidden="true"></i>
                                    </div>
                                    <div class="contact-info-detail">
                                        <?php echo "<a href='$vendor_website'>$vendor_website</a>"; ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if($vendor_desc){ ?>
                                <div class="vendor-desc-wrap">
                                    <div class="vendor-desc-heading">Description</div>
                                    <div class="vendor-desc">
                                        <?php echo $vendor_desc; ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <!-- Single Vendor Ended  -->
        <?php }
        echo "</div>";
    }else{
        echo "No Vendors Found"; 
    }
    wp_reset_postdata(  );
     return ob_get_clean();
}
function pcx_user_profile_fields($user){
    global $pagenow;
    $user_office_id = null;
    if($pagenow !== 'user-new.php'){
        $user_office_id = get_user_meta( $user->ID, 'pcx_office', true );
    }
  ?>
    <h3>Extra PCX Teams information</h3>
    <table class="form-table">
        <tr>
            <th><label for="pcx_office">Select Office</label></th>
            <td>
                <select name="pcx_office" id="pcx_office">
                    <?php 
                    $offices = display_offices_list();
                    foreach ($offices as $office_id => $office_name) {
                        if($office_id==$user_office_id){
                            echo "<option value='$office_id' selected>$office_name</option>";
                        }else{
                            echo "<option value='$office_id'>$office_name</option>";
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
    </table>
  <?php
}
add_action( 'show_user_profile', 'pcx_user_profile_fields' );
add_action( 'edit_user_profile', 'pcx_user_profile_fields' );
add_action( "user_new_form", "pcx_user_profile_fields" );

function save_pcx_user_profile_fields($user_id){
    if(isset($_POST['pcx_office'])){
	 update_usermeta($user_id, 'pcx_office', $_POST['pcx_office']);	
	}
}
add_action('user_register', 'save_pcx_user_profile_fields');
add_action('profile_update', 'save_pcx_user_profile_fields');

add_shortcode( 'persons-list', 'persons_list' );
function persons_list(){
    $user_office_id = get_current_user_office();
    if(!$user_office_id || $user_office_id == ''){
        return 'Invalid Office ID';
    }
    $coach_profile_image = 'https://pcxteams.com/wp-content/uploads/2023/05/default-vendor-image.jpg';
    $tl_profile_image = 'https://pcxteams.com/wp-content/uploads/2023/05/default-vendor-image.jpg';
    $mca_profile_image = 'https://pcxteams.com/wp-content/uploads/2023/05/default-vendor-image.jpg';
    $managing_broker_profile_image = 'https://pcxteams.com/wp-content/uploads/2023/05/default-vendor-image.jpg'; // Managing Broker
    $services_manager_profile_image = 'https://pcxteams.com/wp-content/uploads/2023/05/default-vendor-image.jpg'; // Service Manager
    // Coach Data
    $coach = get_field('coach_details',$user_office_id);
    if($coach){
    $coach_name = $coach['coach_name'];
    $coach_phone = $coach['coach_phone'];
    $coach_email = $coach['coach_email'];
    $booking_calender_link = $coach['coach_calender'];
    $coach_intro = $coach['intro'];
    $coach_profile_image = $coach['coach_profile_image'];
    }else{
        return 'Incomplete Coach Information';
    }
    // TL Data
    $tl = get_field('tl_details',$user_office_id);
    if($tl){
    $tl_name = $tl['tl_name'];
    $tl_phone = $tl['tl_phone'];
    $tl_email = $tl['tl_email'];
    $tl_intro = $tl['intro'];
    $tl_profile_image = $tl['tl_profile_image'];
    }else{
        return 'Incomplete TL Information';
    }
    // MCA Data
    $mca = get_field('mca_details',$user_office_id);
    if($mca){
    $mca_name = $mca['mca_name'];
    $mca_phone = $mca['mca_phone'];
    $mca_email = $mca['mca_email'];
    $mca_intro = $mca['intro'];
    // echo var_dump($mca['mca_profile_image']);
    $mca_profile_image = $mca['mca_profile_image'];
    }else{
        return 'Incomplete MCA Information';
    }

    // Managing Broker Data
    $managing_broker_checkbox = get_field('add_managing_broker_details',$user_office_id);
    if($managing_broker_checkbox){
    $managing_broker = get_field('managing_broker_details',$user_office_id);
    $managing_broker_name = $managing_broker['managing_broker_name'];
    $managing_broker_phone = $managing_broker['managing_broker_phone'];
    $managing_broker_email = $managing_broker['managing_broker_email'];
    $mb_booking_calender_link = $managing_broker['managing_broker_calender'];
    $managing_broker_intro = $managing_broker['intro'];
    $managing_broker_profile_image = $managing_broker['managing_broker_profile_image'];
    }
    // Services Manager Data
    $services_manager_checkbox = get_field('add_services_manager_details',$user_office_id);
    if($services_manager_checkbox){
    $services_manager = get_field('services_manager_details',$user_office_id);
    $services_manager_name = $services_manager['services_manager_name'];
    $services_manager_phone = $services_manager['services_manager_phone'];
    $services_manager_email = $services_manager['services_manager_email'];
    $sm_booking_calender_link = $services_manager['services_manager_calender'];
    $services_manager_intro = $services_manager['intro'];
    $services_manager_profile_image = $services_manager['services_manager_profile_image'];
    }
    ob_start(); ?>
        <div class="vendors-list persons-list">
            <!-- Coach  -->
            <div class="single-vendor">
                    <div class="single-vendor-header">
                        <div class="vendor-image">
                            <img src="<?php echo $coach_profile_image; ?>" alt="<?php echo $coach_name; ?>">
                        </div>
                        <div class="vendor-basic-info">
                            <?php if($coach_name){ ?>
                                <div class="vendor-company">Coach</div>
                            <?php } ?>
                            <div class="vendor-name"><?php echo $coach_name; ?></div>
                        </div>
                    </div>
                    <div class="single-vendor-details">
                        <div class="vendor-contact-info-wrap">
                            <?php if($coach_phone){ ?>
                                <div class="single-contact-info">
                                    <div class="contanct-info-icon">
                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                    </div>
                                    <div class="contact-info-detail">
                                        <?php echo $coach_phone; ?>
                                    </div>
                                </div>
                            <?php } 
                            if($coach_email){?>
                                <div class="single-contact-info">
                                    <div class="contanct-info-icon">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                    </div>
                                    <div class="contact-info-detail">
                                        <?php echo "<a href='mailto:$coach_email'>$coach_email</a>"; ?>
                                    </div>
                                </div>
                            <?php } 
                            if($booking_calender_link){ ?>
                                <div class="single-contact-info">
                                    <div class="contanct-info-icon">
                                        <i class="fa fa-globe" aria-hidden="true"></i>
                                    </div>
                                    <div class="contact-info-detail">
                                        <?php echo "<a target='_blank' href='$booking_calender_link'>Book Here</a>"; ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if($coach_intro){ ?>
                                <div class="vendor-desc-wrap">
                                    <div class="vendor-desc-heading">Contact For</div>
                                    <div class="vendor-desc">
                                        <?php echo $coach_intro; ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <!-- Coach Ended  -->
            <!-- TL  -->
            <div class="single-vendor">
                    <div class="single-vendor-header">
                        <div class="vendor-image">
                            <img src="<?php echo $tl_profile_image; ?>" alt="<?php echo $tl_name; ?>">
                        </div>
                        <div class="vendor-basic-info">
                            <?php if($tl_name){ ?>
                                <div class="vendor-company">Team Leader</div>
                            <?php } ?>
                            <div class="vendor-name"><?php echo $tl_name; ?></div>
                        </div>
                    </div>
                    <div class="single-vendor-details">
                        <div class="vendor-contact-info-wrap">
                            <?php if($tl_phone){ ?>
                                <div class="single-contact-info">
                                    <div class="contanct-info-icon">
                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                    </div>
                                    <div class="contact-info-detail">
                                        <?php echo $tl_phone; ?>
                                    </div>
                                </div>
                            <?php } 
                            if($tl_email){?>
                                <div class="single-contact-info">
                                    <div class="contanct-info-icon">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                    </div>
                                    <div class="contact-info-detail">
                                        <?php echo "<a href='mailto:$tl_email'>$tl_email</a>"; ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if($tl_intro){ ?>
                                <div class="vendor-desc-wrap">
                                    <div class="vendor-desc-heading">Contact For</div>
                                    <div class="vendor-desc">
                                        <?php echo $tl_intro; ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <!-- TL Ended  -->
            <!-- MCA  -->
            <div class="single-vendor">
                    <div class="single-vendor-header">
                        <div class="vendor-image">
                            <img src="<?php echo $mca_profile_image; ?>" alt="<?php echo $mca_name; ?>">
                        </div>
                        <div class="vendor-basic-info">
                            <?php if($mca_name){ ?>
                                <div class="vendor-company">MCA</div>
                            <?php } ?>
                            <div class="vendor-name"><?php echo $mca_name; ?></div>
                        </div>
                    </div>
                    <div class="single-vendor-details">
                        <div class="vendor-contact-info-wrap">
                            <?php if($mca_phone){ ?>
                                <div class="single-contact-info">
                                    <div class="contanct-info-icon">
                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                    </div>
                                    <div class="contact-info-detail">
                                        <?php echo $mca_phone; ?>
                                    </div>
                                </div>
                            <?php } 
                            if($mca_email){?>
                                <div class="single-contact-info">
                                    <div class="contanct-info-icon">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                    </div>
                                    <div class="contact-info-detail">
                                        <?php echo "<a href='mailto:$mca_email'>$mca_email</a>"; ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if($mca_intro){ ?>
                                <div class="vendor-desc-wrap">
                                    <div class="vendor-desc-heading">Contact For</div>
                                    <div class="vendor-desc">
                                        <?php echo $mca_intro; ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <!-- MCA Ended  -->
            <?php if($managing_broker_checkbox){ ?>
            <!-- Managing Broker  -->
            <div class="single-vendor">
                    <div class="single-vendor-header">
                        <div class="vendor-image">
                            <img src="<?php echo $managing_broker_profile_image; ?>" alt="<?php echo $managing_broker_name; ?>">
                        </div>
                        <div class="vendor-basic-info">
                            <?php if($managing_broker_name){ ?>
                                <div class="vendor-company">Managing Director</div>
                            <?php } ?>
                            <div class="vendor-name"><?php echo $managing_broker_name; ?></div>
                        </div>
                    </div>
                    <div class="single-vendor-details">
                        <div class="vendor-contact-info-wrap">
                            <?php if($managing_broker_phone){ ?>
                                <div class="single-contact-info">
                                    <div class="contanct-info-icon">
                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                    </div>
                                    <div class="contact-info-detail">
                                        <?php echo $managing_broker_phone; ?>
                                    </div>
                                </div>
                            <?php } 
                            if($managing_broker_email){?>
                                <div class="single-contact-info">
                                    <div class="contanct-info-icon">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                    </div>
                                    <div class="contact-info-detail">
                                        <?php echo "<a href='mailto:$managing_broker_email'>$managing_broker_email</a>"; ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if($mb_booking_calender_link){ ?>
                                <div class="single-contact-info">
                                    <div class="contanct-info-icon">
                                        <i class="fa fa-globe" aria-hidden="true"></i>
                                    </div>
                                    <div class="contact-info-detail">
                                        <?php echo "<a target='_blank' href='$mb_booking_calender_link'>Book Here</a>"; ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if($managing_broker_intro){ ?>
                                <div class="vendor-desc-wrap">
                                    <div class="vendor-desc-heading">Contact For</div>
                                    <div class="vendor-desc">
                                        <?php echo $managing_broker_intro; ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <!-- Managing Broker Ended  -->
            <?php } ?>
            <?php if($services_manager_checkbox){ ?>
            <!-- Services Manager  -->
            <div class="single-vendor">
                    <div class="single-vendor-header">
                        <div class="vendor-image">
                            <img src="<?php echo $services_manager_profile_image; ?>" alt="<?php echo $services_manager_name; ?>">
                        </div>
                        <div class="vendor-basic-info">
                            <?php if($services_manager_name){ ?>
                                <div class="vendor-company">Managing Director</div>
                            <?php } ?>
                            <div class="vendor-name"><?php echo $services_manager_name; ?></div>
                        </div>
                    </div>
                    <div class="single-vendor-details">
                        <div class="vendor-contact-info-wrap">
                            <?php if($services_manager_phone){ ?>
                                <div class="single-contact-info">
                                    <div class="contanct-info-icon">
                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                    </div>
                                    <div class="contact-info-detail">
                                        <?php echo $services_manager_phone; ?>
                                    </div>
                                </div>
                            <?php } 
                            if($services_manager_email){?>
                                <div class="single-contact-info">
                                    <div class="contanct-info-icon">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                    </div>
                                    <div class="contact-info-detail">
                                        <?php echo "<a href='mailto:$services_manager_email'>$services_manager_email</a>"; ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if($sm_booking_calender_link){ ?>
                                <div class="single-contact-info">
                                    <div class="contanct-info-icon">
                                        <i class="fa fa-globe" aria-hidden="true"></i>
                                    </div>
                                    <div class="contact-info-detail">
                                        <?php echo "<a target='_blank' href='$sm_booking_calender_link'>Book Here</a>"; ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if($services_manager_intro){ ?>
                                <div class="vendor-desc-wrap">
                                    <div class="vendor-desc-heading">Contact For</div>
                                    <div class="vendor-desc">
                                        <?php echo $services_manager_intro; ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <!-- Services Manager Ended  -->
            <?php } ?>
        </div>
    <?php return ob_get_clean();
}
add_shortcode( 'pcx-bulliten-board', 'pcx_bulliten_board' );
function pcx_bulliten_board(){
    wp_enqueue_script('slick-slider');
    wp_enqueue_style( 'slick-slider' );
    wp_enqueue_style( 'slick-slider-theme' );
    $user_office_id = get_current_user_office();
    $posts_per_page = 8;
    if(!$user_office_id || $user_office_id==''){
        return 'Invalid Office ID';
    }
    $coach_id = get_post_field( 'post_author', $user_office_id);
    $notices_query_args = array(
        'post_type' => 'post',
        'posts_per_page' => $posts_per_page,
        'author' => $coach_id
    );
    $notices_query = new WP_Query($notices_query_args);
    ob_start();
    if($notices_query->have_posts(  )){
        echo '<div class="bulletin-board-wrap">';
        while($notices_query->have_posts(  )){
            $notices_query->the_post(  ); ?>
            <!-- Single Notice Started  -->
            <div class="single-notice">
                <div class="single-notice-body">
                    <div class="notice-headline">
                    <span class="notice-cat">News: </span> <a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a>
                    </div>
                    <div class="notice-excerpt">
                        <?php echo get_the_content(); ?>
                    </div>
                </div>
                <div class="notice-footer">
                    <div class="notice-author">
                        <?php $coach = get_user_by('ID', $coach_id); 
                        echo "By: $coach->display_name"; 
                        ?>
                    </div>
                    <div class="notice-date">
                        <?php echo get_the_date('M d, Y'); ?>
                    </div>
                </div>
            </div>
            <!-- Single Notice Ended  -->
        <?php }
        echo '</div>';
         ?>
        <script>
            jQuery(document).ready(function(){
                jQuery('.bulletin-board-wrap').slick({
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 2000,
                    infinite: true,
                    dots: true,
                    arrows:true, 
                    prevArrow: '<button class="prev-btn">←</button>',
    				nextArrow: '<button class="next-btn">→</button>',

                });
            });
        </script>
        <?php
    }else{
        echo 'No Notices Found';
    }
    wp_reset_postdata(  );
     ?>
    <?php return ob_get_clean();
}

// Custom Bulliten Board Page Elementor Query

function bulletin_board_query_elementor( $query ) {
    $user_office_id = get_current_user_office();
    $coach_id = get_post_field( 'post_author', $user_office_id);
	$query->set( 'author', $coach_id );
}
add_action( 'elementor/query/bulletin_board_query', 'bulletin_board_query_elementor' );

// Google Drive Iframe Shortcode

add_shortcode( 'pcx-gd-folder', 'pcx_gd_folder' );
function pcx_gd_folder($atts){
    $args = shortcode_atts( array(
        'link_for' => 'business_plan'
    ), $atts );
    $user_office_id = get_current_user_office();
    $link_for = $args['link_for'];
    ob_start();
    if($user_office_id){
        $gd_ids = get_field('office_google_drive_ids',$user_office_id);
        if(isset($gd_ids[$link_for])){
            $requested_id = $gd_ids[$link_for];
            if($requested_id && $requested_id!=''){
            $iframe = "<iframe src='https://drive.google.com/embeddedfolderview?id=$requested_id#list' style='width:100%; height:600px; border:0;'></iframe>";
            echo $iframe;
            }else{
                echo 'Folder ID Not Given';
            }
        }else{
            echo 'Invalid Parameter';
        }
    }else{
        echo 'Invalid Office ID';
    }
    return ob_get_clean();
}

add_shortcode( 'quick-links', 'display_quick_links' );
function display_quick_links($atts){
    $args = shortcode_atts( array(
        'link'=> null,
    ), $atts);
    if($args['link']===null){
        return 'Link Id Required';
    }else{
        $link_id = $args['link'];
        $user_office_id = get_current_user_office();
        $quick_links = get_field('quick_links', $user_office_id);
        if($link_id == 'mls_url'){
            $link = $quick_links['mls_url'];
            $label = 'MLS';
        }elseif($link_id == 'board_of_realtors'){
            $link = $quick_links['board_of_realtors'];
            $label = 'Board Of Realtors';
        }else{
            $link = '#';
            $label = 'Invalid Link ID';
        }
        if(!$link || $link==='' ){
            $link = '#';
        }
        return "<a href='$link' class='pcx-quick-link'>$label</a>";
    }
}

// Shortcode For displaying Additional Resources 
add_shortcode( 'pcx-office-calender', 'pcx_office_calender' );
function pcx_office_calender(){
    ob_start();
    $user_id = get_current_user_id();
    $user_office_id = get_user_meta( $user_id , 'pcx_office', true );
    if($user_office_id && $user_office_id!=""){ // If User has Office ID
        $calender_link = get_field('pcx_office_calender',$user_office_id);
        $calender_iframe = "<iframe src='$calender_link&mode=WEEK' style='border:solid 1px #777' width='800' height='600' frameborder='0' scrolling='no'></iframe>";
        echo $calender_iframe;
    }else{ // Otherwise
        echo 'No Office Linked With Your Profile';
    }
    return ob_get_clean();
}
// Short Code For Displaying Officed Based LMS Content i.e ["office_lms_content" "content_id"="ACF_FIELD_ID_HERE"]
add_shortcode( 'office_lms_content', 'office_lms_content' );
function office_lms_content($atts){

    $args = shortcode_atts( array(
        'content_id' => null
    ), $atts);

    // Current User Data
    $current_user = wp_get_current_user();
    // Current User Office ID
    $current_user_id = $current_user->ID;
    $user_office_id = get_user_meta( $current_user_id , 'pcx_office', true );
    ob_start();
    if($args['content_id']){ // Shortode have a valid content ID
        
        if($user_office_id){ // User Is Registered With Valid User ID

            // Search Cotent Based on Office ID and Content ID 
            $content = get_field($args['content_id'], $user_office_id);

            // Print Content If Valid Content Found
            if($content){
                echo $content;
            }else{ // No Content Found
                return; // Leave the Function
            }

        }else{
            echo 'Invalid Office ID';
        }

    }else{
        echo 'No Video Found For Your Office';
    }
    return ob_get_clean();
}
// / Shortcode For displaying Contract Writing Office Content [contract-writing-content section="ACF_SECTION_ID"]

add_shortcode( 'contract-writing-content', 'render_contract_writing_content' );
function render_contract_writing_content($atts){
    $args = shortcode_atts( array(
        'section' => 'main_content'
    ), $atts);
    // Get Current Office ID
    $user_id = get_current_user_id();
    $user_office_id = get_user_meta( $user_id , 'pcx_office', true );
    // Get All Content By Office ID
    $content_group = get_field('contract_writing_content', $user_office_id);
    // Render Content Depending on Section ID Mentioned in Shortcode
    // Supported IDs main_content (Default), mc_optional_content, mc_required_content
    $section_id = $args['section'];
    // If System is Able to Search Content Based on Office ID Or Not 
    ob_start();
    if($content_group){
        if($content_group[$section_id]){
            echo $content_group[$section_id];
        }
    }
    return ob_get_clean();
}

// Shortcode For Rendering LMS Content AnyWhere  [render-content post_id="POST_ID_HERE"]
add_shortcode( 'render-content', 'render_any_content' );
function render_any_content($atts){
    $args = shortcode_atts( array(
        'post_id' => null,
    ), $atts);
    ob_start();
    if($args['post_id']){
        echo get_the_content( null, false , $args['post_id'] );
    }else{
        echo 'Post ID Required';
    }
    return ob_get_clean();
}

add_shortcode( 'business-guide', 'business_guide_btn' );
function business_guide_btn(){
    $user_office_id = get_current_user_office();
    if(!$user_office_id){
        return '';
    }
    $busines_guide_url = get_field('business_guide', $user_office_id);
    if($busines_guide_url){
        $button_html = "<a href='$busines_guide_url' target='_blank' class='business-guide-url'>Course Workbook</a>";
        return $button_html;
    }
}
add_shortcode( 'office-resources-btn', 'office_resources_btn' );
function office_resources_btn(){
    $user_office_id = get_current_user_office();
    $gd_ids = get_field('office_google_drive_ids',$user_office_id);
    $gd_id = $gd_ids['office_resources'];
    if(!$user_office_id){
        return '';
    }
    if(!$gd_id){
        return '';
    }
    $btn_url = "https://drive.google.com/drive/folders/$gd_id";
    if($btn_url){
        $button_html = "<a href='$btn_url' target='_blank' class='business-guide-url'>Office Resources</a>";
        return $button_html;
    }
}
add_shortcode( 'office-resources-iframe', 'office_resources_iframe' );
function office_resources_iframe(){
    if(isset($_GET['gid'])){
        $gd_id = $_GET['gid'];
        $iframe = '<iframe src="https://drive.google.com/embeddedfolderview?id=';
        $iframe .= $gd_id;
        $iframe .= '#grid" width="600" height="500" frameborder="0"></iframe>';
        return $iframe;
    }else{
        return 'File ID Not Found';
    }
}

add_shortcode( 'render-office-content-field', 'render_office_content_field' );

function render_office_content_field($atts){
    $args = shortcode_atts( array(
        'field_id' => null,
    ), $atts);
    $user_office_id = get_current_user_office();
    
    ob_start();
    if($user_office_id){
        if($args['field_id']){
            $field = get_field($args['field_id'], $user_office_id);
            if($field){
                echo $field;
            }
        }
    }else{
        echo 'Invalid Office ID';
    }
    return ob_get_clean();
}
add_shortcode( 'render-office-logo', 'render_office_logo' );
function render_office_logo(){
    $user_office_id = get_current_user_office();
    ob_start();
    if($user_office_id){
        $logo = get_field('market_center_logo', $user_office_id);
        if($logo){
            echo "<img class='office-logo' src='$logo' alt='office-logo'/>";
        }
    }
    return ob_get_clean();
}
add_shortcode( 'pcx-logout', 'pcx_logout_btn' );
function pcx_logout_btn(){
    $logout_url = wp_logout_url(get_home_url());
    return "<a 
    style='font-weight: 600; text-decoration: none; padding: 5px 20px 10px 20px; background-color: #b40202; color: white;'
    href='$logout_url'>Sign Out</a>";
}

function belmont_insert_header_in_focus_mode() {
    if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
		if ( did_action( 'elementor/loaded' ) && hello_header_footer_experiment_active() ) {
			get_template_part( 'template-parts/dynamic-header' );
		} else {
			get_template_part( 'template-parts/header' );
		}
	}
}
add_action( 'learndash-focus-template-start', 'belmont_insert_header_in_focus_mode' );

// Bypass Email change confirmation 
add_action ( 'admin_init', function() { 
    remove_action( 
        'personal_options_update', 
        'send_confirmation_on_profile_email'
    );
    add_action( 
        'personal_options_update', 
        'wpse409429_update_profile_email'
    );
} );
function wpse409429_update_profile_email( $user_id ) {

    // Stop confirmation email when running 
    // send_confirmation_on_profile_email().
    add_filter( 'pre_wp_mail', '__return_false' );
    send_confirmation_on_profile_email();
    remove_filter( 'pre_wp_mail', '__return_false' );

    // Get new email that is already validated and saved
    // as _new_email within send_confirmation_on_profile_email().
    $new_email = get_user_meta( $user_id, '_new_email', true );

    if ( ! empty( $new_email['newemail'] ) && is_email( $new_email['newemail'] ) ) {
        // Core uses this output! escaping during saving, so we do the same!
        $newemail = esc_html( trim( $new_email['newemail'] ) );
    
        wp_update_user( array( 
            'ID'         => $user_id,
            'user_email' => $newemail
        ) );

        // Core modifies the _POST value for email, 
        // with the old email value, so we need to 
        // override that with the new one.
        $_POST['email'] = $newemail;

        // Delete post meta to avoid confirmation messages.
        delete_user_meta( $user_id, '_new_email' );
    }
}

// Steps Shortcode 
add_shortcode( 'pcx-steps', 'render_pcx_steps' );
function render_pcx_steps(){
    $user_office_id = get_current_user_office();
    // default Urls 
    $step_1 = 'https://drive.google.com/file/d/1FkjbcYav_aRH2kTGAp75R0LCu18jRxG3/view?usp=drive_link';
    $step_2 = 'https://drive.google.com/file/d/11W-B539MFZZZtwiFzDqXknGVpcmrbCAz/view?usp=sharing';
    $step_3 = 'https://drive.google.com/file/d/15-VfkfOWpxkPnpjLsa8bobkfN7bcH4S6/view?usp=sharing';
    $step_4 = 'https://drive.google.com/file/d/1-fm6m4y5yhMYsp6mvWBWbfatvVWDVUA7/view?usp=sharing';
    if($user_office_id){
        $steps = get_field('steps', $user_office_id);
        if($steps){
            if($steps['step_1'] != ''){
                $step_1 = $steps['step_1'];
            }
            if($steps['step_2'] != ''){
                $step_2 = $steps['step_2'];
            }
            if($steps['step_3'] != ''){
                $step_3 = $steps['step_3'];
            }
            if($steps['step_4'] != ''){
                $step_4 = $steps['step_4'];
            }
        }
    }

    ob_start(); ?>
   <div class="steps_wrapper">
        <div class="single-step">
            <a href="<?php echo $step_1; ?>">
             <img src="https://pcxteams.com/wp-content/uploads/2023/11/Step-1-8-e1699652383779-300x173.jpg" width="80px" height="47px" alt="Step 1">
            </a>
        </div>
        <div class="single-step">
            <a href="<?php echo $step_2; ?>">
                <img src="https://pcxteams.com/wp-content/uploads/2023/11/Step-1-9-e1699652429150-300x171.jpg" width="80px" height="47px" alt="Step 2">
            </a>
        </div>
        <div class="single-step">
            <a href="<?php echo $step_3; ?>">
                <img src="https://pcxteams.com/wp-content/uploads/2023/11/Step-1-10-e1699652488906-300x179.jpg" width="80px" height="47px" alt="Step 3">
            </a>
        </div>
        <div class="single-step">
            <a href="<?php echo $step_4; ?>">
                <img src="https://pcxteams.com/wp-content/uploads/2023/12/Step-4-updated.jpg" width="80px" height="47px" alt="Step 4">
            </a>
        </div>
   </div>
   <?php return ob_get_clean();
}
add_shortcode( 'marketing-templates-pdf', 'render_mtp' );
function render_mtp(){
    $user_office_id = get_current_user_office();
    $marketing_file = 'https://drive.google.com/file/d/16cusQlYTlCcwHud3-rbQOjOTKVAVyTGv/preview';
    $is_pdf = false;
    if($user_office_id){
        $pdf_file = get_field('marketing_template_pdf', $user_office_id);
        if($pdf_file){
            $is_pdf = true;
            $marketing_file = $pdf_file;
        }
    }
    ob_start(); 
    if($is_pdf){
        echo "<iframe src='$marketing_file' width='640' height='480' allow='autoplay'></iframe>";
    }else{
        echo "<iframe src='https://drive.google.com/file/d/16cusQlYTlCcwHud3-rbQOjOTKVAVyTGv/preview' width='640' height='480' allow='autoplay'></iframe>";
    }
     return ob_get_clean();
}

add_shortcode( 'refferal-map', 'render_refferal_map' );
function render_refferal_map(){
    $user_office_id = get_current_user_office();
    if($user_office_id){
        $office_type = get_field('office_type', $user_office_id);
        if($office_type == 'market_center'){
            return do_shortcode('[elementor-template id="6935"]');
        }
    }
    return '';
}

add_shortcode( 'e-live-recordings-pdf', 'render_e_live_rec_pdf' );
function render_e_live_rec_pdf(){
    $user_office_id = get_current_user_office();
    if($user_office_id){
        $is_visible = get_field('display_essentials_live_call_recordings', $user_office_id);
        if($is_visible){
            return "<iframe src='https://drive.google.com/file/d/1In_gMcGaRg0TEB4j0kdDgbYfZS0n_P9r/preview' width='640' height='480' allow='autoplay'></iframe>";
        }
    }
    return '';
}




function pcx_is_user_logged_in(){
    
    $is_logged_in = "no";
    $is_user_an_admin = "no";
    $is_offic_id_set = "no";
    $redirect_them = "no";
    
    $user_office_id = get_current_user_office();
    
    if( !empty($user_office_id) && isset($user_office_id) && trim($user_office_id) != ''){
        $is_offic_id_set = "yes";
    }
    
    
   if ( is_user_logged_in() ) {
        $is_logged_in = "yes";
   }
    
    if( current_user_can('administrator') ) {
        $is_user_an_admin = "yes";
    }
    
    
    
    if( $is_logged_in == "no" ) {
		header("Location: https://pcxteams.com/login/");
        die;
	
	}
    
    // Should they go to the login page
    if( $is_logged_in == "no" && $is_user_an_admin == "no" ){
        $redirect_them = "yes";
    }else if(
                $is_logged_in == "yes" 
                && $is_user_an_admin == "no" 
                && $is_offic_id_set == "no" ){
        $redirect_them = "yes";
        
    }else{
        // Do nothing   
    }
    
        
    
    if($redirect_them  == "yes" ){
        
        // For Live Mode
        // header("Location: https://pcxteams.com/login/");
        header("Location: https://pcxteams.com/error-invalid-office-id/");
        
        die;
        
        
        
    }
    
}
    */
    
    