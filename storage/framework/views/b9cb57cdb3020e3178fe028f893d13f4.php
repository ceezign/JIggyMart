<nav class="navbar navbar-expand-lg navbar-dark navbar-jm sticky-top py-3">
    <div class="container">
        <a class="navbar-brand" href="<?php echo e(route('home')); ?>">Jiggy<span class="brand-dot">Mart</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <form class="d-flex mx-auto my-2 my-lg-0" style="max-width: 480px; width: 100%;" action="<?php echo e(route('products.index')); ?>" method="GET">
                <input class="form-control me-0" type="search" name="search" placeholder="Search products, brands, categories…" value="<?php echo e(request('search')); ?>">
                <button class="btn btn-accent btn-search" type="submit">Search</button>
            </form>
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link" href="<?php echo e(route('products.index')); ?>">Products</a></li>
                <?php if(auth()->guard()->check()): ?>
                    <?php ($u = auth()->user()); ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('wishlist.index')); ?>">♥ Wishlist</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('cart.index')); ?>">🛍 Cart</a></li>

                    <?php if($u->isAdmin()): ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo e(route('dashboard.admin')); ?>">Admin</a></li>
                    <?php endif; ?>

                    
                    <?php if($u->hasRole('seller') && $u->seller_status === 'approved'): ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo e(route('dashboard.seller')); ?>">Seller Hub</a></li>
                    <?php elseif($u->hasRole('seller') && $u->seller_status === 'pending'): ?>
                        <li class="nav-item"><span class="badge badge-pending">Seller: Pending Approval</span></li>
                    <?php elseif($u->hasRole('seller') && $u->seller_status === 'rejected'): ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo e(route('seller.register')); ?>">Seller application rejected — Re-apply</a></li>
                    <?php elseif(! $u->isAdmin()): ?>
                        
                        <li class="nav-item"><a class="nav-link" href="<?php echo e(route('seller.register')); ?>">Sell on JiggyMart</a></li>
                    <?php endif; ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><?php echo e($u->name); ?></a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo e(route('dashboard.customer')); ?>">Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('orders.index')); ?>">My Orders</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('addresses.index')); ?>">Addresses</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button class="dropdown-item" type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('login')); ?>">Login</a></li>
                    <li class="nav-item"><a class="btn btn-accent btn-sm ms-lg-2" href="<?php echo e(route('register')); ?>">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/components/navbar.blade.php ENDPATH**/ ?>