<?php
/**
 * @var $this System\View
 * @var $user UserModel
 * @var $post PostModel
 * @var $message string
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

<?php if ($user): ?>
    Здравствуйте, <?= $user->getDisplayName() ?>
    <a href="/user/logout" class="btn btn-default btn-xs"><i class="glyphicon"></i> Выход</a>
<?php else: ?>
    <a href="/user/login">Вход</a>
<?php endif; ?>

<hr />
<?= nl2br($post->content) ?>
<hr />
<legend>Комментарии</legend>
<?php if (empty($post->getComments())): ?>
    <div class="alert alert-info">Нет ни одного комментария</div>
<?php else: ?>
    <?php foreach ($post->getComments() as $comment): ?>
        <div class="list-group">
            <h4 class="list-group-item-heading">
                <?= $comment->getDisplayName() ?>
                <small class="text-muted pull-right">
                    Создано: <?= date(DISPLAY_DATE_TIME, strtotime($comment->created_at)) ?>
                </small>
            </h4>
            <p class="list-group-item-text"><?= $comment->message ?></p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<legend>Добавить комментарий</legend>
<a name="comments"></a>
<form class="form-horizontal" method="post" action="/post/saveComment/<?= $post->id ?>">
    <?php if ($message): ?>
        <div class="alert alert-danger">
            <?= $message ?>
        </div>
    <?php endif; ?>
    <?php if (!$user): ?>
        <div class="form-group">
            <label for="name" class="control-label col-sm-2">Ваше имя:</label>
            <div class="col-sm-10">
                <input type="text" name="data[name]" id="name" class="form-control" />
            </div>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <label for="message" class="control-label col-sm-2">Комментарий</label>
        <div class="col-sm-10">
            <textarea name="data[message]" id="message" class="form-control"></textarea>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" value="Добавить" class="btn btn-default"/>
        </div>
    </div>
</form>
