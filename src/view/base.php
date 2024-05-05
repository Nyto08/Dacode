<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Libre+Franklin&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/8802cd6801.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="<?= $css?? '' ?>">
  <link rel="icon" type="image/x-icon" href="../assets/img/favicon.ico">
  <link rel="stylesheet" type="text/css" href="../assets/css/output.css">
  <title><?= $title?? '' ?></title>
</head>
<body class="flex flex-col min-h-screen">
    <?= $navbar ?>

    <?= $content ?>
    
    <?= $footer ?>
</body>
</html>