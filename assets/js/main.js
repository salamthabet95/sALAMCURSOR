// Preview Template Switching with Dynamic Data
document.addEventListener('DOMContentLoaded', function() {
  const previewButtons = document.querySelectorAll('.preview-btn');
  const previewDisplay = document.getElementById('previewDisplay');
  const previewCity = document.getElementById('previewCity');
  const previewLoading = document.getElementById('previewLoading');
  let currentTemplate = '1';
  let currentCity = 'رام الله';
  let prayerTimesData = null;

  // Load prayer times on page load
  loadPrayerTimes(currentCity);

  // City selector change
  if (previewCity) {
    previewCity.addEventListener('change', function() {
      currentCity = this.value;
      loadPrayerTimes(currentCity);
    });
  }

  // Template buttons
  if (previewButtons.length > 0) {
    previewButtons.forEach(btn => {
      btn.addEventListener('click', function() {
        previewButtons.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        currentTemplate = this.getAttribute('data-template');
        updatePreviewTemplate(currentTemplate);
      });
    });
  }

  // Load prayer times from API
  function loadPrayerTimes(city) {
    if (previewLoading) previewLoading.style.display = 'block';
    
    fetch(`api/preview-data.php?city=${encodeURIComponent(city)}`)
      .then(response => response.json())
      .then(data => {
        if (data.success && data.prayer_times) {
          prayerTimesData = data;
          updatePreviewTable(data.prayer_times);
          updateCityName(city);
          if (previewLoading) previewLoading.style.display = 'none';
        } else {
          console.error('Failed to load prayer times:', data.message);
          if (previewLoading) previewLoading.style.display = 'none';
        }
      })
      .catch(error => {
        console.error('Error loading prayer times:', error);
        if (previewLoading) previewLoading.style.display = 'none';
      });
  }

  // Update preview table with prayer times
  function updatePreviewTable(prayerTimes) {
    const tbody = document.getElementById('previewTableBody');
    if (!tbody) return;

    tbody.innerHTML = '';
    
    prayerTimes.forEach((day, index) => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${day.day}</td>
        <td>${day.hijri_day} ${day.hijri_month}</td>
        <td class="imsak-time">${day.imsak}</td>
        <td class="fajr-time">${day.fajr}</td>
        <td class="maghrib-time">${day.maghrib}</td>
      `;
      tbody.appendChild(row);
    });
  }

  // Update city name in preview
  function updateCityName(city) {
    const cityNameEl = document.querySelector('.preview-city-name');
    if (cityNameEl) {
      cityNameEl.textContent = city;
    }
  }

  // Update preview template style
  function updatePreviewTemplate(templateNum) {
    const preview = document.getElementById('imsakiyaPreview');
    if (!preview) return;

    const templates = {
      1: {
        name: 'Corporate Clean',
        class: 'template-1',
        bgColor: '#ffffff',
        textColor: '#2c5530',
        headerBg: '#2c5530',
        headerText: '#ffffff'
      },
      2: {
        name: 'Modern Ramadan',
        class: 'template-2',
        bgColor: 'linear-gradient(135deg, #2c5530 0%, #1e3a21 100%)',
        textColor: '#ffffff',
        headerBg: 'rgba(212, 175, 55, 0.3)',
        headerText: '#ffffff'
      },
      3: {
        name: 'Traditional Arabic',
        class: 'template-3',
        bgColor: '#f5f5dc',
        textColor: '#8b4513',
        headerBg: '#8b4513',
        headerText: '#f5f5dc'
      }
    };

    const template = templates[templateNum];
    if (!template) return;

    preview.className = `imsakiya-preview ${template.class}`;
    preview.style.background = template.bgColor;
    preview.style.color = template.textColor;
    
    // Update header
    const header = preview.querySelector('.preview-header');
    if (header) {
      header.style.background = template.headerBg;
      header.style.color = template.headerText;
      header.style.borderColor = template.textColor;
    }

    // Update table
    const table = preview.querySelector('.preview-table');
    if (table) {
      const th = table.querySelectorAll('th');
      th.forEach(th => {
        th.style.background = template.headerBg;
        th.style.color = template.headerText;
      });
    }
  }

  // Initial template update
  updatePreviewTemplate(currentTemplate);
});

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
