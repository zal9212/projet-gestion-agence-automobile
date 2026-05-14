<?php
/**
 * ZAIX SOC Agent - PHP Edition
 * Version: 1.0.0
 * Security Operations Center Active Defense
 */

class ZaixAgent {
    private $siemUrl;
    private $siteId;
    private $options;

    public function __construct($config = []) {
        $this->siemUrl = isset($config['siemUrl']) ? rtrim($config['siemUrl'], '/') : 'http://localhost:3001';
        $this->siteId  = isset($config['siteId']) ? $config['siteId'] : 'VOTRE_SITE_ID';
        $this->options = array_merge([
            'enableBlocking' => true,
            'logLevel'       => 'INFO'
        ], $config);

        $this->run();
    }

    private function run() {
        $attack = $this->detectAttack();
        if ($attack) {
            $this->triggerAttack($attack);
        }
    }

    public function triggerAttack($attack) {
        // 1. Envoyer l'alerte au tableau de bord ZAIX
        $this->reportIncident($attack);
        
        // 2. BLOQUER physiquement le pirate si le blocage est activé
        if ($this->options['enableBlocking']) {
            header('HTTP/1.1 403 Forbidden');
            die('<div style="text-align:center; margin-top:50px; font-family:sans-serif; background-color: #111; color: #fff; padding: 40px; border-radius: 10px; max-width: 600px; margin-left: auto; margin-right: auto; box-shadow: 0 0 20px rgba(255,0,0,0.5);">
                    <h1 style="color:#ff4444; font-size: 32px;">⚠️ Accès Refusé</h1>
                    <p style="font-size: 18px;">Une tentative de piratage (<strong>' . htmlspecialchars($attack['type']) . '</strong>) a été détectée et stoppée par <strong>ZAIX SOC</strong>.</p>
                    <p style="color: #888; font-size: 14px;">Votre adresse IP (' . htmlspecialchars($attack['ip']) . ') a été signalée.</p>
                 </div>');
        }
    }

    private function detectAttack() {
        $patterns = [
            'SQL_INJECTION'   => '/(union|select|insert|update|delete|drop|alter|--|#|OR\s+[\'"]?\d[\'"]?\s*=\s*[\'"]?\d|SLEEP\(|BENCHMARK\(|WAITFOR|INFORMATION_SCHEMA)/i',
            'XSS'             => '/(<script|alert\(|onerror|onload|javascript:)/i',
            'PATH_TRAVERSAL'  => '/(\.\.\/|\.\.\\\\|etc\/passwd|boot\.ini)/i',
            'SHELL_INJECTION' => '/(curl|wget|nc -e|bash -i|python -c)/i'
        ];

        $payloads = [
            'GET'    => $_GET,
            'POST'   => $_POST,
            'COOKIE' => $_COOKIE,
            'URI'    => $_SERVER['REQUEST_URI']
        ];

        foreach ($patterns as $type => $regex) {
            foreach ($payloads as $source => $data) {
                $items = is_array($data) ? $data : [$data];
                foreach ($items as $key => $content) {
                    if (is_string($content) && preg_match($regex, $content)) {
                        return [
                            'type'     => $type,
                            'ip'       => $this->getClientIP(),
                            'url'      => $_SERVER['REQUEST_URI'],
                            'severity' => 'CRITICAL',
                            'message'  => "Detection $type dans $source (champ: $key)",
                            'details'  => ['source' => $source, 'payload' => $content, 'field' => $key]
                        ];
                    }
                }
            }
        }

        return null;
    }

    private function reportIncident($incident) {
        $url = "{$this->siemUrl}/api/v1/webhook/{$this->siteId}";
        $ch  = curl_init($url);
        
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($incident));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);

        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }

    private function getClientIP() {
        return $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}

// Initialisation automatique (facultatif si appelé manuellement)
// $zaix = new ZaixAgent(['siteId' => 'VOTRE_SITE_ID']);
