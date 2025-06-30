
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3 sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF'])=="index.php"?'active':''?>" aria-current="page" href="./">
                    <span data-feather="bar-chart" class="align-text-bottom"></span>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF'])=="reservar.php"?'active':''?>" href="reservar.php">
                    <span data-feather="arrow-up-circle" class="align-text-bottom"></span>
                    Reservar
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF'])=="recibir.php"?'active':''?>" href="recibir.php">
                    <span data-feather="arrow-down-circle" class="align-text-bottom"></span>
                    Recibir
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF'])=="libros.php"?'active':''?>" href="libros.php">
                    <span data-feather="book-open" class="align-text-bottom"></span>
                    Libros
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF'])=="clientes.php"?'active':''?>" href="clientes.php">
                    <span data-feather="users" class="align-text-bottom"></span>
                    Clientes
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
            <span>Configuraciones</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF'])=="usuarios.php"?'active':''?>" href="usuarios.php">
                    <span data-feather="user-check" class="align-text-bottom"></span>
                    Usuarios del Sistema
                </a>
            </li>
        </ul>
    </div>
</nav>