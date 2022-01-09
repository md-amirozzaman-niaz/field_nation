<?php

require_once './src/table.php';

$data = [
    [
        'id' => 1,
        'name' => 'tony',
        'status' => 'Deactive',
        'not_always' => 'hmm'
    ],
    [
        'name' => 'matt',
        'age' => 35,
        'id' => 2,
        'status' => 'active'
    ],
    [
        'id' => 3,
        'age' => 35,
        'name' => 'james',
        'nested' => [
            'hello' =>'world'
        ]
    ]
];

$attributes = [
    'table' => ['class' => 'table table-border'],
];
echo '<link rel="stylesheet" href="/css/bootstrap.min.css" integrity="undefined" crossorigin="anonymous">';

echo '<a href="/">Home</a>';

echo "<h3>Datatable without styling and attribute</h3>";
$htmlTable1 = new \Niaz\Html\Table($data, $attributes);
$htmlTable1->displayAsTable();


echo '<hr>';


echo "<h3>Datatable with specific styling and attribute</h3>";

$emptyCell = 'no data';
$attributes = [
    // binding stying or any other attribute for table
    'table' => ['class' => 'table table-border', 'data' => 'hmm'],
    
    // binding stying or any other attribute for heading 
    'th' => [
        'class' => 'bg-warning'
    ],

    // binding stying or any other attribute for specific cell
    'data' => [
        'id' => [
            '1' => ['class' => 'custom class', 'data' => 'id', 'style' => 'color:white'],
            '2' => ['class' => 'bg-danger', 'style' => 'color:white;']
        ],
        'bob' => [
            '2' => ['class' => 'bg-warninf', 'data' => 'bob', 'style' => 'color:white;']
        ],
    ],
    // binding stying or any other attribute for any specific column only
    'col' => [
        'id' => ['class' => 'bg-primary', 'data' => 'name', 'style' => 'font-size:18px']
    ],

    // binding stying or any other attribute for any specific row only
    'row' => [
        '1' => ['class' => 'bg-success', 'style' => 'font-style:italic;color:white']
    ],

    // binding stying or any other attribute for which cell has no data
    'empty' => ['class' => 'empty-cell', 'style' => 'background:#e3e3e3;', 'data' => 'empty']
];

$htmlTable2 = new \Niaz\Html\Table($data, $attributes,$emptyCell);
$htmlTable2->displayAsTable();
