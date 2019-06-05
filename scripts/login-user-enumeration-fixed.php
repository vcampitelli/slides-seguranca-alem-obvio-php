<?php
/**
 * Script para se proteger de enumeração de usuários através de timing attacks
 *
 * Uso: php login-user-enumeration-fixed.php user pass
 * Benchmark: time for i in $(seq 1 100); do php login-user-enumeration-fixed.php user pass; done
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
        'user1' => '$argon2i$v=19$m=1024,t=2,p=2$S0p6RE5MY0RmZDQuZS43cA$tQW1qre4LBVk8hIbnAt+7hr3wORe3RDx9pvDDwG5FxY', // "pass1"
        'user2' => '$argon2i$v=19$m=1024,t=2,p=2$UHR0RmtWbUxTQy5JT2tGRQ$xlI5gx8ak+DGWCgo2uFRg4SlInP6DFBNi1yc0UWGteg', // "pass2"
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
    echo 'Usuário não especificado' . PHP_EOL;
    die(1);
}

// Argumento senha
if (! isset($argv[2])) {
    echo 'Senha não especificada' . PHP_EOL;
    die(1);
}

if (! (new UserLogin)->login($argv[1], $argv[2])) {
    echo 'Usuário/senha incorretos' . PHP_EOL;
    die(2);
}

echo 'Logado!' . PHP_EOL;
