<?php

namespace Lopi;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * @author Pierre-Louis Launay <lopi@marinlaunay.fr>
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
