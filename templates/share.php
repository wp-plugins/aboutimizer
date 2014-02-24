<?php if (!defined('ABSPATH')) exit; ?>
<span class="share" data-form="<?php echo Aboutimizer::SLUG ?>-donate">
    <a href="#donate" title="PayPal - The safer, easier way to pay online!" class="button button-donate"><?php _e('donate', Aboutimizer::SLUG) ?></a></li>
    <a href="http://wordpress.org/support/view/plugin-reviews/<?php echo urlencode(Aboutimizer::NAME_LOWER) ?>" class="button button-rate" target="_blank"><?php _e('rate', Aboutimizer::SLUG) ?></a>
    <a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode(Aboutimizer::DOWNLOAD_URL) ?>" class="button button-share" target="_blank"><?php _e('share', Aboutimizer::SLUG) ?></a>
    <a href="http://twitter.com/share?url=<?php echo urlencode(Aboutimizer::DOWNLOAD_URL) ?>&text=<?php echo urlencode(sprintf(__('%1$s is a WordPress plugin that adds a flexible about me widget your site. Check it out! #%2$s', Aboutimizer::SLUG), Aboutimizer::NAME, Aboutimizer::NAME_LOWER)) ?>" class="button button-tweet" target="_blank"><?php _e('tweet', Aboutimizer::SLUG) ?></a>
</span>