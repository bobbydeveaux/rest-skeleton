<?php

namespace DVO\Entity\User;

use DVO\Entity\EntityAbstract\EntityAbstractGateway;
use DVO\Db;

/**
 * UserGateway class
 */
class UserGateway extends EntityAbstractGateway
{
    public function __construct(Db $db)
    {
        $this->table = 'users';
        parent::__construct($db);
    }

    /**
     * Get users.
     *
     * @param  DVO\Entity\User $user
     * @param  array           $search Array of search terms & values
     * @return array
     */
    public function getUsers(\DVO\Entity\User $user, array $search = [])
    {
        if (true === empty($search)) { // Search operation
            $stmt = $this->db->prepare("SELECT * FROM `users` LIMIT 10;");
        } else { // Get one user by ID
            try {
                foreach ($search as $key => $value) {
                    $user->$key = $value;
                }
            } catch (\DVO\Entity\Exception $e) {
                throw new \DVO\Entity\User\UserGateway\Exception($e->getMessage());
            }

            $stmt = $this->getSearchStatement($user);
        }

        if ($stmt->execute()) {
            $results = $stmt->fetchAll(Db::FETCH_ASSOC);
        } else {
            throw new \DVO\Entity\User\UserGateway\Exception("User ID not found: ".$stmt->errorInfo()[2]);
        }

        return $results;
    }

    /**
     * Insert a new user
     *
     * @param  DVO\Entity\User $user
     * @return int               The user ID
     */
    public function insertUser(\DVO\Entity\User $user)
    {
        $stmt = $this->getInsertStatement($user);

        if ($stmt->execute()) {
            $id = $this->db->lastInsertId();
            return $id;
        } else {
            throw new \DVO\Entity\User\UserGateway\Exception("User could not be inserted: ".$stmt->errorInfo()[2]);
        }
    }

    /**
     * Update user based on a User
     *
     * @param  DVO\Entity\User $user
     * @param  array             $data
     * @return boolean
     */
    public function updateUser(\DVO\Entity\User $user, array $data)
    {
        foreach ($data as $key => $value) {
            $user->$key = $value;
        }

        $stmt = $this->getUpdateStatement($user);

        if ($stmt->execute()) {
            $id = $this->db->lastInsertId();
            return $id;
        } else {
            throw new \DVO\Entity\User\UserGateway\Exception("User could not be updated: ".$stmt->errorInfo()[2]);
        }
    }

    public function countUsers()
    {
        $stmt = $this->getCountStatement();

        if ($stmt->execute()) {
            $results = $stmt->fetchAll(Db::FETCH_ASSOC);
            $count   = $results[0]['the_count'];
        } else {
            throw new \DVO\Entity\User\UserGateway\Exception("User ID not found.");
        }

        return $count;
    }
}
