<?php

class cfs_time_picker extends cfs_field
{
    function __construct() {
        $this->name = 'time_picker';
        $this->label = __( 'Time picker', 'cfs-time' );
    }

    function html( $field ) {
        if ( empty( $field->value ) || ( ! isset($field->value["hour"])) || ( ! isset($field->value["minute"])) ) {
            $field->value = array(
                'hour'    => $field->options["default_hour"],
                'minute'     => $field->options["default_minute"],
            );
        }
        $hours = array();
        $minutes = array();
        // Creating the hour options
        if($field->options["required"] == false){
            $option = "<option value='-1' %s></option>";
            if ($field->value["hour"] == -1)
                $option = str_replace("%s", "selected", $option);
            else
                $option = str_replace("%s", "", $option);
            $hours[] = $option;
        }
        for ($h = 0; $h <= 23; $h++) {
            $option = "<option value='%v' %s>%p%v</option>";
            $option = str_replace("%v", $h, $option);
            if ($h == $field->value["hour"])
                $option = str_replace("%s", "selected", $option);
            else
                $option = str_replace("%s", "", $option);
            if ($h < 10 && $field->options["leading_zeros"] == true) 
                $option = str_replace("%p", "0", $option);
            else
                $option = str_replace("%p", "", $option);
            $hours[] = $option;
        }
        // Creating the minute options
        if($field->options["required"] == false){
            $option = "<option value='-1' %s></option>";
            if ($field->value["minute"] == -1)
                $option = str_replace("%s", "selected", $option);
            else
                $option = str_replace("%s", "", $option);
            $minutes[] = $option;
        }
        for ($h = 0; $h <= 59; $h++) {
            $option = "<option value='%v' %s>%p%v</option>";
            $option = str_replace("%v", $h, $option);
            if($h == $field->value["minute"])
                $option = str_replace("%s", "selected", $option);
            else
                $option = str_replace("%s", "", $option);
            if ($h < 10) 
                $option = str_replace("%p", "0", $option);
            else
                $option = str_replace("%p", "", $option);
            $minutes[] = $option;
        }
    ?>
        <script>
            (function($) {
                $(function() {
                    // add get_field_value function to CFS['get_field_value'] array
                    if(CFS !== undefined && CFS['get_field_value'] !== undefined && CFS['get_field_value']['time_picker'] === undefined){
                        CFS['get_field_value']['time_picker'] = function(el) {
                            var hour = $($('div.field.field-time1 select :selected')[0]).val();
                            var minute = $($('div.field.field-time1 select :selected')[1]).val();
                            return (hour < 10 ? "0" + hour : hour) + ":" + (minute < 10 ? "0" + minute : minute)
                        };
                    }
                    // clear button handler
                    $('button[name="<?php echo $field->input_name; ?>[clear]"]').on('click', function(){
                        $('select[name="<?php echo $field->input_name; ?>[hour]"]').val(-1);
                        $('select[name="<?php echo $field->input_name; ?>[minute]"]').val(-1);
                    });
                    // hour change handler
                    $('select[name="<?php echo $field->input_name; ?>[hour]"]').on('change', function(){
                        if($('select[name="<?php echo $field->input_name; ?>[hour]"]').val() == -1){
                            $('select[name="<?php echo $field->input_name; ?>[minute]"]').val(-1);
                        } else {
                            if($('select[name="<?php echo $field->input_name; ?>[minute]"]').val() == -1){
                                $('select[name="<?php echo $field->input_name; ?>[minute]"]').val(0);
                            }
                        }
                    });
                    // minute change handler
                    $('select[name="<?php echo $field->input_name; ?>[minute]"]').on('change', function(){
                        if($('select[name="<?php echo $field->input_name; ?>[minute]"]').val() == -1){
                            $('select[name="<?php echo $field->input_name; ?>[hour]"]').val(-1);
                        } else {
                            if($('select[name="<?php echo $field->input_name; ?>[hour]"]').val() == -1){
                                $('select[name="<?php echo $field->input_name; ?>[hour]"]').val(0);
                            }
                        }
                    })
                });
            })(jQuery);
        </script>
        <?php if($field->options["required"] == false) { ?>
            <button type="button" name="<?php echo $field->input_name; ?>[clear]" class="button-secondary" style="float:right;" >
                <?php _e( 'Clear', 'cfs-time' ); ?>
            </button>
        <?php } ?>
        <table style="width: auto;">
            <tr>
                <td>
                    <select name="<?php echo $field->input_name; ?>[hour]" class="<?php echo $field->input_class; ?>">
                        <?php echo implode("\n", $hours); ?>
                    </select>
                </td>
                <td>
                    :
                </td>
                <td>
                    <select name="<?php echo $field->input_name; ?>[minute]" class="<?php echo $field->input_class; ?>">
                        <?php echo implode("\n", $minutes); ?>
                    </select>
                </td>
            </tr>
        </table>
    <?php
    }

    function options_html( $key, $field ) {
        $default_time = array();
        for ($i = 0; $i < 60; $i++) {
            $default_time[(string)$i] = ($i < 10) ? '0' . $i : $i;
        }
    ?>
        <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label><?php _e( 'Default Hour', 'cfs-time' ); ?></label>
            </td>
            <td>
                <?php
                    CFS()->create_field( array(
                        'type' => 'select',
                        'input_name' => "cfs[fields][$key][options][default_hour]",
                        'value' => ("" !== $this->get_option( $field, 'default_hour' )) ? $this->get_option( $field, 'default_hour' ) : "12",
                        'options' => array(
                            'force_single' => 1,
                            'choices' => array_slice($default_time, 0, 24)
                        )                        
                    ));
                ?>
            </td>
        </tr>
        <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label><?php _e( 'Default Minute', 'cfs-time' ); ?></label>
            </td>
            <td>
                <?php
                    CFS()->create_field( array(
                        'type' => 'select',
                        'input_name' => "cfs[fields][$key][options][default_minute]",
                        'value' => ("" !== $this->get_option( $field, 'default_minute' )) ? $this->get_option( $field, 'default_minute' ) : "0",
                        'options' => array(
                            'force_single' => 1,
                            'choices' => $default_time
                        )                        
                    ));
                ?>
            </td>
        </tr>
        <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label><?php _e( 'Leading zeros', 'cfs-time' ); ?></label>
            </td>
            <td>
                <?php
                    CFS()->create_field( array(
                        'type' => 'true_false',
                        'input_name' => "cfs[fields][$key][options][leading_zeros]",
                        'input_class' => 'true_false',
                        'value' => ("" !== $this->get_option( $field, 'leading_zeros' )) ? $this->get_option( $field, 'leading_zeros' ) : true,
                        'options' => array( 'message' => __( 'Use leading zeros with hours', 'cfs-time' ) ),
                    ));
                ?>
            </td>
        </tr>
        <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label><?php cfs_e( 'Validation', 'cfs' ); ?></label>
            </td>
            <td>
                <?php
                    CFS()->create_field( array(
                        'type' => 'true_false',
                        'input_name' => "cfs[fields][$key][options][required]",
                        'input_class' => 'true_false',
                        'value' => $this->get_option( $field, 'required' ),
                        'options' => array( 'message' => cfs__( 'This is a required field', 'cfs' ) ),
                    ));
                ?>
            </td>
        </tr>
    <?php
    }

    function pre_save( $value, $field = null ) {
        if ( isset( $value[0]['hour'] ) && isset( $value[1]['minute'] ) ) {
            if ($value[0]['hour'] >= 0 && $value[1]['minute'] >= 0) {
                $value = array(
                    'hour' => $value[0]['hour'],
                    'minute' => $value[1]['minute'],
                );    
            } else {
                $value = array(
                    'hour' => '-1',
                    'minute' => '-1',
                );
            }            
        } else {
            if ($value['hour'] < 0 || $value['minute'] < 0) {
                $value = array(
                    'hour' => '-1',
                    'minute' => '-1',
                );
            }  
        }
        return serialize( $value );
    }

    function prepare_value( $value, $field = null ) {
        return unserialize( $value[0] );
    }

    function format_value_for_api( $value, $field = null ) {
        $output = '';
        if($value['hour'] < 0 || $value['minute'] < 0){
            return $output;
        }
        if ( isset($field) && $field->options["leading_zeros"] == false) {
            $hour = $value['hour'];
            $minute = str_pad($value['minute'], 2, "0", STR_PAD_LEFT);
            $output = $hour . ":" . $minute;
        } else {
            if ( isset($value['hour'], $value['minute'] ) ) {
                $output = str_pad($value['hour'], 2, "0", STR_PAD_LEFT) . ":" . str_pad($value['minute'], 2, "0", STR_PAD_LEFT);
            }
        }        
        return $output;
    }
}


// Wrappers for l10n functions in cfs domain
if ( ! function_exists( 'cfs__' ) ) {
    function cfs__( $string, $textdomain = 'cfs' ) {
        return __( $string, $textdomain );
    }
}
if ( ! function_exists( 'cfs_e' ) ) {
    function cfs_e( $string, $textdomain = 'cfs' ) {
        echo __( $string, $textdomain );
    }
}

?>
