<?php


namespace API\Models;


class NavEntry
{
    private string $type;
    private string $label;
    private string $icon;
    private array $link;
    private string $active;

    public function __construct(){
        $args = func_get_args();
        $this->type = (string)$args[0];
        $this->label = (string)$args[1];
        $this->icon = (string)$args[2];
        $this->link = (array) $args[3];
        $this->active =(string)$args[4];
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @return array
     */
    public function getLink(): array
    {
        return $this->link;
    }

    /**
     * @return string
     */
    public function getActive(): string
    {
        return $this->active;
    }

}