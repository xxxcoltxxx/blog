<?php
use Data\Handlers\Text;
/**
 * @var $this System\View
 * @var $user null|UserModel
 * @var $posts PostModel[]
 * @var $pagination \Utils\Pagination
 */
?>
<h1><?= $this->title ?></h1>

<?= $this->template("auth", compact("user")) ?>

<hr />

<?php if (empty($posts)): ?>
    <div class="alert alert-info">Нет ни одного поста</div>
<?php else: ?>
    <?php foreach ($posts as $post): ?>
        <div class="list-group">
            <h4 class="list-group-item-heading">
                <?= $post->title ?>
                <small class="text-muted pull-right">
                    Создано: <?= Text::formatDate(strtotime($post->created_at)) ?>
                </small>
            </h4>
            <p class="list-group-item-text"><?= nl2br(Text::cutStr($post->content, 1000)) ?></p>
            <p class="text-right">
                <?php if (!empty($user)): ?>
                    <a href="/post/edit/<?= $post->id ?>" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i> Редактировать</a>
                <?php endif; ?>
                <a href="/post/show/<?= $post->id ?>" class="btn btn-default btn-xs">
                    <i class="glyphicon glyphicon-comment"></i> <?= $post->getCommentCount() ?> Комментари<?= Text::getNumEnding($post->getCommentCount(), ["й", "я", "ев"]) ?>
                </a>
            </p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<hr />
<div class="text-center">
    <?= $pagination->getPagination('/index/index/{page}', true) ?>
</div>
