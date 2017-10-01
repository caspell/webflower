( function( $ ) {

	'use strict';

	// if ( typeof webflow === 'undefined' || webflow === null ) {
	// 	return;
	// }

	var qrControl = (function($container, counter){

		var $table = $container.find("table:first tbody");

		$container.find("#btn_add").click(function(){
			var row = $container.find("#row-template").html();
			var $rows = $table.find("tr:not(.dummy)");
			$table.find("tr.dummy").hide();
			var length = $rows.length + 1;
			row = row.replace('{{num}}', length);

			$container.find(counter).val(length);
			$table.append(row);
		});

		$container.delegate(".row-delete", "click", function(){
			$(this).closest('tr').remove();
			var $rows = $table.find("tr:not(.dummy)");
			var length = $rows.length + 1


			$container.find(counter).val($rows.length);

			$rows.find(".qnumber").each(function(i, o){
				$(this).text(i + 1);
			});
		});
	});

	qrControl($("#question-panel"), "#qcount");
	qrControl($("#result-panel"), "#rcount");


} )( jQuery );
