# ✅ Deploy Checklist - قائمة التحقق للنشر

## 📋 قبل الـ Push

### Git Local
- [ ] `git status` - تحقق من الملفات المعدلة
- [ ] `git diff` - راجع التغييرات
- [ ] `git branch` - تأكد من أنك على `main`
- [ ] `git log --oneline -3` - راجع آخر commits
- [ ] تحقق من `.gitignore` - لا ترفع ملفات حساسة
- [ ] Commit message واضح ووصفي

### الملفات
- [ ] `config/config.php` موجود (أو `.example` موجود)
- [ ] `config/database.php` موجود (أو `.example` موجود)
- [ ] `.htaccess` syntax صحيح
- [ ] `index.html` موجود في `public_html/`
- [ ] لا توجد ملفات test مؤقتة

### بنية المشروع
- [ ] المشروع منظم: `public_html/` في الجذر
- [ ] Install Path في Hostinger = `/` (الجذر) ⚠️ **مهم**
- [ ] لا يوجد `public_html/public_html/` في المشروع

### الاختبار المحلي (إن أمكن)
- [ ] الملفات تعمل محلياً
- [ ] لا توجد أخطاء JavaScript في Console
- [ ] لا توجد أخطاء CSS

---

## 📤 بعد الـ Push

### GitHub
- [ ] `git push origin main` نجح
- [ ] GitHub → Commits → آخر commit ظاهر
- [ ] GitHub → Settings → Webhooks → Status: ✅
- [ ] GitHub → Webhooks → Recent Deliveries → آخر delivery: ✅

### Webhook
- [ ] Recent Deliveries → Status: `200 OK`
- [ ] Recent Deliveries → Duration: أقل من 5 ثوان
- [ ] Recent Deliveries → Response: لا يوجد أخطاء

---

## 🚀 بعد الـ Deploy

### Hostinger
- [ ] Git → Recent Deployments → آخر deployment: ✅
- [ ] **Git → Install Path = `/` (الجذر)** ⚠️ **مهم جداً**
- [ ] File Manager → `public_html/` → تاريخ الملفات محدث
- [ ] File Manager → `public_html/index.html` موجود **مباشرة** (ليس في `public_html/public_html/`)
- [ ] **File Manager → لا يوجد `public_html/public_html/`** ⚠️
- [ ] File Manager → Permissions:
  - [ ] Files: 644
  - [ ] Folders: 755

### Server Response
- [ ] افتح الموقع في المتصفح → Status: 200
- [ ] `https://yourdomain.com/` يعمل
- [ ] `https://yourdomain.com/test.html` يعمل (إن وجد)
- [ ] `https://yourdomain.com/api/test-connection.php` يعمل

### Browser
- [ ] Hard Refresh: `Ctrl+Shift+R` → التغييرات ظاهرة
- [ ] DevTools → Network tab → لا توجد أخطاء 404/500
- [ ] DevTools → Console tab → لا توجد أخطاء JavaScript

### الوظائف
- [ ] الصفحة الرئيسية تعمل
- [ ] Wizard يعمل (إن وجد)
- [ ] API يعمل (إن وجد)
- [ ] Forms تعمل (إن وجد)

---

## 🔍 اختبار سريع (5 دقائق)

### 1. اختبار الملفات الأساسية (1 دقيقة)
```
✅ https://yourdomain.com/
✅ https://yourdomain.com/test.html
✅ https://yourdomain.com/api/test-connection.php
```

### 2. اختبار الصلاحيات (1 دقيقة)
```
File Manager → public_html/
→ Permissions: 755
→ index.html → Permissions: 644
```

### 3. اختبار Server Response (1 دقيقة)
```
Browser → DevTools (F12) → Network tab
→ Refresh
→ تحقق من Status: 200
```

### 4. اختبار Cache (1 دقيقة)
```
Hard Refresh: Ctrl+Shift+R
→ التغييرات ظاهرة؟
```

### 5. اختبار الوظائف (1 دقيقة)
```
✅ الصفحة الرئيسية
✅ Navigation
✅ Forms (إن وجد)
```

---

## 🚨 إذا فشل Deploy

### الخطوة 1: تحقق من Webhook
```
GitHub → Settings → Webhooks → Recent Deliveries
→ آخر delivery → Status?
```

### الخطوة 2: تحقق من Deploy
```
Hostinger → Git → Recent Deployments
→ آخر deployment → Status?
```

### الخطوة 3: تحقق من الملفات
```
File Manager → public_html/
→ تاريخ الملفات محدث؟
```

### الخطوة 4: تحقق من الصلاحيات
```
File Manager → Permissions
→ Files: 644
→ Folders: 755
```

### الخطوة 5: تحقق من Logs
```
cPanel → Error Logs
→ اقرأ آخر خطأ
```

---

## 📝 ملاحظات مهمة

### قبل كل Deploy:
1. **احفظ نسخة احتياطية** من الملفات المهمة
2. **اختبر محلياً** إن أمكن
3. **راجع التغييرات** قبل Commit

### بعد كل Deploy:
1. **اختبر الموقع** فوراً
2. **تحقق من Logs** إذا كان هناك مشاكل
3. **سجّل أي مشاكل** للتعلم منها

### أفضل الممارسات:
- ✅ Commit صغير ومتكرر أفضل من commit كبير
- ✅ Commit message واضح ووصفي
- ✅ اختبر بعد كل deploy
- ✅ احتفظ بنسخة احتياطية

---

## 🎯 Checklist سريعة (نسخة مختصرة)

### قبل Push:
- [ ] `git status` نظيف
- [ ] على branch `main`
- [ ] Commit message واضح

### بعد Push:
- [ ] Push نجح
- [ ] Webhook: ✅
- [ ] Recent Deliveries: 200 OK

### بعد Deploy:
- [ ] الموقع يعمل
- [ ] Hard Refresh → التغييرات ظاهرة
- [ ] لا توجد أخطاء في Console

---

## 📞 إذا استمرت المشاكل

1. **راجع `DEBUGGING_WORKFLOW.md`** للتفاصيل
2. **راجع `ERROR_MAPPING.md`** للأخطاء الشائعة
3. **استخدم `api/test-connection.php`** للتشخيص
4. **تحقق من Error Logs** في cPanel
