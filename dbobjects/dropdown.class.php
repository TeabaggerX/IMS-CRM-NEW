<?

    /*

    Copyright Pure Imagination 2006
    Author: Kris Kehler

    class DropDown {
    Provides functionality for creating HTML inputs within a form.

    //// USAGE ////

    $object = new Input(name, type, {value, size, rows, id, style, maxChar, arg})
    $object->drawInput();

    type = text, password, multi
    rows = only applys to textareas
    maxChar = only applys to text,password

    ///////////////


    //// COMMENT LEGEND ////

    NR = not required
    R = required

    ////////////////////////

    */


class Dropdown extends Form{

    var $id;

    var $name;

    var $style;

    var $arg;

    var $options = array();

    var $LE = "\n";

    function Dropdown($name, $style, $id = "", $arg = ""){
        $this->setName($name);
        $this->setStyle($style);
        $this->setArg($arg);
        $this->setID(($id) ? $id : $name);
    }

    function setID($id){
        $this->id = $id;
    }

    function setName($name){
        $this->name = $name;
    }

    function setStyle($class){
        $this->style = $class;
    }

    function setArg($arg){
        $this->arg = $arg;
    }

    function setOptions($options = array(),$selVal){
        $tmp = "";
        foreach($options as $sKey => $sVal){
            $tmp .= '<option value="'.htmlspecialchars($sVal).'"';
            if($selVal == $sVal)
                $tmp .= ' selected="selected"';

            $tmp .= '>'.htmlspecialchars($sKey).'</option>'.$this->LE;
        }
        $this->options = $tmp;
    }

    function getID(){
        return $this->id;
    }

    function getName(){
        return $this->name;
    }

    function getStyle(){
        return $this->style;
    }

    function getArg(){
        return $this->arg;
    }

    function getOptions(){
        return $this->options;
    }

    function drawDropdown(){
        $tmp = '<select id="'.$this->getID().'" name="'.$this->getName().'" class="'.$this->getStyle().'" '.$this->getArg().'>'.$this->LE;
        $tmp .= $this->getOptions().$this->LE;
        $tmp .= '</select>'.$this->LE;
        return $tmp;
    }
}

$options = array('Pick an option' => '','Option1' => '1','Option2' => '2', 'Option3' => '3'); // Options array
$dd = new Dropdown('mysel','style1','1asdf'); // ( Name, Class, {ID, Arguments} )
$dd->setArg("onchange=\"alert(this.value)\""); // Additional Way to Define Arguments
$dd->setOptions($options,'1'); // Set the options for the drop down (Options Array, Selected Value)
echo $dd->drawDropdown(); // Draw out the drop down

?>
