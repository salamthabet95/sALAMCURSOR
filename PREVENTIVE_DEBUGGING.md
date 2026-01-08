# 🛡️ Preventive Debugging - منع الأخطاء قبل حدوثها

## أفضل الممارسات لتفادي الأخطاء

---

## 1️⃣ تنظيم المشروع

### هيكل المجلدات الموصى به:

```
project/
├── public_html/              # الملفات العامة (للنشر)
│   ├── index.html
│   ├── assets/
│   ├── api/
│   └── config/
├── .gitignore               # ملفات Git
├── README.md                # توثيق المشروع
├── DEPLOY_CHECKLIST.md      # قائمة التحقق
└── DEBUGGING_WORKFLOW.md    # دليل Debugging
```

### قواعد مهمة:

✅ **DO:**
- ضع كل شيء للنشر في `public_html/`
- استخدم `.gitignore` للملفات الحساسة
- احتفظ بنسخة `.example` للملفات الحساسة
- نظّم الملفات حسب الوظيفة

❌ **DON'T:**
- لا ترفع ملفات `config.php` مباشرة
- لا تضع ملفات حساسة في Git
- لا تخلط ملفات التطوير مع ملفات النشر

---

## 2️⃣ تسمية الفروع (Branches)

### استراتيجية التسمية:

```
main                    # Production (النشر النهائي)
develop                 # Development (التطوير)
feature/feature-name    # ميزة جديدة
fix/bug-name           # إصلاح خطأ
hotfix/urgent-fix      # إصلاح عاجل
```

### مثال عملي:

```bash
# ميزة جديدة
git checkout -b feature/add-payment

# إصلاح خطأ
git checkout -b fix/403-error

# إصلاح عاجل
git checkout -b hotfix/security-patch
```

### قواعد:

✅ **DO:**
- استخدم أسماء واضحة ووصفية
- استخدم prefixes: `feature/`, `fix/`, `hotfix/`
- ادمج في `main` فقط بعد الاختبار

❌ **DON'T:**
- لا تستخدم أسماء غامضة: `test`, `new`, `fix`
- لا تعمل مباشرة على `main` (إلا للـ hotfix)

---

## 3️⃣ إعداد Install Path

### Hostinger Git Settings:

```
Repository URL: https://github.com/USERNAME/REPO.git
Branch: main
Install Path: /  ⚠️ مهم: استخدم / وليس /public_html
Auto Deploy: ✅ Enabled
```

### قواعد مهمة:

✅ **DO:**
- استخدم `/` إذا كان المشروع فيه مجلد `public_html/` (مثل مشروعنا)
- استخدم `/public_html` فقط إذا كان المشروع في الجذر بدون مجلد `public_html/`
- تأكد من أن Path يتطابق مع هيكل المشروع

❌ **DON'T:**
- لا تستخدم `/public_html` إذا كان المشروع فيه `public_html/` (ستحصل على `/public_html/public_html`)
- لا تستخدم paths نسبية
- لا تغيّر Path بدون سبب

### مثال:

```
إذا كان المشروع (مثل مشروعنا):
project/
└── public_html/
    └── index.html

Install Path يجب أن يكون:
/  ✅ (الجذر)

لأن Hostinger سينسخ محتويات المشروع إلى Install Path
فإذا كان Install Path = /public_html
والملفات في public_html/ في المشروع
النتيجة: /public_html/public_html/ ❌
```

---

إذا كان المشروع:
project/
└── index.html

Install Path يجب أن يكون:
/
```

---

## 4️⃣ التعامل مع Cache

### Browser Cache:

**المشكلة:** التغييرات لا تظهر بسبب Cache

**الحل:**

1. **في التطوير:**
   ```html
   <!-- أضف version parameter -->
   <link rel="stylesheet" href="assets/css/main.css?v=1.0.1">
   <script src="assets/js/main.js?v=1.0.1"></script>
   ```

2. **في Production:**
   ```html
   <!-- استخدم hash أو timestamp -->
   <link rel="stylesheet" href="assets/css/main.css?v=<?php echo time(); ?>">
   ```

3. **في .htaccess:**
   ```apache
   # Cache static assets
   <IfModule mod_expires.c>
       ExpiresActive On
       ExpiresByType text/css "access plus 1 month"
       ExpiresByType application/javascript "access plus 1 month"
   </IfModule>
   ```

### CDN Cache:

إذا كنت تستخدم CDN:
- امسح Cache بعد كل deploy
- استخدم versioning في URLs
- استخدم Cache-Control headers

---

## 5️⃣ اختبار Deploy بسرعة

### ملف Test دائم:

أنشئ `public_html/test.html`:

```html
<!DOCTYPE html>
<html>
<head>
    <title>Test Page</title>
</head>
<body>
    <h1>✅ Deploy Successful!</h1>
    <p>Time: <span id="time"></span></p>
    <script>
        document.getElementById('time').textContent = new Date().toLocaleString();
    </script>
</body>
</html>
```

**الاستخدام:**
```
بعد كل deploy:
1. افتح: https://yourdomain.com/test.html
2. إذا ظهرت الصفحة → Deploy نجح
3. إذا لم تظهر → Deploy فشل
```

### سكريبت اختبار سريع:

أنشئ `test-deploy.sh`:

```bash
#!/bin/bash
echo "Testing deploy..."
curl -s https://yourdomain.com/test.html | grep -q "Deploy Successful"
if [ $? -eq 0 ]; then
    echo "✅ Deploy successful!"
else
    echo "❌ Deploy failed!"
fi
```

---

## 6️⃣ Logging بسيط

### للـ Static Projects:

**1. Console Logging (JavaScript):**

```javascript
// في production، استخدم:
const DEBUG = false; // غيّر إلى true للـ debugging

function log(message, data = null) {
    if (DEBUG) {
        console.log('[DEBUG]', message, data);
    }
}

// الاستخدام:
log('Form submitted', formData);
```

**2. Error Tracking:**

```javascript
window.addEventListener('error', function(e) {
    // أرسل الخطأ إلى server (إن أمكن)
    console.error('Error:', e.error);
    
    // أو احفظه في localStorage
    const errors = JSON.parse(localStorage.getItem('errors') || '[]');
    errors.push({
        message: e.message,
        file: e.filename,
        line: e.lineno,
        time: new Date().toISOString()
    });
    localStorage.setItem('errors', JSON.stringify(errors));
});
```

**3. API Logging (PHP):**

```php
// في config.php
define('DEBUG', false);

function debug_log($message, $data = null) {
    if (DEBUG) {
        error_log('[DEBUG] ' . $message . ($data ? ': ' . json_encode($data) : ''));
    }
}

// الاستخدام:
debug_log('API called', ['city' => $city]);
```

---

## 7️⃣ التمييز بين Server Error و Browser Error

### Server Error (500, 403, 404):

**العلامات:**
- Status code في Network tab: 403/404/500
- Response body: رسالة خطأ من Server
- يحدث حتى بعد Hard Refresh

**كيف تكتشف:**
```
Browser → DevTools (F12) → Network tab
→ Refresh
→ ابحث عن Request الفاشل
→ Status: 403/404/500
```

**الحل:**
- تحقق من Server Logs
- تحقق من File Permissions
- تحقق من `.htaccess`

---

### Browser Error (JavaScript, CSS):

**العلامات:**
- Status code: 200 (الملف موجود)
- Console tab: أخطاء JavaScript
- يختفي بعد Hard Refresh (أحياناً)

**كيف تكتشف:**
```
Browser → DevTools (F12) → Console tab
→ ابحث عن أخطاء JavaScript
```

**الحل:**
- تحقق من Console errors
- تحقق من ملفات JavaScript/CSS
- تحقق من Browser Cache

---

## 8️⃣ أفضل الممارسات الإضافية

### 1. Version Control:

```bash
# استخدم tags للإصدارات
git tag -a v1.0.0 -m "First release"
git push origin v1.0.0
```

### 2. Environment Variables:

```php
// في config.php
define('ENVIRONMENT', 'production'); // أو 'development'

if (ENVIRONMENT === 'development') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
```

### 3. Backup Strategy:

```bash
# قبل أي تغيير كبير
git tag backup-$(date +%Y%m%d)
git push origin backup-$(date +%Y%m%d)
```

### 4. Testing Checklist:

```
قبل كل Deploy:
- [ ] اختبر محلياً (إن أمكن)
- [ ] راجع التغييرات
- [ ] تحقق من .gitignore
- [ ] Commit message واضح

بعد كل Deploy:
- [ ] اختبر الموقع
- [ ] تحقق من Logs
- [ ] سجّل أي مشاكل
```

---

## 📋 Checklist نهائية

### إعداد المشروع:
- [ ] هيكل مجلدات منظم
- [ ] `.gitignore` صحيح
- [ ] ملفات `.example` موجودة
- [ ] `test.html` موجود

### Git:
- [ ] Branch naming واضح
- [ ] Commit messages وصفية
- [ ] Tags للإصدارات

### Hostinger:
- [ ] Install Path صحيح
- [ ] Auto Deploy مفعّل
- [ ] Webhook URL صحيح

### Testing:
- [ ] ملف test دائم
- [ ] سكريبت اختبار (اختياري)
- [ ] Logging بسيط

---

## 🎯 الخلاصة

**القاعدة الذهبية:**
> "افترض أن كل شيء يمكن أن يخطئ، واختبر كل شيء"

**الخطوات الأساسية:**
1. نظّم المشروع من البداية
2. استخدم naming conventions واضحة
3. اختبر بعد كل تغيير
4. سجّل الأخطاء للتعلم منها
5. احتفظ بنسخ احتياطية
