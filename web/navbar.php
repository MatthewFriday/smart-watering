<?php
$pages = array(
    "home" => "Áttekintés",
    "measurements" => "Mérési adatok",
    "rules" => "Öntözési szabályok",
    "notify" => "Értesítési beállítások"
)
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary shadow">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="/assets/img/icon.svg" alt="Icon" width="30" height="30" class="d-inline-block align-text-top mx-2">
            Smart-watering
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav align-text-middle">
<?php foreach ($pages as $page => $title) { ?>
                <li class="nav-item">
                    <a class="nav-link<?php if ($parts[0] == $page) echo ' active'; ?>"<?php if ($parts[0] == $page) echo ' aria-current="page"'; ?> href="/<?php echo $page; ?>"><?php echo $title; ?></a>
                </li>
<?php } ?>
            </ul>
            <div class="d-flex flex-fill justify-content-end">
                <div class="dropdown mx-3">
                    <button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i> <?php echo $user["username"]; ?>
                    
                    </button>
                    <ul class="dropdown-menu dropdown-menu-start">
                        <li><a class="dropdown-item" href="#">Beállítások</a></li>
                        <li><a class="dropdown-item" href="/logout">Kijelentkezés</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
