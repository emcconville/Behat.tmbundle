<?php

namespace TextMate {
  
  class Data 
  {
    public $_memory = array();
    public function __get($arg){ return isset($this->_memory[$arg]) ? $this->_memory[$arg] : NULL; }
    public function __set($arg,$value) { $this->_memory[$arg] = $value; }
    public function __isset($arg) { return isset($this->_memory[$arg]); }
    public function __toString() {
      $return = NULL;
      foreach($this->_memory as $arg => $value)
      {
        if(is_array($value)) {
          array_walk($value,function(&$v){ $v = sprintf('"%s"',Data::escape($v)); });
          $return .= sprintf('%s=(%s);',$arg,implode(',',$value));
        } else if ($value instanceof self) { 
          $return .= sprintf('%s=%s;',$arg,(string)$value);;
        }else {
          $return .= sprintf('%s="%s";',$arg,self::escape($value));
        }
      }
      return sprintf('{%s}'.PHP_EOL,$return);
    }
    public static function escape($_str) { return preg_replace('/(")/','\\\${1}',$_str); }
  }
  class UI
  {
    const ALERT_CRITICAL = 'critical';
    const ALERT_INFORMATIONAL = 'informational';
    const ALERT_WARNING = 'warning';
    public $stdout = NULL;
    public $stderr = NULL;
    public $flags  = NULL;
    public $data   = NULL;
    private $_descSpec = array(array('pipe','r'),array('pipe','w'),array('pipe','w'));
    private $_pipes = array();
    public function __construct()
    {
    }
    public function dialog()
    {
      $dialog=$_ENV['DIALOG'];
      $nib_path=$_ENV['TM_SUPPORT_PATH'].'/nibs';
      $status = FALSE;
      $process = proc_open("{$dialog} {$this->flags}",$this->_descSpec,$this->_pipes,NULL,NULL);
      if(is_resource($process))
      {
        fwrite($this->_pipes[0],(string)$this->data);
        fclose($this->_pipes[0]);
        $this->stdout = stream_get_contents($this->_pipes[1]);
        $this->stderr = stream_get_contents($this->_pipes[2]);
        fclose($this->_pipes[1]);
        fclose($this->_pipes[2]);
        $status = proc_close($process);
      }
      return $status;
    }
    static public function alert($style, $title, $message,$buttons=array())
    {
      if(!in_array($style,array(self::ALERT_WARNING, self::ALERT_INFORMATIONAL, self::ALERT_CRITICAL)))
        $style=self::ALERT_CRITICAL;
      $data = new Data();
      $data->informativeText=$message;
      $data->messageTitle=$title;
      $data->alertStyle=$style;
      if(!empty($buttons)) $data->buttonTitles = $buttons;
      $ui = new self;
      $ui->flags = '-e';
      $ui->data  = $data;
      $ui->dialog();
      return trim($ui->stdout);
    }
    static public function tooltip($message,$html=false,$transparent=false)
    {
      $ui = new self();
      $ui->flags = sprintf(' tooltip < /dev/null %s %s \'%s\'',
                            $transparent ? '--transparent' : '',
                            $html ? '--html' : '--text',
                            Data::escape($message)
      );
      $ui->dialog();
    }
  }
}