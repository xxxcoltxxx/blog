<?php
/**
 * @var $total_pages int
 * @var $current_page int
 * @var $url_pattern string
 * @var $indicator boolean
 * @var $max_display_pages int
 */

$border = floor($max_display_pages / 2);

?>
<?php if ($total_pages > 1): ?>
    <nav>
        <ul class="pagination">
            <li <?php if ($current_page == 1): ?> class="disabled" <?php endif; ?>>
                <a href="<?= str_replace('{page}', 1, $url_pattern) ?>">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <?php if ($total_pages > $max_display_pages && $i > $border && $i < $total_pages - $border): ?>
                    <?php if ($i == $border + 1): ?>
                        <li class="disabled">
                            <a href="#"><span aria-hidden="true">...</span></a>
                        </li>
                    <?php endif; ?>
                <?php continue; ?>
                <?php endif; ?>
                <li <?php if ($i == $current_page): ?> class="active" <?php endif; ?>>
                    <a href="<?= str_replace('{page}', $i, $url_pattern) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li <?php if ($current_page == $total_pages): ?> class="disabled" <?php endif; ?>>
                <a href="<?= str_replace('{page}', $total_pages, $url_pattern) ?>">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
    <?php if ($indicator): ?>
        Страница <?= $current_page ?> / <?= $total_pages ?>
    <?php endif; ?>
<?php endif; ?>