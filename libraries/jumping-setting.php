<?php
/**
 * @name theme setting
 * @description 主题配置
 * @version     1.0.0
 * @author      锅子 (http://www.gzpblog.com)
 * @package     Jumping
 **/
class jumping_setting {
    private $defaults;

    public function __construct() {
        $this->defaults = array(
            array(
                'title' => '网站描述description',
                'key'   => 'description',
                'type'  => 'textarea',
                'value' => false,
                'label' => '对你的网站进行描述'
            ),
            array(
                'title' => '网站关键词keywords',
                'key'   => 'keywords',
                'type'  => 'textarea',
                'value' => false,
                'label' => '多个关键词请用英文逗号隔开'
            ),
            array(
                'title' => '首页、分类内容',
                'key'   => 'full-content',
                'type'  => 'checkbox',
                'value' => 1,
                'text'  => '显示全文',
                'label' => '默认首页和分类页文章只显示摘要，勾选开启显示全文后将全文显示。'
            ),
            array(
                'title' => '缩略图',
                'key'   => 'thumbnail',
                'type'  => 'checkbox',
                'value' => 1,
                'text'  => '只显示特色图片',
                'label' => '默认没有特色图片时缩略图会显示文章中插入的图片，勾选后，首页和归档页面只显示特色图片的缩略图，文章中插入的图片缩略图将不会显示。'
            ),
            array(
                'title' => '底部footer布局',
                'key'   => 'footerType',
                'type'  => 'radio',
                'value' => array(
                    '0' => '默认',
                    '1' => '显示微信二维码布局',
                ),
                'label' => '选择微信二维码布局将在底部footer显示有微信公众号二维码的布局，并切记将二维码图片上传至主题目录下的public/images下，并更改名称为wechatPic.png，以确保图片正常显示。'
            ),
        );

        // Add theme setting menu and page
        add_action( 'admin_menu', array( $this, 'menu' ) );

        // Register wordpress plugins install extention
        add_action( 'tgmpa_register', array( $this, 'plugins' ) );
    }

    public function menu() {
        add_menu_page( JUMPING_NAME, JUMPING_NAME, 'manage_options', 'jumping_setting', array(
            $this,
            'page'
        ), jumping_image( 'setting-icon.png' ), 59 );

        add_submenu_page( 'jumping_setting', JUMPING_NAME . ' 设置', '设置', 'edit_themes', 'jumping_setting', array(
            $this,
            'page'
        ) );
        add_submenu_page( 'jumping_setting', JUMPING_NAME . ' 说明', '说明', 'edit_themes', 'jumping_help', array(
            $this,
            'help'
        ) );

        add_action( 'admin_init', array( $this, 'settings_group' ) );
    }

    public function page() {
        if ( isset( $_REQUEST['settings-updated'] ) ) { ?>
            <div id="message" class="updated fade">
                <p><strong>主题设置已保存.</strong></p>
            </div>
            <?php
        }

        ?>

        <div class="wrap">
            <h2>主题设置</h2>

            <form method="post" action="options.php">
                <?php settings_fields( 'jumping-settings-group' ); ?>
                <table class="form-table">
                    <tbody>
                    <?php
                    foreach ( $this->defaults as $key => $arr ) {
                        ?>
                        <tr valign="top">
                            <th scope="row">
                                <label><?php echo $arr['title']; ?></label>
                            </th>
                            <td>
                                <p>
                                    <?php $this->build( $arr ); ?>
                                </p>
                                <?php
                                if ( $arr['label'] ) {
                                    printf( '<p class="description">%s</p>', $arr['label'] );
                                }
                                ?>
                            </td>
                        </tr>
                    <?php }
                    ?>
                    </tbody>
                </table>
                <input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>"/>
            </form>
        </div>

        <?php
    }

    public function help() {
        wp_enqueue_style( 'jumping-help', jumping_style( 'setting-help.css' ) );
        ?>
        <div class="wrap">
            <h1>欢迎使用<?php echo JUMPING_NAME; ?></h1>

            <div class="about-text">当前版本为：<?php echo JUMPING_VERSION; ?></div>
            <div class="changelog">
                <div class="feature-section under-the-hood one-col">
                    <div class="col">
                        <h3>主题说明</h3>
                        <ul>
                            <li>主题提供五个已有样式的侧边栏小工具：热门文章，最新文章，标签云，最新评论，归档；</li>
                            <li>暂时不支持后台更新。</li>
                            <li>禁止加载Google Fonts???</li>
                            <li>关闭了Wordpress默认要输出的 head 部分功能???</li>
                        </ul>
                        <p>
                            依赖插件：WP-PostViews（文章浏览量统计插件）
                        </p>
                        <p>
                            小图标：采用<a href="http://fontawesome.dashgame.com/" target="_blank">Font Awesome v4.7.0</a>，具体图标对应class请前往官网查看。
                        </p>
                    </div>
                </div>

                <div class="feature-section under-the-hood one-col">
                    <div class="col">
                        <h3>共同努力</h3>

                        <p>欢迎对该主题进行完善：<a href="">url</a></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function settings_group() {
        register_setting( 'jumping-settings-group', JUMPING_NAME . '_settings' );
    }

    public function plugins() {
        $plugins = array(
            array(
                'name'     => 'WP-PostViews ',
                'slug'     => 'wp-postviews',
                'required' => false,
            )
        );

        /**
         * Array of configuration settings. Amend each line as needed.
         * If you want the default strings to be available under your own theme domain,
         * leave the strings uncommented.
         * Some of the strings are added into a sprintf, so see the comments at the
         * end of each line for what each argument will be.
         */
        $config = array(
            'domain'       => JUMPING_NAME,           // Text domain - likely want to be the same as your theme.
            'default_path' => '',                          // Default absolute path to pre-packaged plugins
            'has_notices'  => true,                        // Show admin notices or not
            'is_automatic' => true,                        // Automatically activate plugins after installation or not
            'message'      => '',                          // Message to output right before the plugins table
            'strings'      => array(
                'page_title'                      => __( '安装推荐插件', MUTHEME_NAME ),
                'menu_title'                      => __( '安装插件', MUTHEME_NAME ),
                'installing'                      => __( '正在安装插件: %s', MUTHEME_NAME ),
                // %1$s = plugin name
                'oops'                            => __( '出错了.', MUTHEME_NAME ),
                'notice_can_install_required'     => _n_noop( MUTHEME_NAME . ' 需要安装下面的插件: %1$s.', MUTHEME_NAME . ' 需要安装下面的插件: %1$s.' ),
                // %1$s = plugin name(s)
                'notice_can_install_recommended'  => _n_noop( MUTHEME_NAME . ' 推荐安装下面的插件: %1$s.', MUTHEME_NAME . ' 推荐安装下面的插件: %1$s.' ),
                // %1$s = plugin name(s)
                'notice_cannot_install'           => _n_noop( '对不起，您没有权限安装%s插件.请联系网站管理员安装.', '对不起，您没有权限安装%s插件.请联系网站管理员安装..' ),
                // %1$s = plugin name(s)
                'notice_can_activate_required'    => _n_noop( '必需的插件没有被启用: %1$s.', '必需的插件没有被启用: %1$s.' ),
                // %1$s = plugin name(s)
                'notice_can_activate_recommended' => _n_noop( '推荐的插件没有被启用: %1$s.', '推荐的插件没有被启用: %1$s.' ),
                // %1$s = plugin name(s)
                'notice_cannot_activate'          => _n_noop( '对不起，您没有权限启用%s插件，请联系网站管理员启用插件.', '对不起，您没有权限启用%s插件，请联系网站管理员启用插件.' ),
                // %1$s = plugin name(s)
                'notice_ask_to_update'            => _n_noop( '为了获得最好的兼容性，请更新插件（注意：更新后将不再是汉化版本）: %1$s.', '为了获得最好的兼容性，请更新插件（注意：更新后将不再是汉化版本）: %1$s.' ),
                // %1$s = plugin name(s)
                'notice_cannot_update'            => _n_noop( '对不起，您没有权限更新%s插件，请联系网站管理员更新d.', '对不起，您没有权限更新%s插件，请联系网站管理员更新。' ),
                // %1$s = plugin name(s)
                'install_link'                    => _n_noop( '开始安装插件', '开始安装插件' ),
                'activate_link'                   => _n_noop( '启用已安装的插件。', '启用已安装的插件' ),
                'return'                          => __( '继续安装推荐插件。', JUMPING_NAME ),
                'plugin_activated'                => __( '成功开启插件。', JUMPING_NAME ),
                'complete'                        => __( '所有插件已成功安装并开启 %s。', JUMPING_NAME ),
                // %1$s = dashboard link
                'nag_type'                        => 'updated',
                // Determines admin notice type - can only be 'updated' or 'error'
                'dismiss'                         => '不再提示'
            )
        );

        tgmpa( $plugins, $config );
    }

    private function build( $obj ) {
        switch ( $obj['type'] ) {
            case 'number':
                $this->number( $obj['key'] );
                break;

            case 'input':
                $this->input( $obj['key'] );
                break;

            case 'textarea':
                $this->textarea( $obj['key'] );
                break;

            case 'radio':
                $this->radio( $obj['key'], $obj['value'] );
                break;

            case 'checkbox':
                $this->checkbox( $obj['key'], $obj['value'], $obj['text'] );
                break;
        }
    }

    private function input( $key ) {
        printf( '<input type="input" class="regular-text" name="%s_settings[%s]" value="%s" />', JUMPING_NAME, $key, jumping_setting( $key ) );
    }

    private function number( $key ) {
        printf( '<input type="number" class="small-text" name="%s_settings[%s]" value="%s" step="1" min="1" />', JUMPING_NAME, $key, jumping_setting( $key ) );
    }

    private function textarea( $key ) {
        printf( '<textarea type="textarea" class="large-text" name="%s_settings[%s]">%s</textarea>', JUMPING_NAME, $key, jumping_setting( $key ) );
    }

    private function radio( $key, $value ) {
        $real_val = jumping_setting( $key );

        foreach ( $value as $_key => $_val ) { ?>
            <label>
                <input class="jumping-<?php echo $_key; ?>" type="radio"
                       name="<?php echo JUMPING_NAME . '_settings[' . $key . ']'; ?>"
                       value="<?php echo $_key; ?>" <?php if ( $_key == $real_val ) {
                    echo 'checked="checked"';
                } ?> /> <?php echo $_val; ?>
            </label>
        <?php }
    }

    private function checkbox( $key, $value, $text ) {
        $real_val = jumping_setting( $key );

        ?>
        <label>
            <input type="checkbox" name="<?php echo JUMPING_NAME . '_settings[' . $key . ']'; ?>"
                   value="<?php echo $value; ?>" <?php if ( $value == $real_val ) {
                echo 'checked="checked"';
            } ?> /> <?php echo $text; ?>
        </label>
    <?php }
}