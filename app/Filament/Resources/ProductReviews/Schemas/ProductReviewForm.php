<?php

namespace App\Filament\Resources\ProductReviews\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.product_review.review_details'))
                    ->schema([
                        Select::make('product_id')
                            ->label(__('filament.product_review.product'))
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('customer_id')
                            ->label(__('filament.product_review.customer'))
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('order_id')
                            ->label(__('filament.product_review.order'))
                            ->relationship('order', 'order_number')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Radio::make('rating')
                            ->label(__('filament.product_review.rating'))
                            ->options([
                                1 => '1 ★',
                                2 => '2 ★★',
                                3 => '3 ★★★',
                                4 => '4 ★★★★',
                                5 => '5 ★★★★★',
                            ])
                            ->inline()
                            ->required()
                            ->default(5),
                    ])
                    ->columns(2),

                Section::make(__('filament.product_review.content'))
                    ->schema([
                        Textarea::make('review')
                            ->label(__('filament.product_review.comment'))
                            ->columnSpanFull()
                            ->rows(4)
                            ->placeholder(__('filament.product_review.placeholder')),
                        Textarea::make('admin_response')
                            ->label(__('filament.product_review.admin_response'))
                            ->columnSpanFull()
                            ->rows(3)
                            ->placeholder(__('filament.product_review.response_placeholder')),
                    ]),

                Section::make(__('filament.product_review.moderation'))
                    ->schema([
                        Toggle::make('is_approved')
                            ->label(__('filament.product_review.is_approved'))
                            ->helperText(__('filament.product_review.approved_helper')),
                        DateTimePicker::make('approved_at')
                            ->label(__('filament.product_review.approved_at')),
                    ])
                    ->columns(2),
            ]);
    }
}
