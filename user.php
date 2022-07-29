<?php include($_SERVER['DOCUMENT_ROOT'] . 'login/app/header.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . 'login/app/authorize.php'); ?>
<!DOCTYPE html>
<!-- © Martin Šimeček. All rights reserved. -->
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="AuthApp je autentizační aplikace k ověření uživatelů při přístupu k vlastním aplikacím.">
  <meta name="author" content="Martin Šimeček">
  <title>AuthApp | Uživatel</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/custom.css">
  <link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="img/favicon/favicon-16x16.png">
  <link rel="manifest" href="img/favicon/site.webmanifest">

</head>

<body class="d-flex flex-column h-100 bg-light">

  <main class="flex-shrink-0">
    <div class="container col-lg-8 py-4">

      <!-- Header-->
      <header class="d-flex align-items-center justify-content-between border-bottom">
        <h1>Uživatel</h1>
        <div><?php if($auth_manage==1){echo('
          <a href="admin.php" target="_self">Administrátor</a>
          &middot;');} ?>

          <a href="app/logout.php" target="_self">Odhlásit</a>
        </div>
      </header>

      <!-- Card-->
      <div class="card my-4">
        <h3 class="card-header py-3 bg-white">Informace o uživateli</h3>
        <div class="card-body">
          <form>
            <div class="row mb-3">
              <label for="authUser" class="col-sm-3 col-form-label">Uživatel (UUID):</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="authUser" value="<?=$auth_user?>" disabled>
              </div>
            </div>
            <div class="row mb-3">
              <label for="authCreatedBy" class="col-sm-3 col-form-label">Vytvořil (UUID):</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="authCreatedBy" value="<?=$auth_created_by?>" disabled>
              </div>
            </div>
            <div class="row mb-3">
              <label for="authCreatedOn" class="col-sm-3 col-form-label">Vytvořeno:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="authCreatedOn" value="<?=$auth_created_on?>" disabled>
              </div>
            </div>
            <div class="row mb-3">
              <label for="authEmail" class="col-sm-3 col-form-label">E-mailová adresa:</label>
              <div class="col-sm-9">
                <input type="email" class="form-control" id="authEmail" value="<?=$auth_email?>" disabled>
              </div>
            </div>
            <div class="row mb-3">
              <label for="authRole" class="col-sm-3 col-form-label">Uživatelská role:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="authRole" value="<?=$auth_role?>" disabled>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Card-->
      <div class="card my-4">
        <h3 class="card-header py-3 bg-white">Změna hesla</h3>
        <div class="card-body">
          <p class="mb-3">Délka hesla musí být v rozmezí 8 až 20 znaků. Heslo je hashováno silným jednostranným algoritmem <a href="https://www.php.net/manual/en/function.password-hash.php" target="_blank">password_hash()</a>. Důrazně se doporučuje používat kombinace velkých a malých písmen, čísel a speciálních znaků pro maximální zabezpečení Vašeho účtu. Po změně hesla budete automaticky odhlášeni.</p>
          <form action="app/change_pass.php" method="post">
            <div class="row">
              <div class="col-md-5 mb-3">
                <input type="password" name="password1" class="form-control" placeholder="Nové heslo" aria-label="Nové heslo" aria-describedby="button-addon" minlength="8" maxlength="20" required>
              </div>
              <div class="col-md-5 mb-3">
                <input type="password" name="password2" class="form-control" placeholder="Ověření nového hesla" aria-label="Ověření nového hesla" aria-describedby="button-addon" minlength="8" maxlength="20" required>
              </div>
              <div class="col-md-2 mb-3">
                <button type="submit" class="btn btn-primary w-100">Změnit</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Card-->
      <div class="card my-4">
        <h3 class="card-header py-3 bg-white">Výpis přihlášení</h3>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <caption>Maximálně 5 položek. Kompletní výpis lze poskytnout na vyžádání.</caption>
              <thead class="table-dark">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Datum</th>
                  <th scope="col">IP Adresa</th>
                </tr>
              </thead>
              <tbody><?php
  // Select data
  $logins_mysqli = $conn->prepare("SELECT (@r := @r+1) AS 'row', t.* FROM(SELECT created_on, ip_address FROM `auth_login` WHERE created_by = ? ORDER BY id DESC LIMIT 5)t, (SELECT @r:=0)x;");
  $logins_mysqli->bind_param('s', $_SESSION['authUser']);
  $logins_mysqli->execute();
  $logins_result = $logins_mysqli->get_result();
  $logins_mysqli->close();
  while($logins_row = $logins_result->fetch_assoc()) {
    echo ('
                <tr>
                  <th scope="row">' . $logins_row['row'] . '</th>
                  <td>' . $logins_row['created_on'] . '</td>
                  <td>' . $logins_row['ip_address'] . '</td>
                </tr>'
    );
  }
?>

              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Card-->
      <div class="card my-4">
        <h3 class="card-header py-3 bg-white">Deaktivace účtu</h3>
        <div class="card-body">
          <p class="mb-3">Pokud si přejete smazání osobních informací a deaktivaci Vašeho účtu nebo pouze změnu některé z informací, kontaktuje prosím správce s žádostí na e-mailové adrese <a href="mailto:<?=$admin?>"><?=$admin?></a>.</p>
        </div>
      </div>

    </div>
  </main>

  <!-- Footer-->
  <footer class="col-lg-8 offset-lg-2 pb-4">
    <div class="container">
      <div class="d-flex align-items-center justify-content-between small">
        <div class="text-muted">
          © Martin Šimeček. Všechna práva vyhrazena.
        </div>
        <div>
          <a href="doc/privacy_policy.pdf" target="_blank">Zásady ochrany osobních údajů</a>
        </div>
      </div>
    </div>
  </footer>

</body>

</html>
