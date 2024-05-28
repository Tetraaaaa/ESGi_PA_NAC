package bdd

import (
    "database/sql"
    "os"
    _ "github.com/go-sql-driver/mysql"
    "fmt"
)

// Créer une connexion à la bdd
func ConnectDB() (*sql.DB, error) {
    dbUser := os.Getenv("DB_USER")
    dbPassword := os.Getenv("DB_PASSWORD")
    dbHost := os.Getenv("DB_HOST")
    dbName := os.Getenv("DB_NAME")

    dsn := fmt.Sprintf("%s:%s@tcp(%s)/%s", dbUser, dbPassword, dbHost, dbName)
    db, err := sql.Open("mysql", dsn)
    if err != nil {
        return nil, err
    }

    err = db.Ping()
    if err != nil {
        db.Close()
        return nil, err
    }

    return db, nil
}
