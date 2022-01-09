<?php

namespace Niaz\Html;

use ArrayObject;

class Table extends ArrayObject
{

    public function __construct($collection = [],$attributes=[], $empty = '-')
    {
        $this->offsetSet('headers', new ArrayObject(array(),ArrayObject::ARRAY_AS_PROPS));
        $this->offsetSet('tableData', new ArrayObject(array(),ArrayObject::ARRAY_AS_PROPS));
        $this->offsetSet('attributes', new ArrayObject(array(),ArrayObject::ARRAY_AS_PROPS));
        $this->offsetSet('emptyCell', $empty);

        // set table data
        foreach ($collection as $row => $value) {
            $this->offsetGet('tableData')->append($value);

            // get all available header and persist
            foreach ($value as $header => $rowData) {
                 $this->getHeaders()->offsetExists($header) ?: $this->getHeaders()->offsetSet($header,$header);
            }
        }

        // persist all attributes
        foreach ($attributes as $key => $value) {
            $this->getAttributes()->offsetSet($key, $value);
        }

    }

    public function displayAsTable()
    {

        $table = $this->addTag($this->preparedTableHeading(), 'thead');
        $table .= $this->addTag($this->preparedTableBody(), 'tbody');

        echo $this->addTag($table, 'table');
    }

    public function preparedTableHeading() : string
    {
        $headingRow = '';
        foreach ($this->getHeaders() as $header) {
            $headingRow .= $this->addTag($header, 'th');
        }

        return $this->addTag($headingRow, 'tr');
    }

    public function preparedTableBody() : string
    {
        $tableBody = '';
        foreach ($this->offsetGet('tableData') as $offset => $rowData) {
            $row = '';
            foreach ($this->getHeaders() as $header) {
                $row .= array_key_exists($header, $rowData)
                    ?
                        is_array($rowData[$header]) 
                        ? 
                        $this->addTag('not supported', 'td') 
                        : 
                        $this->addTag($rowData[$header], 'td', $this->getCellAttributes($header,$offset))
                    :
                    $this->addTag(
                        $this->offsetGet('emptyCell'), 
                        'td',
                        $this->getEmptyAttributes()
                    );
            }

            $tableBody .= $this->addTag($row, 'tr',);
        }

        return $tableBody;
    }
    public function addTag($data, $tag, $atrribute=[]) : string
    {
        return '<' . $tag . ' ' . $this->addAttribute($tag,$atrribute) . ' ' . '>' . $data . '</' . $tag . '>';
    }

    public function addAttribute($tag,$attributes)
    {
        $attr = '';
        $attributes = $this->getTagAttribute($tag,$attributes);
        foreach ($attributes as $attribute => $value) {
            $attr .= $attribute . '="' . $value . '"';
        }
        return $attr;
    }
    public function getTagAttribute($tag,$attributes){
        // for `td` tag all attributes handle on @getCellAttributes method
        
        if($tag == 'td'){
            return $attributes;
        }
        return (
            $this->getAttributes()->offsetExists($tag) 
            ? $this->getAttributes()->offsetGet($tag) 
            : $attributes
        );
    }

    public function getCellAttributes($col,$row){
        
        $attr=[];
        
        if($this->hasCellAttributes($col,$row)){
            
            $attr=$this->getAttributes()->offsetGet('data')[$col][($row+1)];
            
        }

        if($this->hasColumnAttributes($col))
        {
            $colAttrs = $this->getAttributes()->offsetGet('col')[$col];
            $this->attributeArr($attr,$colAttrs);
        }

        if($this->hasRowAttributes($row))
        {
            $rowAttrs = $this->getAttributes()->offsetGet('row')[($row+1)];
            $this->attributeArr($attr,$rowAttrs);
        }
       
        return $attr;
    }

    public function getHeaders() : object
    {
        return $this->offsetGet('headers');
    }

    public function getAttributes(): object
    {
        return $this->offsetGet('attributes');
    }

    public function hasCellAttributes($col,$row) : bool 
    {
        return (
            $this->getAttributes()->offsetExists('data') 
            && 
            array_key_exists($col,$this->getAttributes()->offsetGet('data'))
            && 
            array_key_exists(($row+1),$this->getAttributes()->offsetGet('data')[$col])
        );
    }

    public function hasColumnAttributes($col) : bool
    {
        return (
            $this->getAttributes()->offsetExists('col') 
            && 
            array_key_exists($col,$this->getAttributes()->offsetGet('col'))
        );
    }

    public function hasRowAttributes($row) : bool
    {
        return (
            $this->getAttributes()->offsetExists('row') 
            && 
            array_key_exists(($row+1),$this->getAttributes()->offsetGet('row'))
        );
    }

    public function getEmptyAttributes() : array
    {
        return (
            $this->getAttributes()->offsetExists('empty') 
            ? 
            $this->getAttributes()->offsetGet('empty')
            :
            []
        );
    }

    public function attributeArr(&$attr,$colAttrs) : array
    {
        
        foreach($colAttrs as $colAttr => $values ){
            if (array_key_exists($colAttr, $attr)) {
                $separator = $colAttr == 'style' ? ';' : ' '; 
                $attr[$colAttr]=$attr[$colAttr] .$separator.$values;
            }else{
                $attr[$colAttr]=$values;
            }
        }
        return $attr;
    }

}