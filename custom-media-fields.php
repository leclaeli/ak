<?php
Class Wptuts_Custom_Media_Fields {
 
    private $media_fields = array();
 
    function __construct( $fields ) {
        $this->media_fields = $fields;

        add_filter( 'attachment_fields_to_edit', array( $this, 'applyFilter' ), 11, 2 );
        add_filter( 'attachment_fields_to_save', array( $this, 'saveFields' ), 11, 2 );

    }

 
    public function applyFilter( $form_fields, $post = null ) {

        // If our fields array is not empty
        if ( ! empty( $this->media_fields ) ) {
            // We browse our set of options
            foreach ( $this->media_fields as $field => $values ) {
                // If the field matches the current attachment mime type
                // and is not one of the exclusions
                if ( preg_match( "/" . $values['application'] . "/", $post->post_mime_type) && ! in_array( $post->post_mime_type, $values['exclusions'] ) ) {
                    // We get the already saved field meta value
                    $meta = get_post_meta( $post->ID, '_' . $field, true );
                    
                    // Define the input type to 'text' by default
                    switch ( $values['input'] ) {
                        default:
                        case 'text':
                            $values['input'] = 'text';
                            break;
                     
                        case 'textarea':
                            $values['input'] = 'textarea';
                            break;
                     
                        case 'select':
                     
                            // Select type doesn't exist, so we will create the html manually
                            // For this, we have to set the input type to 'html'
                            $values['input'] = 'html';
                     
                            // Create the select element with the right name (matches the one that wordpress creates for custom fields)
                            $html = '<select name="attachments[' . $post->ID . '][' . $field . ']">';
                     
                            // If options array is passed
                            if ( isset( $values['options'] ) ) {
                                // Browse and add the options
                                foreach ( $values['options'] as $k => $v ) {
                                    // Set the option selected or not
                                    if ( $meta == $k )
                                        $selected = ' selected="selected"';
                                    else
                                        $selected = '';
                     
                                    $html .= '<option' . $selected . ' value="' . $k . '">' . $v . '</option>';
                                }
                            }
                     
                            $html .= '</select>';
                     
                            // Set the html content
                            $values['html'] = $html;
                     
                            break;
                     
                        case 'checkbox':
                     
                            // Checkbox type doesn't exist either
                            $values['input'] = 'html';

                            $html = '';
                     
                        $terms = get_terms( 'media-categories' );
                        $i = 0;
                        $attachment_meta = get_post_custom( $post->ID );
                       
                        if ( !($attachment_meta['_sport_name']) ) {
                            
                        }
                        $attached_customs = $attachment_meta['_sport_name'];
                        $attached_customs_array = unserialize( $attached_customs[0] );
                        

                            foreach ( $terms as $term ) {
                                // Set the checkbox checked or not

                                if ( in_array( $term->term_id, $attached_customs_array ) )
                                    $checked = ' checked="checked"';
                                else
                                    $checked = '';
                         
                                $html .= '<input' . $checked . ' type="checkbox" name="attachments[' . $post->ID . '][' . $field . '][]" id="' . sanitize_key( $field . '_' . $post->ID . '_' . $i ) . '" value="' . $term->term_id . '" /><label>' . $term->name . '</label>';
                                 $i++;
                
                            }
                            $values['html'] = $html;
                            
                            break;
                     
                        case 'radio':
                     
                            // radio type doesn't exist either
                            $values['input'] = 'html';
                     
                            $html = '';
                     
                            if ( ! empty( $values['options'] ) ) {
                                $i = 0;
                     
                                foreach ( $values['options'] as $k => $v ) {
                                    if ( $meta == $k )
                                        $checked = ' checked="checked"';
                                    else
                                        $checked = '';
                     
                                    $html .= '<input' . $checked . ' value="' . $k . '" type="radio" name="attachments[' . $post->ID . '][' . $field . ']" id="' . sanitize_key( $field . '_' . $post->ID . '_' . $i ) . '" /> <label for="' . sanitize_key( $field . '_' . $post->ID . '_' . $i ) . '">' . $v . '</label><br />';
                                    $i++;
                                }
                            }
                     
                            $values['html'] = $html;
                    
                            break;
                    }
     
                    // And set it to the field before building it
                    $values['value'] = $meta;
     
                    // We add our field into the $form_fields array
                    $form_fields[$field] = $values;
                }
            }
        }
        // We return the completed $form_fields array
        return $form_fields;
    }
 
    function saveFields( $post, $attachment ) {
        
        // If our fields array is not empty
        if ( ! empty( $this->media_fields ) ) {
            // Browser those fields
            foreach ( $this->media_fields as $field => $values ) {
                // If this field has been submitted (is present in the $attachment variable)
                if ( isset( $attachment[$field] ) ) {
                    var_dump($attachment[$field]);
                        die;
                    // If submitted field is empty
                    // We add errors to the post object with the "error_text" parameter we set in the options
                    if ( strlen( trim( $attachment[$field] ) ) == 0 ) {
                        var_dump($attachment[$field]);
                        die;
                        $post['errors'][$field]['errors'][] = __( $values['error_text'] );
                        
                    }
                    // Otherwise we update the custom field                   
                    else {
                        $field_name = '';
                        $field_name = '_' . $field; 
                        update_post_meta( $post['ID'], $field_name, $attachment[$field] );
                        //update_post_meta( $post['ID'], '_sport_name', $attachment[$field] );
                        var_dump($attachment[$field]);
                        die;
                    }   

// $fake_array = array(14);
// update_post_meta( 174, '_sport_name', $fake_array );

                }
                // Otherwise, we delete it if it already existed
                else {
                 
                        $field_name = '';
                        $field_name = '_' . $field; 
                    delete_post_meta( $post['ID'], $field_name );
                }
            }
        }
        
        return $post;
    }
 
}
$themename = "asapkids";
$attchments_options = array(
    // 'image_copyright' => array(
    //     'label'       => __( 'Image copyright', $themename ),
    //     'input'       => 'text',
    //     'helps'       => __( 'If your image is protected by copyrights', $themename ),
    //     'application' => 'image',
    //     'exclusions'  => array( 'audio', 'video' ),
    //     'required'    => true,
    //     'error_text'  => __( 'Copyright field required', $themename )
    // ),
    // 'image_author_desc' => array(
    //     'label'       => __( 'Image author description', $themename ),
    //     'input'       => 'textarea',
    //     'application' => 'image',
    //     'exclusions'   => array( 'audio', 'video' ),
    // ),
    'sport_name' => array(
        'label'       => __( 'Sports Cats', $themename ),
        'input'       => 'checkbox',
        'application' => 'image',
        'exclusions'   => array( 'audio', 'video' )
    ),
    // 'image_stars' => array(
    //     'label'       => __( 'Image rating', $themename ),
    //     'input'       => 'radio',
    //     'options' => array(
    //         '0' => 0,
    //         '1' => 1,
    //         '2' => 2,
    //         '3' => 3,
    //         '4' => 4
    //     ),
    //     'application' => 'image',
    //     'exclusions'   => array( 'audio', 'video' )
    // ),
    // 'image_disposition' => array(
    //     'label'       => __( 'Image disposition', $themename ),
    //     'input'       => 'select',
    //     'options' => array(
    //         'portrait' => __( 'portrtait', $themename ),
    //         'landscape' => __( 'landscape', $themename )
    //     ),
    //     'application' => 'image',
    //     'exclusions'   => array( 'audio', 'video' )
    // )
);

$cmf = new Wptuts_Custom_Media_Fields( $attchments_options );
