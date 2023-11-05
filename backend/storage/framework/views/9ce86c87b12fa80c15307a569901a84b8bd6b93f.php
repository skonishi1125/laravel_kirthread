<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>
      <?php if(config('app.env') !== 'production'): ?>
        <?php echo e(config('app.env')); ?>

      <?php endif; ?>
      <?php echo e(config('app.name', 'Laravel')); ?>

    </title>

    <!-- Scripts -->
    <script src="<?php echo e(asset('js/app.js')); ?>" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/style.css').'?'.time()); ?>" rel="stylesheet">

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                    <?php echo e(config('app.name', 'Laravel')); ?>

                    <small>v1.1</small>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="<?php echo e(__('Toggle navigation')); ?>">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <span id="now-time"></span>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        <?php if(auth()->guard()->guest()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo e(route('login')); ?>"><?php echo e(__('Login')); ?></a>
                            </li>
                            <?php if(Route::has('register')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo e(route('register')); ?>"><?php echo e(__('Register')); ?></a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item">
                              <a href="<?php echo e(route('about')); ?>" class="nav-link">かあスレッドとは</a>
                            </li>

                            <li class="nav-item">
                              <a href="<?php echo e(route('game')); ?>" class="nav-link">ゲーム</a>
                            </li>
                        <?php else: ?>
                            <?php if(isset(Auth::user()->icon)): ?>
                                <img class="profile-icon" src="<?php echo e(asset('storage/icons/' . Auth::user()->icon)); ?>">
                            <?php else: ?>
                                <img class="profile-icon" src="<?php echo e(asset('storage/icons/default.png' . Auth::user()->icon)); ?>">
                            <?php endif; ?>
                            <li class="nav-item dropdown">

                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <?php echo e(Auth::user()->name); ?> <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                  <a class="dropdown-item" href="<?php echo e(route('config.index')); ?>">
                                      <?php echo e(__('Config')); ?>

                                  </a>
                                    <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <?php echo e(__('Logout')); ?>

                                    </a>

                                    <a class="dropdown-item" href="<?php echo e(route('about')); ?>">かあスレッドとは</a>
                                    <a class="dropdown-item" href="<?php echo e(route('game')); ?>">ゲーム</a>
                                    <a id="csv-download" class="dropdown-item" href="<?php echo e(route('study_download_post_csv', ['user_id' => Auth::id()])); ?>">
                                        CSVでDL
                                    </a>

                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
    <script type="text/javascript" src="<?php echo e(asset('js/stopwatch.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/script.js?20230312')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/csv_download.js?20230130')); ?>"></script>
    
    <script type="text/javascript">
      var path_to_image = '<?php echo e(asset('')); ?>';
    </script>
</body>
</html>
<?php /**PATH /var/www/laravel_kirthread/resources/views/layouts/app.blade.php ENDPATH**/ ?>