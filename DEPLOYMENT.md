# دليل النشر - Deployment Guide

## النشر على Hostinger

### الخطوة 1: إعداد قاعدة البيانات

1. افتح cPanel على Hostinger
2. اذهب إلى phpMyAdmin
3. أنشئ قاعدة بيانات جديدة باسم `ramadan_imsakiya`
4. شغّل ملف `public_html/database.sql` في قاعدة البيانات

### الخطوة 2: إعدادات قاعدة البيانات

1. انسخ ملف `public_html/config/database.php.example` إلى `public_html/config/database.php`
2. عدّل الملف وأدخل بيانات قاعدة البيانات من Hostinger:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');
```

### الخطوة 3: إعدادات التطبيق

عدّل ملف `public_html/config/config.php`:

```php
define('BASE_URL', 'https://yourdomain.com');
```

### الخطوة 4: الصلاحيات

تأكد من أن المجلدات التالية قابلة للكتابة (755 أو 777):

```
public_html/storage/uploads/
public_html/storage/exports/
public_html/storage/cache/
```

يمكنك تعديل الصلاحيات من File Manager في cPanel.

## النشر عبر GitHub + Auto Deploy

### الخطوة 1: إنشاء Repository على GitHub

1. اذهب إلى GitHub وأنشئ repository جديد باسم `sALAMCURSOR`
2. لا تضيف README أو .gitignore (موجودان بالفعل)

### الخطوة 2: ربط المشروع مع GitHub

```bash
git remote add origin https://github.com/YOUR_USERNAME/sALAMCURSOR.git
git branch -M main
git push -u origin main
```

### الخطوة 3: إعداد Auto Deploy على Hostinger

1. اذهب إلى Hostinger Control Panel
2. ابحث عن "Git" أو "Version Control"
3. أضف Repository:
   - Repository URL: `https://github.com/YOUR_USERNAME/sALAMCURSOR.git`
   - Branch: `main`
   - **Install Path: `/`** ⚠️ **مهم:** استخدم `/` وليس `/public_html` لأن الملفات موجودة أصلاً في `public_html/` في المشروع
   - فعّل Auto Deploy
   
   **ملاحظة:** إذا كان Install Path `/public_html`، الملفات ستكون في `/public_html/public_html` على السيرفر!
4. احفظ Webhook URL (ستحتاجه لاحقاً)

### الخطوة 4: إعداد GitHub Webhook (اختياري)

1. اذهب إلى GitHub Repository → Settings → Webhooks
2. أضف Webhook جديد:
   - Payload URL: Webhook URL من Hostinger
   - Content type: `application/json`
   - Events: `Just the push event`
3. احفظ

### الخطوة 5: اختبار Auto Deploy

1. قم بتعديل أي ملف
2. Commit و Push:

```bash
git add .
git commit -m "test auto deploy"
git push origin main
```

3. انتظر دقيقة وتحقق من الموقع

## ملاحظات مهمة

- ⚠️ **لا ترفع ملف `config/database.php`** إلى GitHub (موجود في .gitignore)
- ⚠️ تأكد من أن `public_html` هو المجلد الصحيح على Hostinger
- ⚠️ إذا كان لديك WordPress في نفس المجلد، استخدم subdomain أو مجلد فرعي
- ✅ بعد كل push، سيتم تحديث الموقع تلقائياً

## استكشاف الأخطاء

### المشكلة: Auto Deploy لا يعمل

1. تحقق من Webhook URL في Hostinger
2. تحقق من Branch name (يجب أن يكون `main`)
3. تحقق من Install Path (يجب أن يكون `/public_html`)

### المشكلة: قاعدة البيانات لا تعمل

1. تحقق من بيانات الاتصال في `config/database.php`
2. تأكد من أن قاعدة البيانات موجودة
3. تحقق من صلاحيات المستخدم

### المشكلة: الملفات لا تُحفظ

1. تحقق من صلاحيات مجلدات `storage/`
2. تأكد من أن PHP يمكنه الكتابة في هذه المجلدات

## الدعم

للأسئلة والدعم، راجع ملف `README.md` أو تواصل مع فريق التطوير.
