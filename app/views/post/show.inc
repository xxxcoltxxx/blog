<?php
/**
 * @var $this System\View
 * @var $post PostModel
 */
?>
<ol class="breadcrumb">
    <li><a href="/">Блог</a></li>
    <li class="active"><?= $post->title ?></li>
</ol>

<div class="text-muted pull-right">
    <small>Создан: <?= date(DISPLAY_DATE_TIME, strtotime($post->created_at)); ?>
        <?php if (!is_null($post->updated_at)): ?>
            (изменен <?= date(DISPLAY_DATE_TIME, strtotime($post->updated_at)); ?>)
        <?php endif; ?>
    </small>
</div>
<h1><?= $this->title ?></h1>
<hr />
<?= $post->content ?>
