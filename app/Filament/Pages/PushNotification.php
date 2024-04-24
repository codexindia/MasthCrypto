<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Pages\Page;

class PushNotification extends Page
{
    protected static ?int $navigationSort = 3;


    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    protected static string $view = 'filament.pages.push-notification';
    protected function getHeaderActions(): array
    {
        return [];
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Banner Image')->schema([
                        FileUpload::make('image')
                            ->image()
                            ->directory('users/popup')
                            ->label('Banner Image'),
                    ]),

                ]),
                Group::make()->schema([
                    Section::make('General options')->schema([
                        TextInput::make('title')->required(),
                       
                        Textarea::make('message')->columnSpanFull()




                    ]),

                ]),
            ])->columns(2)->statePath('data');;
    }
    protected function getActions(): array
    {
        return [
            Action::make('Push Notification')
                ->icon('heroicon-o-paper-airplane')
                ->requiresConfirmation()
                ->submit('save')

        ];
    }
    public function save()
    {
        dd($this->form->getState());
    }
}
