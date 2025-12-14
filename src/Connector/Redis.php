<?php

namespace Connector;

use Redis as PhpRedis;
use Exception;

/**
 * Classe pour gérer les connexions et opérations Redis
 */
class Redis
{
    private ?PhpRedis $connection = null;
    private string $host;
    private int $port;
    private ?string $password;
    private int $database;
    private float $timeout;
    private bool $isConnected = false;

    /**
     * Constructeur
     * 
     * @param string $host Hôte Redis (défaut: 'localhost')
     * @param int $port Port Redis (défaut: 6379)
     * @param string|null $password Mot de passe Redis (optionnel)
     * @param int $database Numéro de base de données (défaut: 0)
     * @param float $timeout Timeout de connexion en secondes (défaut: 2.5)
     */
    public function __construct(
        string $host = 'localhost',
        int $port = 6379,
        ?string $password = null,
        int $database = 0,
        float $timeout = 2.5
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->password = $password;
        $this->database = $database;
        $this->timeout = $timeout;
    }

    /**
     * Établit la connexion à Redis
     * 
     * @return bool True si la connexion réussit
     * @throws Exception Si la connexion échoue
     */
    public function connect(): bool
    {
        try {
            $this->connection = new PhpRedis();
            
            // Tentative de connexion
            if (!$this->connection->connect($this->host, $this->port, $this->timeout)) {
                throw new Exception("Impossible de se connecter à Redis sur {$this->host}:{$this->port}");
            }

            // Authentification si mot de passe fourni
            if ($this->password !== null && !$this->connection->auth($this->password)) {
                throw new Exception("Échec de l'authentification Redis");
            }

            // Sélection de la base de données
            if (!$this->connection->select($this->database)) {
                throw new Exception("Impossible de sélectionner la base de données {$this->database}");
            }

            $this->isConnected = true;
            return true;

        } catch (Exception $e) {
            $this->isConnected = false;
            throw new Exception("Erreur de connexion Redis : " . $e->getMessage());
        }
    }

    /**
     * Vérifie si la connexion est active
     * 
     * @return bool True si connecté
     */
    public function isConnected(): bool
    {
        if (!$this->isConnected || $this->connection === null) {
            return false;
        }

        try {
            return $this->connection->ping() !== false;
        } catch (Exception $e) {
            $this->isConnected = false;
            return false;
        }
    }

    /**
     * Teste la connexion avec PING
     * 
     * @return bool True si Redis répond
     */
    public function ping(): bool
    {
        $this->ensureConnected();
        return $this->connection->ping() !== false;
    }

    /**
     * Définit une valeur dans Redis
     * 
     * @param string $key Clé
     * @param mixed $value Valeur
     * @param int|null $ttl Durée de vie en secondes (optionnel)
     * @return bool True si succès
     */
    public function set(string $key, $value, ?int $ttl = null): bool
    {
        $this->ensureConnected();

        // Sérialisation si nécessaire
        if (!is_string($value)) {
            $value = serialize($value);
        }

        if ($ttl !== null && $ttl > 0) {
            return $this->connection->setex($key, $ttl, $value);
        }

        return $this->connection->set($key, $value);
    }

    /**
     * Récupère une valeur depuis Redis
     * 
     * @param string $key Clé
     * @return mixed Valeur ou null si non trouvée
     */
    public function get(string $key)
    {
        $this->ensureConnected();
        
        $value = $this->connection->get($key);
        
        if ($value === false) {
            return null;
        }

        // Tentative de désérialisation
        $unserialized = @unserialize($value);
        return $unserialized !== false ? $unserialized : $value;
    }

    /**
     * Supprime une ou plusieurs clés
     * 
     * @param string|array $keys Clé(s) à supprimer
     * @return int Nombre de clés supprimées
     */
    public function delete($keys): int
    {
        $this->ensureConnected();
        return $this->connection->del($keys);
    }

    /**
     * Vérifie si une clé existe
     * 
     * @param string $key Clé
     * @return bool True si la clé existe
     */
    public function exists(string $key): bool
    {
        $this->ensureConnected();
        return $this->connection->exists($key) > 0;
    }

    /**
     * Définit un TTL (Time To Live) sur une clé
     * 
     * @param string $key Clé
     * @param int $seconds Durée en secondes
     * @return bool True si succès
     */
    public function expire(string $key, int $seconds): bool
    {
        $this->ensureConnected();
        return $this->connection->expire($key, $seconds);
    }

    /**
     * Récupère le TTL d'une clé
     * 
     * @param string $key Clé
     * @return int TTL en secondes (-1 si pas de TTL, -2 si clé inexistante)
     */
    public function ttl(string $key): int
    {
        $this->ensureConnected();
        return $this->connection->ttl($key);
    }

    /**
     * Récupère toutes les clés correspondant à un motif
     * 
     * @param string $pattern Motif (ex: 'user:*')
     * @return array Liste des clés
     */
    public function keys(string $pattern = '*'): array
    {
        $this->ensureConnected();
        return $this->connection->keys($pattern);
    }

    /**
     * Vide la base de données courante
     * 
     * @return bool True si succès
     */
    public function flushDb(): bool
    {
        $this->ensureConnected();
        return $this->connection->flushDB();
    }

    /**
     * Vide toutes les bases de données
     * 
     * @return bool True si succès
     */
    public function flushAll(): bool
    {
        $this->ensureConnected();
        return $this->connection->flushAll();
    }

    /**
     * Récupère des informations sur le serveur Redis
     * 
     * @return array Informations serveur
     */
    public function info(): array
    {
        $this->ensureConnected();
        return $this->connection->info();
    }

    /**
     * Ferme la connexion Redis
     */
    public function disconnect(): void
    {
        if ($this->connection !== null) {
            $this->connection->close();
            $this->isConnected = false;
            $this->connection = null;
        }
    }

    /**
     * Récupère l'instance PhpRedis native
     * 
     * @return PhpRedis|null Instance Redis
     */
    public function getConnection(): ?PhpRedis
    {
        return $this->connection;
    }

    /**
     * Vérifie que la connexion est établie
     * 
     * @throws Exception Si non connecté
     */
    private function ensureConnected(): void
    {
        if (!$this->isConnected()) {
            throw new Exception("Non connecté à Redis. Appelez connect() d'abord.");
        }
    }

    /**
     * Destructeur - ferme la connexion automatiquement
     */
    public function __destruct()
    {
        $this->disconnect();
    }
}

$redis = new Redis("redis-dev", 6379, "lgjs_redis_dev");

$redis->connect();

$redis->set("foo", "bar");