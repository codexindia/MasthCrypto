<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use App\Models\CoinsTransaction;



class ListUserTransactions extends page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.pages.transactions';
    protected $record;
    public function mount($record = null): void
    {
        $this->record = $record;
    }



    public  function table(Table $table): Table
    {

        return $table
            ->query(CoinsTransaction::query()->where('user_id', $this->record)->orderBy('id', 'desc'))
            ->columns([
                TextColumn::make('transaction_type')
                    ->label('Transaction Type')->sortable(),
                TextColumn::make('coin')
                    ->label('Coins')
                    ->sortable(),
                TextColumn::make('description')
                    ->sortable(),
                TextColumn::make('transaction_id')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        
                        'pending' => 'warning',
                        'success' => 'success',
                        'failed' => 'danger',
                    }),


                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable()
                    ->since(),

            ]);
    }
    public static function formatProperties($properties)
    {
        if (is_string($properties)) {
            $properties = json_decode($properties, true);
        }

        if (!is_array($properties)) {
            return $properties;
        }

        $output = '<ul>';
        foreach ($properties as $key => $value) {
            $output .= "<li><strong>$key:</strong> " . (is_array($value) ? json_encode($value) : $value) . "</li>";
        }
        $output .= '</ul>';

        return $output;
    }
}
