<div class="container post-container">
  <div class="row">

      
      <div class="col-auto">
        <?php if(is_null($post->user->icon)): ?> 
          <img class="profile-icon" src="<?php echo e(asset('storage/icons/' . 'default.png')); ?>" alt="p_icon">
        <?php else: ?>
          <img class="profile-icon" src="<?php echo e(asset('storage/icons/' . $post->user->icon)); ?>" alt="p_icon">
        <?php endif; ?>
      </div>
      
      <div class="col-auto name-wrapper">
        <span><b><?php echo e($post->user->name); ?></b></span>
        <br>
        <small><a href="<?php echo e(route('show', ['id' => $post->id])); ?>">id:[<?php echo e($post->id); ?>]</a></small>
        <small><?php echo e($post->created_at); ?></small>

        
        <div class="reaction-wrapper">
          <?php if(Auth::id() === $post->user_id): ?>
          <form action="<?php echo e(route('destroy')); ?>" method="post" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="post_id" value="<?php echo e($post->id); ?>">
            <button type="submit" class="trash clearButton">
              <span class="material-icons">delete</span>
            </button>
          </form>
          <?php else: ?>
            <?php if(Auth::check()): ?>
              <span class="material-icons reaction" style="z-index: 10; user-select:none;">add_reaction</span>
            <?php endif; ?>
          <?php endif; ?>
        </div>

        
        
        <?php echo $__env->make('parts.reaction.hukidashi', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

      </div>

    <div class="col-12">
      <p><?php echo nl2br($post->makeLink(e($post->message))); ?></p>

      <?php if(isset($post->youtube_url)): ?>
        <div class="embed-responsive embed-responsive-16by9">
          <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo e($post->youtube_url); ?>" allowfullscreen></iframe>
        </div>
        <hr>
      <?php endif; ?>

      <?php if(isset($post->picture)): ?>
        <div class="text-center mb-3">
          <img class="img-fluid post-image" src="<?php echo e(asset('storage/uploads/' . $post->picture)); ?>" alt="画像">
        </div>
      <?php endif; ?>

      <ul class="reaction-icons reaction-buttons">
        <?php if(isset($post->reaction)): ?>
          
          <?php echo $__env->make('parts.reaction.button', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

          
        <?php endif; ?>
      </ul>

    </div>

  </div>
</div>
<?php /**PATH /var/www/laravel_kirthread/resources/views/parts/post.blade.php ENDPATH**/ ?>