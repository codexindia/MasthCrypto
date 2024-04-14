<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\Alignment;

class PopupImagePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $title = 'Popup Manager';


    protected static string $view = 'filament.pages.popup-image-page';
    public $popupImage;
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('General options')->schema([
                        FileUpload::make('popupImage')
                            ->image()
                            ->directory('users/profile')
                            ->label('Popup Image'),
                    ]),

                ]),
                Group::make()->schema([
                    Section::make('General options')->schema([
                        TextInput::make('Button Text')->required(),
                        TextInput::make('Action Link')->required(),
                    ])->columns(2),

                ]),
            ]);
    }
    protected function getActions(): array
    {
        return [
            Action::make('Save And Update')
                ->icon('heroicon-m-star')->requiresConfirmation()
                ->submit('save'),

        ];
    }
    protected function getHeaderActions(): array
    {
        return [];
    }

    public function save(): void
    {


        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }
}
