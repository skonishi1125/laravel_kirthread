<?php if(Auth::check()): ?>
<div class="reaction-modal d-none">
  <ul class="reaction-icons">

    <?php $__currentLoopData = $reaction_icons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php if($r->is_picture_icon): ?>
        <form action="<?php echo e(route('select_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_icon_id' => $r->id])); ?>" method="post" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <li>
            <button type="submit" class="clearButton">
                <img src="<?php echo e(asset('storage/reaction_icons/' . $r->url)); ?>" alt="reaction_<?php echo e($r->id); ?>">
            </button>
            </li>
        </form>
      <?php else: ?>
        <li>
          <a role="button" class="reactions <?php echo e($r->name_plural); ?>" data-reaction="<?php echo e($r->id); ?>" data-postid="<?php echo e($post->id); ?>" data-userid="<?php echo e(Auth::user()->id); ?>">
            <?php echo e($r->value); ?>

          </a>
        </li>
      <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

  </ul>
</div>
<?php endif; ?><?php /**PATH /var/www/laravel_kirthread/resources/views/parts/reaction/hukidashi.blade.php ENDPATH**/ ?>