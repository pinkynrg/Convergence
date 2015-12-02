<?php 

use App\Models\User;

echo 'test roles<br><hr>';

$user = User::where('username','sample_customer')->first();

var_dump($user->can('create-tickets'));
var_dump($user->inGroup('e80-helpdesk'));
var_dump($user->inGroup('basic-customer'));

?>

