// Preview Template Switching
document.addEventListener('DOMContentLoaded', function() {
  const previewButtons = document.querySelectorAll('.preview-btn');
  const previewDisplay = document.getElementById('previewDisplay');

  if (previewButtons.length > 0) {
    previewButtons.forEach(btn => {
      btn.addEventListener('click', function() {
        // Remove active class from all buttons
        previewButtons.forEach(b => b.classList.remove('active'));
        // Add active class to clicked button
        this.classList.add('active');
        
        const template = this.getAttribute('data-template');
        updatePreview(template);
      });
    });
  }
});

function updatePreview(templateNum) {
  const previewDisplay = document.getElementById('previewDisplay');
  const templates = {
    1: {
      name: 'Corporate Clean',
      class: 'template-1',
      style: 'background: #ffffff; color: #2c5530;'
    },
    2: {
      name: 'Modern Ramadan',
      class: 'template-2',
      style: 'background: linear-gradient(135deg, #2c5530 0%, #1e3a21 100%); color: #ffffff;'
    },
    3: {
      name: 'Traditional Arabic',
      class: 'template-3',
      style: 'background: #f5f5dc; color: #8b4513; border: 2px solid #8b4513;'
    }
  };

  const template = templates[templateNum];
  const previewContent = previewDisplay.querySelector('.imsakiya-preview');
  
  if (previewContent) {
    previewContent.className = `imsakiya-preview ${template.class}`;
    previewContent.setAttribute('style', template.style);
  }
}

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      target.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
    }
  });
});
