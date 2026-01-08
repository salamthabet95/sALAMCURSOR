# 🗺️ Error Mapping - خريطة الأخطاء والحلول

## جدول شامل: Error Message → السبب → مكان الفحص → الحل

| Error Message | السبب المحتمل | مكان الفحص | الحل السريع |
|--------------|---------------|------------|-------------|
| **403 Forbidden** | صلاحيات خاطئة | File Manager → Permissions | `chmod 644` للملفات، `755` للمجلدات |
| **403 Forbidden** | `.htaccess` يمنع | File Manager → `.htaccess` | احذف أو عدّل `.htaccess` |
| **403 Forbidden** | `index.html` غير موجود | File Manager → `public_html/` | تأكد من وجود `index.html` |
| **404 Not Found** | الملف غير موجود | File Manager → المسار | تحقق من المسار الصحيح |
| **404 Not Found** | مسار خاطئ في URL | Browser → Address Bar | تحقق من URL |
| **404 Not Found** | `.htaccess` rewrite خاطئ | File Manager → `.htaccess` | عدّل أو احذف rewrite rules |
| **500 Internal Server Error** | خطأ في PHP | cPanel → Error Logs | اقرأ آخر خطأ في Logs |
| **500 Internal Server Error** | `.htaccess` syntax error | File Manager → `.htaccess` | تحقق من syntax |
| **500 Internal Server Error** | قاعدة بيانات غير متصلة | `config/database.php` | تحقق من بيانات الاتصال |
| **"nothing to commit"** | ملفات غير متتبعة | Terminal → `git status` | `git add .` |
| **"remote rejected"** | تغييرات على GitHub | Terminal → `git pull` | `git pull --rebase` |
| **"fatal: not a git repository"** | ليس في مجلد Git | Terminal → `pwd` | `cd` إلى مجلد المشروع |
| **Webhook: 404** | URL خاطئ | GitHub → Webhooks → Recent Deliveries | عدّل URL في Hostinger |
| **Webhook: Timeout** | Server بطيء | GitHub → Webhooks → Duration | تحقق من Server response |
| **"حدث خطأ في الاتصال"** | API لا يعمل | Browser → DevTools → Network | تحقق من `api/generate-imsakiya.php` |
| **"فشل في جلب مواقيت الصلاة"** | API خارجي لا يعمل | `api/prayer-times.php` | تحقق من Aladhan API |
| **"ملف الإعدادات غير موجود"** | `config.php` غير موجود | File Manager → `config/` | انسخ من `.example` |
| **"Database connection failed"** | بيانات DB خاطئة | `config/database.php` | عدّل بيانات الاتصال |
| **"Directory is not writable"** | صلاحيات خاطئة | File Manager → `storage/` | `chmod 755` للمجلدات |
| **تغييرات لا تظهر** | Browser Cache | Browser → DevTools | Hard Refresh: `Ctrl+Shift+R` |
| **CSS/JS قديم** | CDN Cache | Browser → Network tab | Clear Cache |
| **"Cannot lock ref"** | Conflict في Git | Terminal → `git status` | `git pull --rebase` |
| **"Permission denied"** | صلاحيات SSH | Terminal → `ls -la` | `chmod` الصحيح |
| **"File not found"** | مسار خاطئ | File Manager → المسار | تحقق من المسار |
| **"Module not found"** | مكتبة غير مثبتة | Terminal → `composer list` | `composer install` |

---

## 🔍 تفاصيل الأخطاء الشائعة

### 1. 403 Forbidden

**الأسباب المحتملة (بترتيب الاحتمالية):**
1. صلاحيات الملفات خاطئة (90%)
2. `.htaccess` يمنع الوصول (5%)
3. `index.html` غير موجود (5%)

**خطوات التشخيص:**
```bash
1. File Manager → public_html → index.html
   → Right-click → Properties → Permissions
   → يجب أن يكون: 644

2. File Manager → public_html → .htaccess
   → احذف مؤقتاً أو أعد تسميته
   → Refresh المتصفح
   → إذا عمل → المشكلة في .htaccess

3. File Manager → public_html/
   → تحقق من وجود index.html
```

**الحل:**
```bash
# في File Manager
Permissions:
- Files: 644
- Folders: 755
```

---

### 2. 404 Not Found

**الأسباب المحتملة:**
1. الملف غير موجود في المسار المحدد (80%)
2. مسار خاطئ في URL (15%)
3. `.htaccess` rewrite rules خاطئة (5%)

**خطوات التشخيص:**
```bash
1. Browser → Address Bar
   → انسخ URL الكامل
   → تحقق من المسار

2. File Manager → اتبع المسار في URL
   → تحقق من وجود الملف

3. File Manager → .htaccess
   → ابحث عن RewriteRule
   → تحقق من syntax
```

**الحل:**
```bash
# مثال: URL هو /api/test.php لكن الملف في /public_html/api/test.php
# الحل: تأكد من أن Install Path صحيح في Hostinger Git
```

---

### 3. 500 Internal Server Error

**الأسباب المحتملة:**
1. خطأ في PHP syntax (60%)
2. `.htaccess` syntax error (20%)
3. قاعدة بيانات غير متصلة (15%)
4. Memory limit (5%)

**خطوات التشخيص:**
```bash
1. cPanel → Error Logs
   → اقرأ آخر خطأ
   → الخطأ سيخبرك بالملف والسطر

2. File Manager → الملف المذكور في الخطأ
   → افتحه وابحث عن السطر المذكور

3. إذا كان الخطأ في .htaccess
   → احذف مؤقتاً للاختبار
```

**الحل:**
```bash
# مثال: "Parse error: syntax error in /public_html/api/test.php on line 15"
# الحل: افتح الملف، اذهب للسطر 15، أصلح syntax error
```

---

### 4. Git Push Failed

**الأسباب المحتملة:**
1. تغييرات على GitHub لم تسحبها (70%)
2. Authentication failed (20%)
3. Branch محمي (10%)

**خطوات التشخيص:**
```bash
1. Terminal → git status
   → تحقق من الحالة

2. Terminal → git fetch origin
   → Terminal → git log HEAD..origin/main
   → إذا كان هناك commits → pull أولاً

3. GitHub → Settings → Personal Access Tokens
   → تحقق من Token
```

**الحل:**
```bash
git fetch origin
git pull origin main --rebase
git push origin main
```

---

### 5. Webhook لا يعمل

**الأسباب المحتملة:**
1. URL خاطئ (50%)
2. Webhook معطل (30%)
3. Server لا يرد (20%)

**خطوات التشخيص:**
```bash
1. GitHub → Settings → Webhooks
   → Recent Deliveries
   → انقر على آخر delivery
   → شاهد Response

2. Hostinger → Git → Webhook URL
   → انسخ URL
   → تأكد من صحته

3. اختبر URL يدوياً:
   curl -X POST WEBHOOK_URL
```

**الحل:**
```bash
# إذا كان Response: 404
# → عدّل URL في Hostinger Git

# إذا كان Response: Timeout
# → تحقق من Server response time
```

---

### 6. "حدث خطأ في الاتصال" (JavaScript)

**الأسباب المحتملة:**
1. API لا يعمل (60%)
2. CORS error (20%)
3. Network error (20%)

**خطوات التشخيص:**
```bash
1. Browser → DevTools (F12) → Network tab
   → Refresh الصفحة
   → ابحث عن Request الفاشل
   → انقر عليه → شاهد Response

2. Browser → Console tab
   → ابحث عن أي أخطاء JavaScript

3. اختبر API مباشرة:
   → افتح api/generate-imsakiya.php في المتصفح
   → إذا ظهر JSON → API يعمل
   → إذا ظهر خطأ → المشكلة في API
```

**الحل:**
```bash
# إذا كان Response: 500
# → تحقق من Error Logs في cPanel

# إذا كان Response: CORS error
# → تحقق من headers في API file
```

---

## 🎯 Quick Reference - مرجع سريع

### الأوامر الأساسية:

```bash
# Git
git status                    # حالة الملفات
git log --oneline -5          # آخر 5 commits
git remote -v                 # Remote URLs

# File Permissions (في File Manager)
Files: 644
Folders: 755

# Browser
Hard Refresh: Ctrl+Shift+R     # Windows
Hard Refresh: Cmd+Shift+R      # Mac
Clear Cache: Ctrl+Shift+Delete
```

### الأماكن للفحص:

```
1. GitHub:
   - Repository → Commits
   - Settings → Webhooks → Recent Deliveries

2. Hostinger:
   - Git → Recent Deployments
   - File Manager → Permissions
   - Error Logs

3. Browser:
   - DevTools (F12) → Network tab
   - DevTools → Console tab
```

---

## 📋 Checklist سريعة

عند مواجهة أي خطأ:

- [ ] تحقق من Browser Cache (Hard Refresh)
- [ ] تحقق من DevTools → Network tab
- [ ] تحقق من File Manager → Permissions
- [ ] تحقق من cPanel → Error Logs
- [ ] تحقق من GitHub → Webhooks → Recent Deliveries
- [ ] تحقق من Hostinger → Git → Recent Deployments
