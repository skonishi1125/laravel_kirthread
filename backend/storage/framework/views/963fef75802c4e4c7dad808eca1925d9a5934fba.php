<?php $__env->startSection('content'); ?>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5>みんなの投稿</h5>
        </div>

        <div class="card-body">

          <div class="col-12">
            <?php if($errors->any()): ?>
              <div class="alert alert-danger">
                <ul>
                  <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
              </div>
            <?php endif; ?>
            <?php if(Auth::check() ): ?>
              <p><?php echo e(Auth::user()->name); ?> さん、こんにちは。</p>
              <form action="<?php echo e(route('store')); ?>" method="post" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                  <textarea class="form-control" id="post-message" name="message" placeholder="投稿したい内容を記入"><?php echo e(old('message')); ?></textarea>
                </div>
                
                <div class="form-group" style="font-size: small">
                  <label for="youtube_url">YouTubeの動画を載せる</label>
                  <input type="text" class="form-control form-control-sm" id="youtube_url" name="youtube_url" placeholder="https://www.youtube.com/watch?v=QLXbggM1GXk" value="<?php echo e(old('youtube_url')); ?>">
                </div>

                <div class="form-group" style="font-size: small; width: 250px">
                  <label for="post-picture">画像を添付する</label>
                  <input type="file" name="picture" id="post-picture" class="form-control-file">
                </div>


                  <button type="submit" class="btn btn-primary btn-sm post-button">投稿</button>

              </form>

              <?php else: ?>
              <label for="post-message">投稿するには<a href="<?php echo e(route('login')); ?>">ログイン</a>が必要です。</label>
              <?php endif; ?>
            </div>


            <hr class="my-3">

            
            <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('parts.post',['post => $post, reaction_icons => $reaction_icons'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="my-2" style="border-bottom:1px dotted #333;"></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div> <!-- col-12 -->

          <div class="mt-3 d-flex justify-content-center">
            <?php echo e($posts->links('vendor.pagination.original-pagination')); ?>

          </div>




        </div><!-- card-body -->
      </div><!-- card -->

    </div><!-- col-xs-12 -->

  </div>
</div>
<!-- jsを読み込むときは、backend/publicのパス記述を省略させる -->



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravel_kirthread/resources/views/index.blade.php ENDPATH**/ ?>