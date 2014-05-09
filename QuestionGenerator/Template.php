<?php

error_reporting(0);


class CTemplate
{
    // class-internal variables
    var $runMode;         // run mode
    var $fileName;        // the file name we work with

    var $formElements;    // form elements related to this template
    var $prepArray;       // preparation array, change template values with these ones
    var $tmplDirectory;   // set templates directory where the templates should be
    var $tmplContent;     // template content


    function CTemplate()
    {

        $this->tmplContent      = "";
        $this->tmplDirectory    = "../question_pool";
    }


    function readTemplateDir($type , $name = "default")
    {

        $this->tmplContent = file_get_contents($this->tmplDirectory."/".$name.".question.".$type.".config");
    }


    function mainFileConfig($keysArray = array(),$name,$type)
    {

        $this->readTemplateDir($type);

        foreach($keysArray as $key => $value)
        {
            $key = preg_replace("/\//","\\/", $key);
            $this->tmplContent = preg_replace("/\[\%\s*$key\s*\%\]/", $value, $this->tmplContent);
        }

        foreach($this->prepArray as $key => $value)
        {
            $key = preg_replace("/\//","\\/", $key);
            $this->tmplContent = preg_replace("/\[\%\s*$key\s*\%\]/", $value, $this->tmplContent);
        }


        touch($this->tmplDirectory."/".$name.".question.".$type.".config");
        $h = fopen($this->tmplDirectory."/".$name.".question.".$type.".config","w");
        fwrite($h,$this->tmplContent);
        fclose($h);


        return;
    }

    function updateFileConfig($pathToKey, $name, $type, $newKeyValue)
    {
        $this->readTemplateDir($type,$name);

        $keys = explode(",",$pathToKey);

        $tmplArray = (array)json_decode($this->tmplContent,true);

        $newJson = $this->assign($test,$keys,$newKeyValue);

        $newArray = $this->my_merge($tmplArray,$newJson);

        $h = fopen($this->tmplDirectory."/".$name.".question.".$type.".config","r+a");
        fwrite($h,json_encode($newArray,JSON_PRETTY_PRINT));
        fclose($h);

        print_r($newArray);

    }


    function keyValue($array){
        $new_array = array();

        foreach ($array as $key => $value) {
            array_push($new_array, array(
                'name' => $key,
                'value' => is_array($value) ? $this->keyValue($value) : $value
            ));
        }

        return $new_array;
    }

    function readFile($path,$name,$type)
    {
        $this->readTemplateDir($type,$name);

        $keys = explode(",",$path);

        $newArray = json_decode($this->tmplContent,true);

        $newArray = $this->readRecursive($newArray,$keys);

        print $newArray;

    }

    function readRecursive($array1,$keys ,$c = 0)
    {
        if(!is_array($array1))
        {
            print $array1;
        }

        foreach($array1 as $key => $value)
        {
           // echo $key;
            if( $key == $keys[$c] )
            {
                $c++;
                $this->readRecursive($value,$keys,$c);
            }else{
                //print $value;
            }
        }

    }

    function my_merge( $arr1, $arr2 )
    {
        $keys = array_keys( $arr2 );
        foreach( $keys as $key ) {
            if( isset( $arr1[$key] )
                && is_array( $arr1[$key] )
                && is_array( $arr2[$key] )
            ) {
                $arr1[$key] = $this->my_merge( $arr1[$key], $arr2[$key] );
            } else {
                $arr1[$key] = $arr2[$key];
            }
        }
        return $arr1;
    }


    function assign(&$array, $keys, $value) {
        $last_key = array_pop($keys);
        $tmp = &$array;
        foreach($keys as $key)
        {
            if(!isset($tmp[$key]) || !is_array($tmp[$key]))
            {
                $tmp[$key] = array();
            }
            $tmp = &$tmp[$key];
        }
        $tmp[$last_key] = $value;
        return $array;
    }


}// end of CTemplate class

?>