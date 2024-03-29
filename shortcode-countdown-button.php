<?php
/*
 * Cách dùng

1. nếu là mã số thì dùng shortcode như sau [button_countdown code="123456"][/button_countdown] trong đó 123456 thay bằng mã số của bạn.
2. nếu là nội dung khác thì dùng
[button_countdown]Content here - có thể tự chèn link vào[/button_countdown] với content bạn thay đổi tùy ý

Chú ý: mặc định time là 10s. Bạn có thể đổi chỉ cần thêm time vào shortcode. ví dụ [button_countdown time="20" code="123456"][/button_countdown]

 * */
add_shortcode('button_countdown', 'devvn_button_countdown_func');
function devvn_button_countdown_func($atts, $content)
{
    $atts = shortcode_atts(array(
        'time' => 60,
        'code' => '',
        'title' => 'Nhấp vào đây để lấy mã bảo mật'
    ), $atts, 'button_countdown');

    $time = isset($atts['time']) ? intval($atts['time']) : 10;
    $code = isset($atts['code']) ? sanitize_text_field($atts['code']) : '';
    $title = isset($atts['title']) ? sanitize_text_field($atts['title']) : '';

    $id = time() . rand(0,99);

    ob_start();
    ?>
    <button style="background: #e81e1e;border-radius: 10px;border:none;color: #ffffff;width: 59%;padding: 10px 0;text-transform: uppercase;font-weight: bold;font-size: 16px;outline: none; cursor: pointer;" id="countDown_<?php echo $id;?>" get-code="true" class="coundownmobile" onclick="startcountdown_<?php echo $id;?>(); this.onclick=null;">
        <?php echo $title;?>
    </button>
    <?php if($content):?>
    <div style="display: none;" id="content_countDown_<?php echo $id;?>"><?php echo $content;?></div>
    <?php endif;?>
    <script type="text/javascript">
        function startcountdown_<?php echo $id;?>(){
            document.getElementById('countDown_<?php echo $id;?>').setAttribute("style", "background: #0b1df5;border-radius: 10px;border:none;color: #ffffff;width: 59%;padding: 10px 0;text-transform: uppercase;font-weight: bold;font-size: 16px;outline: none; cursor: pointer;");
            var counter=<?php echo $time;?>;
            var $code = '<?php echo $code;?>';
            var $content = '<?php echo esc_attr(wp_strip_all_tags($content));?>';
            var countdownCode_<?php echo $id;?>=setInterval(function(){
                counter--
                document.getElementById('countDown_<?php echo $id;?>').innerHTML='Nội dung sẽ hiện sau ' + counter + ' giây';
                if(counter==0){
                    if($code) {
                        document.getElementById('countDown_<?php echo $id;?>').innerHTML = 'Mã bảo mật của bạn là: ' + $code;
                        document.getElementById('countDown_<?php echo $id;?>').setAttribute("style", "background: #ea3b7b;border-radius: 10px;border:none;color: #ffffff;width: 59%;padding: 10px 0;text-transform: uppercase;font-weight: bold;font-size: 16px;outline: none; cursor: pointer;");
                    }else if($content){
                        document.getElementById('countDown_<?php echo $id;?>').remove();
                        document.getElementById('content_countDown_<?php echo $id;?>').setAttribute("style","display: inline");
                    }
                    clearInterval(countdownCode_<?php echo $id;?>);
                    return false;
                }}, 1000);
        }
    </script>
    <?php
    return ob_get_clean();

}

/*Flatsome element*/
function devvn_button_countdown_builder_element(){

    add_ux_builder_shortcode( 'button_countdown', array(
        'type' => 'container',
        'name' => __( 'Button Countdown', 'devvn'),
        'category' => __( 'Content' ),
        'compile' => false,
        'template_shortcode' => function ( $element, $options, $content, $parent = null ) {
            if (
                ! empty( $options )
            ) {
                return "[button_countdown{options}]\n\n{content}\n[/button_countdown]\n";
            }
            return "[button_countdown]\n\n{content}\n[/button_countdown]\n";
        },
        'directives' => array( 'ux-text-editor' ),
        'priority' => 1,

        'presets' => array(
            array(
                'name' => __( 'Ví dụ' ),
                'content' => '[button_countdown]<p>Nội dung cần ẩn</p>[/button_countdown]'
            )
        ),

        'options' => array(
            '$content' => array(
                'type'       => 'text-editor',
                'full_width' => true,
                'height'     => 'calc(100vh - 691px)',
            ),
            'code' => array(
                'type' => 'textfield',
                'heading' => 'Mật khẩu cần ẩn',
                'default' => '',
            ),
            'time' => array(
                'type' => 'textfield',
                'heading' => 'Thời gian của nút (s)',
                'default' => '60',
            ),
            'title' => array(
                'type' => 'textfield',
                'heading' => 'Chữ của nút',
                'default' => 'Nhấp vào đây để lấy mã bảo mật',
            )
        )
    ) );

}
add_action('ux_builder_setup', 'devvn_button_countdown_builder_element');
