# استكشاف أخطاء 403 Forbidden

## المشكلة: خطأ 403 Forbidden

إذا واجهت خطأ 403 عند الوصول إلى الموقع، جرب الحلول التالية:

### الحل 1: تبسيط ملف .htaccess

1. احذف أو أعد تسمية ملف `.htaccess` الحالي
2. استخدم النسخة المبسطة من `.htaccess.backup`:

```bash
# في File Manager على Hostinger
mv .htaccess .htaccess.old
mv .htaccess.backup .htaccess
```

### الحل 2: التحقق من الصلاحيات

تأكد من أن صلاحيات الملفات والمجلدات صحيحة:

```bash
# في File Manager على Hostinger
# صلاحيات المجلدات: 755
# صلاحيات الملفات: 644
```

### الحل 3: التحقق من مسار الملفات

إذا كان Auto Deploy يضع الملفات في `/public_html/public_html`:

1. اذهب إلى Hostinger Git Settings
2. غيّر Install Path من `/public_html` إلى `/` (الجذر)
3. أو انقل الملفات يدوياً من `public_html/` إلى الجذر

### الحل 4: تعطيل .htaccess مؤقتاً

1. احذف أو أعد تسمية `.htaccess`
2. اختبر الموقع
3. إذا عمل، المشكلة في `.htaccess`
4. أعد إضافة `.htaccess` تدريجياً

### الحل 5: التحقق من إعدادات PHP

في cPanel → Select PHP Version:
- تأكد من تفعيل Apache modules
- تأكد من تفعيل mod_rewrite

### الحل 6: ملف index.html في المكان الصحيح

تأكد من أن `index.html` موجود في:
- `/public_html/index.html` (إذا كان Install Path = `/public_html`)
- أو `/index.html` (إذا كان Install Path = `/`)

### الحل 7: إنشاء ملف test.php

أنشئ ملف `test.php` في نفس مجلد `index.html`:

```php
<?php
phpinfo();
?>
```

إذا ظهرت صفحة PHP info، المشكلة ليست في PHP.

### الحل 8: التحقق من logs

في cPanel → Error Logs، ابحث عن:
- خطأ 403 مع تفاصيل
- أي رسائل خطأ أخرى

## إذا استمرت المشكلة

1. تحقق من أن الملفات موجودة في المكان الصحيح
2. تحقق من أن `index.html` موجود ويمكن قراءته
3. اتصل بدعم Hostinger مع تفاصيل الخطأ
