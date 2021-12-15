<?php include($_SERVER['DOCUMENT_ROOT'] . 'login/app/authorize.php'); ?>
<?php
  // Check right to manage
  if($auth_manage !== 1) {
    die("Access denied - missing permission (manage).");
  }
?>
<?php include($_SERVER['DOCUMENT_ROOT'] . 'login/app/header.php'); ?>
<?php
// Select data
  $stats_mysqli = $conn->prepare("SELECT * FROM (SELECT l.created_on, u.email FROM `auth_login` l LEFT OUTER JOIN `auth_user` u ON l.created_by = u.user WHERE l.id = (SELECT MAX(id) FROM `auth_login`)) a, (SELECT COUNT(id) FROM `auth_login`) b, (SELECT COUNT(DISTINCT ip_address) FROM `auth_login`) c");
  $stats_mysqli->execute();
  $stats_mysqli->bind_result($stats_result1 , $stats_result2, $stats_result3, $stats_result4);
  $stats_mysqli->fetch();
  $stats_mysqli->close();
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
  <title>AuthApp | Administrátor</title>
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
        <h1>Administrátor</h1>
        <div>
          <a href="user.php" target="_self">Uživatel</a>
          &middot;
          <a href="https://business.martinsimecek.cz/" target="_self">BTool</a>
          &middot;
          <a href="app/logout.php" target="_self">Odhlásit</a>
        </div>
      </header>

      <!-- Card-->
      <div class="card my-4">
        <h3 class="card-header py-3 bg-white">Seznam uživatelů</h3>
        <div class="card-body">
          <p class="mb-3">V následujícím seznamu je možné provádět změny uživatelských rolí jednotlivých uživatelů. Uživatel bude o provedení změny informován e-mailem. Nelze měnit roli vlastního uživatelského účtu.</p>
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <caption>Tabulka obsahuje kompletní výpis uživatelů.</caption>
              <thead class="table-dark">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">E-mailová adresa</th>
                  <th scope="col">Datum vytvoření</th>
                  <th scope="col">Uživatelská role</th>
                </tr>
              </thead>
              <tbody><?php
  // Select data
  $users_mysqli = $conn->prepare("SELECT (@r := @r+1) AS 'row', t.* FROM(SELECT user, created_on, email, role FROM `auth_user` ORDER BY email)t, (SELECT @r:=0)x;");
  $users_mysqli->execute();
  $users_result = $users_mysqli->get_result();
  $users_mysqli->close();
  while($users_row = $users_result->fetch_assoc()) {
    echo ('
                <tr class="align-middle">
                  <th scope="row">' . $users_row['row']. '</th>
                  <td>' . $users_row['email']. '</td>
                  <td>' . $users_row['created_on']. '</td>
                  <td>
                    <form action="app/rolechange.php" method="post">
                      <div class="input-group">
                        <select class="form-select form-select-sm" aria-label="Výběr uživatelské role" name="role" required '.(($users_row['user']==$auth_user)?'disabled':'').'>
                          <option selected disabled>' . $users_row['role']. '</option>
                          <option value="Neaktivní">Neaktivní</option>
                          <option value="Uživatel">Uživatel</option>
                          <option value="Editor">Editor</option>
                          <option value="Správce">Správce</option>
                          <option disabled>-----------</option>
                          <option value="DEL">Smazat účet</option>
                        </select>
                        <input type="text" name="email" value="' . $users_row['email']. '" hidden>
                        <button type="submit" class="btn btn-sm btn-primary '.(($users_row['user']==$auth_user)?'disabled':'').'">Změnit</button>
                      </div>
                    </form>
                  </td>
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
        <h3 class="card-header py-3 bg-white">Vytvoření uživatele</h3>
        <div class="card-body">
          <p class="mb-3">Pro vytvoření nového uživatele zadejte <u>e-mailovou adresu</u>, která bude sloužit pro přihlášení a na kterou bude následně zasláno náhodně vygenerované heslo. Úroveň oprávnění udává <u>uživatelská role</u>, která lze kdykoliv měnit.</p>
          <form action="app/register.php" method="post">
            <div class="row">
              <div class="col-md-5 mb-3">
                <input type="email" class="form-control" placeholder="uzivatel@domena.cz" name="email" maxlength="255" required>
              </div>
              <div class="col-md-5 mb-3">
                <select class="form-select" aria-label="Výběr uživatelské role" name="role" required>
                  <option selected disabled>Vyberte roli</option>
                  <option value="Neaktivní" disabled>Neaktivní</option>
                  <option value="Uživatel">Uživatel</option>
                  <option value="Editor">Editor</option>
                  <option value="Správce">Správce</option>
                </select>
              </div>
              <div class="col-md-2 mb-3">
                <button type="submit" class="btn btn-primary w-100">Vytvořit</button>
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
              <caption>Maximálně 10 položek.</caption>
              <thead class="table-dark">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Datum</th>
                  <th scope="col">E-mailová adresa</th>
                  <th scope="col">IP Adresa</th>
                </tr>
              </thead>
              <tbody><?php
  // Select data
  $logins_mysqli = $conn->prepare("SELECT (@r := @r+1) AS 'row', t.* FROM(SELECT l.created_on, u.email, l.ip_address FROM `auth_login` AS l LEFT OUTER JOIN `auth_user` u ON l.created_by = u.user ORDER BY l.id DESC LIMIT 10)t, (SELECT @r:=0)x;");
  $logins_mysqli->execute();
  $logins_result = $logins_mysqli->get_result();
  $logins_mysqli->close();
  while($logins_row = $logins_result->fetch_assoc()) {
    echo ('
                <tr>
                  <th scope="row">' . $logins_row['row'] . '</th>
                  <td>' . $logins_row['created_on'] . '</td>
                  <td>' . $logins_row['email'] . '</td>
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
        <h3 class="card-header py-3 bg-white">Statistiky</h3>
        <div class="card-body">
          <form>
            <div class="row mb-3">
              <label for="statCreatedOn" class="col-sm-3 col-form-label">Poslední přihlášení:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="statCreatedOn" value="<?=$stats_result1?>" disabled>
              </div>
            </div>
            <div class="row mb-3">
              <label for="statEmail" class="col-sm-3 col-form-label">Naposledy přihlášen:</label>
              <div class="col-sm-9">
                <input type="email" class="form-control" id="statEmail" value="<?=$stats_result2?>" disabled>
              </div>
            </div>
            <div class="row mb-3">
              <label for="statLogins" class="col-sm-3 col-form-label">Celkem přihlášení:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="statLogins" value="<?=$stats_result3?>" disabled>
              </div>
            </div>
            <div class="row mb-3">
              <label for="statIps" class="col-sm-3 col-form-label">Celkem IP adres:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="statIps" value="<?=$stats_result4?>" disabled>
              </div>
            </div>
          </form>
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
