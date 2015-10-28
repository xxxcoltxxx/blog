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

<?php if ($user): ?>
    <a href="/post/add" class="btn btn-default pull-right"><i class="glyphicon glyphicon-plus-sign"></i> Добавить пост</a>
<?php endif; ?>
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

            <p class="list-group-item-text">
                <?= nl2br(Text::cutStr($post->content, 400)) ?>
                <a href="/post/show/<?= $post->id ?>">Подробнее</a>
            </p>
            <br />

            <a href="/post/show/<?= $post->id ?>" class="btn btn-default btn-xs">
                <i class="glyphicon glyphicon-comment"></i> <?= $post->getCommentCount() ?> Комментари<?= Text::getNumEnding($post->getCommentCount(), ["й", "я", "ев"]) ?>
            </a>
            <div class="pull-right">
                <?php if (!empty($user) && $user->id == $post->user_id): ?>
                    <a href="/post/edit/<?= $post->id ?>" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i> Редактировать</a>
                    <a href="/post/delete/<?= $post->id ?>" class="btn btn-danger btn-xs confirm"><i class="glyphicon glyphicon-trash"></i> Удалить</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<hr />
<div class="text-center">
    <?= $pagination->getPagination('/index/index/{page}', true) ?>
</div>
