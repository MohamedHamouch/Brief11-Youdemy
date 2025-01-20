const navButtons = document.querySelectorAll('.dashboard-nav-btn');
const contentSections = document.querySelectorAll('.contentSection');

navButtons.forEach(button => {
  button.addEventListener('click', () => {
    const sectionId = button.dataset.section;

    navButtons.forEach(btn => {
      btn.classList.remove('text-orange-600', 'border-b-2', 'border-orange-500');
      btn.classList.add('text-gray-600');
    });

    button.classList.remove('text-gray-600');
    button.classList.add('text-orange-600', 'border-b-2', 'border-orange-500');

    contentSections.forEach(section => {
      section.classList.add('hidden');
      section.classList.remove('flex');
    });

    const selectedSection = document.querySelector(`#${sectionId}`);
    selectedSection.classList.remove('hidden');
    if (sectionId != 'statistics') {
      selectedSection.classList.add('flex');
    }
  });
});

//users section 
const roleInput = document.querySelector('#roleInput');
const filterForm = document.querySelector('#filterForm');
filterForm.addEventListener('change', function () {
  filterForm.submit();
});


//tags section
const editTagPopup = document.querySelector('#editTagPopup');
const editTagForm = document.querySelector('#editTagForm');
const editTagId = document.querySelector('#editTagId');
const editTagName = document.querySelector('#editTagName');
const closeTagButton = document.querySelector('#closeEditTagPopup');

document.querySelectorAll('.edit-tag-btn').forEach(button => {
  button.addEventListener('click', () => {
    const tagId = button.dataset.tagId;
    const tagName = button.dataset.tagName;

    editTagId.value = tagId;
    editTagName.value = tagName;
    editTagPopup.classList.remove('hidden');
    editTagPopup.classList.add('flex');
  });
});

closeTagButton.addEventListener('click', () => {
  editTagPopup.classList.add('hidden');
  editTagPopup.classList.remove('flex');
});




// Category Section
const editCategoryPopup = document.querySelector('#editCategoryPopup');
const editCategoryForm = document.querySelector('#editCategoryForm');
const editCategoryId = document.querySelector('#editCategoryId');
const editCategoryName = document.querySelector('#editCategoryName');
const editCategoryDescription = document.querySelector('#editCategoryDescription');
const closeCategoryButton = document.querySelector('#closeEditCategoryPopup');

document.querySelectorAll('.edit-category-btn').forEach(button => {
  button.addEventListener('click', function () {
    const categoryId = this.dataset.categoryId;
    const categoryName = this.dataset.categoryName;
    const categoryDescription = this.dataset.categoryDescription;

    editCategoryId.value = categoryId;
    editCategoryName.value = categoryName;
    editCategoryDescription.value = categoryDescription;

    editCategoryPopup.classList.remove('hidden');
    editCategoryPopup.classList.add('flex');
  });
});

closeCategoryButton.addEventListener('click', () => {
  editCategoryPopup.classList.add('hidden');
  editCategoryPopup.classList.remove('flex');
});
