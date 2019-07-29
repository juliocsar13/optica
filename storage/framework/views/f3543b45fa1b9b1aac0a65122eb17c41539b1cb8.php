<?php $__env->startSection('login'); ?>
<!-- <body background="img\fondo.jpeg" class="background-size" alt="">> -->
<body background="img\fondo.jpeg">

<div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card-group mb-0">
          <div class="card p-4">
          <form class="form-horizontal was-validated" method="POST" action="<?php echo e(route('login')); ?>">
          <?php echo e(csrf_field()); ?>

            <div class="card-body">
              <h1>Acceder</h1>
              <p class="text-muted">Control de acceso al sistema</p>
              <div class="form-group mb-3<?php echo e($errors->has('usuario' ? 'is-invalid' : '')); ?>">
                <span class="input-group-addon"><i class="icon-user"></i></span>
                <input type="text" value="<?php echo e(old('usuario')); ?>" name="usuario" id="usuario" class="form-control" placeholder="Usuario">
                <?php echo $errors->first('usuario','<span class="invalid-feedback">:message</span>'); ?>

              </div>
              <div class="form-group mb-4<?php echo e($errors->has('password' ? 'is-invalid' : '')); ?>">
                <span class="input-group-addon"><i class="icon-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                <?php echo $errors->first('password','<span class="invalid-feedback">:message</span>'); ?>

              </div>
              <div class="row">
                <div class="col-6">
                  <button type="submit" class="btn btn-primary px-4">Acceder</button>
                </div>
              </div>
            </div>
          </form>
          </div>
          <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
            <div class="card-body text-center">
              <div>
                <h2>Sistema de Óptica</h2>
                <p>Sistema de Ópticas.</p>
                <a href="#" target="_blank" class="btn btn-primary active mt-3">Optical Management SIGAP Software!</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('auth.content', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>