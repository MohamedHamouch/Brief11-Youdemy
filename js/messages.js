document.addEventListener('DOMContentLoaded', () => {
  const dismissButtons = document.querySelectorAll('.dismiss-button');

  dismissButtons.forEach(button => {
    button.addEventListener('click', (event) => {
      const message = document.querySelector('.message');
      if (message) {
        message.style.display = 'none';
      }
    });
  });
});