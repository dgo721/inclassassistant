<!--

InClass Assistant 2015
Archivo con el encabezado

-->
<header>
<nav class="navigation-bar fixed-top">
<div class="navigation-bar-content">
    <a href="home.php" class="element"><span class="icon-tree-view on-left-more"></span> InClass Assistant </a>
    <span class="element-divider"></span>

    <a class="pull-menu" href="#"></a>


    <div class="no-tablet-portrait">
        <?
        if( isset($_SESSION['id']) ){
        ?>
        <div class="element place-right">
            <a class="dropdown-toggle" href="#">
                <span class="icon-cog"></span>
            </a>
            <ul class="dropdown-menu place-right" data-role="dropdown">
                <li onclick="changeContent(12);"><a href="#">Configurar Cuenta</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </div>
        <?
        }
        ?>
        <span class="element-divider place-right"></span>

        <button class="element place-right" id="user-name-header">
            <? echo isset($_SESSION) ? '<i class="icon-user on-left"></i>'.$_SESSION['name'] : "";?>
        </button>
    </div>
</div>
</nav>
</header>