package bdd

import (
    "database/sql"
    "fmt"
    "os"
    "github.com/joho/godotenv"
)

// LoadEnv loads environment variables from .env file
func LoadEnv() error {
    // Load .env file
    err := godotenv.Load(".env")
    if err != nil {
        return err
    }
    return nil
}

// Créer une connexion à la bdd
func ConnectDB() (*sql.DB, error) {
    // Load environment variables
    if err := LoadEnv(); err != nil {
        return nil, err
    }

    // Retrieve environment variables
    dbUser := os.Getenv("DB_USER")
    dbPassword := os.Getenv("DB_PASSWORD")
    dbHost := os.Getenv("DB_HOST")
    dbName := os.Getenv("DB_NAME")

    // Construct DSN
    dsn := fmt.Sprintf("%s:%s@tcp(%s)/%s", dbUser, dbPassword, dbHost, dbName)
    // Open database connection
    db, err := sql.Open("mysql", dsn)
    if err != nil {
        return nil, err
    }

    // Ping database
    err = db.Ping()
    if err != nil {
        db.Close()
        return nil, err
    }

    return db, nil
}
