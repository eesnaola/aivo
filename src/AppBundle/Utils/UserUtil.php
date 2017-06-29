<?php

namespace AppBundle\Utils;

use AppBundle\Model\User;

class UserUtil
{

    public function setUser($response)
    {
        $user = new User();
        $user->setId($response->id);
        $user->setFirstName($response->first_name);
        $user->setLastName($response->last_name);
        return $user;
    }

}
