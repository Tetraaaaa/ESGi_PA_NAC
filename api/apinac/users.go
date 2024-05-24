package apinac

import (
	"encoding/json"
	"net/http"
	"strings"

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

func GetAllEmailUser(w http.ResponseWriter, r *http.Request) {
    rows, err := db.Query("SELECT email FROM USER")
    if err != nil {
        http.Error(w, err.Error(), http.StatusInternalServerError)
        return
    }
    defer rows.Close()

    var emails []string

    for rows.Next() {
        var email string
        err := rows.Scan(&email)
        if err != nil {
            http.Error(w, err.Error(), http.StatusInternalServerError)
            return
        }
        emails = append(emails, email)
    }

    if err = rows.Err(); err != nil {
        http.Error(w, err.Error(), http.StatusInternalServerError)
        return
    }

    w.Header().Set("Content-Type", "application/json")
    json.NewEncoder(w).Encode(emails)
}

func GetUserByEmail(w http.ResponseWriter, r *http.Request) {
	// Récupérer l'ID de l'utilisateur depuis les paramètres de la requête
	params := mux.Vars(r)
	userEmail := params["email"]

	// Préparer la requête SQL pour récupérer l'utilisateur par son ID
	row := db.QueryRow("SELECT id, id_GRADE, nom, prenom, status, email, age, mot_de_passe, presentation FROM USER WHERE email = ?", userEmail)

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

func GetUserByResearchEmail(w http.ResponseWriter, r *http.Request) {
    params := mux.Vars(r)
    userEmail := params["email"]

    query := "SELECT id, id_GRADE, nom, prenom, status, email, age, mot_de_passe, presentation FROM USER WHERE email LIKE ?"
    rows, err := db.Query(query, "%"+strings.TrimSpace(userEmail)+"%")
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

    if err = rows.Err(); err != nil {
        http.Error(w, err.Error(), http.StatusInternalServerError)
        return
    }

    w.Header().Set("Content-Type", "application/json")
    json.NewEncoder(w).Encode(users)
}

func GetUserByPrenom(w http.ResponseWriter, r *http.Request) {
    // Récupérer le prénom de l'utilisateur depuis les paramètres de la requête
    params := mux.Vars(r)
    userPrenom := params["prenom"]

    // Préparer la requête SQL pour récupérer les utilisateurs par prénom
    rows, err := db.Query("SELECT id, id_GRADE, nom, prenom, status, email, age, mot_de_passe, presentation FROM USER WHERE prenom = ?", userPrenom)
    if err != nil {
        http.Error(w, err.Error(), http.StatusInternalServerError)
        return
    }
    defer rows.Close()

    // Créer une slice pour stocker les utilisateurs
    var users []User

    // Parcourir les résultats et ajouter les utilisateurs à la slice
    for rows.Next() {
        var user User
        err := rows.Scan(&user.ID, &user.GradeID, &user.Nom, &user.Prenom, &user.Status, &user.Email, &user.Age, &user.MotDePasse, &user.Presentation)
        if err != nil {
            http.Error(w, err.Error(), http.StatusInternalServerError)
            return
        }
        users = append(users, user)
    }

    // Vérifier les erreurs lors de l'itération
    if err = rows.Err(); err != nil {
        http.Error(w, err.Error(), http.StatusInternalServerError)
        return
    }

    // Encoder les utilisateurs en JSON et les renvoyer en réponse
    w.Header().Set("Content-Type", "application/json")
    json.NewEncoder(w).Encode(users)
}

func GetUserByStatus(w http.ResponseWriter, r *http.Request) {
    params := mux.Vars(r)
    userStatus := params["status"]

    rows, err := db.Query("SELECT id, id_GRADE, nom, prenom, status, email, age, mot_de_passe, presentation FROM USER WHERE status = ?", userStatus)
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

    if err = rows.Err(); err != nil {
        http.Error(w, err.Error(), http.StatusInternalServerError)
        return
    }

    w.Header().Set("Content-Type", "application/json")
    json.NewEncoder(w).Encode(users)
}