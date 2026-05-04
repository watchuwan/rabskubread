<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("order_number")
                    ->label(__("filament.order.order_number"))
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                BadgeColumn::make("status")
                    ->label(__("filament.order.status"))
                    ->colors([
                        "warning" => "pending",
                        "info" => "processing",
                        "primary" => "shipped",
                        "success" => "completed",
                        "danger" => "cancelled",
                        "danger" => "refunded",
                    ])
                    ->formatStateUsing(
                        fn($state): string => match ($state) {
                            "pending" => __("filament.order.status_pending"),
                            "processing" => __(
                                "filament.order.status_processing",
                            ),
                            "shipped" => __("filament.order.status_shipped"),
                            "completed" => __(
                                "filament.order.status_completed",
                            ),
                            "cancelled" => __(
                                "filament.order.status_cancelled",
                            ),
                            "refunded" => __("filament.order.status_refunded"),
                            default => $state,
                        },
                    ),
                TextColumn::make("customer.name")
                    ->label(__("filament.order.customer"))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("total_amount")
                    ->label(__("filament.order.total_amount"))
                    ->money("IDR")
                    ->sortable(),
                TextColumn::make("paid_at")
                    ->label(__("filament.order.paid_at"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("created_at")
                    ->label(__("filament.common.created_at"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make("status")
                    ->label(__("filament.order.status"))
                    ->options([
                        "pending" => __("filament.order.status_pending"),
                        "processing" => __("filament.order.status_processing"),
                        "shipped" => __("filament.order.status_shipped"),
                        "completed" => __("filament.order.status_completed"),
                        "cancelled" => __("filament.order.status_cancelled"),
                        "refunded" => __("filament.order.status_refunded"),
                    ]),
                SelectFilter::make("customer")
                    ->relationship("customer", "name")
                    ->label(__("filament.order.customer")),
            ])
            ->recordActions([
                EditAction::make()->label(__("filament.common.edit")),
            ])
            ->toolbarActions([
                Action::make("export_excel")
                    ->label("Export to Excel")
                    ->icon("heroicon-o-document-arrow-down")
                    ->color("success")
                    ->action(function ($livewire) {
                        $orders = $livewire->getFilteredTableQuery()->get();
                        return response()->streamDownload(function () use (
                            $orders,
                        ) {
                            $csv = fopen("php://output", "w");
                            fputcsv($csv, [
                                "Order Number",
                                "Customer",
                                "Status",
                                "Total",
                                "Date",
                            ]);
                            foreach ($orders as $order) {
                                fputcsv($csv, [
                                    $order->order_number,
                                    $order->customer->name,
                                    $order->status,
                                    $order->total_amount,
                                    $order->created_at->format("Y-m-d H:i:s"),
                                ]);
                            }
                            fclose($csv);
                        }, "orders-" . now()->format("Y-m-d") . ".csv");
                    }),
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label(
                        __("filament.common.delete"),
                    ),
                ]),
            ]);
    }
}
