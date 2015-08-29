<?php

namespace DVO\Entity\EntityAbstract;

use DVO\Db;

abstract class EntityAbstractGateway
{
    protected $db;
    protected $table;

    /**
     * Setup the SiteGateway.
     *
     */
    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    /**
     * Take an EntityAbstract object & create a prepared statement to search against an array of parameters
     *
     * @param  User\Entity\EntityAbstract $entity
     * @return PDOStatement
     */
    protected function getSearchStatement(\DVO\Entity\EntityAbstract $entity)
    {
        $data = $entity->getData();
        $params = "";

        foreach ($data as $key => $value) {
            if ($value === "") {
                continue;
            }

            $params .= $params !== "" ? " AND " : null;
            $params .= "`$key` = :$key";
        }

        $query = "SELECT * FROM `".$this->table."`";

        if (false === empty($params)) {
            $query .= " WHERE $params;";
        }

        $stmt = $this->bindStatement(
            $this->db->prepare($query),
            $data
        );


        return $stmt;
    }

    /**
     * Take an EntityAbstract object & create a prepared statement to insert it
     *
     * @param  User\Entity\EntityAbstract $entity
     * @return PDOStatement
     */
    protected function getInsertStatement(\DVO\Entity\EntityAbstract $entity)
    {
        $data = $entity->getData();

        $fields = "";
        $params = "";

        foreach ($data as $key => $value) {
            if ($key === '_id' || $key === 'id' || $value === "") {
                continue;
            }

            $fields .= $fields !== "" ? ", " : null;
            $fields .= "`$key`";
            $params .= $params !== "" ? ", " : null;
            $params .= ":$key";
        }

        $query = "INSERT INTO `".$this->table."` ($fields) VALUES ($params);";

        $stmt = $this->bindStatement(
            $this->db->prepare($query),
            $data
        );

        return $stmt;
    }

    /**
     * Take an EntityAbstract object & create a prepared statement to update it
     *
     * @param  User\Entity\EntityAbstract $entity
     * @param  string (optional)           $lookupField
     * @return PDOStatement
     */
    protected function getUpdateStatement(\DVO\Entity\EntityAbstract $entity, $lookupField = 'id')
    {
        $data = $entity->getData();

        $params = "";

        foreach ($data as $key => $value) {
            if ($key === '_id' || $key === $lookupField || $value === "") {
                continue;
            }

            $params .= $params !== "" ? ", " : null;
            $params .= "`$key` = :$key";
        }
        if (is_array($lookupField)) {
            $where = false;
            foreach ($lookupField as $field) {
                $where = $where ?
                    $where." AND (`".$field."` = :".$field.") " :
                    $where." (`".$field."` = :".$field.") " ;
            }
            $query = "UPDATE `".$this->table."` SET $params WHERE ".$where;
        } else {
            $query = "UPDATE `".$this->table."` SET $params WHERE  `$lookupField` = :$lookupField;";
        }


        $stmt = $this->bindStatement(
            $this->db->prepare($query),
            $data
        );

        return $stmt;
    }

    /**
     * Create a prepared statement to count the sites
     *
     * @return PDOStatement
     */
    protected function getCountStatement()
    {
        $query = "SELECT COUNT(*) AS `the_count` FROM `".$this->table."`;";
        $stmt  = $this->db->prepare($query);

        return $stmt;
    }

    /**
     * Statement to bind values
     *
     * @return Statement
     **/
    protected function bindStatement($statement, $data)
    {
        foreach ($data as $key => $value) {
            if ($key === '_id' || $value === "") {
                continue;
            }

            $statement->bindValue(':' . $key, $value);
        }

        return $statement;

    }
}
