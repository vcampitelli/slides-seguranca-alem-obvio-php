<?php
if (! isset($argv[1])) {
    echo 'Usuário não especificado' . PHP_EOL;
    die(1);
}
$user = $argv[1];

if (! isset($argv[2])) {
    echo 'Senha não especificada' . PHP_EOL;
    die(1);
}
$pass = $argv[2];

$users = [
    'user1' => 'password1',
    'user2' => 'password2',
    'user3' => 'password3',
];
if (! isset($users[$user])) {
    echo 'Usuário/senha incorretos' . PHP_EOL;
    die(2);
}
