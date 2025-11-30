<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Secret Santa Assignments - {{ $session->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #dc3545;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #dc3545;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .rules {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
            border-left: 4px solid #dc3545;
        }
        .rules h3 {
            margin-top: 0;
            color: #dc3545;
        }
        .rules ul {
            margin: 0;
            padding-left: 20px;
        }
        .assignments {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .assignment-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            width: calc(50% - 10px);
            box-sizing: border-box;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .assignment-header {
            background-color: #dc3545;
            color: white;
            padding: 10px;
            border-radius: 5px 5px 0 0;
            margin: -15px -15px 15px -15px;
            text-align: center;
        }
        .giver-name {
            font-size: 18px;
            font-weight: bold;
        }
        .assignment-body {
            text-align: center;
        }
        .recipient-name {
            font-size: 16px;
            font-weight: bold;
            color: #28a745;
            margin: 10px 0;
        }
        .secret-notice {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-top: 10px;
        }
        @media print {
            .assignment-card {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Secret Santa Assignments</h1>
        <p><strong>Session:</strong> {{ $session->name }}</p>
        <p><strong>Generated on:</strong> {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>

    <div class="rules">
        <h3>Secret Santa Rules</h3>
        <ul>
            <li>Each participant has been randomly assigned someone to buy a gift for</li>
            <li>Keep your assignment secret until the gift exchange!</li>
            <li>The assignments ensure no one gets themselves</li>
        </ul>
    </div>

    <div class="assignments">
        @foreach ($assignments as $assignment)
            <div class="assignment-card">
                <div class="assignment-header">
                    <div class="giver-name">{{ $assignment['giver']['name'] }}</div>
                </div>
                <div class="assignment-body">
                    <div>
                        <strong>Buys for:</strong>
                        <div class="recipient-name">{{ $assignment['recipient']['name'] }}</div>
                    </div>
                    <div class="secret-notice">
                        <strong>Keep this secret!</strong>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
