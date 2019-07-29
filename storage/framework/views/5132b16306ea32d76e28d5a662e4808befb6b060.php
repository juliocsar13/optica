<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema de Ópticas">
    <meta name="author" content="">
    <meta name="keyword" content="">
    <link rel="shortcut icon" href="img/favicon.png">

    <!-- Id for Channel Notification -->
    <meta name="userId" content="<?php echo e(Auth::check() ? Auth::user()->id : ''); ?>">

    <title>Sistema de Ópticas</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js">
    <!-- Icons -->
    <link href="css/template.css" rel="stylesheet">

</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <div id="app">
    <header class="app-header navbar">
        <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="nav navbar-nav d-md-down-none">
            <li class="nav-item px-3">
                <a class="nav-link" href="#">Escritorio</a>
            </li>
            <li class="nav-item px-3">
                <a class="nav-link" href="#">Configuración</a>
            </li>
        </ul>
        <ul class="nav navbar-nav ml-auto">
            <notificacion :notifications="notifications"></notificacion>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                    <span class="d-md-down-none"><?php echo e(Auth::user()->usuario); ?> </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header text-center">
                        <strong>Cuenta</strong>
                    </div>
                    <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-lock"></i> Cerrar sesión</a>

                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;" >
                        <?php echo e(csrf_field()); ?>

                    </form>
                </div>
            </li>
        </ul>
    </header>

    <div class="app-body">

        <?php if(Auth::check()): ?>
            <?php if(Auth::user()->idrol == 1): ?>
                <?php echo $__env->make('template.sidebaradministrador', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php elseif(Auth::user()->idrol == 2): ?>
                <?php echo $__env->make('template.sidebarvendedor', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php elseif(Auth::user()->idrol == 3): ?>
                <?php echo $__env->make('template.sidebaralmacenero', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php else: ?>

            <?php endif; ?>

        <?php endif; ?>
        <!-- Contenido Principal -->
        <?php echo $__env->yieldContent('content'); ?>
        <!-- /Fin del contenido principal -->
    </div>
    </div>
    <footer class="app-footer">
        <span><a href="http://www.#.com/">Optical Management SIGAP Software ERP - CRM</a> &copy; 2018</span>
        <span class="ml-auto">Desarrollado por <a href="http://www.#.com/">Ledvir Ventura Acosta</a></span>
    </footer>

    <script src="js/app.js"></script>
    <script src="js/template.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</body>

</html>
