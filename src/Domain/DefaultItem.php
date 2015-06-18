<?php

namespace Maatwebsite\Sidebar\Domain;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use Maatwebsite\Sidebar\Append;
use Maatwebsite\Sidebar\Badge;
use Maatwebsite\Sidebar\Exceptions\LogicException;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Traits\CacheableTrait;
use Maatwebsite\Sidebar\Traits\CallableTrait;
use Maatwebsite\Sidebar\Traits\ItemableTrait;
use Maatwebsite\Sidebar\Traits\Routeable;
use Serializable;

class DefaultItem implements Item, Serializable
{
    use CallableTrait, CacheableTrait, ItemableTrait, Routeable;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $weight = 0;

    /**
     * @var string
     */
    protected $icon;

    /**
     * @var Collection|Badge[]
     */
    protected $badges;

    /**
     * @var Collection|Append[]
     */
    protected $appends;

    /**
     * @var Container
     */
    protected $container;

    /**
     * Data that should be cached
     * @var array
     */
    protected $cacheables = [
        'name',
        'weight',
        'url',
        'icon',
        'items',
        'badges',
        'appends'
    ];

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->items     = new Collection();
        $this->badges    = new Collection();
        $this->appends   = new Collection();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return Item $item
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param int $weight
     *
     * @return Item
     */
    public function setWeight($weight)
    {
        if (!is_int($weight)) {
            throw new LogicException('Weight should be an integer');
        }

        $this->weight = $weight;

        return $this;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     *
     * @return Item
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @param callable|null|string $callbackOrValue
     * @param string|null          $className
     *
     * @return Badge
     */
    public function badge($callbackOrValue = null, $className = null)
    {
        $badge = $this->container->make('Maatwebsite\Sidebar\Badge');

        if (is_callable($callbackOrValue)) {
            $this->call($callbackOrValue, $badge);
        } elseif ($callbackOrValue) {
            $badge->setValue($callbackOrValue);
        }

        if ($className) {
            $badge->setClass($className);
        }

        $this->addBadge($badge);

        return $badge;
    }

    /**
     * @param Badge $badge
     *
     * @return Badge
     */
    public function addBadge(Badge $badge)
    {
        $this->badges->push($badge);

        return $badge;
    }

    /**
     * @return Collection|Badge[]
     */
    public function getBadges()
    {
        return $this->badges;
    }

    /**
     * @param null        $callbackOrRoute
     * @param string|null $icon
     * @param null        $name
     *
     * @return Append
     */
    public function append($callbackOrRoute = null, $icon = null, $name = null)
    {
        $append = $this->container->make('Maatwebsite\Sidebar\Append');

        if (is_callable($callbackOrRoute)) {
            $this->call($callbackOrRoute, $append);
        } elseif ($callbackOrRoute) {
            $append->route($callbackOrRoute);
        }

        if ($name) {
            $append->setName($name);
        }

        if ($icon) {
            $append->setIcon($icon);
        }

        $this->addAppend($append);

        return $append;
    }

    /**
     * @param Append $append
     *
     * @return Append
     */
    public function addAppend(Append $append)
    {
        $this->appends->push($append);

        return $append;
    }

    /**
     * @return Collection|Append[]
     */
    public function getAppends()
    {
        return $this->appends;
    }
}
