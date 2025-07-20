<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $certificateData['student_name'] ?? 'Student' }} - Certificate</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', serif;
            background: #fff;
            color: #333;
        }

        .certificate-container {
            width: 297mm;
            height: 210mm;
            position: relative;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border: 2px solid #gold;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .certificate-border {
            position: absolute;
            top: 10mm;
            left: 10mm;
            right: 10mm;
            bottom: 10mm;
            border: 3px solid #gold;
            border-radius: 10px;
        }

        .certificate-content {
            position: relative;
            z-index: 2;
            padding: 20mm;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .header-section {
            text-align: center;
            margin-bottom: 15mm;
        }

        .organization-logo {
            max-width: 80mm;
            max-height: 30mm;
            margin: 0 auto 5mm;
        }

        .organization-name {
            font-size: 24pt;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 2mm;
        }

        .organization-tagline {
            font-size: 12pt;
            color: #7f8c8d;
            font-style: italic;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        .certificate-title {
            font-size: 36pt;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10mm;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .certificate-subtitle {
            font-size: 18pt;
            color: #7f8c8d;
            margin-bottom: 15mm;
            font-style: italic;
        }

        .student-info {
            margin-bottom: 15mm;
        }

        .student-name {
            font-size: 28pt;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5mm;
        }

        .achievement-text {
            font-size: 16pt;
            color: #34495e;
            line-height: 1.6;
            margin-bottom: 10mm;
        }

        .course-details {
            font-size: 14pt;
            color: #7f8c8d;
            margin-bottom: 15mm;
        }

        .footer-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto;
        }

        .signature-section {
            text-align: center;
            flex: 1;
        }

        .signature-image {
            max-width: 60mm;
            max-height: 25mm;
            margin-bottom: 3mm;
        }

        .signature-name {
            font-size: 12pt;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 1mm;
        }

        .signature-title {
            font-size: 10pt;
            color: #7f8c8d;
        }

        .date-section {
            text-align: center;
            flex: 1;
        }

        .issue-date {
            font-size: 12pt;
            color: #7f8c8d;
            margin-bottom: 2mm;
        }

        .date-value {
            font-size: 14pt;
            font-weight: bold;
            color: #2c3e50;
        }

        .security-section {
            text-align: center;
            flex: 1;
        }

        .certificate-number {
            font-size: 10pt;
            color: #7f8c8d;
            margin-bottom: 2mm;
        }

        .number-value {
            font-size: 12pt;
            font-weight: bold;
            color: #2c3e50;
        }

        .qr-code {
            max-width: 25mm;
            max-height: 25mm;
            margin: 0 auto;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 48pt;
            color: rgba(0, 0, 0, 0.03);
            z-index: 1;
            pointer-events: none;
            white-space: nowrap;
        }

        .student-photo {
            position: absolute;
            top: 20mm;
            right: 20mm;
            width: 30mm;
            height: 40mm;
            border: 2px solid #gold;
            border-radius: 5px;
            overflow: hidden;
        }

        .student-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Print styles */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }

            .certificate-container {
                box-shadow: none;
                border: none;
            }
        }

        /* Responsive design */
        @media screen and (max-width: 768px) {
            .certificate-container {
                width: 100%;
                height: auto;
                min-height: 100vh;
            }

            .certificate-content {
                padding: 10mm;
            }

            .certificate-title {
                font-size: 24pt;
            }

            .student-name {
                font-size: 20pt;
            }

            .footer-section {
                flex-direction: column;
                gap: 10mm;
            }
        }
    </style>
</head>

<body>
    <div class="certificate-container">
        <!-- Watermark -->
        <div class="watermark">CERTIFICATE OF COMPLETION</div>

        <!-- Border -->
        <div class="certificate-border"></div>

        <!-- Main Content -->
        <div class="certificate-content">
            <!-- Header Section -->
            <div class="header-section">
                @if(isset($certificateData['organization_logo']) && $certificateData['organization_logo'])
                <img src="{{ $certificateData['organization_logo'] }}" alt="Organization Logo"
                    class="organization-logo">
                @endif

                <div class="organization-name">{{ $certificateData['organization_name'] ?? 'Training Academy' }}</div>
                <div class="organization-tagline">{{ $certificateData['organization_tagline'] ?? 'Excellence in
                    Education' }}</div>
            </div>

            <!-- Student Photo -->
            @if(isset($certificateData['student_photo']) && $certificateData['student_photo'])
            <div class="student-photo">
                <img src="{{ $certificateData['student_photo'] }}" alt="Student Photo">
            </div>
            @endif

            <!-- Main Content -->
            <div class="main-content">
                <div class="certificate-title">Certificate of Completion</div>
                <div class="certificate-subtitle">This is to certify that</div>

                <div class="student-info">
                    <div class="student-name">{{ $certificateData['student_name'] ?? 'Student Name' }}</div>
                    <div class="achievement-text">
                        has successfully completed the course
                    </div>
                    <div class="course-details">
                        <strong>{{ $certificateData['course_title'] ?? 'Course Title' }}</strong><br>
                        @if(isset($certificateData['program_name']))
                        Program: {{ $certificateData['program_name'] }}<br>
                        @endif
                        Duration: {{ $certificateData['duration'] ?? 'N/A' }}<br>
                        Completion Date: {{ $certificateData['completion_date'] ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <!-- Footer Section -->
            <div class="footer-section">
                <!-- Signature Section -->
                <div class="signature-section">
                    @if(isset($certificateData['signature']) && $certificateData['signature'])
                    <img src="{{ $certificateData['signature'] }}" alt="Signature" class="signature-image">
                    @endif
                    <div class="signature-name">{{ $certificateData['signature_name'] ?? 'Authorized Signature' }}</div>
                    <div class="signature-title">{{ $certificateData['signature_title'] ?? 'Course Director' }}</div>
                </div>

                <!-- Date Section -->
                <div class="date-section">
                    <div class="issue-date">Date of Issue</div>
                    <div class="date-value">{{ $certificateData['issue_date'] ?? date('F d, Y') }}</div>
                </div>

                <!-- Security Section -->
                <div class="security-section">
                    <div class="certificate-number">Certificate Number</div>
                    <div class="number-value">{{ $certificateData['certificate_number'] ?? 'CERT-2024-001' }}</div>
                    @if(isset($certificateData['qr_code']) && $certificateData['qr_code'])
                    <img src="{{ $certificateData['qr_code'] }}" alt="QR Code" class="qr-code">
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>

</html>
