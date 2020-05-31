<?php
class WooImages{
    
    public function __construct() {
    }
    function InsertImage($idpost,$urlimage){
        $args=explode('/',$urlimage);
        $name=$args[count($args)-1];
        $extension=explode('.',$name);        
        global $user_ID;
       $attachment = array(
           'guid'=> $urlimage, 
           'post_mime_type' => 'image/'.$extension[1],
           'post_title' => $name,
           'post_status' => 'inherit',
           'post_author' => $user_ID,
           'post_type' => 'attachment',
         );
         $image_id = wp_insert_attachment($attachment, $name, $idpost);
        require_once( ABSPATH .'wp-admin/includes/image.php');
        require_once( ABSPATH .'wp-admin/includes/file.php');
        require_once( ABSPATH .'wp-admin/includes/media.php');
        // Generate the metadata for the attachment, and update the database record.
          $attach_data = wp_generate_attachment_metadata($image_id,$urlimage);  
          $upload_overrides = array( 'test_form' => false );
          $urlimage=file_get_contents($urlimage);          
          //var_dump($urlimage);
          $uploads = wp_upload_dir();
          $fp = fopen($uploads,"w");
          fwrite($fp, $urlimage);
           fclose($fp);
         // $movefile = wp_handle_upload( $urlimage, $upload_overrides );  
         // var_dump($movefile);            
          wp_update_attachment_metadata( $image_id,$attach_data);
          $width = 150;
          $height = 150;
          $image_html = wp_get_attachment_image( $image_id, array( $width, $height ), false, array( 'alt' => '' ) );
          //preg_replace( $pattern, $image_html, $post_content );
          set_post_thumbnail( $idpost, $image_id );
         
       $post_with_imported_images = array(
         'ID'           => $idpost,
         'post_content' => $post_content,
       );
      $updated= wp_update_post( $post_with_imported_images);
     return $image_id;
    }
    function UploadImage($url,$idpost){
        require_once( ABSPATH .'wp-admin/includes/image.php');
        require_once( ABSPATH .'wp-admin/includes/file.php');
        require_once( ABSPATH .'wp-admin/includes/media.php');
        $media=media_sideload_image($url,$idpost);                    
        $url2= explode(" ",$media);
        $url2= explode("'",$url2[1]);
        $url2= $url2[1];        
        $args=explode('/',$url);
        $name=$args[count($args)-1];
        $extension=explode('.',$name);        
        global $user_ID;       
        $attachment = array(
            'guid'=> $url2, 
            'post_mime_type' => 'image/'.$extension[1],
            'post_title' => $name,
            'post_status' => 'inherit',
            'post_author' => $user_ID,
            'post_type' => 'attachment',
          );
          $image_id = wp_insert_attachment($attachment, $url2, $idpost); 
          $width = 150;
          $height = 150;
          $image_html = wp_get_attachment_image( $image_id, array( $width, $height ), false, array( 'alt' => '' ) );
          preg_replace( $pattern, $image_html, $post_content );
          set_post_thumbnail( $idpost, $image_id );
          return $image_id;
    }
    function UploadImage3($urlimage,$post_id){
     //var_dump($urlimage);
      // magic sideload image returns an HTML image, not an ID
      $media = media_sideload_image($urlimage, $post_id);
      //var_dump($media);
      // therefore we must find it so we can set it as featured ID
      if(!empty($media) && !is_wp_error($media)){
          $args = array(
              'post_type' => 'attachment',
              'posts_per_page' => -1,
              'post_status' => 'any',
              'post_parent' => $post_id
          );
          // reference new image to set as featured
          $attachments = get_posts($args);

          if(isset($attachments) && is_array($attachments)){
              foreach($attachments as $attachment){
                  // grab source of full size images (so no 300x150 nonsense in path)
                  $image = wp_get_attachment_image_src($attachment->ID, 'full');
                  //var_dump($image);                 
                  // determine if in the $media image we created, the string of the URL exists
                  if(strpos($media, $image[0]) !== false){
                      // if so, we found our image. set it as thumbnail
                      set_post_thumbnail($post_id, $attachment->ID);
                      return $attachment->ID;
                      // only want one image
                      //break;
                  }
              }
              //
          }
      }
      return 0;
    }
}?>