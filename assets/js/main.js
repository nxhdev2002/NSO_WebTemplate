$(document).ready(function () {});

$(document).on("click", ".checkout", function () {
	var itemId = $(this).data("id");
	var itemTitle = $(this).data("title");
	Swal.fire({
		title: "Mua " + itemTitle,
		input: "number",
		inputAttributes: {
			autocapitalize: "off",
			placeholder: "Số lượng..",
		},
		showCancelButton: true,
		confirmButtonText: "Thanh toán",
		showLoaderOnConfirm: true,
		preConfirm: quantity => {
			var details = {
				quantity: quantity,
				id: itemId,
			};
			var formBody = [];
			for (var property in details) {
				var encodedKey = encodeURIComponent(property);
				var encodedValue = encodeURIComponent(
					details[property]
				);
				formBody.push(encodedKey + "=" + encodedValue);
			}
			formBody = formBody.join("&");
			return fetch("/checkout.php", {
				method: "POST",
				headers: {
					"Content-Type":
						"application/x-www-form-urlencoded;charset=UTF-8",
				},
				body: formBody,
			})
				.then(response => {
					if (!response.ok) {
						throw new Error(
							response.statusText
						);
					}
					return response.json();
				})
				.catch(error => {
					Swal.showValidationMessage(
						`Request failed: ${error}`
					);
				});
		},
		allowOutsideClick: () => !Swal.isLoading(),
	}).then(result => {
		if (result.isConfirmed) {
			if (result.value.success) {
				showAutoCloseDialog(
					"Thành công",
          result.value.message
				);
			} else {
        showErrorDialog(
          result.value.message
        )
      }
		}
	});
});

const showErrorDialog = message => {
	Swal.fire({
		icon: "error",
		title: "Oops...",
		text: message,
	});
};

const showAutoCloseDialog = (title, message, callback) => {
	let timerInterval;
	Swal.fire({
		title: title,
		html: message,
		timer: 2000,
		timerProgressBar: true,
		didOpen: () => {
			Swal.showLoading();
			const b = Swal.getHtmlContainer().querySelector("b");
			timerInterval = setInterval(() => {
				b.textContent = Swal.getTimerLeft();
			}, 100);
		},
		willClose: () => {
			clearInterval(timerInterval);
		},
	}).then(callback);
};
