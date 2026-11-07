<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderItemDownloadController extends Controller
{
    public function __invoke(OrderItem $orderItem)
    {
        abort_unless($orderItem->isDownloadable(), 404);

        $product = $orderItem->product;
        $extension = pathinfo($product->file_path, PATHINFO_EXTENSION);

        return Storage::disk('local')->download(
            $product->file_path,
            Str::slug($product->title) . '.' . $extension,
        );
    }
}
