<?php

namespace App\Core\Models;

use \Phalcon\Mvc\Model\Behavior\Timestampable;

class UserGroup extends Base
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $user_group_name;

    /**
     *
     * @var string
     */
    protected $user_group_created_at;

    /**
     *
     * @var string
     */
    protected $user_group_updated_at;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field user_group_name
     *
     * @param string $user_group_name
     * @return $this
     */
    public function setUserGroupName($user_group_name)
    {
        $this->user_group_name = $user_group_name;

        return $this;
    }

    /**
     * Method to set the value of field user_group_created_at
     *
     * @param string $user_group_created_at
     * @return $this
     */
    public function setUserGroupCreatedAt($user_group_created_at)
    {
        $this->user_group_created_at = $user_group_created_at;

        return $this;
    }

    /**
     * Method to set the value of field user_group_updated_at
     *
     * @param string $user_group_updated_at
     * @return $this
     */
    public function setUserGroupUpdatedAt($user_group_updated_at)
    {
        $this->user_group_updated_at = $user_group_updated_at;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field user_group_name
     *
     * @return string
     */
    public function getUserGroupName()
    {
        return $this->user_group_name;
    }

    /**
     * Returns the value of field user_group_created_at
     *
     * @return string
     */
    public function getUserGroupCreatedAt()
    {
        return $this->user_group_created_at;
    }

    /**
     * Returns the value of field user_group_updated_at
     *
     * @return string
     */
    public function getUserGroupUpdatedAt()
    {
        return $this->user_group_updated_at;
    }

    public function getSource()
    {
        return 'user_group';
    }

    /**
     * @return UserGroup[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return UserGroup
     */
    public static function findFirst($parameters = array())
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'user_group_name' => 'user_group_name',
            'user_group_created_at' => 'user_group_created_at',
            'user_group_updated_at' => 'user_group_updated_at'
        );
    }

    public function initialize()
    {
        $this->hasMany('id', 'App\Core\Models\User', 'group_id', array(
            'alias' => 'users'
        ));

        $this->addBehavior(new Timestampable(array(
            'beforeValidationOnCreate' => array(
                'field' => 'user_group_created_at',
                'format' => 'Y-m-d H:i:s'
            ),
            'beforeValidationOnUpdate' => array(
                'field' => 'user_group_updated_at',
                'format' => 'Y-m-d H:i:s'
            ),
        )));
    }
}
