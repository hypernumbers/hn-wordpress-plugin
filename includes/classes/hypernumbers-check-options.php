<?php 

class hn_check_options {

  const 
    OPTION_NAME = 'hypernumbers_plugin_options',
    VERSION     = '1.0';

  protected
    $options  = null,

    // default options and values go here
    $defaults = array(
                  'version'     => self::VERSION // this one should not change
                );

  public function getOptions () {

    // already did the checks
    if(isset($this->options))
      return $this->options;    

    // first call, get the options
    $options = get_option(self::OPTION_NAME);

    // options exist
    if($options !== false){

      $new_version = version_compare($options['version'], self::VERSION, '!=');
      $desync = array_diff_key($this->defaults, $options) !== array_diff_key($options, $this->defaults);

      // update options if version changed, or we have missing/extra (out of sync) option entries 
      if($new_version || $desync){

        $new_options = array();

        // check for new options and set defaults if necessary
        foreach($this->defaults as $option => $value)
          $new_options[$option] = isset($options[$option]) ? $options[$option] : $value;        

        // update version info
        $new_options['version'] = self::VERSION;

        update_option(self::OPTION_NAME, $new_options);
        $this->options = $new_options;  

      // no update was required
      }else{
        $this->options = $options;     
      }


    // new install (plugin was just activated)
    } else {
      update_option(self::OPTION_NAME, $this->defaults);
      $this->options = $this->defaults; 
    }

    return $this->options; 

  }    

}

$hn_options = new hn_check_options();
$hn_options->getOptions();

?>