<?php

namespace Core\App;
class Config {
    protected $config;
    public function __construct() {
        $this->config = json_decode(file_get_contents(APP_PATH . '/config.json'));
    }
    /*
     * @name    modify_setting()
     *
     * @param1  string  setting_name
     * @param2  string  setting
     *
     * @return  void
     *
     */
    public function modify_setting($setting_name, $setting) {
        if (isset($this->config->settings->$setting_name)) {
            $value_exists = false;
            foreach ($this->config->settings->$setting_name->possible_values as $dvalue) {
                if ($setting === $dvalue) {
                    $value_exists = true;
                }
            }
            if ($value_exists === true) {
                $this->config->settings->$setting_name->value = $setting;
            }
            else {
                echo 'Invalid value!';
            }
        }
    }
    /*
     * @name    create_setting()
     *
     * @param1  string   setting_name - Setting selector
     * @param2  string   setting - Setting set for setting name
     * @param3  array    possible_setting_values - Set accepted values for this specific setting
     *
     * @return  void
     */
    public function create_setting($setting_name, $setting, $possible_setting_values) {
        if (isset($this->config->settings->$setting_name)) {
            echo 'This setting exists already.';
            return;
        }
    /****
        *
        * This method automatically adds $setting to $possible_setting_values array in case
        * if developer forgets to add it.
    *****/
        $valid_setting = false;
        foreach ($possible_setting_values as $setting_value) {
            if ($setting_value === $setting) {
                $valid_setting = true;
            }
        }
        if ($valid_setting === false) {
            array_push($possible_setting_values, $setting);
        }
        $this->config->settings->$setting_name->value = $setting;
        $this->config->settings->$setting_name->possible_values = $possible_setting_values;
    }
    /****
        *
        * @desc    Lists every setting in configuration file
        *
     ****/
    
    public function list_settings() {
        foreach ($this->config->settings as $setting) {
            
        }
    }
    public function save() {
        file_put_contents(APP_PATH . '/config.json', json_encode($this->config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}

?>