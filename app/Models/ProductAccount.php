<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAccount extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsToMany
     */
    public function orderDetails(): BelongsToMany
    {
        return $this->belongsToMany(OrderDetail::class, 'order_detail_product_accounts');
    }

    /**
     * @return array
     */
    public function parsedInfo(): array
    {
        $text = $this->account_text;
        $delim = str_contains($text, '|') ? '|' : ':';
        $parts = explode($delim, $text);

        $labels6 = ['ID（ユーザー名）', 'パスワード', 'メールアドレス', 'メールアドレスパスワード', '2FA', 'auth_token'];
        $labels7 = ['ID（ユーザー名）', 'パスワード', 'メールアドレス', 'メールアドレスパスワード', '電話番号', '2FA', 'auth_token'];
        $labels = count($parts) >= 7 ? $labels7 : $labels6;

        $result = [];
        foreach ($parts as $i => $value) {
            $result[] = [
                'label' => $labels[$i] ?? ('項目' . ($i + 1)),
                'value' => $value,
            ];
        }

        return $result;
    }
}
