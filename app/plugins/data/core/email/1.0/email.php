<?php

namespace Core\App;
class Email {
    private $headers; 
    private $recipients;
    private $defaultHeaders;
    private $from;
    private $subject;
    private $msg;
    public $enableBCC;
    public function __construct() {
        $this->recipients = '';
        $this->headers['X-Mailer'] = 'PHP/' . phpversion();
        $this->defaultHeaders = [
            'from' => ['From', 'SetFrom'],
            'mime' => ['MIME-Version', 'SetMIME'],
            'x-sender' => ['X-Sender', 'SetXSender'],
            'x-priority' => ['X-Priority', 'SetXPriority'],
            'content-type' => ['Content-Type', 'SetContentType']
        ];
    }

    
    public function Send() {
        if (!isset($this->msg)) {
            return [
                'status' => false,
                'msg' => 'Message is not set'
            ];
        }
        if (!$this->from) {
            return [
                'status' => false,
                'msg' => 'From has not been set'
            ];
        }
        if (!isset($this->subject)) {
            return [
                'status' => false,
                'msg' => 'Subject has not been set'
            ];
        }
        if (!isset($this->recipients)) {
            return [
                'status' => false,
                'msg' => 'No recipients have been set'
            ];
        }
        if (isset($this->enableBCC)) {
            $this->headers['Bcc'] = $this->recipients;
        }
        $didEmailSend = mail($this->recipients, $this->subject, $this->msg, $this->headers);
        if (!$didEmailSend) {
            return [
                'status' => false,
                'msg' => 'Email has not been sent'
            ];
        }
        return [
            'status' => true,
            'msg' => 'Email has been sent'
        ];
    }
    public function SetHeader(string $name, $value) {
        if (isset($this->defaultHeaders[$name])) {
            $correctName = $this->defaultHeaders[$name][0];
            $this->headers[$correctName] = $value;
            if ($name === 'from') {
                $this->from = true;
            }
        }
    }
    public function SetRecipients($recipients, $settings = []) {
        foreach ($recipients as $rec) {
            if (end($recipients) !== $rec) {
                $this->recipients .= $rec . ', ';
                continue;
            }
            $this->recipients .= $rec;
        }
    }
    
    public function Template($name, $data) {
        if (!file_exists(__DIR__ . '/templates/' . $name . '.php')) {
            return [
                'status' => false,
                'msg' => 'Email template does not exist.'
            ];
        }
        require __DIR__ . '/templates/' . $name . '.php';
        $classname = 'Core\App\\' . $name;
        $template = new $classname();
        $this->msg = $template->Init($data);
    }
    /*
     *  
     * 
     */
    public function SetSubject($subject) {
        $this->subject = $subject;
    }
}
?>