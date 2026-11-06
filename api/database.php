<?php

/**
 * Database connection factory.
 *
 * The defaults are suitable for a local XAMPP teaching environment. Environment
 * variables are also supported so the same code can be reused in a different
 * setup without editing this file.
 */

// Enable strict type checking.
// PHP will be more strict when checking the types of values
// passed to functions and methods.
declare(strict_types=1);

/**
 * Create and return a connection to the MySQL database.
 *
 * The function returns a PDO object, which PHP uses
 * to communicate with the database.
 */
function getDatabase(): PDO
{
    // Read the database settings from environment variables.
    // If they are not available, use the default XAMPP values.
    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $port = getenv('DB_PORT') ?: '3306';
    $name = getenv('DB_NAME') ?: 'WebDev3_Project';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASSWORD') ?: '';

    // DSN = Data Source Name.
    // It tells PDO which database system to use,
    // where the database server is,
    // which database to connect to,
    // and which character encoding to use.
    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

    // Create and return the PDO database connection.
    return new PDO($dsn, $user, $pass, [

        // If a database error occurs, PDO will throw an exception.
        // This makes errors easier to identify during development.
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

        // Return database rows as associative arrays.
        //
        // Example:
        // ['id' => 1, 'name' => 'Bethany Bay']
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

        // Use MySQL's native prepared statements.
        // Prepared statements are safer when values are supplied
        // by the user, for example an ID in a URL.
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}
