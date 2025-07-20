@extends('certificates.layouts.certificate')

@section('styles')
<style>
    .certificate-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 20px;
    }

    .certificate-border {
        border: none;
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }

    .certificate-title {
        font-family: 'Arial', sans-serif;
        font-size: 32pt;
        color: #667eea;
        text-transform: uppercase;
        letter-spacing: 3px;
        font-weight: 300;
    }

    .student-name {
        font-family: 'Arial', sans-serif;
        font-size: 24pt;
        color: #2c3e50;
        font-weight: 600;
    }

    .organization-name {
        font-family: 'Arial', sans-serif;
        font-size: 20pt;
        color: #667eea;
        font-weight: 600;
    }

    .achievement-text {
        color: #7f8c8d;
        font-weight: 300;
    }

    .course-details {
        background: rgba(102, 126, 234, 0.1);
        padding: 15px;
        border-radius: 10px;
        border-left: 4px solid #667eea;
    }
</style>
@endsection
