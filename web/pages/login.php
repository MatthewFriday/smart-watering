<?php
$error = null;

if (isset($_POST['username'], $_POST['password'])) {
    $check = $db->check_user($_POST['username'], $_POST['password']);
    if (!$check) $error = "Helytelen felhasználónév vagy jelszó!";
    else {
        $_SESSION['ID'] = $check;
        header('Location: /home');
    }
}
?>
<main class="d-flex align-items-center py-4 bg-body-tertiary my-auto">
    <div class="form-signin w-100 m-auto card shadow text-center">
        <form method="post">
            <img class="mb-3" src="/assets/img/icon.svg" alt="Icon" width="75" height="75">
            <h1 class="h3 mb-3 fw-normal">Bejelentkezés</h1>

            <div class="form-floating">
                <input name="username" type="input" class="form-control" id="floatingInput" placeholder="Felhasználónév" required>
                <label for="floatingInput">Felhasználónév</label>
            </div>
            <div class="form-floating">
                <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Jelszó" required>
                <label for="floatingPassword">Jelszó</label>
            </div>
<?php if (isset($error)) { ?>
            <div class="alert alert-danger my-2" role="alert">
                <?php echo $error; ?>
            </div>
<?php } ?>
<?php if (isset($_GET['logout']) && !isset($error)) { ?>
            <div class="alert alert-success my-2" role="alert">
                Sikeresen kijelentkeztél!
            </div>
<?php } ?>
            <button class="btn btn-primary w-100 py-2" type="submit">Tovább</button>
        </form>
    </div>
</main>
