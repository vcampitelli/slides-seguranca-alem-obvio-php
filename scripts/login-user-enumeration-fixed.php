<?php
/**
 * Script para se proteger de enumeração de usuários através de timing attacks
 *
 * Uso: php login-user-enumeration-fixed.php user pass
 * Benchmark: time for i in {1..100}; do php login-user-enumeration-fixed.php user pass; done
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
        $storedPassword = $this->generateFakePassword();
        $userPassword = $this->findByUsername($username);
        if ($userPassword) {
            $storedPassword = $userPassword;
        }
        return ($this->verifyPassword($password, $storedPassword))
            && ($userPassword !== null);
    }

    private function generateFakePassword() : string
    {
        return password_hash('0123456789', PASSWORD_ARGON2I);
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
