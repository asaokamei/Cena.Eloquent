<?php
namespace Cena\Eloquent;

use Cena\Cena\EmAdapter\EmAdapterInterface;
use Eloquent;

class EmaEloquent implements EmAdapterInterface
{
    /**
     * @var Eloquent[]
     */
    protected $entities = array();

    /**
     * @var Eloquent[]
     */
    protected $toDelete = array();

    /**
     * @param object $entity
     */
    protected function emStore( $entity )
    {
        $this->entities[ spl_object_hash( $entity ) ] = $entity;
    }

    /**
     * @param object $entity
     */
    protected function emDelete( $entity )
    {
        $hash = spl_object_hash( $entity );
        if( isset( $this->entities[ $hash ] ) ) {
            unset( $this->entities[ $hash ] );
        }
        $this->toDelete[ $hash ] = $entity;
    }

    /**
     * @api
     * @return mixed
     */
    public function em()
    {
    }

    /**
     * saves entities to database.
     * @api
     */
    public function save()
    {
        foreach( $this->entities as $entity ) {
            $entity->save();
        }
        foreach( $this->toDelete as $entity ) {
            $entity->delete();
        }
    }

    /**
     * clears the entity cache.
     * @api
     */
    public function clear()
    {
        $this->entities = array();
    }

    /**
     * @api
     * @param       $class
     * @return object
     */
    public function newEntity( $class )
    {
        $entity = new $class;
        $this->emStore( $entity );
        return $entity;
    }

    /**
     * @api
     * @param Eloquent $class
     * @param $id
     * @return null|object
     */
    public function findEntity( $class, $id )
    {
        $entity = $class::find( $id );
        $this->emStore( $entity );
        return $entity;
    }

    /**
     * @api
     * @param Eloquent $entity
     * @return mixed
     */
    public function deleteEntity( $entity )
    {
        $this->emDelete( $entity );
    }

    /**
     * @api
     * get id value of the entity.
     *
     * @param Eloquent $entity
     * @return string
     */
    public function getId( $entity )
    {
        return $entity->getKey();
    }

    /**
     * returns if the $entity object is marked as delete.
     *
     * @api
     * @param Eloquent $entity
     * @return mixed
     */
    public function isDeleted( $entity )
    {
        return isset( $this->toDelete[ spl_object_hash($entity) ] );
    }

    /**
     * returns if the $entity object is retrieved from data base.
     *
     * @api
     * @param Eloquent $entity
     * @return mixed
     */
    public function isRetrieved( $entity )
    {
        return $entity->exists;
    }

    /**
     * returns if the $object is a collection of entities or not.
     *
     * @param object $object
     * @return mixed
     */
    public function isCollection( $object )
    {
        if( is_object( $object ) ) {
            return $object instanceof Eloquent;
        }
        return false;
    }
}