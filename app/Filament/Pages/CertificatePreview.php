<?php

namespace App\Filament\Pages;

use App\Models\Enrollment;
use App\Models\Course;
use App\Services\CertificateService;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class CertificatePreview extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Certificate Management';
    protected static ?string $title = 'Certificate Preview';
    protected static ?string $slug = 'certificate-preview';
    protected static ?int $navigationSort = 1;

    public ?array $data = [];
    public ?string $selectedTemplate = 'classic';
    public ?Enrollment $selectedEnrollment = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Certificate Preview Settings')
                    ->schema([
                        Select::make('enrollment_id')
                            ->label('Select Enrollment')
                            ->options(
                                Enrollment::with(['user', 'course'])
                                    ->where('status', Enrollment::STATUS_COMPLETED)
                                    ->get()
                                    ->mapWithKeys(function ($enrollment) {
                                        $label = "{$enrollment->user->name} - {$enrollment->course->title}";
                                        if ($enrollment->certificate_issued) {
                                            $label .= " (Cert: {$enrollment->certificate_number})";
                                        }
                                        return [$enrollment->id => $label];
                                    })
                            )
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                if ($state) {
                                    $this->selectedEnrollment = Enrollment::with(['user', 'course'])->find($state);
                                }
                            }),

                        Select::make('template')
                            ->label('Certificate Template')
                            ->options([
                                'classic' => 'Classic Template',
                                'modern' => 'Modern Template',
                            ])
                            ->default('classic')
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->selectedTemplate = $state;
                            }),
                    ])
                    ->columns(2),
            ]);
    }

    public function previewCertificate(): void
    {
        if (!$this->selectedEnrollment) {
            Notification::make()
                ->danger()
                ->title('No Enrollment Selected')
                ->body('Please select an enrollment to preview the certificate.')
                ->send();
            return;
        }

        // Issue certificate if not already issued
        if (!$this->selectedEnrollment->certificate_issued) {
            $certificateService = app(CertificateService::class);
            $certificateService->issueCertificate($this->selectedEnrollment);
            $this->selectedEnrollment->refresh();
        }
    }

    public function issueCertificate(): void
    {
        if (!$this->selectedEnrollment) {
            Notification::make()
                ->danger()
                ->title('No Enrollment Selected')
                ->body('Please select an enrollment to issue the certificate.')
                ->send();
            return;
        }

        $certificateService = app(CertificateService::class);
        $success = $certificateService->issueCertificate($this->selectedEnrollment);

        if ($success) {
            $this->selectedEnrollment->refresh();
            Notification::make()
                ->success()
                ->title('Certificate Issued')
                ->body("Certificate number: {$this->selectedEnrollment->certificate_number}")
                ->send();
        } else {
            Notification::make()
                ->danger()
                ->title('Certificate Issue Failed')
                ->body('Failed to issue certificate. Please try again.')
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('preview')
                ->label('Preview Certificate')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->action('previewCertificate')
                ->visible(fn() => $this->selectedEnrollment),

            \Filament\Actions\Action::make('issue')
                ->label('Issue Certificate')
                ->icon('heroicon-o-academic-cap')
                ->color('success')
                ->action('issueCertificate')
                ->visible(fn() => $this->selectedEnrollment && !$this->selectedEnrollment->certificate_issued),

            \Filament\Actions\Action::make('download')
                ->label('Download Certificate')
                ->icon('heroicon-o-document-arrow-down')
                ->color('warning')
                ->url(
                    fn() =>
                    $this->selectedEnrollment && $this->selectedEnrollment->certificate_issued && $this->selectedEnrollment->course
                        ? route('certificates.download', $this->selectedEnrollment->course)
                        : '#'
                )
                ->openUrlInNewTab()
                ->visible(fn() => $this->selectedEnrollment && $this->selectedEnrollment->certificate_issued),
        ];
    }

    protected function getViewData(): array
    {
        if (!$this->selectedEnrollment) {
            return [];
        }

        $certificateService = app(CertificateService::class);
        $certificateData = $certificateService->generateCertificateData($this->selectedEnrollment);

        return [
            'certificateData' => $certificateData,
            'enrollment' => $this->selectedEnrollment,
            'template' => $this->selectedTemplate,
        ];
    }

    protected static string $view = 'filament.pages.certificate-preview';
}
