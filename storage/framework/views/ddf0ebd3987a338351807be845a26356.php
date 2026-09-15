<?php $__env->startSection('title', 'Login'); ?>
<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4">
            <h3 class="mb-3">Login</h3>
            <a href="<?php echo e(route('auth.google')); ?>" class="btn btn-outline-danger w-100 mb-3">Continue with Google</a>
            <div class="text-center text-muted mb-3">or</div>
            <form method="POST" action="<?php echo e(route('login')); ?>">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required value="<?php echo e(old('email')); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button class="btn btn-primary w-100" type="submit">Login</button>
            </form>
            <div class="d-flex justify-content-between mt-3 small">
                <a href="<?php echo e(route('password.request')); ?>">Forgot password?</a>
                <a href="<?php echo e(route('register')); ?>">Create an account</a>
            </div>
            <div class="alert alert-secondary small mt-3 mb-0">
                Demo accounts: <br>admin@jiggymart.test / seller@jiggymart.test / customer@jiggymart.test <br>Password: <code>password</code>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/auth/login.blade.php ENDPATH**/ ?>