<?php include($_SERVER['DOCUMENT_ROOT'] . 'login/app/header.php'); ?>
<?php
  session_start();
  if(isset($_SESSION['authUser'])) {
    header('Location: user.php');
    die();
  }
?>
<!DOCTYPE html>
<!-- © Martin Šimeček. All rights reserved. -->
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="AuthApp je autentizační aplikace k ověření uživatelů při přístupu k vlastním aplikacím.">
  <meta name="author" content="Martin Šimeček">
  <title>AuthApp | Přihlášení</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/signin.css">
  <link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="img/favicon/favicon-16x16.png">
  <link rel="manifest" href="img/favicon/site.webmanifest">

</head>

<body class="text-center">

  <main class="form-signin">
    <form action="app/authenticate.php" method="post">
      <img src="img/shield.svg" height="100" width="100" alt="shield">
      <h1 class="h3 my-3">Přihlášení uživatele</h1>
      <div class="form-floating">
        <input type="email" class="form-control" id="loginEmail" name="email" placeholder="E-mailová adresa" minlength="3" maxlength="255" required>
        <label for="loginEmail">E-mailová adresa</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Přihlašovací heslo"  minlength="8" maxlength="20" required>
        <label for="loginPassword">Přihlašovací heslo</label>
      </div>
      <input type="text" name="source" value="<?=($_GET['source'] ?: 'https://'.$_SERVER['HTTP_HOST'])?>" hidden>
      <button class="w-100 btn btn-lg btn-primary my-3" type="submit">Přihlásit</button>
      <p class="small">
        <a href="doc/privacy_policy.pdf" target="_blank">Zásady ochrany osobních údajů</a>
      </p>
    </form>
  </main>

</body>

</html>
