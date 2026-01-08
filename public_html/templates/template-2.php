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
      background: linear-gradient(135deg, #2c5530 0%, #1e3a21 100%);
      color: #ffffff;
      padding: 20px;
    }
    .container {
      max-width: 210mm;
      margin: 0 auto;
      background: linear-gradient(135deg, #2c5530 0%, #1e3a21 100%);
      padding: 30px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    .header {
      text-align: center;
      margin-bottom: 30px;
      border-bottom: 3px solid #d4af37;
      padding-bottom: 20px;
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
      color: #d4af37;
      margin-bottom: 10px;
    }
    .company-info {
      font-size: 14px;
      color: rgba(255,255,255,0.8);
    }
    .title {
      text-align: center;
      font-size: 32px;
      font-weight: 700;
      color: #d4af37;
      margin: 30px 0;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    .city-info {
      text-align: center;
      font-size: 18px;
      color: rgba(255,255,255,0.9);
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      font-size: 14px;
      background: rgba(255,255,255,0.1);
      border-radius: 8px;
      overflow: hidden;
    }
    th {
      background: rgba(212, 175, 55, 0.3);
      color: white;
      padding: 12px;
      text-align: center;
      font-weight: 600;
    }
    td {
      padding: 10px;
      text-align: center;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    tr:hover {
      background: rgba(255,255,255,0.15);
    }
    .imsak {
      color: #d4af37;
      font-weight: 700;
      font-size: 16px;
    }
    .fajr {
      color: #ffffff;
      font-weight: 700;
      font-size: 16px;
    }
    .maghrib {
      color: #ffd700;
      font-weight: 700;
      font-size: 16px;
    }
    .footer-note {
      text-align: center;
      font-size: 12px;
      color: rgba(255,255,255,0.7);
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px solid rgba(255,255,255,0.2);
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
  preg_match('/(\d{2}:\d{2})/', $timeString, $matches);
  return $matches[1] ?? $timeString;
}
?>
