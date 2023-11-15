<?php

function randomString($len = 5)
{
    $i = 0;
    $finalString = '';
    $characters = 'ertyuioplkjhgdsazcvbnmLKJHGFDSAPOIUYTREWMNBCXZ019283465';
    while($i<$len){
        $finalString.=$characters[rand(0, strlen($characters)-1)];
        $i++;
    }
    return $finalString;
}
