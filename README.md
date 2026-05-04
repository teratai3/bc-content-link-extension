# BcContentLinkExtension

`ContentsService::getGlobalNavi()` で取得するグローバルナビ用コンテンツに、**リンクタイプ**（`BcContentLink`）のときだけ実際のリンク先 URL を **`link_url`** として付与する拡張プラグインです。  
`BcContentLink` が読み込まれていない場合は、コアの `getGlobalNavi()` と同様の結果になります。

## 動作要件

- baserCMS 5.x
- PHP 8.1 以上
- Composer 依存: [baserproject/bc-content-link](https://github.com/baserproject/bc-content-link)（`BcContentLink` プラグイン）

## 動作仕様

- DI コンテナの `ContentsServiceInterface` を、コアの `BaserCore\Service\ContentsService` の代わりに `BcContentLinkExtension\Service\ContentsService` に差し替えます（`BcContentLinkExtensionServiceProvider` の `boot()` で登録）。
- `getGlobalNavi()` 内で、`BcContentLink` プラグインが読み込まれている場合のみ、`content_links` テーブルへ **LEFT JOIN** し、`link_url` として `ContentLinks.url` を SELECT します。
- JOIN 条件には `Contents.plugin = 'BcContentLink'` および `Contents.type = 'ContentLink'` を含め、`entity_id` の一致だけによる誤結合を避けます。
- `BcContentLink` が無効または未読込のときは JOIN を行わず、従来どおり `link_url` は付与されません。

## インストール

1. 本プラグインを baserCMS の `plugins` 配下に配置し、親プロジェクトの Composer でパスリポジトリ等により参照できるようにします。
2. `composer require baserproject/bc-content-link-extension`（または同等のパス指定）で依存を解決してください（`baserproject/bc-content-link` が一緒に入ります）。
3. baserCMS 管理画面のプラグイン管理から **BcContentLinkExtension** を有効化してください。
4. `link_url` を API レスポンス等に含めたい場合は、**BcContentLink** プラグインも有効化してください。

## アンインストール

管理画面から無効化すると、`ContentsServiceInterface` の実装はコアの `ContentsService` に戻ります。  
データベースの変更は行いません。
