<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Stripe審査用ダミーシーダー。
 * デジタル素材商品のみを投入し、product_accounts は作成しない。
 * DatabaseSeeder には含めないこと。
 *
 * 実行: php artisan db:seed --class=ProductDummySeeder
 */
class ProductDummySeeder extends Seeder
{
    public function run(): void
    {
        $twitterId   = DB::table('categories')->where('name', 'X(Twitter)')->value('id');
        $instagramId = DB::table('categories')->where('name', 'Instagram')->value('id');

        $now = now();

        $products = [
            // ── X(Twitter) デジタル素材 ──────────────────────────────
            [
                'category_id' => $twitterId,
                'name'        => 'X(Twitter) プロフィールデザインセット',
                'point'       => 500,
                'description' => 'X(Twitter)のプロフィールをプロ仕様に仕上げるヘッダー画像・アイコンのデザインセットです。PSDデータ付きで自由にカスタマイズ可能。',
                'contents'    => 'ヘッダー画像 x10 / アイコン画像 x10 / PSDファイル x20',
                'file_type'   => 'ZIP',
                'popularity_order' => 1,
            ],
            [
                'category_id' => $twitterId,
                'name'        => 'X(Twitter) 投稿テンプレートパック 20選',
                'point'       => 800,
                'description' => '告知・引用・名言など用途別に使えるX(Twitter)投稿デザインテンプレートです。Canvaで編集可能。',
                'contents'    => 'Canvaテンプレートリンク x20',
                'file_type'   => 'PNG',
                'popularity_order' => 2,
            ],
            [
                'category_id' => $twitterId,
                'name'        => 'X(Twitter) バナーテンプレート ビジネス向け',
                'point'       => 1200,
                'description' => 'ビジネスアカウント向けのプロフェッショナルなバナーテンプレート集。全30デザイン収録。',
                'contents'    => 'Canvaテンプレートリンク x30 / PNG書き出しデータ x30',
                'file_type'   => 'ZIP',
                'popularity_order' => 3,
            ],
            [
                'category_id' => $twitterId,
                'name'        => 'SNSマーケティング教材 X(Twitter)編',
                'point'       => 2000,
                'description' => 'フォロワーを増やすための運用ノウハウをまとめたPDF教材。投稿戦略・ハッシュタグ活用法・分析方法を網羅。',
                'contents'    => 'PDFテキスト（全80ページ） / チェックシートPDF x3',
                'file_type'   => 'PDF',
                'popularity_order' => 4,
            ],
            [
                'category_id' => $twitterId,
                'name'        => 'X(Twitter) スプレッドシート 投稿管理テンプレート',
                'point'       => 300,
                'description' => '投稿スケジュールと効果測定を一元管理できるGoogleスプレッドシートテンプレートです。',
                'contents'    => 'Googleスプレッドシートテンプレートリンク x1',
                'file_type'   => 'XLSX',
                'popularity_order' => 5,
            ],
            [
                'category_id' => $twitterId,
                'name'        => 'X(Twitter) アイコン素材集 500種',
                'point'       => 1500,
                'description' => 'SNS投稿に使えるシンプルなラインアイコン素材集。商用利用可・透過PNG形式で使いやすい。',
                'contents'    => 'PNGアイコン x500 / SVGアイコン x500',
                'file_type'   => 'ZIP',
                'popularity_order' => 6,
            ],

            // ── Instagram デジタル素材 ──────────────────────────────
            [
                'category_id' => $instagramId,
                'name'        => 'Instagram フィードテンプレートパック 30選',
                'point'       => 1500,
                'description' => 'フィードをおしゃれに統一できるCanvaテンプレートセット。ライフスタイル・美容・カフェなど複数テーマ収録。',
                'contents'    => 'Canvaテンプレートリンク x30',
                'file_type'   => 'PNG',
                'popularity_order' => 1,
            ],
            [
                'category_id' => $instagramId,
                'name'        => 'Instagram ストーリーズテンプレートパック',
                'point'       => 800,
                'description' => 'アンケート・告知・日常投稿に使えるストーリーズテンプレート20種。トレンドデザイン対応。',
                'contents'    => 'Canvaテンプレートリンク x20',
                'file_type'   => 'PNG',
                'popularity_order' => 2,
            ],
            [
                'category_id' => $instagramId,
                'name'        => 'Instagram ハイライトカバーセット 50種',
                'point'       => 600,
                'description' => 'プロフィールを華やかに彩るハイライトカバーアイコン。カラー・モノクロ各25種収録。商用利用可。',
                'contents'    => 'PNG画像 x50',
                'file_type'   => 'ZIP',
                'popularity_order' => 3,
            ],
            [
                'category_id' => $instagramId,
                'name'        => 'SNSマーケティング教材 Instagram編',
                'point'       => 2500,
                'description' => 'Instagramのアルゴリズムを解説し、リール・フィード・ストーリーズ別の伸ばし方を体系的に学べるPDF教材。',
                'contents'    => 'PDFテキスト（全100ページ） / 投稿カレンダーテンプレート x1 / チェックシートPDF x5',
                'file_type'   => 'PDF',
                'popularity_order' => 4,
            ],
            [
                'category_id' => $instagramId,
                'name'        => 'Instagram リールカバーテンプレート 15選',
                'point'       => 700,
                'description' => 'リール動画の一覧表示を魅力的にするカバー画像テンプレート。Canvaで簡単にテキストを差し替え可能。',
                'contents'    => 'Canvaテンプレートリンク x15',
                'file_type'   => 'PNG',
                'popularity_order' => 5,
            ],
            [
                'category_id' => $instagramId,
                'name'        => 'Instagram プロフィールデザインキット',
                'point'       => 1000,
                'description' => 'アイコン・ハイライト・フィードのカラーパレットを統一できるデザインキット。ブランドイメージを一新。',
                'contents'    => 'Canvaテンプレートリンク x10 / カラーパレットガイドPDF x1',
                'file_type'   => 'ZIP',
                'popularity_order' => 6,
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->insert(array_merge($product, [
                'thumbnail_path' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]));
        }
    }
}
