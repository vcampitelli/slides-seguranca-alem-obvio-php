<?php
/**
 * Script para testar enumeração de usuários através de timing attacks
 *
 * Uso: php login-user-enumeration.php user pass
 * Benchmark: time for i in {1..100}; do php login-user-enumeration.php user pass; done
 *
 * @author Vinícius Campitelli
 */

class UserLogin
{
    /**
     * Para facilitar, vamos deixar isso hardcoded aqui :)
     * As senhas foram geradas utilizando o algoritmo Argon2I
     *
     * @var array
     */
    private $users = [
        'validuser' => '$argon2i$v=19$m=1024,t=2,p=2$aW1rNXpraFdLWjI5Q1JMag$lChRF0TVyzaT0wC8INlJF1rNTChsKFEuICa9e/gli6c', // "validpass"
    ];

    public function login(string $username, string $password) : bool
    {
        // Para facilitar novamente, vamos fazer o método retornar a senha criptografa do usuário
        $userPassword = $this->findByUsername($username);
        if (! $userPassword) {
            return false;
        }
        if (! $this->verifyPassword($password, $userPassword)) {
            return false;
        }
        return true;
    }

    private function findByUsername(string $username) : ?string
    {
        return ($this->users[$username]) ?? null;
    }

    private function verifyPassword($password, $storedPassword) : bool
    {
        return password_verify($password, $storedPassword);
    }
}

// Argumento usuário
if (! isset($argv[1])) {
    echo "\e[31m0\e[0m";
    die(1);
}

// Argumento senha
if (! isset($argv[2])) {
    echo "\e[31m0\e[0m";
    die(1);
}

if (! (new UserLogin)->login($argv[1], $argv[2])) {
    echo "\e[31m0\e[0m";
    die(2);
}

echo "\e[32m1\e[0m";
