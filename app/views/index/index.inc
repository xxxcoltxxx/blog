<?php
/**
 * @var $this System\View
 * @var $user null|UserModel
 * @var $posts PostModel[]
 */
?>
<h1><?= $this->title ?></h1>

<?php if ($user): ?>
    <a href="/post/add" class="btn btn-default pull-right"><i class="glyphicon glyphicon-plus-sign"></i> Добавить пост</a>
    Здравствуйте, <?= $user->getDisplayName() ?>
    <a href="/user/logout" class="btn btn-default btn-xs"><i class="glyphicon"></i> Выход</a>
<?php endif; ?>

<hr />

<?php if (empty($posts)): ?>
    <div class="alert alert-info">Нет ни одного поста</div>
<?php else: ?>
    <?php foreach ($posts as $post): ?>
        <div class="list-group">
            <h4 class="list-group-item-heading">
                <?= $post->title ?>
                <small class="text-muted pull-right">
                    Создано: <?= date(DISPLAY_DATE_TIME, strtotime($post->created_at)) ?>
                </small>
            </h4>
            <p class="list-group-item-text"><?= $post->content ?></p>
            <p class="text-right">
                <?php if (!empty($user)): ?>
                    <a href="/post/edit/<?= $post->id ?>" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i> Редактировать</a>
                <?php endif; ?>
                <a href="/post/show/<?= $post->id ?>" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-comment"></i> Комментарии</a>
            </p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

