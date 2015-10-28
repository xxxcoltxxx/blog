<?php
/**
 * @var $user UserModel
 */
?>
<?php if ($user): ?>
    Здравствуйте, <?= $user->getDisplayName() ?>
    <a href="/user/logout" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-off"></i>  Выход</a>
<?php else: ?>
    <a href="/user/login">Вход</a>
<?php endif; ?>