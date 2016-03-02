<?php

namespace ViewComponents\ViewComponents\Component;


use Nayjest\Tree\ChildNodeTrait;
use ViewComponents\ViewComponents\Base\DataViewComponentInterface;
use ViewComponents\ViewComponents\Common\HasDataTrait;
use ViewComponents\ViewComponents\Rendering\ViewTrait;
use RuntimeException;

class DataView implements DataViewComponentInterface
{
    use ChildNodeTrait;
    use ViewTrait;
    use HasDataTrait;

    /**
     * @var callable
     */
    private $renderer;

    /**
     * Constructor.
     *
     * If using data isn't required, renderer function can be passed to first argument.
     *
     * @param $data
     * @param callable|null $renderer
     */
    public function __construct($data = null, callable $renderer = null)
    {
        if ($renderer === null && is_callable($data)) {
            $renderer = $data;
            $data = null;
        }
        $this->setData($data);
        $this->renderer = $renderer ?: null;
    }

    public function render()
    {
        return $this->renderer === null
            ? $this->defaultRender()
            : call_user_func($this->renderer, $this->getData());
    }

    private function canBeString($value)
    {
        return is_scalar($value)
        || is_null($value)
        || (is_object($value) and method_exists($value, '__toString'));
    }

    private function defaultRender()
    {
        $data = $this->getData();
        if (!$this->canBeString($data)) {
            throw new RuntimeException(
                'Can\'t render data using default method, data can\'t be converted to string'
            );
        }
        return (string)$data;
    }
}
