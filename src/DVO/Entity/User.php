<?php

namespace DVO\Entity;

/**
 * User
 *
 * @package default
 * @author
 **/
class User extends EntityAbstract
{
    /**
     * Constructor
     */
    public function __construct($values = [])
    {
        $this->data = array(
            'id'   => '',
            'username' => '',
            'password' => '',
            'deleted'  => '',
            'email'    => '',
        );

        parent::__construct($values);
    }

    /**
     * Get the ID.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->data['id'];
    }

    /**
     * Get the Name.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->data['username'];
    }
}
