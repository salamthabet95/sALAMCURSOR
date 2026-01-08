<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إمساكية رمضان</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Cairo', 'Arial', sans-serif;
      direction: rtl;
      width: 1080px;
      height: 1920px;
      background: #ffffff;
      color: #2c5530;
      padding: 40px;
      margin: 0 auto;
    }
    .header {
      text-align: center;
      margin-bottom: 30px;
    }
    .logo {
      max-width: 120px;
      max-height: 120px;
      margin: 0 auto 15px;
    }
    .logo img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }
    .company-name {
      font-size: 32px;
      font-weight: 700;
      color: #2c5530;
      margin-bottom: 10px;
    }
    .title {
      text-align: center;
      font-size: 36px;
      font-weight: 700;
      color: #2c5530;
      margin: 20px 0;
    }
    .city-info {
      text-align: center;
      font-size: 20px;
      color: #666;
      margin-bottom: 30px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 18px;
    }
    th {
      background: #2c5530;
      color: white;
      padding: 15px;
      text-align: center;
      font-weight: 600;
    }
    td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid #e0e0e0;
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
  </style>
</head>
<body>
  <div class="header">
    <?php if (!empty($data['logo_path']) && file_exists($data['logo_path'])): ?>
      <div class="logo">
        <img src="<?php echo $data['logo_path']; ?>" alt="Logo">
      </div>
    <?php endif; ?>
    <div class="company-name"><?php echo htmlspecialchars($data['company_name']); ?></div>
  </div>

  <h1 class="title">إمساكية رمضان</h1>
  <div class="city-info"><?php echo htmlspecialchars($data['city']); ?></div>

  <table>
    <thead>
      <tr>
        <th>التاريخ</th>
        <th>الإمساك</th>
        <th>الفجر</th>
        <th>المغرب</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data['prayer_times'] as $day): ?>
        <tr>
          <td><?php echo htmlspecialchars($day['date']); ?></td>
          <td class="imsak"><?php echo formatTime($day['imsak']); ?></td>
          <td class="fajr"><?php echo formatTime($day['fajr']); ?></td>
          <td class="maghrib"><?php echo formatTime($day['maghrib']); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
<?php
function formatTime($timeString) {
  preg_match('/(\d{2}:\d{2})/', $timeString, $matches);
  return $matches[1] ?? $timeString;
}
?>
