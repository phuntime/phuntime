<?php
declare(strict_types=1);

namespace Phuntime\Core;


/**
 * @deprecated
 * Class RuntimeConfiguration
 * @package Phuntime\Core
 */
class RuntimeConfiguration
{
    public const RUNTIME_TYPE_FPM = 'fpm';
    public const RUNTIME_TYPE_EV = 'ev';

    /**
     * @var string
     */
    protected $runtimeType;

    public static function create(): RuntimeConfiguration
    {
        return new self();
    }

    /**
     * @param string $runtimeType
     * @return RuntimeConfiguration
     */
    public function setRuntimeType(string $runtimeType): self
    {
        $this->runtimeType = $runtimeType;
        return $this;
    }

    /**
     * @return string
     */
    public function getRuntimeType(): string
    {
        return $this->runtimeType;
    }

}