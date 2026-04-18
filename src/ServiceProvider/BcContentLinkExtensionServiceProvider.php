<?php

namespace BcContentLinkExtension\ServiceProvider;

use BaserCore\Service\ContentsServiceInterface;
use BcContentLinkExtension\Service\ContentsService;
use Cake\Core\ServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

/**
 * Class BcContentLinkExtensionServiceProvider.
 */
class BcContentLinkExtensionServiceProvider extends ServiceProvider implements BootableServiceProviderInterface
{

    /**
     * Provides.
     * @var string[]
     */
    protected array $provides = [];

    /**
     * Boot.
     *
     * コアの BcServiceProvider はレイジー登録のため、boot() で先に定義を追加することで上書きする.
     *
     * @noTodo
     * @checked
     * @unitTest
     */
    public function boot(): void
    {
        $this->getContainer()->addShared(ContentsServiceInterface::class, ContentsService::class);
    }

    /**
     * Services.
     *
     * @param \Cake\Core\ContainerInterface $container
     *
     * @noTodo
     * @checked
     * @unitTest
     */
    public function services($container): void {}
}
