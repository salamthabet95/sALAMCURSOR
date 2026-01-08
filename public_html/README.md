# إمساكية رمضان للشركات - Ramadan Imsakiya for Business

منصة SaaS تسمح للشركات بإنشاء إمساكية رمضان مخصصة بشعارها خلال دقائق.

## المميزات

- ✅ واجهة عربية RTL كاملة
- ✅ مواقيت صلاة دقيقة من API
- ✅ 3 قوالب تصميم احترافية
- ✅ توليد PDF جاهز للطباعة
- ✅ توليد صورة للواتساب والإنستغرام
- ✅ دعم جميع المدن الفلسطينية
- ✅ بدون تسجيل (Guest Checkout)

## التثبيت

### 1. قاعدة البيانات

قم بتشغيل ملف `database.sql` في قاعدة بيانات MySQL الخاصة بك على Hostinger.

### 2. إعدادات قاعدة البيانات

عدّل ملف `config/database.php` وأدخل بيانات قاعدة البيانات:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');
```

### 3. إعدادات التطبيق

عدّل ملف `config/config.php` وأدخل رابط الموقع:

```php
define('BASE_URL', 'https://yourdomain.com');
```

### 4. الصلاحيات

تأكد من أن المجلدات التالية قابلة للكتابة:

```
storage/uploads/
storage/exports/
storage/cache/
```

## البنية

```
public_html/
├── api/
│   ├── prayer-times.php      # API لجلب مواقيت الصلاة
│   └── generate-imsakiya.php  # API لإنشاء الإمساكية
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── config/
│   ├── database.php           # إعدادات قاعدة البيانات
│   └── config.php             # إعدادات التطبيق
├── includes/
│   ├── pdf-generator.php      # توليد PDF
│   └── image-generator.php    # توليد الصور
├── templates/
│   ├── template-1.php         # قالب Corporate Clean
│   ├── template-2.php         # قالب Modern Ramadan
│   ├── template-3.php         # قالب Traditional Arabic
│   └── template-*-image.php  # قوالب الصور
├── storage/
│   ├── uploads/               # الشعارات المرفوعة
│   ├── exports/               # الملفات المُنشأة
│   └── cache/                 # ملفات الـ Cache
├── index.html                 # الصفحة الرئيسية
├── wizard.html                # صفحة إنشاء الإمساكية
├── success.html               # صفحة النجاح
└── database.sql               # ملف قاعدة البيانات
```

## النشر على Hostinger

1. ارفع جميع الملفات إلى مجلد `public_html` على Hostinger
2. شغّل ملف `database.sql` في قاعدة البيانات
3. عدّل ملفات الإعدادات
4. تأكد من صلاحيات المجلدات

## النشر عبر GitHub + Auto Deploy

1. اربط المستودع مع Hostinger Git Tool
2. اختر Branch: `main`
3. Install Path: `/public_html`
4. فعّل Auto Deployment

## ملاحظات

- المواقيت تأتي من API Aladhan.com
- القوالب قابلة للتخصيص
- يمكن إضافة مكتبات PDF حقيقية (Dompdf/Browsershot) لاحقاً
- النظام جاهز للتوسع بعد رمضان

## الدعم

للأسئلة والدعم، يرجى التواصل مع فريق التطوير.
