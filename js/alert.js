
document.addEventListener('DOMContentLoaded', () => {
	forms = document.querySelectorAll('.deleteForm');

	forms.forEach(form => {
		form.addEventListener('submit', function (event) {
			event.preventDefault();

			Swal.fire({
				title: '<h2 class="text-orange-600 text-lg font-semibold">Confirm Deletion</h2>',
				html: '<p class="text-gray-600 text-sm">This action cannot be undone.</p>',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#FF5722', // Youdemy's primary theme color
				cancelButtonColor: '#4CAF50', // Matching secondary theme color
				confirmButtonText: 'Delete',
				cancelButtonText: 'Cancel',
				customClass: {
					popup: 'rounded-lg shadow-lg px-6 py-4 text-sm', // Smaller size
					title: 'text-lg', // Match Youdemy font sizing
					confirmButton: 'px-4 py-2 text-white font-semibold bg-orange-500 hover:bg-orange-600',
					cancelButton: 'px-4 py-2 text-white font-semibold bg-green-500 hover:bg-green-600',
				},
			}).then((result) => {
				if (result.isConfirmed) {
					event.target.submit();
				}
			});
		});
	});
});