package api

import (
	"database/sql"
	"encoding/json"
	"net/http"
)

type User struct {
	ID          int     `json:"id"`
	GradeID     *int    `json:"gradeId"`
	Nom         string  `json:"nom"`
	Prenom      string  `json:"prenom"`
	Status      string  `json:"status"`
	Email       string  `json:"email"`
	Age         *string  `json:"age"` // Le type est string pour refléter le type DATE
	MotDePasse  string  `json:"motDePasse"`
	Presentation *string  `json:"presentation"`
}

var db *sql.DB

func AddUser(w http.ResponseWriter, r *http.Request) {
	var user User
	err := json.NewDecoder(r.Body).Decode(&user)
	if err != nil {
		http.Error(w, err.Error(), http.StatusBadRequest)
		return
	}

	// Insérer l'utilisateur dans la base de données
	stmt, err := db.Prepare("INSERT INTO USER (id_GRADE, nom, prenom, status, email, age, mot_de_passe, presentation) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer stmt.Close()

	_, err = stmt.Exec(user.GradeID, user.Nom, user.Prenom, user.Status, user.Email, user.Age, user.MotDePasse, user.Presentation)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	w.WriteHeader(http.StatusCreated)
}

func GetAllUsers(w http.ResponseWriter, r *http.Request) {
    rows, err := db.Query("SELECT id, id_GRADE, nom, prenom, status, email, age, mot_de_passe, presentation FROM USER")
    if err != nil {
        http.Error(w, err.Error(), http.StatusInternalServerError)
        return
    }
    defer rows.Close()

    var users []User
    for rows.Next() {
        var user User
        err := rows.Scan(&user.ID, &user.GradeID, &user.Nom, &user.Prenom, &user.Status, &user.Email, &user.Age, &user.MotDePasse, &user.Presentation)
        if err != nil {
            http.Error(w, err.Error(), http.StatusInternalServerError)
            return
        }
        users = append(users, user)
    }

    w.Header().Set("Content-Type", "application/json")
    json.NewEncoder(w).Encode(users)
}