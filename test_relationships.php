<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Course;
use App\Models\Order;
use App\Models\Enrollment;
use App\Models\TrainingProgram;
use App\Models\Category;
use App\Models\Organization;

echo "=== MODEL RELATIONSHIP TEST ===\n\n";

// Test 1: Check if models can be instantiated
echo "1. Model Instantiation Test:\n";
$models = [
    'User' => User::class,
    'Course' => Course::class,
    'Order' => Order::class,
    'Enrollment' => Enrollment::class,
    'TrainingProgram' => TrainingProgram::class,
    'Category' => Category::class,
    'Organization' => Organization::class,
];

foreach ($models as $name => $class) {
    try {
        $instance = new $class();
        echo "   ✓ {$name} model: OK\n";
    } catch (Exception $e) {
        echo "   ✗ {$name} model: ERROR - {$e->getMessage()}\n";
    }
}

echo "\n2. Relationship Method Test:\n";

// Test User relationships
echo "   User relationships:\n";
$user = new User();
$userMethods = ['organization', 'enrollments', 'quizAttempts', 'certificates'];
foreach ($userMethods as $method) {
    echo "      - {$method}: " . (method_exists($user, $method) ? "✓" : "✗") . "\n";
}

// Test Course relationships
echo "   Course relationships:\n";
$course = new Course();
$courseMethods = ['category', 'trainingProgram', 'trainingPrograms', 'creator', 'curriculumItems', 'exams', 'enrollments', 'orderItems'];
foreach ($courseMethods as $method) {
    echo "      - {$method}: " . (method_exists($course, $method) ? "✓" : "✗") . "\n";
}

// Test Order relationships
echo "   Order relationships:\n";
$order = new Order();
$orderMethods = ['user', 'items', 'payment'];
foreach ($orderMethods as $method) {
    echo "      - {$method}: " . (method_exists($order, $method) ? "✓" : "✗") . "\n";
}

// Test Enrollment relationships
echo "   Enrollment relationships:\n";
$enrollment = new Enrollment();
$enrollmentMethods = ['user', 'course', 'trainingProgram'];
foreach ($enrollmentMethods as $method) {
    echo "      - {$method}: " . (method_exists($enrollment, $method) ? "✓" : "✗") . "\n";
}

echo "\n3. Fillable Fields Test:\n";

// Check critical fillable fields
$userFillable = (new User())->getFillable();
echo "   User fillable fields: " . (in_array('email', $userFillable) ? "✓" : "✗") . " (email found)\n";

$enrollmentFillable = (new Enrollment())->getFillable();
echo "   Enrollment fillable: " . (in_array('program_id', $enrollmentFillable) ? "✓" : "✗") . " (program_id found)\n";
echo "   Enrollment fillable: " . (in_array('training_program_id', $enrollmentFillable) ? "✓" : "✗") . " (training_program_id found)\n";

echo "\n=== TEST COMPLETED ===\n";
