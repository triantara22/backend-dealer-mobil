<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Penjualan</title>
  <style>
    body {
      font-family: sans-serif;
      font-size: 12px;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      border: 1px solid #000;
      padding: 6px 8px;
      text-align: left;
    }
    tfoot td {
      font-weight: bold;
    }
  </style>
</head>
<body>
  <h2>Laporan Penjualan</h2>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Merek</th>
        <th>Model</th>
        <th>Jumlah Terjual</th>
        <th>Total Pendapatan</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($laporan as $i => $item): ?>
      <tr>
        <td><?php echo $i + 1?></td>
        <td><?php echo esc($item['merek'])?></td>
        <td><?php echo esc($item['model'])?></td>
        <td><?php echo esc($item['jumlah_terjual'])?></td>
        <td>Rp <?php echo number_format($item['total_pendapatan'], 0, ',', '.')?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="3"><strong>Total</strong></td>
        <td><?php echo esc($total_jumlah)?></td>
        <td>Rp <?php echo number_format($total_pendapatan, 0, ',', '.')?></td>
      </tr>
    </tfoot>
  </table>
</body>
</html>
