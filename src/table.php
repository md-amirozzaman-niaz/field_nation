<?php

namespace Niaz\Html;

use ArrayObject;

class Table extends ArrayObject
{
    protected $tableData;
    protected $headers;
    protected $emptyCell;
    protected $attributes;

    public function __construct($data = [], $empty = '-',$attributes=[])
    {
        $this->headers = new ArrayObject;
        $this->tableData = new ArrayObject;
        $this->attributes = new ArrayObject(array(),ArrayObject::ARRAY_AS_PROPS);
        $this->emptyCell = $empty;

        foreach ($data as $key => $value) {
            $this->tableData[$key] = $value;
            foreach ($value as $header => $rowData) {
                $this->headers[$header] = $header;
            }
        }
        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }
    public function getAttributes(){
        return $this->attributes;
    }
    public function displayAsTable() : string
    {
        $table = '';

        $table .= $this->addTag($this->preparedTableHeading(), 'thead');
        $table .= $this->addTag($this->preparedTableBody(), 'tbody');

        return $this->addTag($table, 'table');
    }

    public function getHeaders() : object
    {
        return $this->headers;
    }

    public function addTag($data, $tag, $atrribute=[]) : string
    {
        return '<' . $tag . ' ' . $this->addAttribute($tag,$atrribute) . ' ' . '>' . $data . '</' . $tag . '>';
    }

    public function addAttribute($tag,$attributes)
    {
        $attr = '';
        $attributes = $this->preparedAttribute($tag,$attributes);
        foreach ($attributes as $attribute => $value) {
            $attr .= $attribute . '="' . $value . '"';
        }
        return $attr;
    }
    public function preparedAttribute($tag,$attributes){
        $restrecited = ['td'];
        if(in_array($tag,$restrecited)){
            return $attributes;
        }
        return property_exists($this->attributes,$tag) ? $this->attributes[$tag]:$attributes;
    }

    public function preparedTableHeading() : string
    {
        $headingRow = '';
        foreach ($this->headers as $header) {
            $headingRow .= $this->addTag($header, 'th');
        }

        return $this->addTag($headingRow, 'tr');
    }

    public function preparedTableBody() : string
    {
        $tableBody = '';
        foreach ($this->tableData as $key => $rowData) {
            $row = '';
            foreach ($this->headers as $header) {
                $row .= array_key_exists($header, $rowData)
                    ?
                    $this->addTag($rowData[$header], 'td',$this->addCellAttribute($header,$key))
                    :
                    $this->addTag($this->emptyCell, 'td',property_exists($this->attributes,'empty') ? $this->attributes['empty']:[]);
            }

            $tableBody .= $this->addTag($row, 'tr',);
        }

        return $tableBody;
    }
    public function addCellAttribute($col,$row){
        $attr=[];
        if(property_exists($this->attributes,'data')){
            if(array_key_exists($col,$this->attributes['data']) && array_key_exists(($row+1),$this->attributes['data'][$col])){
                $attr=$this->attributes['data'][$col][($row+1)];
            }
        }
        if(property_exists($this->attributes,'col') && array_key_exists($col,$this->attributes['col'])){

                $colAttrs = $this->attributes['col'][$col];
                foreach($colAttrs as $colAttr => $values ){
                    if (array_key_exists($colAttr, $attr)) {
                        $separator = $colAttr == 'style' ? ';' : ' '; 
                        $attr[$colAttr]=$attr[$colAttr] .$separator.$values;
                    }else{
                        $attr[$colAttr]=$values;
                    }
                }
        }

        if(property_exists($this->attributes,'row') && array_key_exists(($row+1),$this->attributes['row'])){

            $rowAttrs = $this->attributes['row'][($row+1)];
            foreach($rowAttrs as $rowAttr => $values ){
                if (array_key_exists($rowAttr, $attr)) {
                    $separator = $rowAttr == 'style' ? ';' : ' '; 
                    $attr[$rowAttr]=$attr[$rowAttr] .$separator.$values;
                }else{
                    $attr[$rowAttr]=$values;
                }
            }
    }
       
        return $attr;
    }
}