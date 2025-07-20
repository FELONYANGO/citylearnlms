<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainingProgramController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MpesaCallbackController;
use App\Http\Controllers\CertificateController;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/programs/{program}', [TrainingProgramController::class, 'show'])->name('programs.show');
Route::get('/programs/{program}/level/{level}', [TrainingProgramController::class, 'showLevel'])->name('programs.level');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

// Training Program enrollment routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/programs/{program}/enroll', [TrainingProgramController::class, 'enroll'])->name('programs.enroll');
    Route::get('/programs/{program}/learn', [TrainingProgramController::class, 'learn'])->name('programs.learn');
});

// Course routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::get('/courses/{course}/learn', [CourseController::class, 'learn'])->name('courses.learn');
    Route::get('/courses/{course}/quiz/{quiz}', [CourseController::class, 'showQuiz'])->name('courses.quiz');
    Route::post('/courses/{course}/quiz/{quiz}/submit', [CourseController::class, 'submitQuiz'])->name('courses.quiz.submit');
    Route::post('/courses/{course}/curriculum-item/{curriculumItem}/complete', [CourseController::class, 'markCurriculumItemComplete'])->name('courses.curriculum-item.complete');
    Route::post('/courses/{course}/track-progress', [CourseController::class, 'trackProgress'])->name('courses.track-progress');
    Route::get('/courses/{course}/exam/{exam}', [CourseController::class, 'showExam'])->name('courses.exam');
    Route::post('/courses/{course}/exam/{exam}/submit', [CourseController::class, 'submitExam'])->name('courses.exam.submit');
    Route::get('/courses/{course}/certificate/download', [CourseController::class, 'downloadCertificate'])
        ->name('courses.certificate.download');

    // Certificate Routes
    Route::get('/certificates/{course}', [CertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{course}/download', [CertificateController::class, 'download'])->name('certificates.download');
    Route::get('/certificate/verify/{certificateNumber}', [CertificateController::class, 'verify'])->name('certificate.verify');
    Route::get('/certificates/{course}/preview', [CertificateController::class, 'preview'])->name('certificates.preview');
});

// Checkout Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/courses/{course}/checkout', [CheckoutController::class, 'showCourseCheckout'])->name('courses.checkout');
    Route::get('/programs/{program}/checkout', [CheckoutController::class, 'showProgramCheckout'])->name('programs.checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
});

// Payment Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/payments/{order}/process', [PaymentController::class, 'process'])->name('payments.process');
    Route::get('/payments/{order}/processing', [PaymentController::class, 'processing'])->name('payments.processing');
    Route::post('/payments/{order}/initiate', [PaymentController::class, 'initiate'])->name('payments.initiate');
    Route::get('/payments/{order}/success', [PaymentController::class, 'success'])->name('payments.success');
    Route::get('/payments/{order}/cancel', [PaymentController::class, 'cancel'])->name('payments.cancel');
});

// M-Pesa Callback Route (no auth middleware needed)
Route::post('mpesa/callback', [MpesaCallbackController::class, 'handleCallback'])
    ->name('mpesa.callback');

// Dashboard Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // This route will redirect based on user role
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('filament.admin.pages.dashboard');
        }
        return redirect()->route('student.dashboard');
    })->name('dashboard');

    // Student Dashboard Routes
    Route::middleware(['role:student'])->group(function () {
        Route::get('/student/dashboard', function () {
            return view('student.dashboard');
        })->name('student.dashboard');
    });

    // Trainer Dashboard Routes
    Route::middleware(['role:trainer'])->group(function () {
        Route::get('/trainer/dashboard', function () {
            return view('trainer.dashboard');
        })->name('trainer.dashboard');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// API Routes for Payment Status
Route::middleware(['auth', 'verified'])->get('/api/orders/{order}/status', function (Order $order) {
    return response()->json([
        'status' => $order->status,
        'payment_status' => $order->payments()->latest()->first()?->status ?? 'pending'
    ]);
});

require __DIR__ . '/auth.php';
