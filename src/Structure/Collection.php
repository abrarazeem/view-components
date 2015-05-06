<?php

namespace Nayjest\ViewComponents\Structure;

use InvalidArgumentException;
use Nayjest\Manipulator\Manipulator;
use Nayjest\ViewComponents\BaseComponents\ComponentInterface;
use Nayjest\ViewComponents\Collection\Collection as BaseCollection;
use Traversable;

// use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class Collection
 *
 * Tree nodes collection.
 *
 * @property ChildNodeInterface[] $items
 */
class Collection extends BaseCollection
{
    protected $owner;

    //protected static $propertyAccessor;

    /**
     * Constructor.
     *
     * @param ParentNodeInterface $owner
     */
    public function __construct(ParentNodeInterface $owner)
    {
        $this->owner = $owner;
    }

    /**
     * Adds component to collection.
     *
     * If component is already in collection, it will not be added twice.
     *
     * @param ChildNodeInterface $item
     * @param bool $prepend Pass true to add component to the beginning of an array.
     * @return $this
     */
    public function add($item, $prepend = false)
    {
        if (!$item instanceof ChildNodeInterface) {
            throw new InvalidArgumentException('Collection accepts only objects implementing ChildNodeInterface');
        }
        $old = $item->getParent();
        if ($old !== $this->owner) {
            if ($old !== null) {
                $item
                    ->getParent()
                    ->components()
                    ->remove($item);
            }
            parent::add($item, $prepend);
            $item->internalSetParent($this->owner);
        }
        return $this;
    }

    /**
     * @param ChildNodeInterface $item
     * @return $this
     */
    public function remove($item)
    {
        if ($item->getParent() === $this->owner) {
            $item->internalUnsetParent();
            parent::remove($item);
        }
        return $this;
    }

    /**
     * @param ChildNodeInterface[] $items
     * @return $this
     */
    public function set(array $items)
    {
        return parent::set($items);
    }

    public function clean()
    {
        foreach ($this->items as $item) {
            $item->internalUnsetParent();
        }
        return parent::clean();
    }

//    /**
//     * @return \Symfony\Component\PropertyAccess\PropertyAccessor
//     */
//    protected static function getPropertyAccessor()
//    {
//        if (self::$propertyAccessor === null) {
//            self::$propertyAccessor = PropertyAccess::createPropertyAccessor();
//        }
//        return self::$propertyAccessor;
//    }

//    /**
//     * @param string $attribute
//     * @param $value
//     * @return ChildNodeInterface[]
//     */
//    public function findAllByAttribute($attribute, $value)
//    {
//        $accessor = self::getPropertyAccessor();
//        $results = [];
//        foreach($this->items as $item) {
//            if (
//                $accessor->isReadable($item, $attribute)
//                and $accessor->getValue($item, $attribute) === $value
//            ) {
//                $results[] = $item;
//            }
//        }
//        return $results;
//    }

    /**
     * @param string $section_name
     * @return ComponentInterface[]
     */
    public function findAllBySection($section_name)
    {
        $results = [];
        foreach ($this->items as $item) {
            if (
                $item instanceof ComponentInterface
                && $item->getRenderSection() === $section_name
            ) {
                $results[] = $item;
            }
        }
        return $results;
    }

//    /**
//     * @param Traversable|array $data
//     * @return array
//     */
//    protected function convertToArray($data)
//    {
//        if (method_exists($data, 'toArray')) {}
//        if ($data instanceof Traversable) {
//            $data = iterator_to_array($data);
//        }
//
//        if (!is_array($data)) {
//            throw new InvalidArgumentException(
//                'Data row must be array|Traversable|null'
//            );
//        }
//        return $data;
//    }

    /**
     * @param Traversable|array $data
     * @return $this
     */
    public function fillItemsWith($data)
    {
        foreach($this->items as $item) {
            $writable = Manipulator::getWritable($item);
            $fields = Manipulator::getValues($data, $writable);
            Manipulator::assign($item, $fields);
        }
        return $this;
    }
}
