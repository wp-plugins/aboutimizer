<?php
class Aboutimizer_Widget extends WP_Widget {

    /*************************************************
     * VARIABLES
     ************************************************/

    // variables



    /*************************************************
     * INITIALIZE / CONFIGURE
     ************************************************/

    function __construct() {
        parent::__construct(
            Aboutimizer::SLUG,
            sprintf(__('%1$s', Aboutimizer::SLUG), Aboutimizer::NAME),
            array(
                'description' => __('Creates an about me widget that allows you to add a photo and a description.', Aboutimizer::SLUG)
            ),
            array(
                'width' => 600
            )
        );
    }



    /*************************************************
     * BACKEND
     ************************************************/

    public function getImageSizes(){
        global $_wp_additional_image_sizes;
        $sizes = array();
        foreach(get_intermediate_image_sizes() as $s){
            $sizes[$s] = array(0, 0);
            if( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ){
                $sizes[$s][0] = get_option( $s . '_size_w' );
                $sizes[$s][1] = get_option( $s . '_size_h' );
            } else {
                if ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) )
                    $sizes[$s] = array( $_wp_additional_image_sizes[$s]['width'], $_wp_additional_image_sizes[$s]['height'] );
            }
        }
        return $sizes;
    }

    public function getAttachmentUrlById($attachment_id, $size='medium', $crop=false) {
        $attachment = wp_get_attachment_image_src($attachment_id, $size, $crop);
        if (is_array($attachment))
            return $attachment[0];
        return false;
    }

    public function form( $instance ) {
        $image        = isset($instance['image']) ? $instance['image'] : '';
        $title        = isset($instance['title']) ? $instance['title'] : '';
        $description  = isset($instance['description']) ? $instance['description'] : '';
        $size         = isset($instance['size']) ? $instance['size'] : 'medium';
        ?>
        <div class="<?php echo Aboutimizer::SLUG ?>-widget">
            <div class="image">
                <input type="hidden" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" value="<?php echo $image; ?>" data-url="<?php echo $this->getAttachmentUrlById($image, 'medium') ?>" />
                <p class="hide-if-no-js">
                    <a href="#<?php echo Aboutimizer::SLUG ?>-upload" class="button <?php echo Aboutimizer::SLUG ?>-upload" data-uploader-title="<?php _e('Add image', Aboutimizer::SLUG) ?>" data-uploader-button-text="<?php _e('Set image', Aboutimizer::SLUG) ?>" data-remove-text="<?php _e('Remove image', Aboutimizer::SLUG) ?>" data-post-id="<?php echo $post->ID ?>"><?php _e('Select Image', Aboutimizer::SLUG) ?></a>
                </p>
                <p class="<?php echo Aboutimizer::SLUG ?>-upload-preview"></p>
            </div>
            <div class="text">
                <p>
                    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', Aboutimizer::SLUG) ?></label>
                    <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>"/>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description', Aboutimizer::SLUG) ?></label>
                    <textarea class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>"><?php echo $description; ?></textarea>
                </p>
                <p>
                  <select class="widefat" id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>">
                      <?php foreach ($this->getImageSizes() as $name => $atts): ?>
                          <option value="<?php echo $name ?>"<?php echo ($name==$size?' selected':'') ?>><?php echo ucwords($name) . ' ('. implode( 'x', $atts ). ')' ?></option>
                      <?php endforeach ?>
                  </select>
                </p>
            </div>
            <?php require Aboutimizer::$instance->getPluginPath().'/templates/share.php' ?>
        </div>
        <script>
            if (typeof window.aboutimizer_generate == 'function')
                window.aboutimizer_generate();
        </script>
    <?php
    }

    public function update( $new_instance, $old_instance ) {
        delete_transient($this->transientId);
        $instance = array();
        $instance['image']        = (!empty($new_instance['image'])) ? (int)$new_instance['image'] : '';
        $instance['title']        = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['description']  = (!empty($new_instance['description'])) ? strip_tags($new_instance['description'], '<a>') : '';
        $instance['size']        = (!empty($new_instance['size'])) ? strip_tags($new_instance['size']) : 'medium';
        return $instance;
    }



    /*************************************************
     * FRONTEND
     ************************************************/

    function widget($args, $instance) {
        //$output = get_transient($this->transientId);
        //if (empty($transient)) {
        $instance['title'] = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $output .= $args['before_widget'];
        $output .= '<div class="inner">';
        // title
        if ($instance['title'])
            $output .= $args['before_title'].$instance['title'].$args['after_title'];
        // image
        if ($image = wp_get_attachment_image_src($instance['image'], $instance['size'])) {
            $output .= '<p class="image">';
            $output .= '<img src="'.$image[0].'" alt="'.__('About me', Aboutimizer::SLUG).'" width="'.$image[1].'" height="'.$image[2].'" />';
            $output .= '</p>';
        }
        if ($instance['description']) {
            $output .= wpautop($instance['description']);
        }
        $output .= '</div>';
        $output .= $args['after_widget'];
        //set_transient($this->transientId, $output, 12 * HOUR_IN_SECONDS);
        //$transient = $output;
        //}
        echo $output;
    }

}
function aboutimizer_widget_init() {
    register_widget('Aboutimizer_Widget');
}
add_action('widgets_init', 'aboutimizer_widget_init');