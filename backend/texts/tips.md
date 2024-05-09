
Date format for hours, minutes, seconds
  $date = date_create('2023/04/10 12:12:12');
  $date2 = date_create('2023/04/10 12:12:12');
  var_dump(date_format($date, 'H:i:s') > date_format($date2, 'H:i:s'));
  

Date compare
  var_dump(date('Y/m/d'));
  var_dump(date('Y/m/d') > "2024/04/28 19:48:30");
  exit;