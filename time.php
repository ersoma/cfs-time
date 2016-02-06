<?php

class cfs_time_picker extends cfs_field
{
    function __construct() {
        $this->name = 'time_picker';
        $this->label = __( 'Time picker', 'cfs' );
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
                    if(CFS !== undefined && CFS['get_field_value'] !== undefined && CFS['get_field_value']['time_picker'] === undefined){
                        CFS['get_field_value']['time_picker'] = function(el) {
                            var hour = $($('div.field.field-time1 select :selected')[0]).val();
                            var minute = $($('div.field.field-time1 select :selected')[1]).val();
                            return (hour < 10 ? "0" + hour : hour) + ":" + (minute < 10 ? "0" + minute : minute)
                        };
                    }
                });
            })(jQuery);
        </script>
        <table>
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
            $default_time[$i] = ($i < 10) ? '0' . $i : $i;
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
                <label><?php _e( 'Leading zeros', 'cfs' ); ?></label>
            </td>
            <td>
                <?php
                    CFS()->create_field( array(
                        'type' => 'true_false',
                        'input_name' => "cfs[fields][$key][options][leading_zeros]",
                        'input_class' => 'true_false',
                        'value' => ("" !== $this->get_option( $field, 'leading_zeros' )) ? $this->get_option( $field, 'leading_zeros' ) : true,
                        'options' => array( 'message' => __( 'Use leading zeros with hours', 'cfs' ) ),
                    ));
                ?>
            </td>
        </tr>
        <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label><?php _e( 'Validation', 'cfs' ); ?></label>
            </td>
            <td>
                <?php
                    CFS()->create_field( array(
                        'type' => 'true_false',
                        'input_name' => "cfs[fields][$key][options][required]",
                        'input_class' => 'true_false',
                        'value' => $this->get_option( $field, 'required' ),
                        'options' => array( 'message' => __( 'This is a required field', 'cfs' ) ),
                    ));
                ?>
            </td>
        </tr>
    <?php
    }

    function pre_save( $value, $field = null ) {
        if ( isset( $value[0]['hour'] ) && isset( $value[1]['minute'] ) ) {
            $value = array(
                'hour' => $value[0]['hour'],
                'minute' => $value[1]['minute'],
            );
        }
        return serialize( $value );
    }

    function prepare_value( $value, $field = null ) {
        return unserialize( $value[0] );
    }

    function format_value_for_api( $value, $field = null ) {
        $output = '12:00';
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
