<?php
namespace Cena\Eloquent;

use Cena\Cena\EmAdapter\EmAdapterInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        $this->toDelete = array();
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
            return !$object instanceof Eloquent;
        }
        return true;
    }

    /**
     * relate the $object with $target as $name association.
     * return true if handled in this method, or return false.
     *
     * @param object $object
     * @param string $name
     * @param object $target
     * @return bool
     */
    public function relate( $object, $name, $target )
    {
        if( !$relation = $object->$name() ) {
            return false;
        }
        $type = get_class( $relation );
        $type = substr( $type, strlen( 'Illuminate\Database\Eloquent\Relations\\' ) );
        
        // for BelongsTo relation. 
        if( $type == 'BelongsTo' ) {
            /** @var BelongsTo $relation */
            if( $this->isCollection( $target ) ) {
                $target = $target[0];
            }
            $relation->associate( $target );
            return true;
        }
        // for HasOne, HasMany or BelongsToMany.
        /** @var BelongsToMany $relation */
        if( !$this->isCollection( $target ) ) {
            // $target is an entity. save it as part of the relation. 
            $relation->save( $target );
            return true;
        }
        // OK, the $target is a collection. 
        if( $type == 'BelongsToMany' ) {
            // for many-to-many relation, remove all the existing ones. 
            $relation->detach();
        }
        $relation->saveMany( $target );
        return true;
    }
}