<header>
<nav class="navigation-bar fixed-top">
<div class="navigation-bar-content">
    <a href="home.php" class="element"><span class="icon-tree-view on-left-more"></span> InClass Assitant </a>
    <span class="element-divider"></span>

    <a class="pull-menu" href="#"></a>
    <!--<ul class="element-menu">
        <li>
            <a class="dropdown-toggle" href="#">Menú</a>
            <ul class="dropdown-menu " data-role="dropdown">
                <li><a href="#">Test</a></li>
                <li>
                    <a href="#" class="dropdown-toggle">Test</a>
                    <ul class="dropdown-menu" data-role="dropdown">
                        <li><a href="#">Test</a></li>
                        <li><a href="#">Test</a></li>
                        <div class="divider"></div>
                        <li><a href="#">Test</a></li>
                        <li><a href="#">Test</a></li>
                        <li><a href="#">Test</a></li>
                        <li><a href="#">Test</a></li>
                        <li><a href="#">Test</a></li>
                    </ul>
                </li>
                <li class="divider"></li>
                <li><a href="#">Test 2</a></li>
                <li class="disabled"><a href="#">Test 2</a></li>
                <li class="divider"></li>
                <li><a href="#">Test 2</a></li>
            </ul>
        </li>
    </ul>-->

    <div class="no-tablet-portrait">
        <!--<span class="element-divider"></span>
        <a class="element brand" href="#"><span class="icon-spin"></span></a>
        <span class="element-divider"></span>

        <div class="element input-element">
            <form>
                <div class="input-control text">
                    <input type="text" placeholder="Search...">
                    <button class="btn-search"></button>
                </div>
            </form>
        </div>-->
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