<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    private const FIRST_NAMES = [
        'Agatha', 'Erica', 'Theodore', 'Lucas', 'Mia', 'Oliver', 'Emma', 'James',
        'Sophia', 'William', 'Isabella', 'Benjamin', 'Charlotte', 'Henry', 'Amelia',
        'Alexander', 'Harper', 'Michael', 'Evelyn', 'Daniel', 'Aria', 'Matthew',
        'Lily', 'Ethan', 'Chloe', 'Noah', 'Grace', 'Liam', 'Victoria', 'Elijah',
    ];

    private const LAST_NAMES = [
        'Char', 'Allen', 'Lane', 'Smith', 'Johnson', 'Williams', 'Brown', 'Jones',
        'Davis', 'Miller', 'Wilson', 'Moore', 'Taylor', 'Anderson', 'Thomas',
        'Jackson', 'White', 'Harris', 'Martin', 'Thompson', 'Garcia', 'Martinez',
        'Robinson', 'Clark', 'Rodriguez', 'Lewis', 'Lee', 'Walker', 'Hall', 'Young',
    ];

    private const DOMAINS = [
        'gmail.com', 'outlook.com', 'hotmail.com', 'yahoo.co.jp',
        'icloud.com', 'protonmail.com', 'live.com',
    ];

    public function run(): void
    {
        $twitterId   = DB::table('categories')->where('name', 'X(Twitter)')->value('id');
        $instagramId = DB::table('categories')->where('name', 'Instagram')->value('id');

        $now = now();

        $products = [
            // X(Twitter) — アカウント商品
            [
                'category_id' => $twitterId,
                'name'        => 'Twitterアカウント フォロワー1,000人',
                'point'       => 800,
                'description' => 'フォロワー数1,000人のX(Twitter)アカウントです。アカウントはログイン情報をご提供いたします。',
                'contents'    => 'ID / パスワード / 登録メールアドレス / メールパスワード / 2FA / authトークン',
                'file_type'   => null,
                'account_count' => 10,
                'with_phone'    => false,
            ],
            [
                'category_id' => $twitterId,
                'name'        => 'Twitterアカウント フォロワー5,000人',
                'point'       => 2500,
                'description' => 'フォロワー数5,000人のX(Twitter)アカウントです。育成済みの実績あるアカウントです。',
                'contents'    => 'ID / パスワード / 登録メールアドレス / メールパスワード / 2FA / authトークン',
                'file_type'   => null,
                'account_count' => 5,
                'with_phone'    => false,
            ],
            [
                'category_id' => $twitterId,
                'name'        => 'Twitterアカウント フォロワー10,000人',
                'point'       => 5000,
                'description' => 'フォロワー数10,000人超えのX(Twitter)アカウントです。影響力の高いアカウントをお得に。',
                'contents'    => 'ID / パスワード / 登録メールアドレス / メールパスワード / 電話番号 / 2FA / authトークン',
                'file_type'   => null,
                'account_count' => 3,
                'with_phone'    => true,
            ],
            [
                'category_id' => $twitterId,
                'name'        => 'Twitterアカウント フォロワー30,000人',
                'point'       => 12000,
                'description' => 'フォロワー数30,000人以上のX(Twitter)インフルエンサーアカウントです。',
                'contents'    => 'ID / パスワード / 登録メールアドレス / メールパスワード / 電話番号 / 2FA / authトークン',
                'file_type'   => null,
                'account_count' => 2,
                'with_phone'    => true,
            ],
            [
                'category_id' => $twitterId,
                'name'        => 'Twitterアカウント フォロワー50,000人',
                'point'       => 20000,
                'description' => 'フォロワー数50,000人のプレミアムX(Twitter)アカウント。高エンゲージメント。',
                'contents'    => 'ID / パスワード / 登録メールアドレス / メールパスワード / 電話番号 / 2FA / authトークン',
                'file_type'   => null,
                'account_count' => 1,
                'with_phone'    => true,
            ],
            [
                'category_id' => $twitterId,
                'name'        => 'Twitterインプレッション強化アカウント',
                'point'       => 3000,
                'description' => '高いインプレッション実績を持つX(Twitter)アカウント。ニッチな専門分野で確立されたフォロワー基盤。',
                'contents'    => 'ID / パスワード / 登録メールアドレス / メールパスワード / 2FA / authトークン',
                'file_type'   => null,
                'account_count' => 4,
                'with_phone'    => false,
            ],
            // X(Twitter) — デジタル素材
            [
                'category_id' => $twitterId,
                'name'        => 'Twitter素材パック ヘッダー＆アイコンセット',
                'point'       => 500,
                'description' => 'X(Twitter)プロフィールに使えるヘッダー画像とアイコンのデザインセットです。',
                'contents'    => 'ヘッダー画像 x10 / アイコン画像 x10 / PSD素材',
                'file_type'   => 'ZIP',
                'account_count' => 0,
                'with_phone'    => false,
            ],
            [
                'category_id' => $twitterId,
                'name'        => 'Twitterバナー テンプレートパック',
                'point'       => 1000,
                'description' => 'X(Twitter)の告知バナーに使えるCanva対応テンプレートです。全20デザイン収録。',
                'contents'    => 'Canvaテンプレートリンク x20',
                'file_type'   => 'PNG',
                'account_count' => 0,
                'with_phone'    => false,
            ],

            // Instagram — アカウント商品
            [
                'category_id' => $instagramId,
                'name'        => 'Instagramアカウント フォロワー1,000人',
                'point'       => 1200,
                'description' => 'フォロワー数1,000人のInstagramアカウントです。アカウント情報をご提供します。',
                'contents'    => 'ID / パスワード / 登録メールアドレス / メールパスワード / 2FA / authトークン',
                'file_type'   => null,
                'account_count' => 8,
                'with_phone'    => false,
            ],
            [
                'category_id' => $instagramId,
                'name'        => 'Instagramアカウント フォロワー5,000人',
                'point'       => 4000,
                'description' => 'フォロワー数5,000人のInstagramアカウント。ライフスタイル系の人気アカウントです。',
                'contents'    => 'ID / パスワード / 登録メールアドレス / メールパスワード / 2FA / authトークン',
                'file_type'   => null,
                'account_count' => 4,
                'with_phone'    => false,
            ],
            [
                'category_id' => $instagramId,
                'name'        => 'Instagramアカウント フォロワー10,000人',
                'point'       => 8000,
                'description' => 'フォロワー数10,000人以上のInstagramアカウント。エンゲージメント率が高く実用的です。',
                'contents'    => 'ID / パスワード / 登録メールアドレス / メールパスワード / 電話番号 / 2FA / authトークン',
                'file_type'   => null,
                'account_count' => 3,
                'with_phone'    => true,
            ],
            [
                'category_id' => $instagramId,
                'name'        => 'Instagramアカウント フォロワー30,000人',
                'point'       => 18000,
                'description' => 'フォロワー数30,000人超えのInstagramインフルエンサーアカウント。',
                'contents'    => 'ID / パスワード / 登録メールアドレス / メールパスワード / 電話番号 / 2FA / authトークン',
                'file_type'   => null,
                'account_count' => 2,
                'with_phone'    => true,
            ],
            [
                'category_id' => $instagramId,
                'name'        => 'Instagramアカウント フォロワー50,000人',
                'point'       => 28000,
                'description' => 'フォロワー数50,000人のプレミアムInstagramアカウント。美容・ファッション特化。',
                'contents'    => 'ID / パスワード / 登録メールアドレス / メールパスワード / 電話番号 / 2FA / authトークン',
                'file_type'   => null,
                'account_count' => 1,
                'with_phone'    => true,
            ],
            // Instagram — デジタル素材
            [
                'category_id' => $instagramId,
                'name'        => 'Instagram素材パック ハイライトカバーセット',
                'point'       => 600,
                'description' => 'Instagramのハイライトカバーに使えるアイコンデザインセット。全50種収録。',
                'contents'    => 'PNG画像 x50',
                'file_type'   => 'ZIP',
                'account_count' => 0,
                'with_phone'    => false,
            ],
            [
                'category_id' => $instagramId,
                'name'        => 'Instagramフィード テンプレートパック',
                'point'       => 1500,
                'description' => 'フィードをおしゃれに統一できるCanvaテンプレートセット。全30デザイン収録。',
                'contents'    => 'Canvaテンプレートリンク x30',
                'file_type'   => 'PNG',
                'account_count' => 0,
                'with_phone'    => false,
            ],
            [
                'category_id' => $instagramId,
                'name'        => 'Instagramストーリーズ テンプレートパック',
                'point'       => 800,
                'description' => 'ストーリーズ用のおしゃれなデザインテンプレート。アンケートや告知に最適。',
                'contents'    => 'Canvaテンプレートリンク x20',
                'file_type'   => 'PNG',
                'account_count' => 0,
                'with_phone'    => false,
            ],
        ];

        foreach ($products as $product) {
            $accountCount = $product['account_count'];
            $withPhone    = $product['with_phone'];
            unset($product['account_count'], $product['with_phone']);

            $productId = DB::table('products')->insertGetId(array_merge($product, [
                'thumbnail_path' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]));

            for ($i = 0; $i < $accountCount; $i++) {
                DB::table('product_accounts')->insert([
                    'product_id'   => $productId,
                    'account_text' => $this->generateAccountText($withPhone),
                    'is_used'      => false,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]);
            }
        }
    }

    private function generateAccountText(bool $withPhone): string
    {
        $delim     = rand(0, 1) ? ':' : '|';
        $firstName = self::FIRST_NAMES[array_rand(self::FIRST_NAMES)];
        $lastName  = self::LAST_NAMES[array_rand(self::LAST_NAMES)];
        $domain    = self::DOMAINS[array_rand(self::DOMAINS)];

        $id            = $firstName . $lastName . rand(10000, 99999);
        $password      = $this->randomAlphanumeric(8, 12, mixed: true);
        $email         = $firstName . $lastName . rand(1000, 9999) . '@' . $domain;
        $emailPassword = $this->randomAlphanumeric(6, 10, mixed: false) . rand(10, 99999);
        $twoFa         = strtoupper($this->randomAlphanumeric(16, 16, mixed: true));
        $authToken     = bin2hex(random_bytes(20));

        $fields = [$id, $password, $email, $emailPassword];

        if ($withPhone) {
            $prefix   = ['070', '080', '090'][array_rand(['070', '080', '090'])];
            $fields[] = $prefix . '-' . rand(1000, 9999) . '-' . rand(1000, 9999);
        }

        $fields[] = $twoFa;
        $fields[] = $authToken;

        return implode($delim, $fields);
    }

    private function randomAlphanumeric(int $min, int $max, bool $mixed): string
    {
        $len    = rand($min, $max);
        $chars  = $mixed
            ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
            : 'abcdefghijklmnopqrstuvwxyz';
        $result = '';
        for ($i = 0; $i < $len; $i++) {
            $result .= $chars[rand(0, strlen($chars) - 1)];
        }

        return $result;
    }
}
