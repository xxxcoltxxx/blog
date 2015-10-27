<?php
/**
 * @var $this \System\View
 * @var $post null|PostModel
 */
use Data\Handlers\Text;

?>

<h1><?= $this->title ?></h1>

<form class="form-horizontal" method="post" action="/post/save/<?= !empty($post) ? $post->id : "" ?>">
    <div class="form-group">
        <label for="title" class="control-label col-sm-2">Название поста</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="data[title]" id="title" value="<?= !empty($post) ? Text::htmlEncode($post->title) : "" ?>" />
        </div>
    </div>

    <div class="form-group">
        <label for="content" class="control-label col-sm-2">Текст поста</label>
        <div class="col-sm-10">
            <textarea rows="20" class="form-control" name="data[content]" id="content"><?= !empty($post) ? Text::htmlEncode($post->content) : "" ?></textarea>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" value="Сохранить" class="btn btn-default" />
        </div>
    </div>
</form>