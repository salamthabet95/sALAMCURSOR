<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إمساكية رمضان - <?php echo htmlspecialchars($data['company_name']); ?></title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Cairo', 'Arial', sans-serif;
      direction: rtl;
      background: #ffffff;
      color: #2c5530;
      padding: 20px;
    }
    .container {
      max-width: 210mm;
      margin: 0 auto;
      background: white;
      padding: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .header {
      text-align: center;
      margin-bottom: 30px;
      border-bottom: 3px solid #2c5530;
      padding-bottom: 20px;
      background: linear-gradient(to bottom, #f8f9fa, #ffffff);
      padding: 25px;
      border-radius: 8px;
    }
    .logo {
      max-width: 150px;
      max-height: 150px;
      margin: 0 auto 15px;
    }
    .logo img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }
    .company-name {
      font-size: 28px;
      font-weight: 700;
      color: #2c5530;
      margin-bottom: 10px;
    }
    .company-info {
      font-size: 14px;
      color: #666;
    }
    .title {
      text-align: center;
      font-size: 32px;
      font-weight: 700;
      color: #2c5530;
      margin: 30px 0;
    }
    .city-info {
      text-align: center;
      font-size: 18px;
      color: #666;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      font-size: 14px;
    }
    th {
      background: #2c5530;
      color: white;
      padding: 12px;
      text-align: center;
      font-weight: 600;
    }
    td {
      padding: 10px;
      text-align: center;
      border-bottom: 1px solid #e0e0e0;
    }
    tr:hover {
      background: #f8f9fa;
    }
    .imsak {
      color: #d4af37;
      font-weight: 700;
    }
    .fajr {
      color: #2c5530;
      font-weight: 700;
    }
    .maghrib {
      color: #c0392b;
      font-weight: 700;
    }
    .footer-note {
      text-align: center;
      font-size: 12px;
      color: #999;
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px solid #e0e0e0;
    }
    @media print {
      body {
        padding: 0;
      }
      .container {
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <?php if (!empty($data['logo_path']) && file_exists($data['logo_path'])): ?>
        <div class="logo">
          <img src="<?php echo $data['logo_path']; ?>" alt="Logo">
        </div>
      <?php endif; ?>
      <div class="company-name"><?php echo htmlspecialchars($data['company_name']); ?></div>
      <div class="company-info">
        <?php if (!empty($data['phone'])): ?>
          <div>📞 <?php echo htmlspecialchars($data['phone']); ?></div>
        <?php endif; ?>
        <?php if (!empty($data['address'])): ?>
          <div>📍 <?php echo htmlspecialchars($data['address']); ?></div>
        <?php endif; ?>
      </div>
    </div>

    <h1 class="title">إمساكية شهر رمضان المبارك</h1>
    <div class="city-info">مدينة: <?php echo htmlspecialchars($data['city']); ?></div>

    <table>
      <thead>
        <tr>
          <th>التاريخ</th>
          <th>الإمساك</th>
          <th>الفجر</th>
          <th>الشروق</th>
          <th>الظهر</th>
          <th>العصر</th>
          <th>المغرب</th>
          <th>العشاء</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data['prayer_times'] as $day): ?>
          <tr>
            <td><?php echo htmlspecialchars($day['date']); ?></td>
            <td class="imsak"><?php echo formatTime($day['imsak']); ?></td>
            <td class="fajr"><?php echo formatTime($day['fajr']); ?></td>
            <td><?php echo formatTime($day['sunrise']); ?></td>
            <td><?php echo formatTime($day['dhuhr']); ?></td>
            <td><?php echo formatTime($day['asr']); ?></td>
            <td class="maghrib"><?php echo formatTime($day['maghrib']); ?></td>
            <td><?php echo formatTime($day['isha']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="footer-note">
      المواقيت حسب الحساب الفلكي وقد تختلف دقائق حسب الظروف المحلية.
    </div>
  </div>
</body>
</html>
<?php
function formatTime($timeString) {
  // Extract time from format like "04:30 (EET)" or "04:30"
  preg_match('/(\d{2}:\d{2})/', $timeString, $matches);
  return $matches[1] ?? $timeString;
}
?>
