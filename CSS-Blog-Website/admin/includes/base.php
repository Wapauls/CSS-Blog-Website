<!-- base.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Admin - Base' ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<body>
    <?php include 'sidebar.php'; ?>
    <!--All the content will be dynamically injected here-->
    <?= $content ?>
</body>
</html>
