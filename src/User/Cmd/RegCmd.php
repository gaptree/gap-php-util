<?php
namespace Gap\Util\User\Cmd;

use Gap\User\UserAdapter;

class RegCmd extends CmdBase
{
    public function run(): void
    {
        if (!isset($this->parameters[1])) {
            echo "$ ./vendor/bin/gap reg '{username}' '{password}'\n";
            return;
        }

        $userAdapter = new UserAdapter($this->app->get('dmg'));
        list($username, $password) = $this->parameters;

        $userAdapter->reg($username, $password);
    }
}
