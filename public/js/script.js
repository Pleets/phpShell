$(function(){
	$("#frm-shell").submit(function(event){
		
		event.preventDefault();

		var input = $(this).find("input");

		var shell = $(this).parent().parent().parent();
		var data = $(this).serializeArray();

		$.ajax({
			url: "adapter.php",
			type: 'post',
			data: data,
			beforeSend: function() {
				input.attr("readonly", "readonly");
			},
			success: function(response) {
				shell.children(".body").append("<div>$: " + data[0]["value"] + "</div>");
				shell.children(".body").append("<div>" + response + "</div>");
				input.val("");
				$('.shell')[0].scrollTop = 9999999;
			},
			complete: function() {
				input.removeAttr("readonly");
			}
		});

	});
});
