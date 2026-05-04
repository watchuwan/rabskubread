<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label('Key')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono')
                    ->copyable(),

                TextColumn::make('label')
                    ->label('Label')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('group')
                    ->label('Grup')
                    ->badge()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'boolean' => 'success',
                        'image'   => 'warning',
                        'url'     => 'info',
                        default   => 'gray',
                    }),

                TextColumn::make('value')
                    ->label('Nilai')
                    ->limit(50)
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->label('Grup')
                    ->options([
                        'general' => 'General',
                        'contact' => 'Contact',
                        'social'  => 'Social',
                        'seo'     => 'SEO',
                    ]),

                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'text'     => 'Text',
                        'textarea' => 'Textarea',
                        'boolean'  => 'Boolean',
                        'image'    => 'Image',
                        'url'      => 'URL',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('group')
            ->groups(['group']);
    }
}
