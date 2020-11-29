<?php

namespace App\Services;

use App\DB;

/**
 * Email logging class
 * 
 */
class EmailLog
{
    public $payload;

    /**
     * Logging sent email
     *
     * @param string $payload 
     * 
     * @return void
     */
    public function sentEmail(string $payload): void
    {
        $db = new DB();
        $db->query('INSERT INTO sent_emails (payload, sent_at) VALUES (:payload, :sent_at)');
        $db->bind(':payload', $payload);
        $db->bind(':sent_at', date('Y-m-d H:i:s'));
        $db->execute();
    }

    /**
     * Logging unsent email
     *
     * @param string $payload 
     * 
     * @return void
     */
    public function unsentEmail(string $payload): void
    {
        $db = new DB();
        $db->query('INSERT INTO unsent_emails (payload, failed_at) VALUES (:payload, :failed_at)');
        $db->bind(':payload', $payload);
        $db->bind(':failed_at', date('Y-m-d H:i:s'));
        $db->execute();
    }

    /**
     * Delete unsent email log
     *
     * @param string $id 
     * 
     * @return void
     */
    public function deleteUnsentEmail(string $id): void
    {
        $db = new DB();
        $db->query('DELETE FROM unsent_emails WHERE id=:id');
        $db->bind(':id', $id);
        $db->execute();
    }
}
