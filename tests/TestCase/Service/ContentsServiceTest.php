<?php

namespace BcContentLinkExtension\Test\TestCase\Service;

use BaserCore\TestSuite\BcTestCase;
use BaserCore\Service\ContentsServiceInterface;
use BcContentLinkExtension\Service\ContentsService;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use BaserCore\Test\Scenario\ContentsScenario;
use BaserCore\Utility\BcContainer;

/**
 * ContentsServiceTest.
 *
 * @property ContentsService $ContentsService
 */
class ContentsServiceTest extends BcTestCase
{
    /**
     * Trait
     */
    use ScenarioAwareTrait;

    /**
     * ContentsService
     *
     * @var ContentsService
     */
    private $ContentsService;

    /**
     * コンテンツID（ダミーデータ用）.
     *
     * @var int
     */
    const CONTENT_ID = 1;

    /**
     * Set Up
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        // 起動時にサービスを上書きできないため、直接コンテナに登録して上書き
        $container = BcContainer::get();
        $container->addShared(ContentsServiceInterface::class, ContentsService::class);
        // サービスを取得.
        $this->ContentsService = $this->getService(ContentsServiceInterface::class);
        // ダミーデータを読み込む.
        $this->loadFixtureScenario(ContentsScenario::class);
    }

    /**
     * BcContentLinkプラグインが読み込まれている場合は、
     * コンテンツに実際のリンク先URL（link_url）が付与されているか確認する.
     *
     * @return void
     */
    public function testGetGlobalNaviWithLinkUrl(): void
    {
        // BcContentLinkプラグインを読み込む.
        $this->loadPlugins(['BcContentLink']);

        // グローバルナビを取得する.
        $results = $this->ContentsService->getGlobalNavi(self::CONTENT_ID)->toArray();

        foreach ($results as $result) {
            // リンクURLキーが存在するか確認する(リンクアイテムでなくてもnullで入るため).
            $this->assertArrayHasKey('link_url', $result->toArray());
        }
    }

    /**
     * BcContentLinkプラグインが読み込まれていない場合は、
     * コンテンツに実際のリンク先URL（link_url）が付与されていないことを確認する.
     *
     * @return void
     */
    public function testGetGlobalNaviWithoutLinkUrl(): void
    {
        // BcContentLinkプラグインを削除する.
        $this->removePlugins(['BcContentLink']);

        // グローバルナビを取得する.
        $results = $this->ContentsService->getGlobalNavi(self::CONTENT_ID)->toArray();

        foreach ($results as $result) {
            // リンクURLキーが存在しないことを確認する.
            $this->assertArrayNotHasKey('link_url', $result->toArray());
        }
    }
}
