<?
class form  
{

    /* Action of a form e.g. index.php?var=value */
    var $action;

    /* Method for the form, either POST or GET */
    var $method;

    /* Name of the form */
    var $name;

    /* Array of fields to exclude processing on */
    var $excludedFields = array();

    /* Form processing trigger */
    var $trigger;

    var $error_highlight;

    var $arg;

    var $input;

    var $acceptedArg = array('id','tabindex','style','size','class','rows','cols','maxlength','onclick','onfocus','onblur','checked','disabled','readonly','value','onkeypress','onchange','onsubmit','onkeydown','onkeyup','onmousedown','onmouseover','onmouseout');


    ///////////////////////////     BEGIN METHODS



    /* Constructor function. sets default values if not passed */
    function Form($name="form1", $method="post", $action="")
    {
       $this->name = $name;
       $this->method = $method;
       $this->action = $action;
    }

    /* Draws the opening of a form */
    function drawForm()
    {
        return '<form name="'.$this->name.'" action="'.$this->action.'" method="'.$this->method.'">';
    }

    /* Closes the form */
    function closeForm()
    {
        return '</form>';
    }

    /* Validates the form */
    function validate($trigger)
    {
        if($trigger)
        {
            if(1==1) {
                return true;
            }
        }
    }

    /* Processes the form based on a trigger and event */
    function process($data, $excluded)
    {
        echo $this->concat($data,$this->excludedFields);
    }

    function addElement($name,$args)
    {
        //
    }

    function getFormData($aData)
    {
        echo "<pre>";
        print_r($aData);
        echo "</pre>";
        $this->process($aData,$this->excludedFields);
    }

    function concat($data,$excluded = array(),$seperator=": ")
    {
        //$this->_debug($data);
        $tmp = "";
        foreach($data as $key => $val)
        {
            if(!is_array($val))
            {
                if(!in_array($key,$excluded))
                {
                    $tmp .= ucfirst($key).$seperator.$val."<br/>";
                }
            }
            else
            {
                $tmp .= ucfirst($key).$seperator;
                $i=0;
                foreach($val as $skey => $sval)
                {
                    $tmp .= $sval;
                    $i++;
                    if($i < $total = sizeof($val))
                    {
                        $tmp .=", ";
                    }
                }
                 $tmp .= "<br/>";
            }
        }
        return $tmp;
    }

    function _debug($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    function setTrigger($trigger)
    {
        $this->trigger = $trigger;
    }

    function setErrorHighlight($color)
    {
        $this->error_highlight = $color;
    }

    function getHighlight(){
        return $this->error_highlight;
    }

    function clearPost()
    {
        foreach($_POST as $key => $val)
        {
            $key[$val] = '';
        }
    }

    function setArgArray($aValue = array())
    {
        if(sizeof($aValue) > 0)
        {
            foreach($aValue as $key => $value)
            {
                if(in_array($key, $this->acceptedArg))
                {
                    $cur = count($this->arg);
                    $this->arg[$cur][0] = $key;
                    $this->arg[$cur][1] = $value;
                }
            }
        }
    }

    function setName($name)
    {
        $this->name = $name;
    }
}

    /*

    class Input {
    Provides functionality for creating HTML inputs within a form.

    //// USAGE ////

    $object = new Input(name, type, value)
    $object->drawInput();

    type = text, password, multi
    rows = only applys to textareas
    maxChar = only applys to text,password

    ///////////////

    */
class Radio {

    var $name;
    var $vals;
    var $type;
    var $id;
    var $checked;
    var $display = 'inline';

    function Radio($name, $vals,$checked=null) {
      $this->name = $name;
      $this->id = $name;
      $this->vals = $vals;
      $this->checked = $checked;
    }

    function draw() {
      $tmp = '';
      if(is_array($this->vals)) {
        foreach($this->vals as $key => $val) {
          $tmp .= '<input type="radio" name="'.$this->name.'" id="'.$this->id.'" value="'.$val.'" ';
          if($this->checked != null) {
            if($val == $this->checked) {
              $tmp .= 'checked="checked" ';
            }
          }
          $tmp .= ' /> '.$key.' ';
          if($this->display != 'inline') $tmp .= "<br/>";
        }
      } else {

      }
      echo $tmp;
    }
}

class Input extends Form
{
    /* R - The name tag of the input */
    var $name;

    /* R - The type of the input */
    var $type;

    /* NR - The id tag of the input */
    var $id;

    /* NR - The css class assocatied with the input */
    var $style;

    /* NR - Additonal arguements, e.g. onblur, onfocus, onchange */
    var $arg;

    /* NR - The value of the input */
    var $value;

    /* NR - The size of the input */
    var $size;

    /* NR - The size of the input */
    var $rows = 3;

    /* NR - The max amount of characters */
    var $maxChar;

    /* Minimum number of characters */
    var $min;

    /* Maximum number of characters */
    var $max;

    /* NR - The label for the input */
    var $label;

    /* NR - The expected value of the input: numeric, any, char, email */
    var $expectedValue;

    /* NR - The expected value of the input: numeric, any, char, email */
    var $tabindex;

    /* Accepted type of inputs */
    var $acceptedType = array('text','password','multi','button','file','checkbox','radio','reset','submit');

    /* Accepted type of arguments for the input */
    var $acceptedArg = array('id','style','tabindex','size','class','rows','cols','maxlength','onclick','onfocus','onblur','checked','disabled','readonly','value','onkeypress','onchange','onsubmit','onkeydown','onkeyup','onmousedown','onmouseover','onmouseout');

    /* Parameters array */
    var $params = array();

    /* Accepted parameters */
    var $acceptedParams = array('required','minc','maxc');

    /* Boolean - Is this field required */
    var $required;

    /* Boolean - There is an error */
    var $has_error = false;
    
    function Input($name, $type, $value = "")
    {
        (in_array($type,$this->acceptedType)) ? $this->setType($type) : die('Invalid Input Type');

        $this->setName($name);
        $this->value = $value;
        $this->type = $type;
        $this->required = 1;
        $this->id = $name;

        if($value!="")
        {
            $this->setArg('value',$value);
        }
    }

    // Set the arguments for the input individually


    function setArg($name, $value)
    {
        if(in_array($name, $this->acceptedArg))
        {
          $cur = count($this->arg);
          $this->arg[$cur][0] = $name;
          $this->arg[$cur][1] = $value;
        }
        else
        {
          die('Not accepted arguement.');
        }
    }

    // Set the arguments list based on an array

    function setArgArray($aValue = array())
    {
        if(sizeof($aValue) > 0)
        {
            foreach($aValue as $key => $value)
            {
                if(in_array($key, $this->acceptedArg))
                {
                    $cur = count($this->arg);
                    $this->arg[$cur][0] = $key;
                    $this->arg[$cur][1] = $value;
                }
            }
        }
    }

    function setType($type)
    {
        $this->type = $type;
    }

    function setSize($size)
    {
        $this->size = ($size);
    }

    function setRows($rows)
    {
        $this->rows = $rows;
    }

    function setID($id)
    {
        if(!$id)
        {
            $this->id = $this->name;
        }
        else
        {
            $this->id = $id;
        }
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function setLabel($label)
    {
        $this->label = $label;
    }

    function setStyle($class)
    {
        $this->style = $class;
    }

    function setValue($value)
    {
        $this->value = $value;
    }

    function setMaxChar($length)
    {
        $this->maxChar = $length;
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

    function getLabel()
    {
        return $this->label;
    }

    function drawLabel()
    {
        $tmp = '<label for="'.$this->id.'">';
        if($this->has_error == true)
        {
            $tmp .= '<span style="color:#FF0000;">';
        }
        
        $tmp .= $this->label;
        if($this->required)
        {
            $tmp .= '*';
        }
        
        if($this->has_error == true)
        {
            $tmp .= '</span>';
        }
        
        $tmp .= '</label>';

        return $tmp;
    }

    function drawInput()
    {
        if($this->type != 'multi')
        {
          $tmp = '<input name="'.$this->name.'" type="'.$this->type.'" id="'.$this->id.'" ';

          if(!empty($this->arg))
          {
            if(count($this->arg) > 1)
            {
              for($i = 0; $i < count($this->arg); $i++)
                  $tmp .= $this->arg[$i][0].'="'.$this->arg[$i][1].'" ';
            }
            else
            {
              $tmp .= $this->arg[0][0].'="'.$this->arg[0][1].'" ';
            }
          }
          $tmp .= '/>';
        }
        else
        {
          $tmp = '<textarea name="'.$this->name.'" id="'.$this->id.'" ';
          if(!empty($this->arg))
          {
            if(count($this->arg) > 1)
            {
              for($i = 0; $i < count($this->arg); $i++)
                  $tmp .= $this->arg[$i][0].'="'.$this->arg[$i][1].'" ';
            } else {
              $tmp .= $this->arg[0][0].'="'.$this->arg[0][1].'" ';
            }
          }
          
          $tmp .= '>';
          $tmp .= $this->value;
          $tmp .= '</textarea>';
        
        }
        return $tmp;
    }

    function isRequired()
    {
        $this->required = true;
    }

    function setCharLimit($min, $max)
    {
        if(is_numeric($min) && is_numeric($max))
        {
            $this->min = $min;
            $this->max = $max;
        }
    }


    function validatesAs($how, $trigger, $required=false, $maxchar=0)
    {
        $this->required = $required;

        if($trigger)
        {
            
            if($this->required == true && $this->value == '')
            {
                $this->has_error = true;
                return false;
            }
            if($maxchar != 0)
            {
                if(strlen($this->value) < $maxchar){
                    $this->has_error = true;
                }
            }
            $methods = array('email','string','number','decimal','phone','anything');
            if($this->value != '')
            {
                if(in_array($how,$methods))
                {
                    switch($how)
                    {
                        case 'email':
                            if(preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $this->value)) {
                                return true;
                            } else {
                                $this->has_error = true;
                            }
                            break;

                        case 'number':
                            if(is_numeric($this->value))
                            {
                                return true;
                            }
                            else
                            {
                                $this->has_error = true;
                            }
                            break;
                        case 'string':
                            if(is_string($this->value))
                            {
                                return true;
                            }
                            else
                            {
                                $this->has_error = true;
                            }
                            break;
                        case 'anything':
                            if(is_string($this->value))
                            {
                                return true;
                            }
                            else
                            {
                                $this->has_error = true;
                            }
                            break;
                    }
                }
            }
        }
    }
}




class dropdown extends form
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

    function drawLabel()
    {
        $tmp = '<label for="'.$this->id.'">';
        if($this->has_error)
        {
            $tmp .= '<span style="color:#'.parent::getHighlight().';">';
        }
        $tmp .= $this->label;
        if($this->required == true)
        {
            $tmp .= '*';
        }
        if($this->has_error)
        {
            $tmp .= '</span>';
        }
        $tmp .= '</label>';
        
        return $tmp;
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

    function addInput($type,$name)
    {
        $this->input[] = 1;
    }
}

class Button extends Form
{

    var $confirmation;
    var $action;
    var $type;
    var $label;
    var $name;
    var $sclass;
    var $arg;

    function Button($label, $name, $type='submit')
    {
        $this->label = $label;
        $this->type = $type;
        $this->name = $name;
    }

    function setConfirmation($message)
    {
        $this->confirmation = $message;
    }

    function setClass($class)
    {
        $this->sclass = $class;
    }

    function setArgArray($aValue = array())
    {
        if(sizeof($aValue) > 0)
        {
            foreach($aValue as $key => $value)
            {
                if(in_array($key, $this->acceptedArg))
                {
                    $cur = count($this->arg);
                    $this->arg[$cur][0] = $key;
                    $this->arg[$cur][1] = $value;
                }
            }
        }
    }

    function draw()
    {
        /* Start drawing input */
        $tmp = '<input type="'.$this->type.'" name="'.$this->name.'" value="'.$this->label.'" ';

        /* Add confirmation message */

        if($this->confirmation != '')
        {
            $tmp .= 'onclick="if(confirm(\''.$this->confirmation.'\')){ return true; } else { return false; }" ';
        }

        if($this->sclass != '')
        {
            $tmp .= 'class="'.$this->sclass.'" ';
        }

        /* Add arguments */

        if(!empty($this->arg))
          {
            if(count($this->arg) > 1)
            {
              for($i = 0; $i < count($this->arg); $i++)
                  $tmp .= $this->arg[$i][0].'="'.$this->arg[$i][1].'" ';
            }
            else
            {
              $tmp .= $this->arg[0][0].'="'.$this->arg[0][1].'" ';
            }
          }
        /* Close Input */
        $tmp .= '/>';


        return $tmp;
    }
}



?>
