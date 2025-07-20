@extends('certificates.layouts.certificate')

@section('styles')
<style>
    .certificate-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 3px solid #gold;
    }

    .certificate-border {
        border: 3px solid #gold;
        border-radius: 10px;
    }

    .certificate-title {
        font-family: 'Times New Roman', serif;
        font-size: 36pt;
        color: #2c3e50;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .student-name {
        font-family: 'Times New Roman', serif;
        font-size: 28pt;
        color: #2c3e50;
    }

    .organization-name {
        font-family: 'Times New Roman', serif;
        font-size: 24pt;
        color: #2c3e50;
    }
</style>
@endsection
