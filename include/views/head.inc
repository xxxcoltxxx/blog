<?php
/**
 * @var \System\View $this
 */
?>
<title><?= $this->title ?></title>
<meta charset="<?= $this->charset ?>" />

<?php foreach ($this->css as $css): ?>
    <link rel="stylesheet" type="text/css" href="<?= $css ?>?uid=<?= mt_rand(100000,999999) ?>" />
<?php endforeach; ?>

<?php foreach ($this->js as $js): ?>
    <script language="javascript" type="text/javascript" src="<?= $js['src'] ?>" <? if($js['charset']): ?> charset="<?= $js['charset'] ?>"<?php endif; ?>>
    </script>
<?php endforeach; ?>
