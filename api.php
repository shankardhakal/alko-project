<?php

require_once __DIR__ . '/vendor/autoload.php';

$token = filter_input(INPUT_GET, 'token', null);

$updateData = filter_input_array(INPUT_POST, [
        'id' => FILTER_VALIDATE_INT,
        'Orderamount' => FILTER_VALIDATE_INT
    ]
);

if (isset($updateData['id']) && isset($updateData['Orderamount'])) {

    $config = require __DIR__ . "/config.php";
    $medoo = new \Medoo\Medoo($config['database']);
$where =   ['Orderamount[+]'=>1];
if ($updateData['Orderamount'] === 0){

 $where= ['Orderamount'=>$updateData['Orderamount']];

}

    $res =$medoo->update(
        'alko_table',
      $where,
        ['id' => $updateData['id']]

    );


$newCount = $medoo->select(
        'alko_table',
      'Orderamount',
        ['id' => $updateData['id']]

    );
    echo json_encode(['success'=>true,'rows'=>$res->rowCount(),'newOrderAmount'=>$newCount[0]]);
    exit();



}

if ($token = "dfas854XX") {

    echo readData();
    exit();
}

echo json_encode(['success'=>false]);


function readData()
{
    $config = require __DIR__ . "/config.php";
    $medoo = new \Medoo\Medoo($config['database']);

    return json_encode($medoo->select('alko_table', '*'));

}