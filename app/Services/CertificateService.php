<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use App\Models\Exam;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CertificateService
{
    /**
     * Generate certificate data for an enrollment
     */
    public function generateCertificateData(Enrollment $enrollment): array
    {
        $user = $enrollment->user;
        $course = $enrollment->course;

        // Generate unique certificate number
        $certificateNumber = $this->generateCertificateNumber($enrollment);

        // Generate QR code (requires QR code package)
        $qrCode = null; // $this->generateQRCode($certificateNumber, $enrollment);

        // Generate barcode (requires barcode package)
        $barcode = null; // $this->generateBarcode($certificateNumber);

        return [
            'student_name' => $user->name,
            'student_photo' => $user->profile_photo_url ?? null,
            'course_title' => $course->title ?? 'Course Title',
            'program_name' => $course->trainingProgram->title ?? null,
            'organization_name' => config('app.name', 'Training Academy'),
            'organization_logo' => $this->getOrganizationLogo(),
            'organization_tagline' => config('app.tagline', 'Excellence in Education'),
            'signature' => $this->getSignature(),
            'signature_name' => config('certificate.signature_name', 'Authorized Signature'),
            'signature_title' => config('certificate.signature_title', 'Course Director'),
            'qr_code' => $qrCode,
            'barcode' => $barcode,
            'certificate_number' => $certificateNumber,
            'issue_date' => now()->format('F d, Y'),
            'completion_date' => $enrollment->completed_at ? $enrollment->completed_at->format('F d, Y') : now()->format('F d, Y'),
            'duration' => $course->duration_text ?? 'N/A',
            'verification_url' => route('certificate.verify', $certificateNumber),
        ];
    }

    /**
     * Generate unique certificate number
     */
    public function generateCertificateNumber(Enrollment $enrollment): string
    {
        $prefix = 'CERT';
        $year = date('Y');
        $courseId = str_pad($enrollment->course_id, 3, '0', STR_PAD_LEFT);
        $enrollmentId = str_pad($enrollment->id, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$year}-{$courseId}-{$enrollmentId}";
    }

    /**
     * Generate QR code for certificate verification
     * Requires: composer require simplesoftwareio/simple-qrcode
     */
    public function generateQRCode(string $certificateNumber, Enrollment $enrollment): string
    {
        // TODO: Implement when QR code package is installed
        return '';

        /*
        $verificationUrl = route('certificate.verify', $certificateNumber);

        $qrCode = QrCode::format('png')
            ->size(200)
            ->margin(0)
            ->generate($verificationUrl);

        // Save QR code to storage
        $filename = "qr_codes/{$certificateNumber}.png";
        Storage::disk('public')->put($filename, $qrCode);

        return Storage::disk('public')->url($filename);
        */
    }

    /**
     * Generate barcode for certificate
     * Requires: composer require milon/barcode
     */
    public function generateBarcode(string $certificateNumber): string
    {
        // TODO: Implement when barcode package is installed
        return '';

        /*
        $barcode = DNS1D::getBarcodePNG($certificateNumber, 'C128', 3, 50);

        // Save barcode to storage
        $filename = "barcodes/{$certificateNumber}.png";
        Storage::disk('public')->put($filename, base64_decode($barcode));

        return Storage::disk('public')->url($filename);
        */
    }

    /**
     * Get organization logo
     */
    public function getOrganizationLogo(): ?string
    {
        $logoPath = config('certificate.organization_logo');

        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            return asset('storage/' . $logoPath);
        }

        return null;
    }

    /**
     * Get signature image
     */
    public function getSignature(): ?string
    {
        $signaturePath = config('certificate.signature_image');

        if ($signaturePath && Storage::disk('public')->exists($signaturePath)) {
            return asset('storage/' . $signaturePath);
        }

        return null;
    }

    /**
     * Render certificate template
     */
    public function renderCertificate(string $template, array $data): string
    {
        $certificateData = $this->generateCertificateData($data['enrollment']);

        return view("certificates.templates.{$template}", [
            'certificateData' => $certificateData
        ])->render();
    }

    /**
     * Generate PDF from certificate
     */
    public function generatePDF(string $template, array $data): string
    {
        $html = $this->renderCertificate($template, $data);

        // You can use packages like DomPDF or Snappy to convert HTML to PDF
        // For now, we'll return the HTML
        return $html;
    }

    /**
     * Verify certificate by number
     */
    public function verifyCertificate(string $certificateNumber): ?array
    {
        // Extract enrollment ID from certificate number
        $parts = explode('-', $certificateNumber);

        if (count($parts) !== 4) {
            return null;
        }

        $enrollmentId = (int) $parts[3];

        $enrollment = Enrollment::with(['user', 'course'])
            ->where('id', $enrollmentId)
            ->where('status', Enrollment::STATUS_COMPLETED)
            ->first();

        if (!$enrollment) {
            return null;
        }

        return [
            'certificate_number' => $certificateNumber,
            'student_name' => $enrollment->user->name,
            'course_title' => $enrollment->course->title,
            'completion_date' => $enrollment->completed_at->format('F d, Y'),
            'issue_date' => $enrollment->certificate_issued_at->format('F d, Y'),
            'status' => 'valid',
            'enrollment' => $enrollment
        ];
    }

    /**
     * Issue certificate for enrollment
     */
    public function issueCertificate(Enrollment $enrollment): bool
    {
        try {
            $certificateNumber = $this->generateCertificateNumber($enrollment);

            $enrollment->update([
                'certificate_issued' => true,
                'certificate_issued_at' => now(),
                'certificate_number' => $certificateNumber
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to issue certificate', [
                'enrollment_id' => $enrollment->id,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }
}
