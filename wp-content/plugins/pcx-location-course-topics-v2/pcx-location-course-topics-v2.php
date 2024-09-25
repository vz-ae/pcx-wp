<?php

/*

Plugin Name: PCX Get Location For Course Topics V2

Description: PCX Get Location Content For Course Topics.

Version: 2

Author: Scottsdale Website Design

*/



// Add your plugin's code here

function topics_video_fucn_v2($atts) { 

	ob_start();

    $user_id = get_current_user_id(); // Replace 123 with the ID of the user you want to retrieve meta data for

$meta_key = 'pcx_office'; // Replace 'your_meta_key' with the key of the meta data you want to retrieve



$user_meta = get_user_meta( $user_id, $meta_key, true );



	$atts = shortcode_atts(array(

        'day' => '', // Default value for param1

    ), $atts);



    // Access parameters

    $param1_value = $atts['day'];



// WP_Query arguments

$post_ids = array( $user_meta ); // Replace with the desired post IDs

if($user_meta){

$args = array(

	

    'post_type' => 'office', // Change 'your_post_type_slug' to your actual post type slug

    'posts_per_page' => -1, // Number of posts to display

    'post__in' => $post_ids,

);



// The Query

$query = new WP_Query($args);



// The Loop

if ($query->have_posts()) {

    while ($query->have_posts()) {

        $query->the_post();

        // Display the post content or any other desired information

        ?>

        

<?php 

$jj = 1;


        $dayname = 'day_'.$param1_value;
        $sub_value = get_field($dayname);


	

            

            $title_a = "";
            $title_b = "";
            $title_c = "";
            $title_d = "";

            $title_wraper_open = "";
            $title_wraper_close = "";
            $build_results = "" ;



            /* Title A */
			if( trim($sub_value['title_a']) !=  "" ){ 
                $title_a .= '<h3 style="padding-bottom: 10px;">'.$sub_value['title_a'].'</h3>'; 
			}

            /* Link A */
            if( trim($sub_value['link_a']) !=  "" ){ 
                $title_a .= '<a href="'.$sub_value['link_a'] ;
                
                $title_a .= ' " target="_blank" >'.$sub_value['link_a'].'</a>'; 
            }

            /* Description A */
			if( trim($sub_value['description_a']) !=  "" ){ 
			    $title_a .= '<div style="padding-top: 3px;">'.$sub_value['description_a'].'</div>'; 
                
			}

			

            if( !empty($title_a) ){
                $title_a .= '<hr />'; 
            }

            /* For Wrapper */ 
            $title_wraper_open .= '<div class="locationtopicsseccls">';
            $title_wraper_close .=  '</div>';

            

            
            /* For Testing 
            
            echo "<h1>title_a</h1>";
            echo $title_a;
            echo "<hr>";
            
            echo "<h1>title_b</h1>";
            echo $title_b;
            echo "<hr>";
            
            echo "<h1>title_c</h1>";
            echo $title_c;
            echo "<hr>";
            
            echo "<h1>title_d</h1>";
            echo $title_d;
            echo "<hr>";
            
            die;
            */ 
            

           $build_results .= trim($title_a) ;
           $build_results = trim($build_results) ;

            

           

         if( !empty($build_results) && trim($build_results) != "" ){
				 echo $title_wraper_open;
				 echo $build_results; 
				 echo $title_wraper_close;
            }else{
                 echo  "<h2>No Content For This Day.</h2>";
            }

            

            

            

        // Do something, but make sure you escape the value if outputting directly...

    // End loop.

    $jj++;

    

		?>

        <?php

    }

} else {

    // If no posts are found

    //echo 'No posts found';

}

}



// Restore original Post Data

wp_reset_postdata();

	$output = ob_get_clean();



    // Return output

    return $output;

}

add_shortcode('PCX-get-location', 'topics_video_fucn_v2');









