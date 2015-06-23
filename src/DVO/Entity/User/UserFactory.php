<?php

namespace DVO\Entity\User;

use DVO\Cache;

/**
 * Factory for User entities
 */
class UserFactory
{
    protected $gateway;
    protected $cache;

    /**
     * UserFactory constructor.
     *
     * @param UserGateway $gateway The user gateway.
     */
    public function __construct(UserGateway $gateway, Cache $cache)
    {
        $this->gateway = $gateway;
        $this->cache   = $cache;
    }

    /**
     * Get the gateway!.
     *
     * @return \DVO\Entity\User\UserGateway
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * Creates the User
     *
     * @return \DVO\Entity\Users
     */
    public function create($data = array())
    {
        return new \DVO\Entity\User($data);
    }

    /**
     * Gets the users
     *
     * @param  array $search Search parameters & terms
     * @return array
     */
    public function getUsers(array $search = [])
    {
        $key   = get_called_class() . '::' . __FUNCTION__ . md5(implode(' ', $search));
        $users = false;

        if (true === $this->cache->enabled) {
            $users = $this->cache->get($key);
        }

        if (true === empty($users)) {
            try {
                $users = $this->gateway->getUsers($this->create(), $search);
            } catch (\DVO\Entity\User\UserGateway\Exception $e) {
                throw new \DVO\Entity\User\UserGateway\Exception($e->getMessage());
            }
            $users = array_map(function ($user) {
                $s = $this->create();

                foreach ($user as $key => $value) {
                    $s->$key = $value;
                }

                return $s;
            }, $users);

            $this->cache->set($key, $users, 10);
        }

        return $users;
    }
}
