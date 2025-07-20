<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Course;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    protected $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Display certificate for an enrollment
     */
    public function show(Request $request, Course $course)
    {
        // Check if user is enrolled and has completed the course
        $enrollment = Auth::user()->enrollments()
            ->where('course_id', $course->id)
            ->where('status', Enrollment::STATUS_COMPLETED)
            ->first();

        if (!$enrollment) {
            return redirect()->route('courses.learn', $course)
                ->with('error', 'You must complete the course to view your certificate.');
        }

        // Issue certificate if not already issued
        if (!$enrollment->certificate_issued) {
            $this->certificateService->issueCertificate($enrollment);
            $enrollment->refresh();
        }

        $certificateData = $this->certificateService->generateCertificateData($enrollment);

        return view('certificates.show', [
            'certificateData' => $certificateData,
            'enrollment' => $enrollment,
            'course' => $course
        ]);
    }

    /**
     * Download certificate as PDF
     */
    public function download(Request $request, Course $course)
    {
        // Check if user is enrolled and has completed the course
        $enrollment = Auth::user()->enrollments()
            ->where('course_id', $course->id)
            ->where('status', Enrollment::STATUS_COMPLETED)
            ->first();

        if (!$enrollment) {
            return redirect()->route('courses.learn', $course)
                ->with('error', 'You must complete the course to download your certificate.');
        }

        // Issue certificate if not already issued
        if (!$enrollment->certificate_issued) {
            $this->certificateService->issueCertificate($enrollment);
            $enrollment->refresh();
        }

        $certificateData = $this->certificateService->generateCertificateData($enrollment);

        // For now, return HTML view (you can integrate PDF generation later)
        return view('certificates.templates.classic', [
            'certificateData' => $certificateData
        ]);
    }

    /**
     * Verify certificate by number
     */
    public function verify(Request $request, string $certificateNumber)
    {
        $certificateInfo = $this->certificateService->verifyCertificate($certificateNumber);

        if (!$certificateInfo) {
            return view('certificates.verify', [
                'certificateNumber' => $certificateNumber,
                'valid' => false,
                'message' => 'Certificate not found or invalid.'
            ]);
        }

        return view('certificates.verify', [
            'certificateNumber' => $certificateNumber,
            'certificateInfo' => $certificateInfo,
            'valid' => true
        ]);
    }

    /**
     * Generate certificate preview (admin only)
     */
    public function preview(Request $request, Course $course)
    {
        // Check if user is admin
        if (!Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $enrollment = $course->enrollments()
            ->where('status', Enrollment::STATUS_COMPLETED)
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'No completed enrollments found for this course.');
        }

        $certificateData = $this->certificateService->generateCertificateData($enrollment);

        return view('certificates.templates.classic', [
            'certificateData' => $certificateData
        ]);
    }
}
