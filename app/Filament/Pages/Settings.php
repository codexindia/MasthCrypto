<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\{Group, Section};
use Filament\Forms\Components\{Select,Radio,TextInput};
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;


use Filament\Pages\Page;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';
public $json;
    protected static string $view = 'filament.pages.settings';
    public function mount(): void
    {
        $this->form->fill();
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('General options')->schema([
                        Select::make('maintenance_mode')
                            ->required()->options([
                                '1' => 'Enable',
                                '0' => 'Disable'
                            ]),
                        Select::make('force_update')
                            ->required()->options([
                                '1' => 'Enable',
                                '0' => 'Disable'
                            ]),
                            Select::make('ad_network')
                            ->required()->options([
                                'admob' => 'Admob',
                               
                            ]),
                            Radio::make('mining_function')
                            ->label('Mining Function')
                            ->boolean()
                            ->inline()
                            ->default(1)
                            ->inlineLabel(false),
                        
                    ])->columns(2)

                ]),
                Group::make()->schema([
                    Section::make('Important Settings')->schema([
                       
                        TextInput::make('referral_coin')
                            ->required()->integer(),
                        TextInput::make('joining_coin')
                            ->required()->integer(),
                        TextInput::make('sm_country_t_charge')
                            ->required()->label('Same Country Transfer Charges')->integer(),
                        TextInput::make('diff_country_t_charge')
                            ->required()->label('Other Country Transfer Charges')->integer(),
                    ])->columns(2)

                ])

            ])->columns(2);
           
    }
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }
    public function save()
    {
        
     
        Notification::make()
            ->success()
            ->title("Changes Saved SuccessFully")
            ->send();
    }
}
