<?php

namespace App\Service\Notification;

use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/**
 * @author Wilhelm Zwertvaegher
 */
class NotificationPropsFactory implements NotificationPropsFactoryInterface
{
    /**
     * @var array<string, NotificationPropsBuilder>
     */
    private array $builders;

    /**
     * @param iterable<NotificationPropsBuilder> $builders
     */
    public function __construct(
        #[AutowireIterator('app.notification_props_builder')]
        iterable $builders,
    ) {
        foreach ($builders as $builder) {
            $this->builders[$builder->getSupportedClass()] = $builder;
        }
    }

    public function create(object $source): NotificationProps
    {
        if (!isset($this->builders[$source::class])) {
            throw new \InvalidArgumentException(sprintf('No builder found for %s', $source::class));
        }

        return $this->builders[$source::class]->buildProps($source);
    }
}
