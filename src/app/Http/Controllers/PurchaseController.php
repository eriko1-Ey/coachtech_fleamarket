<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;

class PurchaseController extends Controller
{
    // 商品購入画面
    public function showPurchase(Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', '購入にはログインが必要です');
        }

        $user = Auth::user();

        return view('purchase', compact('product', 'user'));
    }

    public function completePurchase(Request $request, Product $product)
    {
        $user = Auth::user();

        // 購入データを保存
        Purchase::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'payment_method' => $request->payment_method,
        ]);

        // 商品のステータスをSOLDに変更
        $product->update(['is_sold' => true]);

        return redirect()->route('getMypage')->with('success', '購入が完了しました！');
    }
}
