
jQuery(document).ready(function() {
	
	jQuery('.sagepaycw-transaction-table .sagepaycw-more-details-button').each(function() {
		jQuery(this).click(function() {
			
			// hide all open 
			jQuery('.sagepaycw-transaction-table').find('.active').removeClass('active');
			
			// Get transaction ID
			var mainRow = jQuery(this).parents('.sagepaycw-main-row');
			var transactionId = mainRow.attr('id').replace('sagepaycw-_main_row_', '');
			
			var selector = '.sagepaycw-transaction-table #sagepaycw_details_row_' + transactionId;
			jQuery(selector).addClass('active');
			jQuery(mainRow).addClass('active');
		})
	});
	
	jQuery('.sagepaycw-transaction-table .sagepaycw-less-details-button').each(function() {
		jQuery(this).click(function() {
			// hide all open 
			jQuery('.sagepaycw-transaction-table').find('.active').removeClass('active');
		})
	});
	
	jQuery('.sagepaycw-transaction-table .transaction-information-table .description').each(function() {
		jQuery(this).mouseenter(function() {
			jQuery(this).toggleClass('hidden');
		});
		jQuery(this).mouseleave(function() {
			jQuery(this).toggleClass('hidden');
		})
	});
	
});