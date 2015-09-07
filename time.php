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
                'hour'    => '12',
                'minute'     => '00',
            );
        }
        $hours = array();
        $minutes = array();
        // Creating the hour options
        for ($h = 0; $h <= 23; $h++) {
            $option = "<option value='%v' %s>%v</option>";
            $option = str_replace("%v", $h, $option);
            if($h == $field->value["hour"])
                $option = str_replace("%s", "selected", $option);
            else
                $option = str_replace("%s", "", $option);
            $hours[] = $option;
        }
        // Creating the minute options
        for ($h = 0; $h <= 59; $h++) {
            $option = "<option value='%v' %s>%v</option>";
            $option = str_replace("%v", $h, $option);
            if($h == $field->value["minute"])
                $option = str_replace("%s", "selected", $option);
            else
                $option = str_replace("%s", "", $option);
            $minutes[] = $option;
        }
    ?>
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
        if ( isset($value['hour'], $value['minute'] ) ) {
            $output = str_pad($value['hour'], 2, "0", STR_PAD_LEFT) . ":" . str_pad($value['minute'], 2, "0", STR_PAD_LEFT);
        }
        return $output;
    }
}
