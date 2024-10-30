<?php defined('ABSPATH') || exit;
$args = (object) $args;
?>
<div class="pagination">
    <?php foreach (range(1, $args->pagination->totalPages) as $page): ?>

        <?php $pageNumberCssClass =
            $args->pagination->currentPage == $page
                ? 'ht-selected-page-number '
                : '';

        $pageBackgroundColorCssClass =
            $args->pagination->currentPage == $page
                ? esc_attr(HEYRECRUIT_CUSTOM_COLOR)
                : '';
        ?>

        <span data-page="<?php echo $page; ?>" class="<?php echo $pageNumberCssClass; ?>hr-page-numbers" style="background-color: <?php echo $pageBackgroundColorCssClass; ?>">
                <?php echo $page; ?>
            </span>
    <?php endforeach; ?>
</div>