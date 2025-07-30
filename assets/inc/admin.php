<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submissions - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .submission {
            border: 1px solid #ddd;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
            background: #fafafa;
        }
        .submission-header {
            background: #4a90e2;
            color: white;
            margin: -15px -15px 15px -15px;
            padding: 10px 15px;
            border-radius: 5px 5px 0 0;
            font-weight: bold;
        }
        .field {
            margin-bottom: 10px;
        }
        .field strong {
            display: inline-block;
            width: 100px;
            color: #555;
        }
        .message-content {
            background: white;
            padding: 10px;
            border-left: 4px solid #4a90e2;
            margin-top: 10px;
            white-space: pre-wrap;
        }
        .no-submissions {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 40px;
        }
        .stats {
            background: #e8f4fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .refresh-btn {
            background: #4a90e2;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .refresh-btn:hover {
            background: #357abd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 Contact Form Submissions</h1>
        
        <button class="refresh-btn" onclick="location.reload()">🔄 Refresh</button>
        
        <?php
        $json_file = __DIR__ . '/contact_submissions.json';
        
        if (file_exists($json_file)) {
            $json_content = file_get_contents($json_file);
            $submissions = json_decode($json_content, true);
            
            if (!empty($submissions)) {
                $total_submissions = count($submissions);
                echo "<div class='stats'>";
                echo "<strong>Total Submissions: {$total_submissions}</strong>";
                echo "</div>";
                
                // Sort by timestamp (newest first)
                usort($submissions, function($a, $b) {
                    return strtotime($b['timestamp']) - strtotime($a['timestamp']);
                });
                
                foreach ($submissions as $index => $submission) {
                    echo "<div class='submission'>";
                    echo "<div class='submission-header'>";
                    echo "Submission #" . ($total_submissions - $index) . " - " . htmlspecialchars($submission['name']);
                    echo "</div>";
                    
                    echo "<div class='field'><strong>Date:</strong> " . htmlspecialchars($submission['timestamp']) . "</div>";
                    echo "<div class='field'><strong>Name:</strong> " . htmlspecialchars($submission['name']) . "</div>";
                    echo "<div class='field'><strong>Email:</strong> <a href='mailto:" . htmlspecialchars($submission['email']) . "'>" . htmlspecialchars($submission['email']) . "</a></div>";
                    
                    if (!empty($submission['phone'])) {
                        echo "<div class='field'><strong>Phone:</strong> " . htmlspecialchars($submission['phone']) . "</div>";
                    }
                    
                    echo "<div class='field'><strong>Subject:</strong> " . htmlspecialchars($submission['subject']) . "</div>";
                    
                    if (!empty($submission['ip_address'])) {
                        echo "<div class='field'><strong>IP:</strong> " . htmlspecialchars($submission['ip_address']) . "</div>";
                    }
                    
                    echo "<div class='field'><strong>Message:</strong></div>";
                    echo "<div class='message-content'>" . nl2br(htmlspecialchars($submission['message'])) . "</div>";
                    
                    echo "</div>";
                }
            } else {
                echo "<div class='no-submissions'>No submissions found.</div>";
            }
        } else {
            echo "<div class='no-submissions'>No submissions file found. Submissions will appear here once someone submits the contact form.</div>";
        }
        ?>
        
        <div style="margin-top: 30px; text-align: center; color: #666; font-size: 12px;">
            📁 Data is stored in: <?php echo htmlspecialchars($json_file); ?>
        </div>
    </div>
</body>
</html>
