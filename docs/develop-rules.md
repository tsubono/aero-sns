# Laravel 開発ルール

## 基本方針

- 既存のディレクトリ構成、命名規則、Controller名、Model名、View構成を優先すること
- 不要なリファクタリングは行わないこと
- 指示されていないメソッド名・変数名・カラム名の変更は行わないこと
- 既存処理に合わせた最小差分で実装すること
- 存在しないカラム・リレーション・設定値を推測で使用しないこと
- DB設計書、URL設計書、機能一覧に記載されている内容を優先すること
- 日本語の項目名・画面文言は、設計書の表記に合わせること
- 変数名は意味が分かる名前にすること
    - `$q` などの省略名は使用しない
    - 例：`$query`, `$exam`, `$examQuestion`, `$examQuestionChoice`

---

## テーブル作成・マイグレーションに関して

- マイグレーションはDB設計書の内容に合わせて作成すること
- カラム名、型、nullable、default、PK、FK、備考をDB設計書に合わせること
- マイグレーションのコメントには、db.mdの「内容」を記載すること

例：

~~~php
$table->string('title')->comment('タイトル');
$table->boolean('is_public')->default(true)->comment('公開フラグ');
~~~

- 中間テーブルは、組み合わせの重複を防ぐためにFKを複合uniqueにすること

例：

~~~php
$table->unique(['job_id', 'category_id']);
~~~

- 論理削除に影響が出るため、上記以外の `email` などには原則unique制約を付けないこと。既存のファイルでuniqueがついていれば削除すること
- `deleted_at` があるテーブルでは、論理削除後の再登録や同一メールアドレス再利用を考慮すること
- 外部キーを貼る場合は、削除時の挙動を用途に合わせて指定すること
    - 履歴系データは安易にcascadeしないこと
    - マスタ・子データなど親削除時に一緒に消えてよいもののみcascadeを検討すること
- `softDeletes()` を使用する場合は、Model側にも `SoftDeletes` を設定すること
- `timestamps()` を使用する場合は、DB設計書の `created_at` / `updated_at` に合わせること
- Enumやconfigで管理する値は、DB上では `tinyint` や `string` など設計書通りの型で定義すること
- DB設計の備考欄にEnumやconfig定義がある場合は、マイグレーションだけでなくEnum / configも合わせて作成すること
- 初期データの記載がある場合は、シーダーも合わせて作成すること
- シーダーはテーブルごとにクラスを分けること (PrefectureSeeder, AdminSeederなど)

---

## Modelに関して

- モデルは `fillable` ではなく `guarded` を使用すること

例：

~~~php
protected $guarded = ['id'];
~~~

- テーブルに `deleted_at` がある場合は `SoftDeletes` を使用すること

例：

~~~php
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;
}
~~~

- Enumカラムは `$casts` に設定すること

例：

~~~php
protected $casts = [
    'category' => ExamCategory::class,
    'is_public' => 'boolean',
];
~~~

- booleanカラムは `$casts` で `boolean` にすること
- jsonカラムは `$casts` で `array` にすること

例：

~~~php
protected $casts = [
    'answers' => 'array',
];
~~~

- リレーションはModelに定義すること
- リレーション名は分かりやすい名前にすること

例：

~~~php
public function questions()
{
    return $this->hasMany(ExamQuestion::class);
}

public function choices()
{
    return $this->hasMany(ExamQuestionChoice::class);
}
~~~

- 算出する値などはモデルの `getAttribute` を使用すること
- ただし、実カラム名と同名のアクセサは作成しないこと
    - 実値が参照できなくなるため禁止
    - 必ず別名アクセサにすること

悪い例：

~~~php
public function getStatusAttribute()
{
    return ...
}
~~~

良い例：

~~~php
public function getStatusLabelAttribute()
{
    return $this->status?->label();
}
~~~

- 表示用のラベルや加工値は、必要に応じて `xxx_label` や `xxx_text` のような別名で定義すること
- ControllerやBladeに計算処理を書きすぎず、Model側に寄せられる処理はModelに定義すること

---

## Enum / config に関して

- DB設計の備考欄に「Enumで管理」とある場合やtinyIntegerの場合は、LaravelのEnumを作成すること
- Enumには必要に応じて `label()` を定義すること

例：

~~~php
<?php

namespace App\Enums;

enum ExamCategory: int
{
    case SecondClassDronePilot = 1;

    public function label(): string
    {
        return match ($this) {
            self::SecondClassDronePilot => '二等無人航空機操縦士',
        };
    }
}
~~~

- 選択肢として画面表示する場合は、Enum側に `options()` などを用意してもよい
- configで管理する指定がある場合は、`config/` 配下に定義すること
- Enumとconfigを混在させる場合は、どちらを正とするかを明確にすること
- BladeやControllerにマジックナンバーを直接書かないこと

悪い例：

~~~php
if ($exam->category === 1) {
    ...
}
~~~

良い例：

~~~php
if ($exam->category === ExamCategory::SecondClassDronePilot) {
    ...
}
~~~

---

## Controllerに関して

- Controllerには処理を書きすぎないこと
- 複雑な登録処理、集計処理、外部API連携などはServiceクラスに分離すること
- Controllerでは以下を中心に行うこと
    - Requestの受け取り
    - FormRequestによるバリデーション
    - Service呼び出し
    - redirect / view返却
- DB登録・更新で複数テーブルを操作する場合は、必ずトランザクションを使用すること
- 例外発生時に中途半端なデータが残らないようにすること
- redirect時は成功・失敗メッセージを付与すること

例：

~~~php
return redirect()
    ->route('admin.exams.index')
    ->with('success', '模試を登録しました。');
~~~

---

## Serviceに関して

- 複雑な処理はServiceクラスに分離すること
- Serviceは `app/Services/` 配下に作成すること
- 機能単位でディレクトリを分けてもよい

例：

~~~txt
app/Services/Exam/ExamService.php
app/Services/Coupon/CouponService.php
~~~

- Serviceでは以下のような処理を担当すること
    - データ整形
    - 複数テーブル登録
    - 集計
    - 外部API連携
- Service内でDB更新を行う場合は、Controller側またはService側のどちらでトランザクションを張るか明確にすること
- 同じトランザクションを二重に無意味に張らないこと

---

## バリデーションに関して

- フォームに `required` は使用しないこと
- 必須チェックを含む全ての入力チェックはフォームバリデーションで行うこと
- バリデーションはFormRequestを使用すること
- Controller内に直接 `$request->validate()` を書かないこと
- 機能ごとにディレクトリを分けてFormRequestを作成すること
- store / update は共通のFormRequestを使用すること
- 作成・更新でルールが異なる場合は、FormRequest内でHTTPメソッドやroute parameterを見て分岐すること

例：

~~~txt
app/Http/Requests/Admin/Shop/UpsertRequest.php
app/Http/Requests/Admin/Job/ToggleStatusRequest.php
app/Http/Requests/Shop/Job/UpsertRequest.php
app/Http/Requests/Job/ApplyRequest.php
~~~

- FormRequest名は用途が分かる名前にすること
- 新規登録・編集で共通の場合は `UpsertRequest` を使用すること
- ステータス変更など単一操作の場合は `ToggleStatusRequest` など用途名を付けること
- エラーメッセージが必要な場合は `messages()` に定義すること
- 属性名の日本語表示が必要な場合は `attributes()` に定義すること
- SoftDeleteされているレコードはuniqueにひっかからないようにすること

例：

~~~php
public function attributes(): array
{
    return [
        'title' => 'タイトル',
        'csv_file' => 'CSVファイル',
    ];
}
~~~

---

## view / Bladeに関して

- Controllerごとにディレクトリを作成し、routingが複数形の場合はディレクトリ名も合わせること
- ただし、TopController@indexのように1つしかない場合はディレクトリを作成せずにtop.blade.phpのようにする
- 新規登録 / 編集フォームのフォーム内容は共通化して使用すること
- `_form-content.blade.php` のような部分テンプレートを作成し、`<form></form>` の間でincludeすること

例：

~~~txt
resources/views/admin/shops/_form-content.blade.php
~~~

使用例：

~~~blade
<form method="POST" action="{{ route('adminz.shops.store') }}">
    @csrf

    @include('admin.shops._form-content')

    <button type="submit">登録</button>
</form>
~~~

- 編集フォームでは同じ `_form-content.blade.php` を使用し、methodだけ切り替えること

例：

~~~blade
<form method="POST" action="{{ route('admin.shops.update', $shop) }}">
    @csrf
    @method('PUT')

    @include('admin.shops._form-content')

    <button type="submit">更新</button>
</form>
~~~

- レイアウトは `resources/views/layouts/` に置き、各画面は `@extends` で継承すること

例：

~~~txt
resources/views/layouts/app.blade.php
resources/views/layouts/shop/app.blade.php
resources/views/layouts/admin/app.blade.php
~~~

- 上記の場合、レイアウトの用途は以下とすること

| ファイル | 用途 |
|---|---|
| `resources/views/layouts/app.blade.php` | ユーザー画面 |
| `resources/views/layouts/shop/app.blade.php` | 店舗ポータル |
| `resources/views/layouts/admin/app.blade.php` | 管理画面 |

- ヘッダー・メニュー等の共通パーツは `components/` に置くこと

例：

~~~txt
resources/views/components/layout/header.blade.php
resources/views/components/layout/menu.blade.php
resources/views/components/shop/layout/header.blade.php
resources/views/components/admin/layout/menu.blade.php
resources/views/components/ui/modal.blade.php
resources/views/components/ui/button.blade.php
~~~

- 汎用UIは `components/ui/` に配置すること
- 管理画面専用コンポーネントは `components/admin/` 配下に配置すること
- Blade内に複雑な条件分岐や集計処理を書きすぎないこと
- @php...@endphpは必要な場合のみとし、変数の代入などは原則Controllerで行うこと
- 表示に必要な加工済みデータは、Controller、Service、Modelアクセサなどで用意すること
- `old()` を使用してバリデーションエラー時に入力値が戻るようにすること

例：

~~~blade
<input type="text" name="title" value="{{ old('title', $exam->title ?? '') }}">
~~~

- select / checkbox / radio も `old()` を考慮すること
- エラー表示は共通コンポーネント化してもよい

---

## route / ルーティングに関して

- ルート名は機能・画面に合わせて分かりやすく定義すること
- ルーティングは機能単位・認証単位で `group` 化すること
- prefix、name、middleware は `Route::prefix()` / `name()` / `middleware()` を使用してまとめること
- 管理画面は `admin.` prefixを付けること
- ユーザー画面、管理画面などはguardやURL構造に応じてgroupを分けること
- 管理画面内でも、機能単位でさらに `prefix()` / `name()` を使ってgroup化すること
- RESTに寄せられるものはLaravelのresourceに近い命名にすること
- URL設計書がある場合は、URL設計書の内容を優先すること
- 既存ルートがある場合は、勝手に変更しないこと
- resourceを使用してソースをシンプルにすること
- 管理画面には原則 `auth:admin` を使用すること
- ユーザー画面のログイン必須ページには `auth` を使用すること
- guest専用ページには `guest` を使用すること
- 管理者guest専用ページには必要に応じて `guest:admin` を使用すること

---

## 認証・権限に関して

- 認証機能はLaravel Breezeを使用すること
- Breezeの構成をベースに実装し、不要な独自認証処理を作成しないこと
- 管理者とユーザーのguardが分かれている場合は、必ず適切なguardを使用すること
- 管理画面では `auth:admin` を使用すること
- ユーザー画面では `auth` を使用すること
- guest middlewareもguardに応じて正しく設定すること
    - ユーザー向けログイン画面：`guest`
    - 管理者向けログイン画面：`guest:admin`
- 認証済みの場合のリダイレクト先は、guardに応じて正しく設定すること
- ユーザーでログイン済みの場合は、ユーザー用のダッシュボード等へリダイレクトすること
- 管理者でログイン済みの場合は、管理画面のダッシュボード等へリダイレクトすること
- `RedirectIfAuthenticated` やログイン後の遷移先をguard別に調整すること

例：

~~~php
// ユーザー認証済みの場合
return redirect()->route('dashboard');

// 管理者認証済みの場合
return redirect()->route('admin.dashboard');
~~~

- ログイン中ユーザーのIDが必要な場合は、適切なguardから取得すること

例：

~~~php
$userId = auth()->id();
$adminId = auth('admin')->id();
~~~

- 権限チェックが必要な操作は、ControllerまたはPolicyでチェックすること
- 他ユーザーのデータを参照・更新できないように注意すること

---

## 命名に関して

- 変数名・メソッド名・クラス名は意味が分かる名前にすること
- `$q` などの省略名は使用しないこと
- DBカラム名と意味がズレる名前を使わないこと
- 既存の命名規則がある場合はそれに合わせること
- `sort_order` は汎用的な表示順に使用すること
- 問題番号など意味が明確な場合は `question_no` のように具体名を使用してよい

---

## エラーハンドリングに関して

- 登録・更新・削除に失敗した場合は、ユーザーに分かるメッセージを返すこと
- 例外を握りつぶさないこと
- 必要に応じてログを出力すること

例：

~~~php
logger()->error('登録処理に失敗しました。', [
    'message' => $exception->getMessage(),
]);
~~~

- 本番環境で詳細な例外メッセージを画面に表示しないこと
- 入力エラーはFormRequestやバリデーションで通常エラーとして返すこと
- システムエラーはログに記録し、画面には一般的なエラーメッセージを表示すること

---

## 削除処理に関して

- `deleted_at` があるテーブルは原則論理削除を使用すること
- 物理削除が必要な場合は、理由を明確にすること
- 親データ削除時に子データをどう扱うか明確にすること
- 履歴テーブルは基本的に削除しないこと
- 中間テーブルなど履歴性が低いものは物理削除でもよい
- 子データを全入れ替えする場合は、対象データの範囲を必ず親IDで絞ること

---

## 一覧・検索に関して

- 一覧画面は必要に応じてページネーションを使用すること
- 検索条件はRequestから受け取り、queryに反映すること
- 検索条件はページネーション時も維持すること

例：

~~~php
$items = $query->paginate(20)->withQueryString();
~~~

- 一覧の並び順はDB設計に `sort_order` がある場合はそれを優先すること
- 指定がない場合は `created_at desc` など既存仕様に合わせること

---

## フロントエンドに関して

- フォームに `required` は使用しないこと
- 必須チェックは必ずFormRequestで行うこと
- JavaScriptは補助的なUI制御として使用すること
- JavaScriptだけで重要なバリデーションを完結させないこと
- Tailwind CSSを使用している場合は既存のクラス設計に合わせること
- Bladeコンポーネントがある場合は、既存コンポーネントを優先して使用すること

---

## テスト・確認に関して

- 新規登録、編集、削除、一覧表示、詳細表示など基本操作を確認すること
- バリデーションエラー時に入力値が保持されることを確認すること
- 権限がない状態でアクセスできないことを確認すること
- guardごとのログイン・ログアウト・認証済みリダイレクトが正しいことを確認すること
- ユーザーで管理画面にアクセスできないことを確認すること
- 管理者でユーザー画面の想定外ページにアクセスできないことを確認すること
- guestページに認証済み状態でアクセスした場合、guardに応じた正しい画面へリダイレクトされることを確認すること
