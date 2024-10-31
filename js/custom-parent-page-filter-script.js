
var cur_obj = '';
var cur_len = 0;
var pre_obj = '';
var pre_len = 0; 
var ctr = 0;
jQuery(document).ready(function(){	

	jQuery("#parentId > option").each(function() {

        if(ctr){
            cur_obj = this;
            next_obj = jQuery(cur_obj).next();

            cur_text = jQuery(cur_obj).text();
            next_text = jQuery(next_obj).text();
			
            cur_len = cur_text.match(/^\xa0*/)[0].length; 
			
            next_len = next_text.match(/^\xa0*/)[0].length; 
            
            if(cur_len >= next_len){

	           jQuery(cur_obj).remove();
            }

            if(cur_len == 0 && next_obj == "undefined" ){

	           jQuery(cur_obj).remove();
            }
			if(cur_len > 0 ){
					cur_text = cur_text.replace(/\s\s/g,'-');
					jQuery(cur_obj).text(cur_text);
				
				}
				
			}
				   
        else{

	       ctr++;
        }

    });
    
});


