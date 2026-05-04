<?php

namespace App\Filament\Resources\Wishlists\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class WishlistForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->label(__('filament.wishlist.customer'))
                    ->relationship('customer', 'name')
                    ->required(),
                Select::make('product_id')
                    ->label(__('filament.wishlist.product'))
                    ->relationship('product', 'name')
                    ->required(),
            ]);
    }
}
