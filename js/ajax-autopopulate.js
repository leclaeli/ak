jQuery(document).ready(function($) {
 
    // Add default 'Select one'
    // $( '#acf-field-country' ).prepend( $('<option></option>').val('0').html('Select Country').attr({ selected: 'selected', disabled: 'disabled'}) );
 
    /**
     * Get Associated Interests
     */
    
    $( '#acf-prog_categories .selectit input' ).change(function () {

 
        var interests_categories = []; // Selected value
 
        // Get selected value
        $( '#acf-prog_categories .selectit input:checked' ).each(function() {
            var checked_value = $(this);
            interests_categories.push(checked_value.val());
        });
 
        // $( '#acf-field-area' ).attr( 'disabled', 'disabled' );
 
        // If default is not selected get areas for selected country
        if( interests_categories.length != 0 ) {
            // Send AJAX request
            data = {
                action: 'pi_add_interests',
                pi_nonce: pi_vars.pi_nonce,
                interest_cats: interests_categories,
            };
 
            // Get response and populate area select field
            $.post( ajaxurl, data, function(response) {
 
                if( response ){
                   // alert( ajaxurl );
                //     //console.log(response);
                    $('#acf-associated_interests .relationship_left .relationship_list li').remove();
                    // Disable 'Select Area' field until country is selected
                    // $( '#acf-associated_interests .b1' ).html( $('<option></option>').val('0').html('Select Area').attr({ selected: 'selected', disabled: 'disabled'}) );
 
                    //$('#acf-associated_interests');
                    //Add areas to select field options
                    $.each(response['name'], function(index, text) {
                        var id = response['id'][index];
                        var intUrl = response['url'][index];
                        $( '#acf-associated_interests .relationship_left .relationship_list' ).append('<li><a data-post_id="' +id + '" href="' + intUrl + '">' + text + '<span class="relationship-item-info"></span><span class="acf-button-add"></span></a></li>');
                    });
                    //     // $( '#acf-associated_interests .has-search .relationship_left .relationship_list' ).prepend( $('<li></li>').val(text).html(text) );
                    // });

                    // $('#acf-associated_interests .relationship_right li a').each(function(index, el) {
                    //     var dataPostId = $(el).attr('data-post_id');
                    //     console.log(dataPostId);
                    // });
 
                    // Enable 'Select Area' field
                    // $( 'acf-associated_interests .b1' ).removeAttr( 'disabled' );
                }
            });
        }
    });
    
    // $(document).ajaxComplete( function( event, XMLHttpRequest, ajaxOptions ){
    //     var str = ajaxOptions.data;
    //     var search_string = "acf%2Flocation%2Fmatch_field_groups_ajax";
    //     if (str.search(search_string) > 0) {
    //         $('#acf_88').removeClass('acf-hidden');
    //     }
    // });
});

