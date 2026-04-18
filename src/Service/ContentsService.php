<?php

namespace BcContentLinkExtension\Service;

use Cake\Core\Plugin;
use BaserCore\Service\ContentsService as BaseContentsService;

/**
 * Class ContentsService.
 */
class ContentsService extends BaseContentsService
{
    /**
     * グローバルナビ用のコンテンツ一覧を取得する.
     *
     * @param int $id
     * @return \Cake\Datasource\ResultSetInterface|false
     * @checked
     * @noTodo
     * @unitTest
     */
    public function getGlobalNavi(int $id)
    {
        $current = $this->get($id);
        $root = $this->Contents->find()->where([
            'Contents.site_id' => $current->site_id,
            'Contents.site_root' => true,
            $this->Contents->getConditionAllowPublish()
        ])->first();
        if (!$root) return false;
        $query = $this->Contents->find('children', for: $root->id, direct: true);
        $contents =$query->where([
            'Contents.exclude_menu' => false,
            $this->Contents->getConditionAllowPublish()
        ]);

        // BcContentLinkプラグインが読み込まれている場合は、リンクタイプのコンテンツに実際のリンク先URL（link_url）を付与する.
        // entity_id はページ等でも使うため、plugin / type を ON に含めないと ID 一致だけで誤結合する.
        if (Plugin::isLoaded('BcContentLink')) {
            $query->leftJoin(
                ['ContentLinks' => 'content_links'],
                [
                    'ContentLinks.id = Contents.entity_id',
                    'Contents.plugin' => 'BcContentLink',
                    'Contents.type' => 'ContentLink',
                ]
            )->select(['link_url' => 'ContentLinks.url'])->enableAutoFields();
        }
        return $contents->all();
    }
}
