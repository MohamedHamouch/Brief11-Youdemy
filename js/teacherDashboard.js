
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
    selectedSection.classList.add('flex');
  });
});

