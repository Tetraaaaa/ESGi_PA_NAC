package apinac

import (
	"encoding/json"
	"net/http"

	"github.com/gorilla/mux"
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

func GetUserByID(w http.ResponseWriter, r *http.Request) {
	// Récupérer l'ID de l'utilisateur depuis les paramètres de la requête
	params := mux.Vars(r)
	userID := params["id"]

	// Préparer la requête SQL pour récupérer l'utilisateur par son ID
	row := db.QueryRow("SELECT id, id_GRADE, nom, prenom, status, email, age, mot_de_passe, presentation FROM USER WHERE id = ?", userID)

	// Créer une variable pour stocker les données de l'utilisateur
	var user User

	// Scanner les données de l'utilisateur dans la variable user
	err := row.Scan(&user.ID, &user.GradeID, &user.Nom, &user.Prenom, &user.Status, &user.Email, &user.Age, &user.MotDePasse, &user.Presentation)
	if err != nil {
		// Si une erreur se produit lors de la récupération de l'utilisateur, renvoyer une erreur au client
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	// Encoder les données de l'utilisateur en JSON et les renvoyer en réponse
	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(user)
}