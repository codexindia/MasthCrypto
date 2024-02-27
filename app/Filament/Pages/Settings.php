<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\{Group, Section};

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;


use Filament\Pages\Page;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

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
                    Section::make()->schema([
                        Select::make('maintenance_mode')
                        ->required()->options([
                            '1' => 'Active',
                            '2' => 'Deactive'
                        ]),
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('name')
                            ->required(),
                    ])->columns(2)

                    ]),
                    Group::make()->schema([
                        Section::make()->schema([
                            Select::make('maintenance_mode')
                            ->required()->options([
                                '1' => 'Active',
                                '2' => 'Deactive'
                            ]),
                            TextInput::make('name')
                                ->required(),
                            TextInput::make('name')
                                ->required(),
                            TextInput::make('name')
                                ->required(),
                            TextInput::make('name')
                                ->required(),
                        ])->columns(2)
    
                    ])

            ])
            ->statePath('data');
    }
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }
    public function save(): void
    {


        Notification::make()
            ->success()
            ->title("Changes Saved SuccessFully")
            ->send();
    }
}
