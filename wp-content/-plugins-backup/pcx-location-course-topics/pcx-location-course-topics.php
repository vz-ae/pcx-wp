<?php

/*

Plugin Name: PCX Get Location For Course Topics

Description: PCX Get Location Content For Course Topics.

Version: 1.0

Author: Developer

*/



// Add your plugin's code here

function topics_video_fucn($atts) { 

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

if( have_rows('topics-1-30') ):

    // Loop through rows.

    while( have_rows('topics-1-30') ) : the_row();

        // Load sub field value.

        $sub_value = get_sub_field('day');

		

		

	/* For Testing 	
	if($jj==$param1_value){
		echo ($param1_value);
		echo "<hr>";
		echo "jj = " . ($jj);
		echo "<hr>";
	    var_dump ($sub_value );
		// die;
    }
	*/

		

		

		

		

		//print_r($sub_value);

		if($jj==$param1_value){

		    

		if(!empty($sub_value)){

            

            

            $title_a = "";
            $title_b = "";
            $title_c = "";
            $title_d = "";

            $title_wraper_open = "";
            $title_wraper_close = "";
            $build_results = "" ;



            /* Title A */
			if( trim($sub_value['title_a']) !=  "" ){ 
                $title_a .= '<h3>'.$sub_value['title_a'].'</h3>'; 
			}

            /* Link A */
            if( trim($sub_value['link_a']) !=  "" ){ 
                $title_a .= '<br />'; 
                $title_a .= '<a href="'.$sub_value['link_a'].'" target="_blank">'.$sub_value['link_a'].'</a>'; 
            }

            /* Description A */
			if( trim($sub_value['description_a']) !=  "" ){ 
			    $title_a .= '<div>'.$sub_value['description_a'].'</div>'; 
                
			}

			




            /* Title B */
            if( trim($sub_value['title_b']) !=  "" ){ 
                $title_b .= '<h3>'.$sub_value['title_b'].'</h3>'; 
            }
            
            /* Link B */
            if( trim($sub_value['link_b']) !=  "" ){ 
                $title_b .= '<br />'; 
                $title_b .= '<a href="'.$sub_value['link_b'].'" target="_blank">'.$sub_value['link_b'].'</a>'; 
            }

            /* Description B */
            if( trim($sub_value['description_b']) !=  "" ){ 
                $title_b .= '<div>'.$sub_value['description_b'].'</div>'; 
                
            }

            

           
            /* Title C */
			if( trim($sub_value['title_c']) !=  "" ){ 
                $title_c .= '<h3>'.$sub_value['title_c'].'</h3>'; 
			}

            /* Link C */
            if( trim($sub_value['link_c']) !=  "" ){ 
                $title_c .= '<br />'; 
                $title_c .= '<a href="'.$sub_value['link_c'].'" target="_blank">'.$sub_value['link_c'].'</a>'; 
            }


            /* Description C */
			if( trim($sub_value['description_c']) !=  "" ){ 
			    $title_c .= '<div>'.$sub_value['description_c'].'</div>'; 
               
			}

         
         
         
         
            /* Title D */
			if( trim($sub_value['title_d']) !=  "" ){ 
                $title_d .= '<h3>'.$sub_value['title_d'].'</h3>'; 
			}

            /* Link D */
            if( trim($sub_value['link_d']) !=  "" ){ 
                $title_d .= '<br />'; 
                $title_d .= '<a href="'.$sub_value['link_d'].'" target="_blank">'.$sub_value['link_d'].'</a>'; 
            }


            /* Description D */
			if( trim($sub_value['description_d']) !=  "" ){ 
			    $title_d .= '<div>'.$sub_value['description_d'].'</div>'; 
                
			}

            if( !empty($title_a) ){
                $title_a .= '<hr />'; 
            }
            if( !empty($title_b) ){
                $title_b .= '<hr />'; 
            }
            if( !empty($title_c) ){
                $title_c .= '<hr />'; 
            }
            if( !empty($title_d) ){
                // $title_d .= '<hr />'; 
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
           $build_results .= trim($title_b) ;
           $build_results .= trim($title_c) ;
           $build_results .= trim($title_d) ;
           $build_results = trim($build_results) ;

            

           

         if( !empty($build_results) && trim($build_results) != "" ){
				 echo $title_wraper_open;
				 echo $build_results; 
				 echo $title_wraper_close;
            }else{
                 echo  "<h2>No Content For This Day.</h2>";
            }

            

            

            

		}

		}

        // Do something, but make sure you escape the value if outputting directly...

    // End loop.

    $jj++;

    endwhile;

// No value.

else :

    // Do something...

endif;

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

add_shortcode('PCX-get-location', 'topics_video_fucn');









