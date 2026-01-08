<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إمساكية رمضان - <?php echo htmlspecialchars($data['company_name']); ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;700&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Noto Naskh Arabic', 'Traditional Arabic', serif;
      direction: rtl;
      background: #f5f5dc;
      color: #8b4513;
      padding: 20px;
    }
    .container {
      max-width: 210mm;
      margin: 0 auto;
      background: #f5f5dc;
      padding: 30px;
      border: 3px solid #8b4513;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .header {
      text-align: center;
      margin-bottom: 30px;
      border-bottom: 3px solid #8b4513;
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
      color: #8b4513;
      margin-bottom: 10px;
    }
    .company-info {
      font-size: 14px;
      color: #654321;
    }
    .title {
      text-align: center;
      font-size: 32px;
      font-weight: 700;
      color: #8b4513;
      margin: 30px 0;
      text-decoration: underline;
    }
    .city-info {
      text-align: center;
      font-size: 18px;
      color: #654321;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      font-size: 14px;
      border: 2px solid #8b4513;
    }
    th {
      background: #8b4513;
      color: #f5f5dc;
      padding: 12px;
      text-align: center;
      font-weight: 700;
      border: 1px solid #654321;
    }
    td {
      padding: 10px;
      text-align: center;
      border: 1px solid #8b4513;
      background: #fffef5;
    }
    tr:nth-child(even) {
      background: #f5f5dc;
    }
    tr:hover {
      background: #e8e8d0;
    }
    .imsak {
      color: #8b4513;
      font-weight: 700;
      font-size: 16px;
    }
    .fajr {
      color: #654321;
      font-weight: 700;
      font-size: 16px;
    }
    .maghrib {
      color: #a0522d;
      font-weight: 700;
      font-size: 16px;
    }
    .footer-note {
      text-align: center;
      font-size: 12px;
      color: #654321;
      margin-top: 30px;
      padding-top: 20px;
      border-top: 2px solid #8b4513;
      font-style: italic;
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
