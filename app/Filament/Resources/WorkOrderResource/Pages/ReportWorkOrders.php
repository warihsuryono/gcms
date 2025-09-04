<?php

namespace App\Filament\Resources\WorkOrderResource\Pages;

use App\Models\Field;
use App\Models\WorkOrder;
use Filament\Tables\Table;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\WorkOrderExporter;
use App\Filament\Resources\WorkOrderResource;
use Filament\Tables\Concerns\InteractsWithTable;


class ReportWorkOrders extends Page implements HasTable
{
    use InteractsWithTable;
    protected static string $resource = WorkOrderResource::class;
    protected static string $model = WorkOrder::class;

    protected static string $view = 'filament.resources.work-order-resource.pages.report-work-orders';
    protected static ?string $title = 'Work Orders Report';

    public static function table(Table $table): Table
    {
        return $table
            ->query(WorkOrder::query())
            ->columns([
                TextColumn::make('work_order_status.name')->label('Status'),
                TextColumn::make('work_start')->dateTime(),
                TextColumn::make('work_end')->dateTime(),
                TextColumn::make('division.name'),
                TextColumn::make('field_ids')->label('Fields')->formatStateUsing(function ($record) {
                    $field_ids = json_decode($record->field_ids);
                    $fields = "";
                    foreach ($field_ids as $field_id) {
                        $fields .= @Field::find($field_id)->name . "<br>";
                    }
                    return $fields;
                })->html(),
                TextColumn::make('works')->searchable()->html(),
            ])
            ->filters([
                Filter::make('work_start')
                    ->form([DatePicker::make('work_start_from')->default(now()->firstOfMonth()), DatePicker::make('work_start_until')->default(now()->lastOfMonth())])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['work_start_from'], fn(Builder $query, $date): Builder => $query->whereDate('work_start', '>=', $date))
                            ->when($data['work_start_until'], fn(Builder $query, $date): Builder => $query->whereDate('work_start', '<=', $date));
                    })->columns(2),
                SelectFilter::make('work_order_status_id')->relationship('work_order_status', 'name')
            ])
            ->paginated(false)
            ->headerActions([
                Action::make('chart_work_orders')
                    ->label('Chart Work Orders')
                    ->action(fn() => redirect()->route('filament.' . env('PANEL_PATH') . '.resources.work-orders.chart'))
                    ->icon('heroicon-o-presentation-chart-bar')
                    ->color('primary'),
                ExportAction::make()
                    ->exporter(WorkOrderExporter::class)
                    ->label('Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color("success")
                    ->filename('work-orders-' . time())
                    ->columnMapping(false),
            ]);
    }
}
