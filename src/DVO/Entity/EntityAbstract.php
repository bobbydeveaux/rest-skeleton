<?php

namespace DVO\Entity;

/**
 * Abstract Entity.
 *
 * @package default
 * @author
 **/
abstract class EntityAbstract
{
    protected $data;

    public function __construct($values = array())
    {
        if (true === is_array($values)) {
            foreach ($values as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Magic function to capture getters & setters.
     *
     * @param string $name      The name of the function.
     * @param array  $arguments An array of arguments.
     *
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        $type     = substr($name, 0, 3);
        $variable = strtolower(substr($name, 3));
        switch ($type) {
            case 'get':
                return $this->$variable;
            break;
            case 'set':
                $this->$variable = $arguments[0];
                break;
            default:
                return $this->invalid($type);
            break;
        }
    }

    /**
     * Get the data.
     *
     * @return mixed
     */
    final public function getData()
    {
        return $this->data;
    }

    /**
     * Magic function to capture getters.
     *
     * @param string $name Name of the variable.
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (true === array_key_exists($name, $this->data)) {
            return $this->data[$name];
        } else {
            throw new \DVO\Entity\Exception('Param ' . $name . ' not found in ' . get_called_class());
        }
    }

    /**
     * Magic function to capture setters.
     *
     * @param string $name  The name of the var.
     * @param string $value The value for the var.
     *
     * @return mixed
     */
    public function __set($name, $value)
    {
        if (true === array_key_exists($name, $this->data)) {
            $this->data[$name] = $value;
        }
    }

    /**
     * Called when invalid function is called.
     *
     * @param string $type The requested method.
     *
     * @throws Exception Throws an exception.
     * @return void
     */
    public function invalid($type)
    {
        throw new Exception('Error: Invalid handler (' . $type . ') in ' . get_called_class());
    }
}
