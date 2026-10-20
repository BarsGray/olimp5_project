<?php // php -S localhost:4000 router.php ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Политика</title>
  <link rel="stylesheet" href="/style.css">
</head>

<?php
  $company     = '{{ company }}';
  $adress      = '{{ adress }}';
  $pocht_index = '{{ pocht_index }}';
  $inn         = '{{ inn }}';
  $email       = '{{ email }}';
  $url         = '{{ url }}';
?>

<body>
  <header>
    <div class="container">
      <div class="index_links">
        <a href="/privacy-policy">Политика конфиденциальности</a>
        <a href="/politika-ispolzovaniya-fajlov-cookie">Политика использования файлов cookie</a>
        <a href="/soglasie-na-obrabotku-personalnyh-dannyh">Согласие на обработку персональных данных</a>
      </div>
    </div>
  </header>