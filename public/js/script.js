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
			dataType: 'json',
			error: function(a,b,c) {
				alert(b);
			},
			beforeSend: function() {
				input.attr("readonly", "readonly");
			},
			success: function(response) 
			{
				shell.children(".body").append("<div>$: " + input.val() + "</div>");
				shell.children(".body").append("<div>" + response["message"] + "</div>");
				input.val(""); $("#prompt").text(response["path"]+"#");
				$('.shell')[0].scrollTop = 9999999;
			},
			complete: function() {
				input.removeAttr("readonly");
			}
		});

	});
});
