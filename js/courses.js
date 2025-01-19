const categoryInput = document.querySelector('#categorySelect');
const filterForm = document.querySelector('#filterForm');

categoryInput.addEventListener('change', function () {
    filterForm.submit();
});