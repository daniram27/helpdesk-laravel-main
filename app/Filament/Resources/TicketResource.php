<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers\CommentsRelationManager;
use App\Models\ProblemCategory;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\Unit;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Forms\Components\Select::make('owner_id')
                        ->label(__('Client'))
                        ->relationship('owner', 'name')
                        ->required()
                        ->default(fn() => auth()->id())
                        ->disabled()
                        ->columnSpan([
                            'sm' => 2,
                        ]),

                    Forms\Components\TextInput::make('title')
                        ->label(__('Case'))
                        ->required()
                        ->maxLength(255)
                        ->columnSpan([
                            'sm' => 2,
                        ]),

                    Forms\Components\RichEditor::make('description')
                        ->label(__('Description'))
                        ->required()
                        ->maxLength(65535)
                        ->columnSpan([
                            'sm' => 2,
                        ]),
                ])->columns([
                            'sm' => 2,
                        ])->columnSpan(2),

                Card::make()->schema([
                    Forms\Components\Select::make('ticket_statuses_id')
                    ->label(__('Status'))
                    ->options(TicketStatus::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->default(fn() => TicketStatus::where('name', 'Open')->first()->id)
                    ->hiddenOn('create')
                    ->hidden(fn() => !auth()->user()->hasAnyRole(['Super Admin', 'Admin Unit', 'Staff Unit']))
                    ->disabled(fn ($record) => $record !== null && $record->ticket_statuses_id == TicketStatus::where('name', 'Closed')->first()->id), // Disable if the record is not null and status is "Closed"
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('owner.name')
                    ->label(__('Owner'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Case'))
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->translateLabel()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('ticketStatus.name')
                    ->label(__('Status'))
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'view' => Pages\ViewTicket::route('/{record}'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where(function ($query) {
                // Display all tickets to Super Admin
                if (auth()->user()->hasRole('Super Admin')) {
                    return;
                }

                if (auth()->user()->hasRole('Admin Unit')) {
                    $query->where('tickets.unit_id', auth()->user()->unit_id)
                        ->orWhere('tickets.owner_id', auth()->id());
                } elseif (auth()->user()->hasRole('Staff Unit')) {
                    $query->where('tickets.responsible_id', auth()->id())
                        ->orWhere('tickets.owner_id', auth()->id());
                } else {
                    $query->where('tickets.owner_id', auth()->id());
                }
            })
            ;
    }

    public static function getPluralModelLabel(): string
    {
        return __('Tickets');
    }
}
