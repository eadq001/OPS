<?php
include 'db_connection.php';
check_admin();

try {
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

    // Get statistics
    $statsQuery = "SELECT COUNT(*) as total_complaints, SUM(CASE WHEN status IN ('Submitted', 'Under Review', 'In Progress') THEN 1 ELSE 0 END) as pending, SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) as resolved FROM complaints WHERE DATE(created_at) BETWEEN ? AND ?";
    
    $stmt = $conn->prepare($statsQuery);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $statsResult = $stmt->get_result()->fetch_assoc();

    // Get feedback count
    $feedbackQuery = "SELECT COUNT(*) as total_feedback FROM feedback WHERE DATE(created_at) BETWEEN ? AND ?";
    
    $stmt = $conn->prepare($feedbackQuery);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $feedbackResult = $stmt->get_result()->fetch_assoc();

    // Get complaints by status
    $statusQuery = "SELECT status, COUNT(*) as count FROM complaints WHERE DATE(created_at) BETWEEN ? AND ? GROUP BY status";
    
    $stmt = $conn->prepare($statusQuery);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $statusResult = $stmt->get_result();
    $statusBreakdown = $statusResult->fetch_all(MYSQLI_ASSOC);

    // Get complaints by type
    $typeQuery = "SELECT complaint_type as type, COUNT(*) as count FROM complaints WHERE DATE(created_at) BETWEEN ? AND ? GROUP BY complaint_type";
    
    $stmt = $conn->prepare($typeQuery);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $typeResult = $stmt->get_result();
    $typeBreakdown = $typeResult->fetch_all(MYSQLI_ASSOC);

    // Generate HTML for PDF
    $html = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
            h1 { color: #1f2937; border-bottom: 3px solid #3b82f6; padding-bottom: 10px; }
            h2 { color: #374151; margin-top: 20px; border-bottom: 1px solid #d1d5db; padding-bottom: 5px; }
            .stats { display: flex; gap: 20px; margin: 20px 0; }
            .stat-card { 
                flex: 1; 
                padding: 15px; 
                border: 1px solid #e5e7eb; 
                border-radius: 8px; 
                background: #f9fafb;
            }
            .stat-label { font-size: 12px; color: #6b7280; text-transform: uppercase; }
            .stat-value { font-size: 28px; font-weight: bold; color: #1f2937; margin: 5px 0; }
            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin: 15px 0;
            }
            th { 
                background: #3b82f6; 
                color: white; 
                padding: 10px; 
                text-align: left; 
            }
            td { 
                padding: 10px; 
                border-bottom: 1px solid #e5e7eb;
            }
            tr:nth-child(even) { background: #f9fafb; }
            .footer { 
                margin-top: 30px; 
                padding-top: 20px; 
                border-top: 1px solid #e5e7eb; 
                font-size: 12px; 
                color: #6b7280;
            }
        </style>
    </head>
    <body>
        <h1>Complaints Management System - Report</h1>
        <p><strong>Report Period:</strong> " . date('M d, Y', strtotime($start_date)) . " to " . date('M d, Y', strtotime($end_date)) . "</p>
        <p><strong>Generated on:</strong> " . date('M d, Y \a\t h:i A') . "</p>
        
        <h2>Statistics Summary</h2>
        <div class='stats'>
            <div class='stat-card'>
                <div class='stat-label'>Total Complaints</div>
                <div class='stat-value'>" . ($statsResult['total_complaints'] ?? 0) . "</div>
            </div>
            <div class='stat-card'>
                <div class='stat-label'>Pending Review</div>
                <div class='stat-value'>" . ($statsResult['pending'] ?? 0) . "</div>
            </div>
            <div class='stat-card'>
                <div class='stat-label'>Resolved</div>
                <div class='stat-value'>" . ($statsResult['resolved'] ?? 0) . "</div>
            </div>
            <div class='stat-card'>
                <div class='stat-label'>Total Feedback</div>
                <div class='stat-value'>" . ($feedbackResult['total_feedback'] ?? 0) . "</div>
            </div>
        </div>

        <h2>Complaints by Status</h2>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>";
    
    foreach ($statusBreakdown as $item) {
        $html .= "<tr><td>" . htmlspecialchars($item['status']) . "</td><td>" . $item['count'] . "</td></tr>";
    }
    
    $html .= "
            </tbody>
        </table>

        <h2>Complaints by Type</h2>
        <table>
            <thead>
                <tr>
                    <th>Complaint Type</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>";
    
    foreach ($typeBreakdown as $item) {
        $html .= "<tr><td>" . htmlspecialchars($item['type']) . "</td><td>" . $item['count'] . "</td></tr>";
    }
    
    $html .= "
            </tbody>
        </table>

        <div class='footer'>
            <p>This report was automatically generated from the Complaints Management System.</p>
        </div>
    </body>
    </html>";

    // Output as PDF using mPDF
    require_once 'vendor/autoload.php';
    
    $mpdf = new \Mpdf\Mpdf([
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 20,
        'margin_bottom' => 20
    ]);
    
    $mpdf->WriteHTML($html);
    $mpdf->Output('complaint-report-' . date('Y-m-d') . '.pdf', 'D');

} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
