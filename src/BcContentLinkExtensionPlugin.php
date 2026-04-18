<?php
declare(strict_types=1);

namespace BcContentLinkExtension;

use BaserCore\BcPlugin;
use BcContentLinkExtension\ServiceProvider\BcContentLinkExtensionServiceProvider;
use Cake\Core\ContainerInterface;

/**
 * Class BcContentLinkExtensionPlugin
 */
class BcContentLinkExtensionPlugin extends BcPlugin
{

    /**
     * services.
     *
     * サービスプロバイダを追加する.
     *
     * @param \Cake\Core\ContainerInterface $container
     *   The service container.
     *
     * @noTodo
     * @checked
     * @unitTest
     */
    public function services(ContainerInterface $container): void
    {
        $container->addServiceProvider(new BcContentLinkExtensionServiceProvider());
    }

}
