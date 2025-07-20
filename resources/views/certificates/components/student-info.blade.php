<div class="student-info">
    <div class="student-name">{{ $studentName ?? 'Student Name' }}</div>
    <div class="achievement-text">
        has successfully completed the course
    </div>
    <div class="course-details">
        <strong>{{ $courseTitle ?? 'Course Title' }}</strong><br>
        @if(isset($programName))
        Program: {{ $programName }}<br>
        @endif
        Duration: {{ $duration ?? 'N/A' }}<br>
        Completion Date: {{ $completionDate ?? 'N/A' }}
    </div>
</div>
