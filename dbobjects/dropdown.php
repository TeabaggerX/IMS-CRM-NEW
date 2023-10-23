<?
class dropdown
{
    var $id;

    var $name;

    var $style;

    var $arg;

    var $blankFirst = "--Select--";
    
    var $firstValue;

    var $options = array();

    var $size;

    var $multiple;

    var $label;

    var $LE = "\n";

    var $required = false;

    var $has_error = false;
    var $is_array;
    var $value;
   
    var $name_array_val = '';
 
    function Dropdown($name = null, $isarray = false, $value="", $style="", $id = "", $arg = "")
    {
        $this->is_array = $isarray;
        if($isarray) {
          if(is_bool($isarray)) {
            $tname = $name.'[]';
          } else {
            $tname = $name.'['.$isarray.']';
          }
        } else {
          $tname = $name;
        }
        $this->setName($tname);
        $this->setStyle($style);
        $this->setArg($arg);
        $this->setID(($id) ? $id : $name);
        $this->value = $value;
    }

    function setID($id)
    {
        $this->id = $id;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function setStyle($class = null)
    {
        $this->style = $class;
    }

    function setArg($arg)
    {
        $this->arg = $arg;
    }
    function setSize($arg) {
      $this->size = $arg;
    }
    function setMultiple($arg) {
      $this->multiple = $arg;
    }
    function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @param array        $options     options can be [opt1 => val1, opt2 => val2, ...] or [optgroup1 =>  [opt1 => val1, opt2 => val2, ...], optgroup2 => ...]
     * @param array|string $selVal      values that are selected in the dropdown
     * @param string       $expBy       delimiter to explode $selVal by
     * @param array        $disabledVal array of values for options that should be disabled, $selVal takes precedence so if value is in $selVal it will be selected and not disabled
     */
    function setOptions($options = array(), $selVal = null, $expBy = "", $disabledVal = array()) {
        $tmp = "";

        ($expBy) ? $aSel = explode($expBy, $selVal) : $aSel[0] = $selVal;
        if (is_array($selVal)) {
            $aSel = $selVal;
        }
        if ($this->blankFirst != '') {
            $tmp .= '<option value="' . $this->firstValue . '">' . $this->blankFirst . '</option>';
        }
        foreach ($options as $sKey => $sVal) {
            if (is_array($sVal)) {
                $tmp .= '<optgroup label="' . $sKey . '">';
                foreach ($sVal as $s1 => $v1) {
                    $tmp .= '<option value="' . $v1 . '"';
                    if (in_array($v1, $aSel)) {
                        $tmp .= ' selected="selected"';
                    } else if (in_array($v1, $disabledVal)) {
                        $tmp .= ' disabled="disabled"';
                    }
                    $tmp .= '>' . $s1 . '</option>';
                }
                $tmp .= '</optgroup>';
            } else {

                $tmp .= '<option value="' . $sVal . '"';

                if (in_array($sVal, $aSel)) {
                    $tmp .= ' selected="selected"';
                } else if (in_array($sVal, $disabledVal)) {
                    $tmp .= ' disabled="disabled"';
                }

                $tmp .= '>' . $sKey . '</option>' . $this->LE;
            }
        }
        $this->options = $tmp;
    }

    function values($options = array(),$selVal=null,$expBy = "")
    {
        $tmp = "";

        ($expBy) ? $aSel = explode($expBy,$selVal) : $aSel[0] = $selVal;
        if(is_array($selVal)) {
            $aSel = $selVal;
        }
        if($this->blankFirst != '') {
            $tmp .= '<option value="'.$this->firstValue.'">'.$this->blankFirst.'</option>';
        }
        foreach($options as $sKey => $sVal)
        {
            if(is_array($sVal)) {
                $tmp .= '<optgroup label="'.$sKey.'">';
                foreach($sVal as $s1 => $v1) {
                    $tmp .= '<option value="'.$s1.'"';
                    if(in_array($s1,$aSel)) $tmp .= ' selected="selected"';
                    $tmp .= '>'.$v1.'</option>';
                }
                $tmp .= '</optgroup>';
            } else {

                $tmp .= '<option value="'.$sKey.'"';

                if(in_array($sKey,$aSel)) {
                    $tmp .= ' selected="selected"';
                }

                $tmp .= '>'.$sVal.'</option>'.$this->LE;
            }
        }
        $this->options = $tmp;
    }

    function getID()
    {
        return $this->id;
    }

    function getName()
    {
        return $this->name;
    }

    function getStyle()
    {
        return $this->style;
    }

    function getArg()
    {
        return $this->arg;
    }

    function getOptions()
    {
        return $this->options;
    }

    function drawDropdown()
    {
        $tmp = '<select id="'.$this->getID().'" name="'.$this->getName().'" class="'.$this->getStyle().'" '.$this->getArg().'>'.$this->LE;
        $tmp .= $this->getOptions().$this->LE;
        $tmp .= '</select>'.$this->LE;
        return $tmp;
    }

    function draw($return=null) {
      $tmp = '<select id="'.$this->getID().'" name="'.$this->getName().'" class="'.$this->getStyle().'" '.$this->getArg().'>'.$this->LE;
        $tmp .= $this->getOptions().$this->LE;
        $tmp .= '</select>'.$this->LE;
        if($return == null) {
            echo $tmp;
        } else {
            return $tmp;
        }
    }

    function validate($trigger)
    {
        if($trigger)
        {
            if($this->required == true)
            {
                if($this->value != '')
                {
                    return true;
                }
                else
                {
                    $this->has_error = true;
                }
            }
        }
    }
}

?>
