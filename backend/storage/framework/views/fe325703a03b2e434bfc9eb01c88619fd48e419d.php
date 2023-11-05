<?php if($paginator->hasPages()): ?>
    <ul class="pagination" role="navigation">
        
        <li class="page-item <?php echo e($paginator->onFirstPage() ? ' disabled' : ''); ?>">
          <a class="page-link" href="<?php echo e($paginator->url(1)); ?>">&laquo;</a>
        </li>

        
          <!-- 前のページへのリンク -->
          <li class="page-item <?php echo e($paginator->onFirstPage() ? ' disabled' : ''); ?>">
            <a class="page-link" href="<?php echo e($paginator->previousPageUrl()); ?>">&lsaquo;</a>
          </li>

        
            
            
            <?php if($paginator->lastPage() > config('paginate.PAGINATE.LINK_NUM')): ?>

                
                <?php if($paginator->currentPage() <= floor(config('paginate.PAGINATE.LINK_NUM') / 2)): ?>
                    <?php $start_page = 1; //最初のページ ?> 
                    <?php $end_page = config('paginate.PAGINATE.LINK_NUM'); ?>

                
                <?php elseif($paginator->currentPage() > $paginator->lastPage() - floor(config('paginate.PAGINATE.LINK_NUM') / 2)): ?>
                    <?php $start_page = $paginator->lastPage() - (config('paginate.PAGINATE.LINK_NUM') - 1); ?>
                    <?php $end_page = $paginator->lastPage(); ?>

                
                <?php else: ?>
                    <?php $start_page = $paginator->currentPage() - (floor((config('paginate.PAGINATE.LINK_NUM') % 2 == 0 ? config('paginate.PAGINATE.LINK_NUM') - 1 : config('paginate.PAGINATE.LINK_NUM'))  / 2)); ?>
                    <?php $end_page = $paginator->currentPage() + floor(config('paginate.PAGINATE.LINK_NUM') / 2); ?>
                <?php endif; ?>

            
            <?php else: ?>
                <?php $start_page = 1; ?>
                <?php $end_page = $paginator->lastPage(); ?>
            <?php endif; ?>

            
            <?php for($i = $start_page; $i <= $end_page; $i++): ?>
                <?php if($i == $paginator->currentPage()): ?>
                    <li class="page-item active"><span class="page-link"><?php echo e($i); ?></span></li>
                <?php else: ?>
                    <li class="page-item"><a class="page-link" href="<?php echo e($paginator->url($i)); ?>"><?php echo e($i); ?></a></li>
                <?php endif; ?>
            <?php endfor; ?>

          
            <li class="page-item <?php echo e($paginator->currentPage() == $paginator->lastPage() ? ' disabled' : ''); ?>">
              <a class="page-link" href="<?php echo e($paginator->nextPageUrl()); ?>">&rsaquo;</a>
            </li>

          
          <li class="page-item <?php echo e($paginator->currentPage() == $paginator->lastPage() ? ' disabled' : ''); ?>">
            <a class="page-link" href="<?php echo e($paginator->url($paginator->lastPage())); ?>">&raquo;</a>
          </li>

    </ul>
<?php endif; ?><?php /**PATH /var/www/laravel_kirthread/resources/views/vendor/pagination/original-pagination.blade.php ENDPATH**/ ?>