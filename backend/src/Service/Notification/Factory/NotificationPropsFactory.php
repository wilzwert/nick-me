<?php

namespace App\Service\Notification\Factory;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;

/**
 * @author Wilhelm Zwertvaegher
 */
class NotificationPropsFactory implements NotificationPropsFactoryInterface
{
    public function __construct(
        #[AutowireLocator('app.notification_props_builder', indexAttribute: 'index')]
        private readonly ContainerInterface $builders,
    ) {
    }

    public function create(object $source): NotificationProps
    {
        if (!$this->builders->has($source::class)) {
            throw new \InvalidArgumentException(sprintf('No builder found for %s', $source::class));
        }

        $builder = $this->builders->get($source::class);
        return $builder->buildProps($source);
    }
}
