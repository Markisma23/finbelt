<?php
class JobQueue {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Enqueue a new job into the job queue.
     *
     * @param string $jobType The type of job (e.g., 'email_notification').
     * @param array $payload An array of data required by the job; it will be JSON-encoded.
     * @param string|null $runAt Optional datetime (YYYY-MM-DD HH:MM:SS) to schedule when the job should run.
     * @return bool
     */
    public function enqueueJob($jobType, array $payload, $runAt = null) {
        $jsonPayload = json_encode($payload);
        $stmt = $this->db->prepare("INSERT INTO job_queue (job_type, payload, run_at) VALUES (?, ?, ?)");
        return $stmt->execute([$jobType, $jsonPayload, $runAt]);
    }

    /**
     * Process pending jobs.
     * This function fetches all pending jobs (that are scheduled to run)
     * and executes the associated action.
     */
    public function processJobs() {
        // Retrieve pending jobs scheduled to run (or with run_at IS NULL meaning run immediately)
        $stmt = $this->db->prepare("SELECT * FROM job_queue WHERE status = 'pending' AND (run_at IS NULL OR run_at <= NOW()) ORDER BY created_at ASC");
        $stmt->execute();
        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($jobs as $job) {
            // Mark the job as processing.
            $updateStmt = $this->db->prepare("UPDATE job_queue SET status = 'processing' WHERE id = ?");
            $updateStmt->execute([$job['id']]);
            
            // Handle the job based on its type.
            $jobType = $job['job_type'];
            $payload = json_decode($job['payload'], true);
            $success = false;
            
            // Example handling for an email notification job.
            if ($jobType === 'email_notification') {
                $success = $this->processEmailNotification($payload);
            } elseif ($jobType === 'sms_notification') {
                // Process SMS notifications.
                require_once __DIR__ . '/SMSNotifier.php';
                $smsNotifier = new SMSNotifier();
                if (isset($payload['to_phone'], $payload['message'])) {
                    $result = $smsNotifier->sendSMS($payload['to_phone'], $payload['message']);
                    $success = $result['success'];
                }}
            
            // Extend with additional job types as needed...
            
            // Update job status based on processing result.
            if ($success) {
                $updateStmt = $this->db->prepare("UPDATE job_queue SET status = 'completed' WHERE id = ?");
                $updateStmt->execute([$job['id']]);
            } else {
                $updateStmt = $this->db->prepare("UPDATE job_queue SET status = 'failed' WHERE id = ?");
                $updateStmt->execute([$job['id']]);
            }
        }
    }
    
    /**
     * Process an email notification job.
     * This is a stub function that simulates sending an email.
     *
     * @param array $payload Expected keys: 'to', 'subject', 'body'
     * @return bool True if processed successfully.
     */
    private function processEmailNotification(array $payload) {
        if (!isset($payload['to'], $payload['subject'], $payload['body'])) {
            return false;
        }
        
        // In production, integrate with a mail service like SendGrid, PHPMailer, etc.
        // For demonstration, we simulate success by writing a log entry.
        $to = $payload['to'];
        $subject = $payload['subject'];
        $body = $payload['body'];
        error_log("Simulated sending email to {$to} with subject '{$subject}'. Body: {$body}");
        
        return true;
    }
}
?>
