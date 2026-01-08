// Wizard Step Management
let currentStep = 1;
const totalSteps = 4;

// Palestinian cities list
const palestinianCities = [
  'رام الله', 'نابلس', 'القدس', 'بيت لحم', 'الخليل', 'جنين', 'طولكرم', 'قلقيلية',
  'سلفيت', 'أريحا', 'يافا', 'حيفا', 'عكا', 'الناصرة', 'بئر السبع', 'غزة',
  'خان يونس', 'رفح', 'دير البلح', 'بيت جالا', 'بيت ساحور', 'البيرة', 'بيت لاهيا',
  'جبل النار', 'شعفاط', 'بيت حنينا', 'عناتا', 'أبو ديس', 'الزعيم', 'الرام'
];

// Initialize wizard
document.addEventListener('DOMContentLoaded', function() {
  updateProgress();
  setupCitySearch();
  setupLogoPreview();
  setupTemplateSelection();
  setupFormSummary();
  setupFormSubmit();
});

function nextStep(step) {
  if (validateStep(currentStep)) {
    if (step <= totalSteps) {
      document.getElementById(`step${currentStep}`).classList.remove('active');
      document.querySelector(`.progress-step[data-step="${currentStep}"]`).classList.remove('active');
      document.querySelector(`.progress-step[data-step="${currentStep}"]`).classList.add('completed');
      
      currentStep = step;
      document.getElementById(`step${currentStep}`).classList.add('active');
      document.querySelector(`.progress-step[data-step="${currentStep}"]`).classList.add('active');
      updateProgress();
      
      if (step === 4) {
        updateSummary();
      }
    }
  }
}

function prevStep(step) {
  if (step >= 1) {
    document.getElementById(`step${currentStep}`).classList.remove('active');
    document.querySelector(`.progress-step[data-step="${currentStep}"]`).classList.remove('active');
    document.querySelector(`.progress-step[data-step="${currentStep}"]`).classList.remove('completed');
    
    currentStep = step;
    document.getElementById(`step${currentStep}`).classList.add('active');
    document.querySelector(`.progress-step[data-step="${currentStep}"]`).classList.add('active');
    updateProgress();
  }
}

function updateProgress() {
  // Progress bar logic can be added here
}

function validateStep(step) {
  switch(step) {
    case 1:
      const city = document.getElementById('city').value.trim();
      if (!city) {
        alert('يرجى إدخال اسم المدينة');
        return false;
      }
      return true;
    case 2:
      const companyName = document.getElementById('companyName').value.trim();
      const logo = document.getElementById('companyLogo').files[0];
      if (!companyName) {
        alert('يرجى إدخال اسم الشركة');
        return false;
      }
      if (!logo) {
        alert('يرجى رفع شعار الشركة');
        return false;
      }
      if (logo.size > 2 * 1024 * 1024) {
        alert('حجم الشعار يجب أن يكون أقل من 2MB');
        return false;
      }
      return true;
    case 3:
      const template = document.querySelector('input[name="template"]:checked');
      if (!template) {
        alert('يرجى اختيار قالب');
        return false;
      }
      return true;
    default:
      return true;
  }
}

// City Search with Autocomplete
function setupCitySearch() {
  const cityInput = document.getElementById('city');
  const suggestionsDiv = document.getElementById('citySuggestions');

  cityInput.addEventListener('input', function() {
    const query = this.value.trim().toLowerCase();
    if (query.length > 0) {
      const filtered = palestinianCities.filter(city => 
        city.toLowerCase().includes(query)
      );
      displaySuggestions(filtered);
    } else {
      suggestionsDiv.style.display = 'none';
    }
  });

  cityInput.addEventListener('blur', function() {
    setTimeout(() => {
      suggestionsDiv.style.display = 'none';
    }, 200);
  });
}

function displaySuggestions(cities) {
  const suggestionsDiv = document.getElementById('citySuggestions');
  if (cities.length === 0) {
    suggestionsDiv.style.display = 'none';
    return;
  }

  suggestionsDiv.innerHTML = cities.map(city => 
    `<div class="suggestion-item" onclick="selectCity('${city}')">${city}</div>`
  ).join('');
  suggestionsDiv.style.display = 'block';
}

function selectCity(city) {
  document.getElementById('city').value = city;
  document.getElementById('citySuggestions').style.display = 'none';
}

// Logo Preview
function setupLogoPreview() {
  const logoInput = document.getElementById('companyLogo');
  const previewDiv = document.getElementById('logoPreview');

  logoInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      if (file.size > 2 * 1024 * 1024) {
        alert('حجم الملف كبير جداً. الحد الأقصى 2MB');
        this.value = '';
        previewDiv.innerHTML = '';
        return;
      }

      const reader = new FileReader();
      reader.onload = function(e) {
        previewDiv.innerHTML = `<img src="${e.target.result}" alt="Logo Preview">`;
      };
      reader.readAsDataURL(file);
    }
  });
}

// Template Selection
function setupTemplateSelection() {
  const templateOptions = document.querySelectorAll('.template-option');
  templateOptions.forEach(option => {
    option.addEventListener('click', function() {
      const radio = this.querySelector('input[type="radio"]');
      radio.checked = true;
      
      // Update visual selection
      templateOptions.forEach(opt => {
        opt.classList.remove('selected');
      });
      this.classList.add('selected');
    });
  });
}

// Form Summary
function setupFormSummary() {
  // Will be called when step 4 is reached
}

function updateSummary() {
  const city = document.getElementById('city').value;
  const companyName = document.getElementById('companyName').value;
  const template = document.querySelector('input[name="template"]:checked');
  
  const templateNames = {
    '1': 'Corporate Clean',
    '2': 'Modern Ramadan',
    '3': 'Traditional Arabic'
  };

  document.getElementById('summaryCity').textContent = city || '-';
  document.getElementById('summaryCompany').textContent = companyName || '-';
  document.getElementById('summaryTemplate').textContent = template ? templateNames[template.value] : '-';
}

// Form Submit
function setupFormSubmit() {
  const form = document.getElementById('wizardForm');
  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!validateStep(4)) {
      return;
    }

    // Show loading
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'جاري الإنشاء...';

    // Collect form data
    const formData = new FormData(form);
    formData.append('city', document.getElementById('city').value);
    formData.append('companyName', document.getElementById('companyName').value);
    formData.append('template', document.querySelector('input[name="template"]:checked').value);

    try {
      const response = await fetch('api/generate-imsakiya.php', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        // Redirect to success page
        window.location.href = `success.html?order_id=${result.order_id}`;
      } else {
        alert('حدث خطأ: ' + (result.message || 'فشل في إنشاء الإمساكية'));
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
      }
    } catch (error) {
      console.error('Error:', error);
      alert('حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.');
      submitBtn.disabled = false;
      submitBtn.textContent = originalText;
    }
  });
}
