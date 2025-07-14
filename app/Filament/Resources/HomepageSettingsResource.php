<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomepageSettingsResource\Pages;
use App\Models\HomepageSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HomepageSettingsResource extends Resource
{
    protected static ?string $model = HomepageSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 0;
    protected static ?string $navigationLabel = 'Homepage Settings';
    protected static ?string $pluralModelLabel = 'Homepage Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Homepage Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Media Assets')
                            ->schema([
                                Forms\Components\Section::make('Site Branding')
                                    ->schema([
                                        Forms\Components\FileUpload::make('logo')
                                            ->label('Site Logo')
                                            ->image()
                                            ->directory('homepage/branding')
                                            ->imageEditor()
                                            ->maxSize(2048)
                                            ->acceptedFileTypes(['image/png', 'image/jpg', 'image/jpeg', 'image/svg+xml'])
                                            ->helperText('Upload your site logo (PNG, JPG, or SVG). Max size: 2MB'),

                                        Forms\Components\FileUpload::make('favicon')
                                            ->label('Favicon')
                                            ->image()
                                            ->directory('homepage/branding')
                                            ->maxSize(512)
                                            ->acceptedFileTypes(['image/png', 'image/ico', 'image/svg+xml'])
                                            ->helperText('Upload favicon (PNG, ICO, or SVG). Max size: 512KB'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Hero Section Media')
                                    ->schema([
                                        Forms\Components\FileUpload::make('hero_video')
                                            ->label('Hero Background Video')
                                            ->disk('public')
                                            ->directory('homepage/hero')
                                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg', 'video/avi', 'video/mov'])
                                            ->maxSize(51200) // 50MB
                                            ->downloadable()
                                            ->openable()
                                            ->helperText('Upload hero background video (MP4, WebM, OGG, AVI, or MOV). Max size: 50MB'),

                                        Forms\Components\FileUpload::make('hero_image')
                                            ->label('Hero Background Image')
                                            ->image()
                                            ->directory('homepage/hero')
                                            ->imageEditor()
                                            ->maxSize(5120)
                                            ->acceptedFileTypes(['image/png', 'image/jpg', 'image/jpeg'])
                                            ->helperText('Upload hero background image (PNG or JPG). Max size: 5MB'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Content Images')
                                    ->schema([
                                        Forms\Components\FileUpload::make('about_image')
                                            ->label('About Section Image')
                                            ->image()
                                            ->directory('homepage/content')
                                            ->imageEditor()
                                            ->maxSize(3072) // 3MB
                                            ->helperText('Image for about section. Max size: 3MB'),

                                        Forms\Components\Repeater::make('testimonial_avatars')
                                            ->label('Testimonial Avatars')
                                            ->schema([
                                                Forms\Components\FileUpload::make('avatar')
                                                    ->label('Avatar Image')
                                                    ->image()
                                                    ->directory('homepage/testimonials')
                                                    ->imageEditor()
                                                    ->maxSize(1024) // 1MB
                                                    ->required(),
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Person Name')
                                                    ->required(),
                                                Forms\Components\TextInput::make('title')
                                                    ->label('Job Title/Role')
                                                    ->required(),
                                            ])
                                            ->columns(3)
                                            ->defaultItems(0)
                                            ->maxItems(10)
                                            ->helperText('Upload avatars for testimonials section'),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Content Settings')
                            ->schema([
                                Forms\Components\Section::make('Site Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('site_title')
                                            ->label('Site Title')
                                            ->required()
                                            ->maxLength(255)
                                            ->default('Nairobi County Training Center'),
                                    ]),

                                Forms\Components\Section::make('Hero Section Content')
                                    ->schema([
                                        Forms\Components\TextInput::make('hero_title')
                                            ->label('Hero Title')
                                            ->required()
                                            ->maxLength(255)
                                            ->default('Empowering Communities Through Education'),

                                        Forms\Components\Textarea::make('hero_subtitle')
                                            ->label('Hero Subtitle')
                                            ->required()
                                            ->rows(3)
                                            ->default('Join thousands of learners in Nairobi County advancing their careers through our comprehensive training programs.'),

                                        Forms\Components\TextInput::make('hero_cta_text')
                                            ->label('Call-to-Action Button Text')
                                            ->required()
                                            ->maxLength(50)
                                            ->default('Explore Programs'),
                                    ]),

                                Forms\Components\Section::make('About Section Content')
                                    ->schema([
                                        Forms\Components\TextInput::make('about_title')
                                            ->label('About Section Title')
                                            ->required()
                                            ->maxLength(255)
                                            ->default('About Our Training Center'),

                                        Forms\Components\Textarea::make('about_description')
                                            ->label('About Description')
                                            ->required()
                                            ->rows(4)
                                            ->default('We provide quality training programs to empower the community.'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('site_title')
                    ->label('Site Title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->square()
                    ->size(40),

                Tables\Columns\TextColumn::make('hero_title')
                    ->label('Hero Title')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // No bulk actions needed for settings
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomepageSettings::route('/'),
            'create' => Pages\CreateHomepageSettings::route('/create'),
            'edit' => Pages\EditHomepageSettings::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
