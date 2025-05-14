<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\ReplicateAction::make()
                ->label('Reorder')
                ->icon('heroicon-o-document-duplicate')
                ->color('slate')
                ->form([
                    DatePicker::make('would_like_it_by')
                        ->label('Would Like It By')
                        ->format('Y-m-d')
                        ->displayFormat('d/m/Y')
                        ->minDate(today())
                        ->native(false)
                        ->required()
                        ->closeOnDateSelection()
                        ->disabledDates(function (): array {
                            $dates = [];
                            $startDate = now();
                            $endDate = now()->addYear();

                            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                                if ($date->dayOfWeek === 0 || $date->dayOfWeek === 6) {
                                    $dates[] = $date->format('Y-m-d');
                                }
                            }

                            return $dates;
                        }),
                ])
                ->modalHeading('Are you sure you want to duplicate this order?')
                ->modalSubmitActionLabel('Yes, Duplicate'),
        ];
    }
}
