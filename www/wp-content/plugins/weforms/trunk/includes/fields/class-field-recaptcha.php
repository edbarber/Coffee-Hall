<?php

/**
 * Text Field Class
 */
class WeForms_Form_Field_reCaptcha extends WeForms_Field_Contract {

    function __construct() {
        $this->name       = __( 'reCaptcha', 'weforms' );
        $this->input_type = 'recaptcha';
        $this->icon       = 'qrcode';
    }

    /**
     * Render the text field
     *
     * @param  array  $field_settings
     * @param  integer  $form_id
     *
     * @return void
     */
    public function render( $field_settings, $form_id ) {

        $settings     = weforms_get_settings( 'recaptcha' );
        $is_invisible = false;
        $public_key   = isset( $settings->key ) ? $settings->key : '';

        if ( isset ( $field_settings['recaptcha_type'] ) ) {
            $is_invisible = $field_settings['recaptcha_type'] == 'invisible_recaptcha' ? true : false;
        }

        $invisible_css   = $is_invisible ? ' style="margin: 0; padding: 0" ' : '';

        ?> <li <?php $this->print_list_attributes( $field_settings ); echo $invisible_css; ?>>

            <?php

            if ( ! $is_invisible ) {
               $this->print_label( $field_settings );
            }

            if ( ! $public_key ) {
                _e( 'reCaptcha API key is missing.', 'weforms');

            } else {

                ?>

                <div class="wpuf-fields <?php echo ' wpuf_'.$field_settings['name'].'_'.$form_id; ?>">
                    <script>
                        function weformsRecaptchaCallback(token) {
                            jQuery('[name="g-recaptcha-response"]').val(token);
                            jQuery('.weforms_submit_btn').attr('disabled',false).show();
                            jQuery('.weforms_submit_btn_recaptcha').hide();
                        }

                        jQuery(document).ready( function($) {
                            $('.weforms_submit_btn').attr('disabled',true);
                        });
                    </script>

                    <input type="hidden" name="g-recaptcha-response">
                <?php

                if ( $is_invisible ) { ?>

                    <script src="https://www.google.com/recaptcha/api.js?onload=weFormsreCaptchaLoaded&render=explicit&hl=en" async defer></script>

                    <script>

                        jQuery(document).ready(function($) {
                            var btn = $('.weforms_submit_btn');
                            var gc_btn = btn.clone().removeClass().addClass('weforms_submit_btn_recaptcha').attr('disabled',false);
                            btn.after(gc_btn);
                            btn.hide();

                            $(document).on('click','.weforms_submit_btn_recaptcha',function(e){
                                e.preventDefault();
                                e.stopPropagation();
                                grecaptcha.execute();
                            })
                        });

                        var weFormsreCaptchaLoaded = function() {

                            grecaptcha.render('recaptcha', {
                                'size' : 'invisible',
                                'sitekey' : '<?php echo $public_key; ?>',
                                'callback' : weformsRecaptchaCallback
                            });

                            grecaptcha.execute();
                        };
                    </script>

                    <div id='recaptcha' class="g-recaptcha" data-sitekey="<?php echo $public_key; ?>" data-callback="weformsRecaptchaCallback" data-size="invisible"></div>

                <?php } else { ?>

                    <script src="https://www.google.com/recaptcha/api.js"></script>
                    <div id='recaptcha' class="g-recaptcha" data-sitekey="<?php echo $public_key; ?>" data-callback="weformsRecaptchaCallback"></div>
                <?php } ?>

                </div>

            <?php } ?>

        </li>
        <?php
    }

    /**
     * Custom validator
     *
     * @return array
     */
    public function get_validator() {
        return array(
            'callback'      => 'has_recaptcha_api_keys',
            'button_class'  => 'button-faded',
            'msg_title'     => __( 'Site key and Secret key', 'weforms' ),
            'msg'           => sprintf(
                __( 'You need to set Site key and Secret key in <a href="%s" target="_blank">Settings</a> in order to use "Recaptcha" field. <a href="%s" target="_blank">Click here to get the these key</a>.', 'weforms' ),
                admin_url( 'admin.php?page=weforms#/settings' ),
                'https://www.google.com/recaptcha/'
            ),
        );
    }

    /**
     * Get field options setting
     *
     * @return array
     */
    public function get_options_settings() {
        $settings = array(
            array(
                'name'          => 'label',
                'title'         => __( 'Title', 'weforms' ),
                'type'          => 'text',
                'section'       => 'basic',
                'priority'      => 10,
                'help_text'     => __( 'Title of the section', 'weforms' ),
            ),

            array(
                'name'          => 'recaptcha_type',
                'title'         => 'reCaptcha type',
                'type'          => 'radio',
                'options'       => array(
                    'enable_no_captcha'    => __( 'Enable noCaptcha', 'weforms' ),
                    'invisible_recaptcha'  => __( 'Enable Invisible reCaptcha', 'weforms' ),
                ),
                'default'       => 'enable_no_captcha',
                'section'       => 'basic',
                'priority'      => 11,
                'help_text'     => __( 'Select reCaptcha type', 'weforms' ),
            )
        );

        return $settings;
    }

    /**
     * Get the field props
     *
     * @return array
     */
    public function get_field_props() {
        $props = array(
            'template'    => $this->get_type(),
            'label'          => '',
            'recaptcha_type' => 'enable_no_captcha',
            'is_meta'       => 'yes',
            'id'            => 0,
            'is_new'        => true,
            'wpuf_cond'     => null
        );

        return $props;
    }
}