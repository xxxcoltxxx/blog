<?php
/**
 * @var $this System\View
 * @var $message string
 */
?>
<h1><?= $this->title ?></h1>
<h2 class="text-center">Вход в систему</h2>

<div class="col-lg-6 col-lg-offset-3 ">
    <form action="/user/login" method="post" class="form-horizontal">

        <?php if (!empty($message)): ?>
            <div class="alert alert-danger"><?= $message ?></div>
        <? endif; ?>

        <div class="form-group">
            <label for="email" class="control-label col-sm-2">Email</label>
            <div class="col-sm-10">
                <input type="email" name="email" id="email" class="form-control" />
            </div>
        </div>

        <div class="form-group">
            <label for="password" class="control-label col-sm-2">Пароль</label>
            <div class="col-sm-10">
                <input type="password" name="password" id="password" class="form-control" />
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-lg-10">
                <input type="submit" class="btn btn-default" value="Вход" />
            </div>
        </div>
    </form>
</div>
