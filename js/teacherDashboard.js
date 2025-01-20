
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


// Handle content type toggle
const courseType = document.querySelector('#courseType');
const documentContent = document.querySelector('#documentContent');
const videoContent = document.querySelector('#videoContent');

courseType.addEventListener('change', () => {
  if (courseType.value === 'document') {
    documentContent.classList.remove('hidden');
    videoContent.classList.add('hidden');
    courseVideo.removeAttribute('required');
    courseDocument.setAttribute('required', '');
  } else if (courseType.value === 'video') {
    documentContent.classList.add('hidden');
    videoContent.classList.remove('hidden');
    courseDocument.removeAttribute('required');
    courseVideo.setAttribute('required', '');
  } else {
    documentContent.classList.add('hidden');
    videoContent.classList.add('hidden');
    courseDocument.removeAttribute('required');
    courseVideo.removeAttribute('required');
  }
});

const coverInput = document.querySelector('#courseCover');
coverInput.addEventListener('change', (e) => {
  const fileName = e.target.files[0]?.name;
  if (fileName) {
    const fileText = coverInput.parentElement.querySelector('p');
    fileText.textContent = `Selected: ${fileName}`;
  }
});

const videoInput = document.querySelector('#courseVideo');
videoInput.addEventListener('change', (e) => {
  const fileName = e.target.files[0]?.name;
  if (fileName) {
    const fileText = videoInput.parentElement.querySelector('p');
    fileText.textContent = `Selected: ${fileName}`;
  }
});