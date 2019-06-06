<?php
/**
 *
 * Show the product details page
 *
 * @package    VirtueMart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_reviews.php 6300 2012-07-26 00:40:10Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die ('Restricted access');

function rating_show($rate){ ?>
	<div class="vote">
		<span id="rating-prod" class="rating-icons">
			<?php 
		    for ($i = 1; $i <= 5 ; $i ++ ) { 		                            	
		    	if ($i <= $rate) {
		    		echo '<i class="rating_icon_' . $i . ' fa fa-star"></i> ';
		    	} else {
		    		echo '<i class="rating_icon_' . $i . ' fa fa-star-o"></i> ';
		    	} 
		    } ?>
    	</span>
    </div>
    <?php                  	
}

// Customer Reviews
if ($this->allowRating || $this->showReview) {
	$maxrating = VmConfig::get ('vm_maximum_rating_scale', 5);
	$ratingsShow = VmConfig::get ('vm_num_ratings_show', 3); // TODO add  vm_num_ratings_show in vmConfig
	$showall = JRequest::getBool ('showall', FALSE);

	/*$stars = array();
	$ratingWidth = $maxrating * 24;
	for ($num = 0; $num <= $maxrating; $num++) {
		$stars[] = '
				<span title="' . (JText::_ ("COM_VIRTUEMART_RATING_TITLE") . $num . '/' . $maxrating) . '" class="vmicon ratingbox" style="display:inline-block;width:' . 24 * $maxrating . 'px;">
					<span class="stars-orange" style="width:' . (24 * $num) . 'px">
					</span>
				</span>';
	}*/ ?>

	<div class="product-reviews product-section">
		<form method="post" action="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id, FALSE); ?>" name="reviewForm" id="reviewform">
	<?php }

	if ($this->showReview) { ?>
	<h4 class="product-section_title hidden"><?php echo JText::_ ('COM_VIRTUEMART_REVIEWS') ?></h4>

	<div class="product-reviews_list">
		<?php
		$i = 0;
		$review_editable = TRUE;
		$reviews_published = 0;

		if ($this->rating_reviews) {
			foreach ($this->rating_reviews as $review) {
				if ($i % 2 == 0) {
					$color = 'normal';
				} else {
					$color = 'highlight';
				}

				/* Check if user already commented */
				// if ($review->virtuemart_userid == $this->user->id ) {
				if ($review->created_by == $this->user->id && !$review->review_editable) {
					$review_editable = FALSE;
				} ?>

				<?php // Loop through all reviews
				if (!empty($this->rating_reviews) && $review->published) {
					$reviews_published++; ?>

					<div class="review <?php echo $color ?>">
						<!-- <span class="vote"><?php /*echo $stars[(int)$review->review_rating]*/ ?></span> -->
						<span class="review_author"><?php echo $review->customer ?></span>
						<?php rating_show($review->review_rates); ?>
						<span class="review_date"><i class="fa fa-calendar"></i> <?php echo JHTML::date ($review->created_on, JText::_ ('DATE_FORMAT_LC')); ?></span>
						<div class="blockquote">
							<blockquote><?php echo $review->comment; ?></blockquote>
						</div>
						
					</div>

					<?php }
				$i++;
				if ($i == $ratingsShow && !$showall) {
					/* Show all reviews ? */
					if ($reviews_published >= $ratingsShow) {
						$attribute = array('class'=> 'details', 'title'=> JText::_ ('COM_VIRTUEMART_MORE_REVIEWS'));
						echo JHTML::link ($this->more_reviews, JText::_ ('COM_VIRTUEMART_MORE_REVIEWS'), $attribute);
					}
					break;
				}
			}
		} else {
			// "There are no reviews for this product"
			?>
			<span class="step"><?php echo JText::_ ('COM_VIRTUEMART_NO_REVIEWS') ?></span>
			<?php
		}  ?>
	</div>

	<?php // Writing A Review
	if ($this->allowReview) { 
		$reviewJavascript = "
				jQuery(document).ready(function () {
					var steps = ".$maxrating.";
					jQuery('.ratintbox .rating-icons').mousemove(function(e){
						var parentPos= jQuery('.ratintbox .rating-icons').position();
						//var boxWidth = jQuery('#rating-prod').width();// nbr of total pixels
						var boxWidth = jQuery('.ratintbox .rating-icons').width();
						//alert(boxWidth);
						var starSize = (boxWidth/steps);
						var ratingboxPos= jQuery('.ratintbox .rating-icons').offset();
						var span = jQuery(this).children();
						var dif = e.pageX-ratingboxPos.left; // nbr of pixels
						//alert(difRatio);
						difRatio = Math.floor(dif/boxWidth* steps )+1; //step
						//span.width(difRatio);
						
						span.addClass('fa-star-o');

						//span.eq(difRatio - 1).toggleClass('fa-star').toggleClass('fa-star-o');

						for ( var i = 1; i <= difRatio; i++ ) {
							var active_span = jQuery(this).find('i.rating_icon_'+i);
						    //active_span.addClass('material-design-bookmark45 star0');
						   active_span.removeClass('fa-star-o');
						}

						jQuery('#vote').val(difRatio);
						console.log('note = ',difRatio);
					});
				});
				";
				vmJsApi::addJScript( 'rating_stars', $reviewJavascript );
		?>
		<div class="product-reviews-form">

			<?php 
				if ($this->showRating) {
				if ($this->allowRating && $review_editable) {
					?>
					<p><?php echo JText::_ ('COM_VIRTUEMART_WRITE_REVIEW')  ?> <span><?php echo JText::_ ('COM_VIRTUEMART_WRITE_FIRST_REVIEW') ?></span></p>
					<span class="step"><?php echo JText::_ ('COM_VIRTUEMART_RATING_FIRST_RATE') ?></span>
					<span class="ratintbox">
						<?php rating_show($maxrating); ?>
					</span>

					<input type="hidden" id="vote" value="<?php echo $maxrating ?>" name="vote">

					<!-- <div class="rating">						
						<label for="vote"><?php /*echo $stars[$maxrating];*/ ?></label> 					
					</div> -->

					<?php
				}
			}
			$reviewJavascript = "
				function check_reviewform() {

				var form = document.getElementById('reviewform');
				var ausgewaehlt = false;

					if (form.comment.value.length < ".VmConfig::get( 'reviews_minimum_comment_length', 100 ).") {
						alert('".addslashes( vmText::sprintf( 'COM_VIRTUEMART_REVIEW_ERR_COMMENT1_JS', VmConfig::get( 'reviews_minimum_comment_length', 100 ) ) )."');
						return false;
					}
					else if (form.comment.value.length > ".VmConfig::get( 'reviews_maximum_comment_length', 2000 ).") {
						alert('".addslashes( vmText::sprintf( 'COM_VIRTUEMART_REVIEW_ERR_COMMENT2_JS', VmConfig::get( 'reviews_maximum_comment_length', 2000 ) ) )."');
						return false;
					}
					else {
						return true;
					}
				}

				function refresh_counter() {
					var form = document.getElementById('reviewform');
					form.counter.value= form.comment.value.length;
				}
				";
				vmJsApi::addJScript( 'check_reviewform', $reviewJavascript );// Show Review Length While Your Are Writing
			
			$document = JFactory::getDocument ();
			
		
			if ($review_editable) { ?>
				<div class="form-group">
					<label>
					<?php echo JText::sprintf ('COM_VIRTUEMART_REVIEW_COMMENT', VmConfig::get ('reviews_minimum_comment_length', 100), VmConfig::get ('reviews_maximum_comment_length', 2000)); ?>
					</label>
					<textarea class="virtuemart" title="<?php echo JText::_ ('COM_VIRTUEMART_WRITE_REVIEW') ?>" class="inputbox" id="comment" onblur="refresh_counter();" onfocus="refresh_counter();" onkeyup="refresh_counter();" name="comment" rows="5" cols="60"><?php if (!empty($this->review->comment)) {
						echo $this->review->comment;
					} ?></textarea>
				</div>

				<button class="btn btn-primary button" type="submit" onclick="return( check_reviewform());" name="submit_review" title="<?php echo JText::_ ('COM_VIRTUEMART_REVIEW_SUBMIT')  ?>" value=""><span><?php echo JText::_ ('COM_VIRTUEMART_REVIEW_SUBMIT')  ?></span></button>

				<div class="chars-count form-inline">
					<label><?php echo JText::_ ('COM_VIRTUEMART_REVIEW_COUNT') ?></label>
					<input type="text" value="0" size="3" class="vm-default" name="counter" maxlength="3" readonly="readonly"/>
				</div>
				<?php
			} else {
				echo '<strong>' . JText::_ ('COM_VIRTUEMART_DEAR') . $this->user->name . ',</strong><br />';
				echo JText::_ ('COM_VIRTUEMART_REVIEW_ALREADYDONE');
			}
			?></div><?php
	}
}

if ($this->allowRating || $this->showReview) {
	?>
	<input type="hidden" name="virtuemart_product_id" value="<?php echo $this->product->virtuemart_product_id; ?>"/>
	<input type="hidden" name="option" value="com_virtuemart"/>
	<input type="hidden" name="virtuemart_category_id" value="<?php echo JRequest::getInt ('virtuemart_category_id'); ?>"/>
	<input type="hidden" name="virtuemart_rating_review_id" value="0"/>
	<input type="hidden" name="task" value="review"/>
		</form>
	</div>
	<?php
}
