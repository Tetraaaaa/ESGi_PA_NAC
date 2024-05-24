package bdd

import (
    "database/sql"
    _ "github.com/go-sql-driver/mysql"
)

//Créer une connection à la bdd
func ConnectDB() (*sql.DB, error) {
    db, err := sql.Open("mysql", "admin:SuDGhKBjzs9d@tcp(91.134.89.127)/NAC_BDD")
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
