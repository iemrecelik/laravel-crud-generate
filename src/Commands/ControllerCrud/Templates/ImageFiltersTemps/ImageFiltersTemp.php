<?php
return function($params){

$modelVarName = $params['modelVarName'];
$filtName = $modelVarName.'ImagesFilt';

return '
        \''.$filtName.'\' => [
            \'_1\' => [
                \'resize\' => [185, 156],
            ],
            \'_2\' => [
                \'resize\' => [90, 76],
            ],
            \'_3\' => [
                \'resize\' => [64, 54],
            ],
        ],
';
};