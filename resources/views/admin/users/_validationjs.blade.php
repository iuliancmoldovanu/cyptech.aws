<script>
//	$("#frm").submit(function (event) {
//		event.preventDefault();
//		$('.loading').show();
//		var form = $(this);
//		var data = new FormData($(this)[0]);
//		var url = form.attr("action");
//		console.log(form);
//		$.ajax({
//			type: "POST",
//			url: url,
//			data: data,
//			cache: false,
//			contentType: false,
//			processData: false,
//			success: function (data) {
//				if (data.fail) {
//					$('#frm input.required, #frm textarea.required, #frm select.required').each(function () {
//						var index = $(this).attr('name');
//						if (index in data.errors) {
//							$("#form-" + index + "-error").addClass("has-error");
//							$("#" + index + "-error").html(data.errors[index]);
//						}
//						else {
//							$("#form-" + index + "-error").removeClass("has-error");
//							$("#" + index + "-error").empty();
//						}
//					});
//					$('#focus').focus().select();
//				} else {
//					$(".has-error").removeClass("has-error");
//					$(".help-block").empty();
//					ajaxLoad(data.url, data.content);
//				}
//			},
//			error: function (xhr, textStatus, errorThrown) {
//				alert(xhr.responseText);
//			}
//		});
////        $('.loading').hide();
//		return false;
//	});
</script>