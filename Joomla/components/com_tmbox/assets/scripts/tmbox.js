function TmboxAddToCompare(product_id) { 
	jQuery.ajax({
		url: '?option=com_tmbox&view=comparelist&task=add',
		type: 'post',
		data: 'product_id=' + product_id,
		dataType: 'json',
		success: function(json){
		  // jQuery('#system_view_overlay').hide();
			if(json){
				jQuery('.modalTmbox').show().html('<div class="success '+json.success+'"><div class="success_wishlist">' + json.message + '<div class="wishlist_product_name">' + json.title + '</div>' + json.message2 + '</div>'+ json.btngocompare +'<div class="close"><i class="fa fa-times"></i></div></div>');
				jQuery('.modalTmbox').fadeIn('slow');
			}
			jQuery('.list_compare'+product_id+' a').addClass('active');
			if(json.totalcompare==4){
				jQuery('.list_compare'+product_id+' a').removeClass('active');
			}
			
			 jQuery('.success .close , .body__').click(function () {
				//jQuery('.modalTmbox').hide();
				jQuery('.modalTmbox').fadeOut('slow');
             });
            setTimeout(function() { 
        		jQuery( ".success .close" ).on( "click", function() {
        			//alert('ddf');
				 	jQuery('.modalTmbox').fadeOut('slow');
				});
				jQuery( ".success .close" ).trigger( "click" );
            }, 4000);
         	
		}
			
	});
}

 function TmboxRemoveCompare(remove_id) { 
	jQuery.ajax({
		url: '?option=com_tmbox&view=comparelist&task=removed',
		type: 'post',
		data: 'remove_id=' + remove_id,
		dataType: 'json',
		success: function(json){
				var countcolumn = json.totalrem+1;
				//alert(countcolumn);
				jQuery('.key'+remove_id).remove();
				jQuery('.table-responsive').find('.table-bordered').removeClass('column' + countcolumn);
				jQuery('.table-responsive').find('.table-bordered').addClass('column' + json.totalrem);
				if(json.totalrem<1){
					jQuery('.compare_box .compare.no-products').fadeIn('slow');
					jQuery(".table-responsive").remove();
				}
			}
	});
}


  function TmboxAddToWishlist(product_id) { 
	jQuery.ajax({
		url: 'index.php?option=com_tmbox&view=mywishlist&task=add',
		type: 'post',
		data: 'product_id=' + product_id,
		dataType: 'json',
		success: function(json){
			
			jQuery('.AjaxPreloaderC').hide();
		  	// jQuery('#system_view_overlay').hide();
		 	 jQuery('.list_wishlists'+product_id+' a').addClass('active');
			if(json){
				jQuery('.modalTmbox').show().html('<div class="success '+json.success+'"><div class="success_wishlist">' + json.message + '<div class="wishlist_product_name">' + json.title + '</div>' + json.message2 + '</div>'+ json.btngomywishlist +'<div class="close"><i class="fa fa-times"></i></div></div>');
				jQuery('.modalTmbox').fadeIn('slow');
			}
			 jQuery('.success .close , .body__').click(function () {
				jQuery('.modalTmbox').fadeOut('slow');
             });
             setTimeout(function() { 
        		jQuery( ".success .close" ).on( "click", function() {
        			//alert('ddf');
				 	jQuery('.modalTmbox').fadeOut('slow');
				});
				jQuery( ".success .close" ).trigger( "click" );
            }, 4000);
		//alert(json.message);
		}
	});
}

 function TmboxRemoveWishlists(remove_id) { 
	jQuery.ajax({
		url: '?option=com_tmbox&view=mywishlist&task=removed',
		type: 'post',
		data: 'remove_id=' + remove_id,
		dataType: 'json',
		success: function(json){
				jQuery('#wishlists_prod_'+remove_id).remove();
				if(json.totalrem==0){
					jQuery('.wishlist.no-products').fadeIn('slow');
				}
			}
	});
}
jQuery(document).ready(function () {
	//location.reload();
});
