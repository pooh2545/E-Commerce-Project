<?php
/*
  Cron Job Script สำหรับยกเลิกออเดอร์ที่หมดเวลาชำระเงิน
  
  วิธีการใช้งาน:
  1. ตั้งค่า Cron Job ให้รันทุก 10-15 นาที
  2. crontab -e
  3. เพิ่มบรรทัด:   /path/to/order_auto_expire.php
 
  การตั้งค่า Cron Job ตัวอย่าง:
  /usr/bin/php /var/www/html/your-project/order_auto_expire.php >> /var/www/html/your-project/logs/cron.log 2>&1
 */

require_once 'config.php';
require_once 'OrderController.php';

// กำหนดค่าการรัน
$logFile = 'logs/auto_expire_orders.log';
$lockFile = 'locks/auto_expire.lock';

// สร้าง directory ถ้าไม่มี
$logDir = dirname($logFile);
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

$lockDir = dirname($lockFile);
if (!is_dir($lockDir)) {
    mkdir($lockDir, 0755, true);
}

// ตรวจสอบว่ามี process อื่นกำลังรันอยู่หรือไม่
if (file_exists($lockFile)) {
    $lockTime = filemtime($lockFile);
    // ถ้า lock file มีอายุเกิน 5 นาที ให้ลบทิ้ง (กันกรณี process ค้าง)
    if (time() - $lockTime > 300) {
        unlink($lockFile);
        logMessage("ลบ lock file ที่ค้างจาก process ก่อนหน้า");
    } else {
        logMessage("Process กำลังทำงานอยู่ ข้าม (PID: " . file_get_contents($lockFile) . ")");
        exit;
    }
}

// สร้าง lock file พร้อม PID
file_put_contents($lockFile, getmypid() . ' - ' . date('Y-m-d H:i:s'));

try {
    logMessage("=== เริ่มต้น Auto Expire Orders Job ===");
    
    $controller = new OrderController($pdo);
    
    // 1. ยกเลิกออเดอร์ที่หมดเวลา
    logMessage("กำลังตรวจสอบออเดอร์ที่หมดเวลาชำระเงิน...");
    $expireResult = $controller->autoExpireOrders();
    
    if ($expireResult['success']) {
        if ($expireResult['expired_count'] > 0) {
            logMessage("ยกเลิกออเดอร์อัตโนมัติจำนวน " . $expireResult['expired_count'] . " รายการ");
            
            // ส่งการแจ้งเตือนให้ Admin
            sendNotificationToAdmin($expireResult);
            
            // บันทึก log รายละเอียด
            logExpiredOrdersDetails($controller, $expireResult['expired_count']);
            
        } else {
            logMessage("ไม่มีออเดอร์ที่หมดเวลาในขณะนี้");
        }
    } else {
        logMessage("ERROR ในการยกเลิกออเดอร์อัตโนมัติ: " . $expireResult['message']);
        sendErrorNotificationToAdmin("Auto Expire Error", $expireResult['message']);
    }
    
    // 2. ตรวจสอบออเดอร์ที่ใกล้หมดเวลา (สำหรับส่งการแจ้งเตือน)
    logMessage("กำลังตรวจสอบออเดอร์ที่ใกล้หมดเวลา...");
    
    // แจ้งเตือน 2 ชั่วโมงก่อนหมดเวลา
    $nearExpiryOrders2h = $controller->getOrdersNearExpiry(2);
    
    // แจ้งเตือน 1 ชั่วโมงก่อนหมดเวลา  
    $nearExpiryOrders1h = $controller->getOrdersNearExpiry(1);
    
    // แจ้งเตือน 30 นาทีก่อนหมดเวลา
    $nearExpiryOrders30m = $controller->getOrdersNearExpiry(0.5);
    
    if (!empty($nearExpiryOrders2h)) {
        logMessage("พบออเดอร์ใกล้หมดเวลา (2 ชั่วโมง) จำนวน " . count($nearExpiryOrders2h) . " รายการ");
        
        foreach ($nearExpiryOrders2h as $order) {
            sendPaymentReminderToCustomer($order, '2 ชั่วโมง');
        }
    }
    
    if (!empty($nearExpiryOrders1h)) {
        logMessage("พบออเดอร์ใกล้หมดเวลา (1 ชั่วโมง) จำนวน " . count($nearExpiryOrders1h) . " รายการ");
        
        foreach ($nearExpiryOrders1h as $order) {
            sendPaymentReminderToCustomer($order, '1 ชั่วโมง');
        }
    }
    
    if (!empty($nearExpiryOrders30m)) {
        logMessage("พบออเดอร์ใกล้หมดเวลา (30 นาที) จำนวน " . count($nearExpiryOrders30m) . " รายการ");
        
        foreach ($nearExpiryOrders30m as $order) {
            sendPaymentReminderToCustomer($order, '30 นาที', true); // urgent = true
        }
    }
    
    if (empty($nearExpiryOrders2h) && empty($nearExpiryOrders1h) && empty($nearExpiryOrders30m)) {
        logMessage("ไม่มีออเดอร์ที่ใกล้หมดเวลาในขณะนี้");
    }
    
    // 3. สรุปสถิติรายวัน (รันเฉพาะเที่ยงคืน)
    if (date('H:i') == '00:00') {
        generateDailySummary($controller);
    }
    
    logMessage("=== Auto Expire Orders Job เสร็จสิ้น ===");
    
} catch (Exception $e) {
    $errorMessage = "FATAL ERROR: " . $e->getMessage() . "\nStack trace: " . $e->getTraceAsString();
    logMessage($errorMessage);
    sendErrorNotificationToAdmin("Auto Expire Script Error", $errorMessage);
    
} finally {
    // ลบ lock file
    if (file_exists($lockFile)) {
        unlink($lockFile);
    }
}

/**
 * บันทึก log
 */
function logMessage($message)
{
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}" . PHP_EOL;
    
    // บันทึกลงไฟล์
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    
    // แสดงผลใน console (สำหรับ debug)
    echo $logMessage;
}

/**
 * บันทึกรายละเอียดออเดอร์ที่หมดเวลา
 */
function logExpiredOrdersDetails($controller, $expiredCount)
{
    if ($expiredCount <= 0) return;
    
    try {
        // ดึงข้อมูลออเดอร์ที่เพิ่งยกเลิก (ล่าสุด 10 นาที)
        global $pdo;
        $sql = "SELECT o.order_id, o.order_number, o.total_amount, o.payment_expire_at,
                       CONCAT(m.fname, ' ', m.lname) as customer_name, m.email, o.member_phone
                FROM orders o
                LEFT JOIN member m ON o.member_id = m.member_id
                WHERE o.order_status_id = 5 
                AND o.update_at >= DATE_SUB(NOW(), INTERVAL 10 MINUTE)
                ORDER BY o.update_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $cancelledOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        logMessage("รายละเอียดออเดอร์ที่ยกเลิก:");
        foreach ($cancelledOrders as $order) {
            logMessage(sprintf(
                "- ออเดอร์: %s | ลูกค้า: %s | จำนวน: %.2f บาท | หมดเวลา: %s",
                $order['order_number'],
                $order['customer_name'] ?: 'ไม่ระบุ',
                $order['total_amount'],
                $order['payment_expire_at']
            ));
        }
        
    } catch (Exception $e) {
        logMessage("ERROR ในการดึงรายละเอียดออเดอร์: " . $e->getMessage());
    }
}

/**
 * สร้างสรุปรายวัน
 */
function generateDailySummary($controller)
{
    try {
        logMessage("กำลังสร้างสรุปรายวัน...");
        
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $report = $controller->getSalesReport($yesterday, $yesterday);
        
        if ($report) {
            $summary = "สรุปรายวันวันที่ {$yesterday}:\n";
            $summary .= "- ออเดอร์ทั้งหมด: " . $report['total_orders'] . " รายการ\n";
            $summary .= "- ยอดขายรวม: " . number_format($report['total_revenue'], 2) . " บาท\n";
            $summary .= "- ออเดอร์เฉลี่ย: " . number_format($report['average_order_value'], 2) . " บาท\n";
            $summary .= "- ลูกค้าที่ซื้อ: " . $report['unique_customers'] . " คน";
            
            logMessage($summary);
            
            // ส่งรายงานให้ Admin (optional)
            sendDailySummaryToAdmin($summary, $yesterday);
        }
        
    } catch (Exception $e) {
        logMessage("ERROR ในการสร้างสรุปรายวัน: " . $e->getMessage());
    }
}

/**
 * ส่งการแจ้งเตือนให้ Admin
 */
function sendNotificationToAdmin($result)
{
    if ($result['expired_count'] <= 0) return;
    
    try {
        $subject = "แจ้งเตือน: ยกเลิกออเดอร์อัตโนมัติ - " . date('Y-m-d H:i:s');
        $message = "ระบบได้ทำการยกเลิกออเดอร์อัตโนมัติแล้ว\n\n";
        $message .= "จำนวนออเดอร์ที่ยกเลิก: " . $result['expired_count'] . " รายการ\n";
        $message .= "เวลาที่ดำเนินการ: " . date('Y-m-d H:i:s') . "\n\n";
        $message .= "กรุณาตรวจสอบระบบเพื่อดูรายละเอียดเพิ่มเติม";
        
        // ส่ง Email (ถ้าต้องการ)
        // sendEmail('admin@example.com', $subject, $message);
        
        // ส่ง LINE Notify (ถ้าต้องการ)
        // sendLineNotify($message);
        
        // ส่ง Webhook (ถ้าต้องการ)
        // sendWebhookNotification($subject, $message);
        
        logMessage("ส่งการแจ้งเตือนให้ Admin สำเร็จ (จำนวนออเดอร์: " . $result['expired_count'] . ")");
        
    } catch (Exception $e) {
        logMessage("ERROR ในการส่งการแจ้งเตือนให้ Admin: " . $e->getMessage());
    }
}

/**
 * ส่งการแจ้งเตือน Error ให้ Admin
 */
function sendErrorNotificationToAdmin($subject, $errorMessage)
{
    try {
        $fullSubject = "ERROR: " . $subject . " - " . date('Y-m-d H:i:s');
        $message = "เกิดข้อผิดพลาดในระบบ Auto Expire Orders\n\n";
        $message .= "รายละเอียด: " . $errorMessage . "\n";
        $message .= "เวลาที่เกิดข้อผิดพลาด: " . date('Y-m-d H:i:s') . "\n\n";
        $message .= "กรุณาตรวจสอบและแก้ไขด่วน";
        
        // ส่งการแจ้งเตือนแบบ urgent
        // sendEmail('admin@example.com', $fullSubject, $message, true);
        // sendLineNotify("🚨 " . $message);
        
        logMessage("ส่งการแจ้งเตือน Error ให้ Admin");
        
    } catch (Exception $e) {
        logMessage("ERROR ในการส่งการแจ้งเตือน Error: " . $e->getMessage());
    }
}

/**
 * ส่งการแจ้งเตือนให้ลูกค้า
 */
function sendPaymentReminderToCustomer($order, $timeLeft, $urgent = false)
{
    try {
        $customerName = $order['fname'] . ' ' . $order['lname'];
        $urgentPrefix = $urgent ? "🔔 ด่วน! " : "";
        
        $message = $urgentPrefix . "เรียนคุณ " . $customerName . "\n\n";
        $message .= "ออเดอร์ " . $order['order_number'] . " จะหมดเวลาชำระเงินในอีก " . $timeLeft . "\n";
        $message .= "จำนวนเงิน: " . number_format($order['total_amount'], 2) . " บาท\n";
        $message .= "กรุณาชำระเงินเพื่อให้ออเดอร์ของท่านดำเนินการต่อ\n\n";
        
        if ($urgent) {
            $message .= "⚠️ หากไม่ชำระภายในเวลาที่กำหนด ออเดอร์จะถูกยกเลิกอัตโนมัติ";
        }
        
        // ส่ง SMS (ถ้ามีบริการ)
        if (!empty($order['member_phone'])) {
            // sendSMS($order['member_phone'], $message);
        }
        
        // ส่ง Email (ถ้ามี email)
        if (!empty($order['email'])) {
            $subject = $urgentPrefix . "แจ้งเตือนการหมดเวลาชำระเงิน - " . $order['order_number'];
            // sendEmail($order['email'], $subject, $message);
        }
        
        // บันทึก log
        logMessage("ส่งการแจ้งเตือนให้ลูกค้า: " . $order['order_number'] . " (เหลือเวลา: " . $timeLeft . ")");
        
        // บันทึกการส่งการแจ้งเตือนลงฐานข้อมูล (optional)
        recordNotificationSent($order['order_id'], 'payment_reminder', $timeLeft, $urgent);
        
    } catch (Exception $e) {
        logMessage("ERROR ในการส่งการแจ้งเตือนให้ลูกค้า " . $order['order_number'] . ": " . $e->getMessage());
    }
}

/**
 * ส่งสรุปรายวันให้ Admin
 */
function sendDailySummaryToAdmin($summary, $date)
{
    try {
        $subject = "สรุปรายวัน - " . $date;
        
        // ส่ง Email
        // sendEmail('admin@example.com', $subject, $summary);
        
        logMessage("ส่งสรุปรายวันให้ Admin สำเร็จ");
        
    } catch (Exception $e) {
        logMessage("ERROR ในการส่งสรุปรายวัน: " . $e->getMessage());
    }
}

/**
 * บันทึกการส่งการแจ้งเตือน
 */
function recordNotificationSent($orderID, $type, $timeLeft = null, $urgent = false)
{
    try {
        global $pdo;
        
        // สร้างตาราง notifications ถ้ายังไม่มี
        $createTableSQL = "CREATE TABLE IF NOT EXISTS order_notifications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            notification_type VARCHAR(50) NOT NULL,
            time_left VARCHAR(20),
            is_urgent BOOLEAN DEFAULT FALSE,
            sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_order_id (order_id),
            INDEX idx_sent_at (sent_at)
        )";
        
        $pdo->exec($createTableSQL);
        
        // บันทึกการแจ้งเตือน
        $sql = "INSERT INTO order_notifications (order_id, notification_type, time_left, is_urgent) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$orderID, $type, $timeLeft, $urgent]);
        
    } catch (Exception $e) {
        logMessage("ERROR ในการบันทึกการแจ้งเตือน: " . $e->getMessage());
    }
}

/**
 * ฟังก์ชันสำหรับส่ง Email (ตัวอย่าง)
 */
function sendEmail($to, $subject, $message, $urgent = false)
{
    // ใช้ PHPMailer หรือ mail() function
    /*
    $headers = "From: noreply@yoursite.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    if ($urgent) {
        $headers .= "Importance: High\r\n";
        $headers .= "X-Priority: 1\r\n";
    }
    
    return mail($to, $subject, $message, $headers);
    */
    
    return true; // placeholder
}

/**
 * ฟังก์ชันสำหรับส่ง SMS (ตัวอย่าง)
 */
function sendSMS($phoneNumber, $message)
{
    // ใช้บริการ SMS API เช่น Twilio, SMS.to, หรือบริการอื่นๆ
    /*
    $apiUrl = "https://api.sms-service.com/send";
    $data = [
        'to' => $phoneNumber,
        'message' => $message,
        'api_key' => 'your_api_key'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
    */
    
    return true; // placeholder
}

/**
 * ฟังก์ชันสำหรับส่ง LINE Notify (ตัวอย่าง)
 */
function sendLineNotify($message)
{
    // ใช้ LINE Notify API
    /*
    $token = 'YOUR_LINE_NOTIFY_TOKEN';
    $apiUrl = 'https://notify-api.line.me/api/notify';
    
    $data = ['message' => $message];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/x-www-form-urlencoded'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
    */
    
    return true; // placeholder
}

/**
 * ฟังก์ชันสำหรับส่ง Webhook (ตัวอย่าง)
 */
function sendWebhookNotification($subject, $message)
{
    // ส่งไปยัง Slack, Discord, หรือ webhook อื่นๆ
    /*
    $webhookUrl = 'https://hooks.slack.com/services/YOUR/WEBHOOK/URL';
    
    $data = [
        'text' => $subject,
        'attachments' => [[
            'text' => $message,
            'color' => 'danger'
        ]]
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $webhookUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
    */
    
    return true; // placeholder
}

?>